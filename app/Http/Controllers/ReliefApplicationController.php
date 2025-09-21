<?php

namespace App\Http\Controllers;

use App\Models\ReliefApplication;
use App\Models\OrganizationType;
use App\Models\Zilla;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\Ward;
use App\Models\ReliefType;
use App\Models\ReliefItem;
use App\Models\ReliefApplicationItem;
use App\Http\Requests\ReliefApplicationRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ReliefApplicationController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): View
	{
		$query = ReliefApplication::with(['organizationType', 'zilla', 'upazila', 'union', 'ward', 'reliefType']);

		// Filter by status if provided
		if ($request->filled('status')) {
			$query->where('status', $request->status);
		}

		// Filter by relief type if provided
		if ($request->filled('relief_type_id')) {
			$query->where('relief_type_id', $request->relief_type_id);
		}

		// Filter by organization type if provided
		if ($request->filled('organization_type_id')) {
			$query->where('organization_type_id', $request->organization_type_id);
		}

		// Filter by zilla if provided
		if ($request->filled('zilla_id')) {
			$query->where('zilla_id', $request->zilla_id);
		}

		$reliefApplications = $query->orderBy('created_at', 'desc')->paginate(15);
		$reliefTypes = ReliefType::where('is_active', true)->ordered()->get();
		$organizationTypes = OrganizationType::ordered()->get();
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();

		return view('relief-applications.index', compact('reliefApplications', 'reliefTypes', 'organizationTypes', 'zillas'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		$organizationTypes = OrganizationType::ordered()->get();
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();
		$reliefTypes = ReliefType::where('is_active', true)->ordered()->get();
		$reliefItems = ReliefItem::where('is_active', true)->orderBy('type')->orderBy('name')->get();

		return view('relief-applications.create', compact('organizationTypes', 'zillas', 'reliefTypes', 'reliefItems'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(ReliefApplicationRequest $request): RedirectResponse
	{
		$validated = $request->validated();

		// Handle file upload
		if ($request->hasFile('application_file')) {
			$file = $request->file('application_file');
			$fileName = time() . '_' . $file->getClientOriginalName();
			$filePath = $file->storeAs('relief-applications', $fileName, 'public');
			$validated['application_file'] = $filePath;
		}

		// Calculate total amount from relief items (only monetary items)
		$totalAmount = 0;
		if ($request->has('relief_items')) {
			foreach ($request->relief_items as $item) {
				if (isset($item['quantity_requested']) && isset($item['unit_price']) && !empty($item['unit_price'])) {
					$totalAmount += floatval($item['quantity_requested']) * floatval($item['unit_price']);
				}
			}
		}
		$validated['amount_requested'] = $totalAmount;

		// Create the relief application
		$reliefApplication = ReliefApplication::create($validated);

		// Create relief application items
		if ($request->has('relief_items')) {
			foreach ($request->relief_items as $item) {
				if (!empty($item['relief_item_id']) && !empty($item['quantity_requested'])) {
					$unitPrice = $item['unit_price'] ?? 0;
					$totalAmount = floatval($item['quantity_requested']) * floatval($unitPrice);
					
					ReliefApplicationItem::create([
						'relief_application_id' => $reliefApplication->id,
						'relief_item_id' => $item['relief_item_id'],
						'quantity_requested' => $item['quantity_requested'],
						'unit_price' => $unitPrice,
						'total_amount' => $totalAmount,
						'remarks' => $item['remarks'] ?? null,
					]);
				}
			}
		}

		return redirect()->route('relief-applications.index')
			->with('success', 'Relief application submitted successfully.');
	}

	/**
	 * Display the specified resource.
	 */
	public function show(ReliefApplication $reliefApplication): View
	{
		$reliefApplication->load(['organizationType', 'zilla', 'upazila', 'union', 'ward', 'reliefType']);

		return view('relief-applications.show', compact('reliefApplication'));
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(ReliefApplication $reliefApplication): View
	{
		$organizationTypes = OrganizationType::ordered()->get();
		$zillas = Zilla::where('is_active', true)->orderBy('name')->get();
		$upazilas = Upazila::where('zilla_id', $reliefApplication->zilla_id)->where('is_active', true)->orderBy('name')->get();
		$unions = Union::where('upazila_id', $reliefApplication->upazila_id)->where('is_active', true)->orderBy('name')->get();
		$wards = Ward::where('union_id', $reliefApplication->union_id)->where('is_active', true)->orderBy('name')->get();
		$reliefTypes = ReliefType::where('is_active', true)->ordered()->get();
		$reliefItems = ReliefItem::where('is_active', true)->orderBy('type')->orderBy('name')->get();

		return view('relief-applications.edit', compact('reliefApplication', 'organizationTypes', 'zillas', 'upazilas', 'unions', 'wards', 'reliefTypes', 'reliefItems'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(ReliefApplicationRequest $request, ReliefApplication $reliefApplication): RedirectResponse
	{
		$validated = $request->validated();

		// Handle file upload
		if ($request->hasFile('application_file')) {
			// Delete old file if exists
			if ($reliefApplication->application_file) {
				Storage::disk('public')->delete($reliefApplication->application_file);
			}

			$file = $request->file('application_file');
			$fileName = time() . '_' . $file->getClientOriginalName();
			$filePath = $file->storeAs('relief-applications', $fileName, 'public');
			$validated['application_file'] = $filePath;
		}

		// Calculate total amount from relief items (only monetary items)
		$totalAmount = 0;
		if ($request->has('relief_items')) {
			foreach ($request->relief_items as $item) {
				if (isset($item['quantity_requested']) && isset($item['unit_price']) && !empty($item['unit_price'])) {
					$totalAmount += floatval($item['quantity_requested']) * floatval($item['unit_price']);
				}
			}
		}
		$validated['amount_requested'] = $totalAmount;

		// Update the relief application
		$reliefApplication->update($validated);

		// Delete existing relief application items
		$reliefApplication->reliefApplicationItems()->delete();

		// Create new relief application items
		if ($request->has('relief_items')) {
			foreach ($request->relief_items as $item) {
				if (!empty($item['relief_item_id']) && !empty($item['quantity_requested'])) {
					$unitPrice = $item['unit_price'] ?? 0;
					$totalAmount = floatval($item['quantity_requested']) * floatval($unitPrice);
					
					ReliefApplicationItem::create([
						'relief_application_id' => $reliefApplication->id,
						'relief_item_id' => $item['relief_item_id'],
						'quantity_requested' => $item['quantity_requested'],
						'unit_price' => $unitPrice,
						'total_amount' => $totalAmount,
						'remarks' => $item['remarks'] ?? null,
					]);
				}
			}
		}

		return redirect()->route('relief-applications.index')
			->with('success', 'Relief application updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(ReliefApplication $reliefApplication): RedirectResponse
	{
		// Delete file if exists
		if ($reliefApplication->application_file) {
			Storage::disk('public')->delete($reliefApplication->application_file);
		}

		$reliefApplication->delete();

		return redirect()->route('relief-applications.index')
			->with('success', 'Relief application deleted successfully.');
	}

	/**
	 * Get upazilas by zilla ID for dynamic dropdowns.
	 */
	public function getUpazilasByZilla(Zilla $zilla)
	{
		$upazilas = $zilla->upazilas()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'name_bn']);
		return response()->json($upazilas);
	}

	/**
	 * Get unions by upazila ID for dynamic dropdowns.
	 */
	public function getUnionsByUpazila(Upazila $upazila)
	{
		$unions = $upazila->unions()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'name_bn']);
		return response()->json($unions);
	}

	/**
	 * Get wards by union ID for dynamic dropdowns.
	 */
	public function getWardsByUnion(Union $union)
	{
		$wards = $union->wards()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'name_bn']);
		return response()->json($wards);
	}
}