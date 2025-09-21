<?php

namespace App\Exports;

use App\Models\ReliefApplication;
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

class ReliefApplicationExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
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
		$query = ReliefApplication::with([
			'organizationType', 'zilla', 'upazila', 'union', 'ward', 
			'reliefType', 'project', 'approvedBy', 'rejectedBy',
			'createdBy', 'updatedBy'
		]);

		// Apply filters
		if (isset($this->filters['status']) && $this->filters['status'] !== 'all') {
			$query->where('status', $this->filters['status']);
		}

		if (isset($this->filters['relief_type_id'])) {
			$query->where('relief_type_id', $this->filters['relief_type_id']);
		}

		if (isset($this->filters['organization_type_id'])) {
			$query->where('organization_type_id', $this->filters['organization_type_id']);
		}

		if (isset($this->filters['zilla_id'])) {
			$query->where('zilla_id', $this->filters['zilla_id']);
		}

		if (isset($this->filters['start_date'])) {
			$query->where('date', '>=', $this->filters['start_date']);
		}

		if (isset($this->filters['end_date'])) {
			$query->where('date', '<=', $this->filters['end_date']);
		}

		return $query->orderBy('created_at', 'desc')->get();
	}

	/**
	 * @return array
	 */
	public function headings(): array
	{
		return [
			'Application ID',
			'Organization Name',
			'Organization Type',
			'Application Date',
			'Zilla',
			'Upazila',
			'Union',
			'Ward',
			'Subject',
			'Relief Type',
			'Applicant Name',
			'Applicant Designation',
			'Applicant Phone',
			'Applicant Address',
			'Organization Address',
			'Amount Requested',
			'Approved Amount',
			'Status',
			'Project',
			'Admin Remarks',
			'Approved By',
			'Approved At',
			'Rejected By',
			'Rejected At',
			'Created By',
			'Created At',
			'Updated By',
			'Updated At',
		];
	}

	/**
	 * @param ReliefApplication $reliefApplication
	 * @return array
	 */
	public function map($reliefApplication): array
	{
		return [
			$reliefApplication->id,
			$reliefApplication->organization_name,
			$reliefApplication->organizationType->name ?? 'Not Specified',
			$reliefApplication->date->format('Y-m-d'),
			$reliefApplication->zilla->name,
			$reliefApplication->upazila->name,
			$reliefApplication->union->name,
			$reliefApplication->ward->name,
			$reliefApplication->subject,
			$reliefApplication->reliefType->name,
			$reliefApplication->applicant_name,
			$reliefApplication->applicant_designation ?? '',
			$reliefApplication->applicant_phone,
			$reliefApplication->applicant_address,
			$reliefApplication->organization_address,
			$reliefApplication->amount_requested,
			$reliefApplication->approved_amount ?? '',
			ucfirst($reliefApplication->status),
			$reliefApplication->project->name ?? '',
			$reliefApplication->admin_remarks ?? '',
			$reliefApplication->approvedBy->name ?? '',
			$reliefApplication->approved_at ? $reliefApplication->approved_at->format('Y-m-d H:i:s') : '',
			$reliefApplication->rejectedBy->name ?? '',
			$reliefApplication->rejected_at ? $reliefApplication->rejected_at->format('Y-m-d H:i:s') : '',
			$reliefApplication->createdBy->name ?? '',
			$reliefApplication->created_at->format('Y-m-d H:i:s'),
			$reliefApplication->updatedBy->name ?? '',
			$reliefApplication->updated_at->format('Y-m-d H:i:s'),
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
					'startColor' => ['rgb' => 'E3F2FD'],
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
			'A' => 15, // Application ID
			'B' => 25, // Organization Name
			'C' => 20, // Organization Type
			'D' => 15, // Application Date
			'E' => 20, // Zilla
			'F' => 20, // Upazila
			'G' => 20, // Union
			'H' => 20, // Ward
			'I' => 30, // Subject
			'J' => 20, // Relief Type
			'K' => 25, // Applicant Name
			'L' => 25, // Applicant Designation
			'M' => 15, // Applicant Phone
			'N' => 30, // Applicant Address
			'O' => 30, // Organization Address
			'P' => 15, // Amount Requested
			'Q' => 15, // Approved Amount
			'R' => 12, // Status
			'S' => 25, // Project
			'T' => 30, // Admin Remarks
			'U' => 20, // Approved By
			'V' => 20, // Approved At
			'W' => 20, // Rejected By
			'X' => 20, // Rejected At
			'Y' => 20, // Created By
			'Z' => 20, // Created At
			'AA' => 20, // Updated By
			'AB' => 20, // Updated At
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
				$sheet->getStyle('A1:AB' . ($sheet->getHighestRow()))
					->getBorders()
					->getAllBorders()
					->setBorderStyle(Border::BORDER_THIN);

				// Auto-fit columns
				foreach (range('A', 'AB') as $column) {
					$sheet->getColumnDimension($column)->setAutoSize(true);
				}
			},
		];
	}
}
