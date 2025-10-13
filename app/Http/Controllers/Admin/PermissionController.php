<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::orderBy('name')
            ->paginate(20);

        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::with(['economicYear', 'reliefType'])
            ->orderBy('name')
            ->get();
            
        return view('admin.permissions.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $permissionType = $request->input('permission_type', 'general');
        
        if ($permissionType === 'project') {
            // Validate project-based permissions
            $request->validate([
                'permission_type' => 'required|in:project',
                'project_id' => 'required|exists:projects,id',
                'actions' => 'required|array|min:1',
                'actions.*' => 'in:view,create,edit,delete,approve,reject,manage',
            ]);

            $project = Project::findOrFail($request->project_id);
            $projectSlug = $this->generateProjectSlug($project->name, $project->id);
            $createdPermissions = [];

            foreach ($request->actions as $action) {
                $permissionName = "projects.{$projectSlug}.{$action}";
                
                // Check if permission already exists
                if (!Permission::where('name', $permissionName)->exists()) {
                    Permission::create([
                        'name' => $permissionName,
                        'guard_name' => 'web',
                    ]);
                    $createdPermissions[] = $permissionName;
                }
            }

            if (empty($createdPermissions)) {
                return redirect()->route('admin.permissions.index')
                    ->with('warning', 'All permissions for this project already exist.');
            }

            $message = count($createdPermissions) > 1 
                ? count($createdPermissions) . ' permissions created successfully.'
                : 'Permission created successfully.';

            return redirect()->route('admin.permissions.index')
                ->with('success', $message);

        } else {
            // Validate general permission
            $request->validate([
                'permission_type' => 'required|in:general',
                'name' => 'required|string|max:255|unique:permissions,name',
                'guard_name' => 'required|string|max:255',
            ]);

            Permission::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name,
            ]);

            return redirect()->route('admin.permissions.index')
                ->with('success', 'Permission created successfully.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        $permission->load('roles');
        
        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id)],
            'guard_name' => 'required|string|max:255',
        ]);

        $permission->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            return redirect()->route('admin.permissions.index')
                ->with('error', 'Cannot delete permission that is assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    /**
     * Generate a unique slug for project-based permissions.
     */
    private function generateProjectSlug(string $projectName, int $projectId): string
    {
        // For Bengali text or non-Latin characters, use project ID
        if (!preg_match('/^[a-zA-Z0-9\s\-_]+$/', $projectName)) {
            return "project-{$projectId}";
        }

        // For Latin text, create a proper slug
        $slug = strtolower(trim($projectName));
        $slug = preg_replace('/[^a-zA-Z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Ensure slug is not empty
        if (empty($slug)) {
            return "project-{$projectId}";
        }

        return $slug;
    }
}
