<?php

namespace App\Exports;

use App\Models\ReliefApplication;
use App\Models\EconomicYear;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ConsolidatedDistributionExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
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
		$selectedUnionId = $this->filters['union_id'] ?? null;
		$selectedProjectId = $this->filters['project_id'] ?? null;

		$start = $selectedYear?->start_date;
		$end = $selectedYear?->end_date;

		$query = ReliefApplication::where('status', 'approved')
			->with(['project.reliefType', 'zilla', 'upazila', 'union', 'organizationType'])
			->when($start && $end, function ($q) use ($start, $end) {
				$s = $start instanceof \Carbon\Carbon ? $start->copy()->startOfDay() : \Carbon\Carbon::parse((string) $start)->startOfDay();
				$e = $end instanceof \Carbon\Carbon ? $end->copy()->endOfDay() : \Carbon\Carbon::parse((string) $end)->endOfDay();
				return $q->whereBetween('approved_at', [$s, $e]);
			})
			->when($selectedZillaId, fn($q) => $q->where('zilla_id', $selectedZillaId))
			->when($selectedUpazilaId, fn($q) => $q->where('upazila_id', $selectedUpazilaId))
			->when($selectedUnionId, fn($q) => $q->where('union_id', $selectedUnionId))
			->when($selectedProjectId, fn($q) => $q->where('project_id', $selectedProjectId));

		return $query->orderByDesc('approved_amount')->get();
	}

	/**
	 * @return array
	 */
	public function headings(): array
	{
		return [
			'ক্রমিক',
			'সংস্থার নাম',
			'তারিখ',
			'জেলা',
			'উপজেলা',
			'ইউনিয়ন',
			'প্রকল্পের নাম',
			'ত্রাণের ধরন',
			'আবেদনকৃত পরিমাণ',
			'অনুমোদিত পরিমাণ',
			'অবস্থা',
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
			$row->date ? bn_date($row->date, 'd/m/Y') : '',
			localized_attr($row->zilla, 'name') ?? '',
			localized_attr($row->upazila, 'name') ?? '',
			localized_attr($row->union, 'name') ?? '',
			$row->project->name ?? '',
			localized_attr($row->reliefType, 'name') ?? '',
			bn_number(number_format($row->amount_requested, 2)),
			bn_number(number_format($row->approved_amount, 2)),
			$row->status === 'approved' ? 'অনুমোদিত' : ($row->status === 'pending' ? 'অপেক্ষমান' : 'প্রত্যাখ্যাত'),
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
			'C' => 12,
			'D' => 15,
			'E' => 15,
			'F' => 15,
			'G' => 25,
			'H' => 15,
			'I' => 15,
			'J' => 15,
			'K' => 12,
		];
	}
}
