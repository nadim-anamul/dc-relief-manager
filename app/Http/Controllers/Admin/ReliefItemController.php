<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReliefItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReliefItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $reliefItems = ReliefItem::withTrashed()
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.relief-items.index', compact('reliefItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.relief-items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'type' => 'required|in:monetary,food,medical,shelter,other',
            'unit' => 'required|in:BDT,metric_ton,kg,liter,piece,box',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        ReliefItem::create($validated);

        return redirect()->route('admin.relief-items.index')
            ->with('success', 'Relief item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ReliefItem $reliefItem): View
    {
        $reliefItem->load(['reliefApplicationItems.reliefApplication', 'inventories.project']);
        
        return view('admin.relief-items.show', compact('reliefItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReliefItem $reliefItem): View
    {
        return view('admin.relief-items.edit', compact('reliefItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReliefItem $reliefItem): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'type' => 'required|in:monetary,food,medical,shelter,other',
            'unit' => 'required|in:BDT,metric_ton,kg,liter,piece,box',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $reliefItem->update($validated);

        return redirect()->route('admin.relief-items.index')
            ->with('success', 'Relief item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReliefItem $reliefItem): RedirectResponse
    {
        $reliefItem->delete();

        return redirect()->route('admin.relief-items.index')
            ->with('success', 'Relief item deleted successfully.');
    }

    /**
     * Restore a soft-deleted relief item.
     */
    public function restore($id): RedirectResponse
    {
        $reliefItem = ReliefItem::withTrashed()->findOrFail($id);
        $reliefItem->restore();

        return redirect()->route('admin.relief-items.index')
            ->with('success', 'Relief item restored successfully.');
    }

    /**
     * Get relief items by type for AJAX requests.
     */
    public function getByType(Request $request)
    {
        $type = $request->get('type');
        $reliefItems = ReliefItem::active()->ofType($type)->get();
        
        return response()->json($reliefItems);
    }
}
