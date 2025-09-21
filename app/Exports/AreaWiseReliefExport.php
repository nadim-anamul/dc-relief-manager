<?php

namespace App\Exports;

use App\Models\ReliefApplication;
use App\Models\Zilla;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;

class AreaWiseReliefExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
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
		$query = ReliefApplication::where('status', 'approved')
			->join('zillas', 'relief_applications.zilla_id', '=', 'zillas.id')
			->join('upazilas', 'relief_applications.upazila_id', '=', 'upazilas.id')
			->join('unions', 'relief_applications.union_id', '=', 'unions.id')
			->join('wards', 'relief_applications.ward_id', '=', 'wards.id')
			->join('relief_types', 'relief_applications.relief_type_id', '=', 'relief_types.id')
			->leftJoin('organization_types', 'relief_applications.organization_type_id', '=', 'organization_types.id')
			->select(
				'zillas.name as zilla_name',
				'upazilas.name as upazila_name',
				'unions.name as union_name',
				'wards.name as ward_name',
				'relief_types.name as relief_type_name',
				'organization_types.name as org_type_name',
				DB::raw('SUM(relief_applications.approved_amount) as total_amount'),
				DB::raw('COUNT(*) as application_count'),
				DB::raw('AVG(relief_applications.approved_amount) as avg_amount'),
				DB::raw('MIN(relief_applications.approved_amount) as min_amount'),
				DB::raw('MAX(relief_applications.approved_amount) as max_amount')
			)
			->groupBy(
				'zillas.id', 'zillas.name',
				'upazilas.id', 'upazilas.name',
				'unions.id', 'unions.name',
				'wards.id', 'wards.name',
				'relief_types.id', 'relief_types.name',
				'organization_types.id', 'organization_types.name'
			)
			->orderBy('total_amount', 'desc');

		// Apply filters
		if (isset($this->filters['zilla_id'])) {
			$query->where('zillas.id', $this->filters['zilla_id']);
		}

		if (isset($this->filters['relief_type_id'])) {
			$query->where('relief_types.id', $this->filters['relief_type_id']);
		}

		if (isset($this->filters['organization_type_id'])) {
			$query->where('organization_types.id', $this->filters['organization_type_id']);
		}

		if (isset($this->filters['start_date'])) {
			$query->where('relief_applications.approved_at', '>=', $this->filters['start_date']);
		}

		if (isset($this->filters['end_date'])) {
			$query->where('relief_applications.approved_at', '<=', $this->filters['end_date']);
		}

		return $query->get();
	}

	/**
	 * @return array
	 */
	public function headings(): array
	{
		return [
			'Zilla',
			'Upazila',
			'Union',
			'Ward',
			'Relief Type',
			'Organization Type',
			'Total Amount',
			'Application Count',
			'Average Amount',
			'Minimum Amount',
			'Maximum Amount',
		];
	}

	/**
	 * @param mixed $item
	 * @return array
	 */
	public function map($item): array
	{
		return [
			$item->zilla_name,
			$item->upazila_name,
			$item->union_name,
			$item->ward_name,
			$item->relief_type_name,
			$item->org_type_name ?? 'Not Specified',
			$item->total_amount,
			$item->application_count,
			round($item->avg_amount, 2),
			$item->min_amount,
			$item->max_amount,
		];
	}

	/**
	 * @param Worksheet $sheet
	 * @return array
	 */
	public function styles(Worksheet $sheet)
	{
		return [
			// Style the first row as bold
			1 => [
				'font' => [
					'bold' => true,
					'size' => 12,
				],
				'fill' => [
					'fillType' => Fill::FILL_SOLID,
					'startColor' => ['rgb' => 'FFF3E0'],
				],
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
					'vertical' => Alignment::VERTICAL_CENTER,
				],
			],
		];
	}

	/**
	 * @return array
	 */
	public function columnWidths(): array
	{
		return [
			'A' => 20, // Zilla
			'B' => 20, // Upazila
			'C' => 20, // Union
			'D' => 20, // Ward
			'E' => 20, // Relief Type
			'F' => 20, // Organization Type
			'G' => 15, // Total Amount
			'H' => 15, // Application Count
			'I' => 15, // Average Amount
			'J' => 15, // Minimum Amount
			'K' => 15, // Maximum Amount
		];
	}

	/**
	 * @return array
	 */
	public function registerEvents(): array
	{
		return [
			AfterSheet::class => function(AfterSheet $event) {
				$sheet = $event->sheet->getDelegate();
				
				// Add borders to all cells
				$sheet->getStyle('A1:K' . ($sheet->getHighestRow()))
					->getBorders()
					->getAllBorders()
					->setBorderStyle(Border::BORDER_THIN);

				// Auto-fit columns
				foreach (range('A', 'K') as $column) {
					$sheet->getColumnDimension($column)->setAutoSize(true);
				}
			},
		];
	}
}
