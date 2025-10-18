<?php

namespace App\Exports;

use App\Models\Project;
use App\Models\EconomicYear;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DetailedProjectsDistributionExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
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

		$search = $this->filters['search'] ?? '';

		$start = $selectedYear?->start_date;
		$end = $selectedYear?->end_date;

		$query = Project::with(['reliefType', 'economicYear'])
			->when($selectedYear, fn($q) => $q->where('economic_year_id', $selectedYear->id))
			->when($search, function ($q) use ($search) {
				return $q->where(function ($subQuery) use ($search) {
					$subQuery->where('name', 'LIKE', "%{$search}%")
						->orWhereHas('reliefType', function ($rtQuery) use ($search) {
							$rtQuery->where('name', 'LIKE', "%{$search}%")
								->orWhere('name_bn', 'LIKE', "%{$search}%");
						});
				});
			})
			->orderBy('name');

		$projects = $query->get();

		return $projects->map(function ($project) use ($start, $end) {
			$applicationsQuery = \App\Models\ReliefApplication::where('status', 'approved')
				->where('project_id', $project->id)
				->when($start && $end, function ($q) use ($start, $end) {
					$s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
					$e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
					return $q->whereBetween('approved_at', [$s, $e]);
				});

			$totalApplications = $applicationsQuery->count();
			$totalDistributed = $applicationsQuery->sum('approved_amount');
			$availableAmount = $project->allocated_amount - $totalDistributed;
			$utilizationPercentage = $project->allocated_amount > 0 ? ($totalDistributed / $project->allocated_amount) * 100 : 0;

			return [
				'project' => $project,
				'total_applications' => $totalApplications,
				'total_distributed' => $totalDistributed,
				'available_amount' => $availableAmount,
				'utilization_percentage' => $utilizationPercentage,
			];
		});
	}

	/**
	 * @return array
	 */
	public function headings(): array
	{
		return [
			'ক্রমিক',
			'প্রকল্পের নাম',
			'ত্রাণের ধরন',
			'অর্থবছর',
			'বরাদ্দকৃত পরিমাণ',
			'বিতরণকৃত পরিমাণ',
			'অবশিষ্ট পরিমাণ',
			'ব্যবহারের হার (%)',
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
			$row['project']->name,
			localized_attr($row['project']->reliefType, 'name') ?? '',
			localized_attr($row['project']->economicYear, 'name') ?? '',
			bn_number(number_format($row['project']->allocated_amount, 2)),
			bn_number(number_format($row['total_distributed'], 2)),
			bn_number(number_format($row['available_amount'], 2)),
			bn_number(number_format($row['utilization_percentage'], 1)),
			bn_number($row['total_applications']),
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
			'B' => 30,
			'C' => 15,
			'D' => 15,
			'E' => 15,
			'F' => 15,
			'G' => 15,
			'H' => 12,
			'I' => 15,
		];
	}
}
