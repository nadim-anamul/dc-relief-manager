<?php

namespace App\Exports;

use App\Models\EconomicYear;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class DetailedDuplicatesDistributionExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
	protected $filters;

	public function __construct($filters = [])
	{
		$this->filters = $filters;
	}

	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function collection()
	{
		$selectedYear = isset($this->filters['economic_year_id']) 
			? EconomicYear::find($this->filters['economic_year_id'])
			: EconomicYear::where('is_current', true)->first();

		$selectedZillaId = $this->filters['zilla_id'] ?? null;
		$selectedUpazilaId = $this->filters['upazila_id'] ?? null;
		$search = $this->filters['search'] ?? '';

		$start = $selectedYear?->start_date;
		$end = $selectedYear?->end_date;

		$query = DB::table('relief_applications as ra')
			->join('projects as p', 'ra.project_id', '=', 'p.id')
			->join('organization_types as ot', 'ra.organization_type_id', '=', 'ot.id')
			->join('zillas as z', 'ra.zilla_id', '=', 'z.id')
			->join('upazilas as u', 'ra.upazila_id', '=', 'u.id')
			->where('ra.status', 'approved')
			->when($start && $end, function ($q) use ($start, $end) {
				$s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
				$e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
				return $q->whereBetween('ra.approved_at', [$s, $e]);
			})
			->when($selectedZillaId, fn($q) => $q->where('ra.zilla_id', $selectedZillaId))
			->when($selectedUpazilaId, fn($q) => $q->where('ra.upazila_id', $selectedUpazilaId))
			->when($search, function ($q) use ($search) {
				return $q->where(function ($subQuery) use ($search) {
					$subQuery->where('ra.organization_name', 'LIKE', "%{$search}%")
						->orWhere('p.name', 'LIKE', "%{$search}%")
						->orWhere('ot.name', 'LIKE', "%{$search}%")
						->orWhere('z.name', 'LIKE', "%{$search}%")
						->orWhere('u.name', 'LIKE', "%{$search}%");
				});
			})
			->select([
				'ra.organization_name',
				'ot.name as organization_type_name',
				'z.name as zilla_name',
				'u.name as upazila_name',
				DB::raw('COUNT(DISTINCT ra.project_id) as project_count'),
				DB::raw('SUM(ra.approved_amount) as total_amount'),
				DB::raw('COUNT(ra.id) as application_count')
			])
			->groupBy(['ra.organization_name', 'ot.name', 'z.name', 'u.name'])
			->havingRaw('COUNT(DISTINCT ra.project_id) > 1')
			->orderBy('total_amount', 'desc');

		return $query->get();
	}

	/**
	 * @return array
	 */
	public function headings(): array
	{
		return [
			'ক্রমিক',
			'সংস্থার নাম',
			'সংস্থার ধরন',
			'জেলা',
			'উপজেলা',
			'প্রকল্প সংখ্যা',
			'মোট পরিমাণ',
			'আবেদনের সংখ্যা',
		];
	}

	/**
	 * @param mixed $row
	 * @return array
	 */
	public function map($row): array
	{
		static $index = 0;
		$index++;

		return [
			$index,
			$row->organization_name,
			$row->organization_type_name,
			$row->zilla_name,
			$row->upazila_name,
			bn_number($row->project_count),
			bn_number(number_format($row->total_amount, 2)),
			bn_number($row->application_count),
		];
	}

	/**
	 * @param Worksheet $sheet
	 * @return array
	 */
	public function styles(Worksheet $sheet)
	{
		return [
			1 => ['font' => ['bold' => true, 'size' => 12]],
		];
	}

	/**
	 * @return array
	 */
	public function columnWidths(): array
	{
		return [
			'A' => 8,
			'B' => 25,
			'C' => 15,
			'D' => 15,
			'E' => 15,
			'F' => 12,
			'G' => 15,
			'H' => 15,
		];
	}
}
