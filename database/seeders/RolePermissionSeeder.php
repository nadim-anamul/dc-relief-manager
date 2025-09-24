<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // Relief Applications
            ['name' => 'View Relief Applications', 'slug' => 'relief-applications.view', 'description' => 'View all relief applications', 'resource' => 'relief-applications', 'action' => 'view'],
            ['name' => 'View Own Relief Applications', 'slug' => 'relief-applications.view-own', 'description' => 'View own relief applications', 'resource' => 'relief-applications', 'action' => 'view-own'],
            ['name' => 'Create Relief Applications', 'slug' => 'relief-applications.create', 'description' => 'Create relief applications', 'resource' => 'relief-applications', 'action' => 'create'],
            ['name' => 'Create Own Relief Applications', 'slug' => 'relief-applications.create-own', 'description' => 'Create own relief applications', 'resource' => 'relief-applications', 'action' => 'create-own'],
            ['name' => 'Update Relief Applications', 'slug' => 'relief-applications.update', 'description' => 'Update relief applications', 'resource' => 'relief-applications', 'action' => 'update'],
            ['name' => 'Update Own Relief Applications', 'slug' => 'relief-applications.update-own', 'description' => 'Update own relief applications', 'resource' => 'relief-applications', 'action' => 'update-own'],
            ['name' => 'Delete Relief Applications', 'slug' => 'relief-applications.delete', 'description' => 'Delete relief applications', 'resource' => 'relief-applications', 'action' => 'delete'],
            ['name' => 'Approve Relief Applications', 'slug' => 'relief-applications.approve', 'description' => 'Approve relief applications', 'resource' => 'relief-applications', 'action' => 'approve'],
            ['name' => 'Reject Relief Applications', 'slug' => 'relief-applications.reject', 'description' => 'Reject relief applications', 'resource' => 'relief-applications', 'action' => 'reject'],

            // Projects
            ['name' => 'View Projects', 'slug' => 'projects.view', 'description' => 'View all projects', 'resource' => 'projects', 'action' => 'view'],
            ['name' => 'View Own Projects', 'slug' => 'projects.view-own', 'description' => 'View own projects', 'resource' => 'projects', 'action' => 'view-own'],
            ['name' => 'Create Projects', 'slug' => 'projects.create', 'description' => 'Create projects', 'resource' => 'projects', 'action' => 'create'],
            ['name' => 'Update Projects', 'slug' => 'projects.update', 'description' => 'Update projects', 'resource' => 'projects', 'action' => 'update'],
            ['name' => 'Delete Projects', 'slug' => 'projects.delete', 'description' => 'Delete projects', 'resource' => 'projects', 'action' => 'delete'],
            ['name' => 'Manage Projects', 'slug' => 'projects.manage', 'description' => 'Full project management', 'resource' => 'projects', 'action' => 'manage'],

            // Administrative Divisions
            ['name' => 'Manage Zillas', 'slug' => 'zillas.manage', 'description' => 'Manage zillas', 'resource' => 'zillas', 'action' => 'manage'],
            ['name' => 'Manage Upazilas', 'slug' => 'upazilas.manage', 'description' => 'Manage upazilas', 'resource' => 'upazilas', 'action' => 'manage'],
            ['name' => 'Manage Unions', 'slug' => 'unions.manage', 'description' => 'Manage unions', 'resource' => 'unions', 'action' => 'manage'],
            ['name' => 'Manage Wards', 'slug' => 'wards.manage', 'description' => 'Manage wards', 'resource' => 'wards', 'action' => 'manage'],

            // Master Data
            ['name' => 'Manage Economic Years', 'slug' => 'economic-years.manage', 'description' => 'Manage economic years', 'resource' => 'economic-years', 'action' => 'manage'],
            ['name' => 'Manage Relief Types', 'slug' => 'relief-types.manage', 'description' => 'Manage relief types', 'resource' => 'relief-types', 'action' => 'manage'],
            ['name' => 'Manage Organization Types', 'slug' => 'organization-types.manage', 'description' => 'Manage organization types', 'resource' => 'organization-types', 'action' => 'manage'],

            // System Administration
            ['name' => 'Manage Users', 'slug' => 'users.manage', 'description' => 'Manage users', 'resource' => 'users', 'action' => 'manage'],
            ['name' => 'Manage Roles', 'slug' => 'roles.manage', 'description' => 'Manage roles', 'resource' => 'roles', 'action' => 'manage'],
            ['name' => 'Manage Permissions', 'slug' => 'permissions.manage', 'description' => 'Manage permissions', 'resource' => 'permissions', 'action' => 'manage'],
            ['name' => 'View Audit Logs', 'slug' => 'audit-logs.view', 'description' => 'View audit logs', 'resource' => 'audit-logs', 'action' => 'view'],
            ['name' => 'Export Data', 'slug' => 'exports.access', 'description' => 'Export data', 'resource' => 'exports', 'action' => 'access'],

            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'description' => 'View dashboard', 'resource' => 'dashboard', 'action' => 'view'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['slug']],
                [
                    'name' => $permissionData['slug'],
                    'guard_name' => 'web'
                ]
            );
        }

        // Create Roles
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full system access with all permissions',
                'permissions' => Permission::all()->pluck('name')->toArray()
            ],
            [
                'name' => 'District Admin',
                'slug' => 'district-admin',
                'description' => 'District-level administration with most permissions',
                'permissions' => [
                    'relief-applications.view',
                    'relief-applications.create',
                    'relief-applications.update',
                    'relief-applications.delete',
                    'relief-applications.approve',
                    'relief-applications.reject',
                    'projects.view',
                    'projects.create',
                    'projects.update',
                    'projects.delete',
                    'projects.manage',
                    'zillas.manage',
                    'upazilas.manage',
                    'unions.manage',
                    'wards.manage',
                    'economic-years.manage',
                    'relief-types.manage',
                    'organization-types.manage',
                    'audit-logs.view',
                    'exports.access',
                    'dashboard.view',
                ]
            ],
            [
                'name' => 'Data Entry',
                'slug' => 'data-entry',
                'description' => 'Data entry and basic management',
                'permissions' => [
                    'relief-applications.view',
                    'relief-applications.create',
                    'relief-applications.update',
                    'relief-applications.view-own',
                    'relief-applications.create-own',
                    'relief-applications.update-own',
                    'projects.view',
                    'projects.create',
                    'projects.update',
                    'projects.view-own',
                    'economic-years.manage',
                    'relief-types.manage',
                    'organization-types.manage',
                    'exports.access',
                    'dashboard.view',
                ]
            ],
            [
                'name' => 'Viewer',
                'slug' => 'viewer',
                'description' => 'Read-only access to view data',
                'permissions' => [
                    'relief-applications.view',
                    'relief-applications.view-own',
                    'projects.view',
                    'projects.view-own',
                    'dashboard.view',
                ]
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::firstOrCreate(
                ['name' => $roleData['slug']],
                [
                    'name' => $roleData['slug'],
                    'guard_name' => 'web'
                ]
            );

            // Attach permissions to role
            $permissionModels = Permission::whereIn('name', $permissions)->get();
            $role->syncPermissions($permissionModels);
        }
    }
}