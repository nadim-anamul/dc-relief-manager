<?php

namespace App\Exports;

use App\Models\Project;
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

class ProjectSummaryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
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
		$query = Project::with([
			'economicYear', 'reliefType', 'createdBy', 'updatedBy'
		]);

		// Apply filters - same as ProjectController
		if (isset($this->filters['economic_year_id'])) {
			$query->where('economic_year_id', $this->filters['economic_year_id']);
		} else {
			$currentYear = \App\Models\EconomicYear::where('is_current', true)->first();
			if ($currentYear) {
				$query->where('economic_year_id', $currentYear->id);
			}
		}

		if (isset($this->filters['relief_type_id'])) {
			$query->where('relief_type_id', $this->filters['relief_type_id']);
		}

		if (isset($this->filters['status'])) {
			switch ($this->filters['status']) {
				case 'active':
					$query->active();
					break;
				case 'completed':
					$query->completed();
					break;
				case 'upcoming':
					$query->upcoming();
					break;
			}
		}

		if (isset($this->filters['start_date'])) {
			$query->whereHas('economicYear', function($q) {
				$q->where('start_date', '>=', $this->filters['start_date']);
			});
		}

		if (isset($this->filters['end_date'])) {
			$query->whereHas('economicYear', function($q) {
				$q->where('end_date', '<=', $this->filters['end_date']);
			});
		}

		return $query->orderBy('created_at', 'desc')->get();
	}

	/**
	 * @return array
	 */
	public function headings(): array
	{
		return [
			'Project ID',
			'Project Name',
			'Economic Year',
			'Relief Type',
			'Budget',
			'Start Date',
			'End Date',
			'Duration (Days)',
			'Duration (Months)',
			'Status',
			'Remarks',
			'Created By',
			'Created At',
			'Updated By',
			'Updated At',
		];
	}

	/**
	 * @param Project $project
	 * @return array
	 */
	public function map($project): array
	{
		return [
			$project->id,
			$project->name,
			$project->economicYear->name,
			$project->reliefType->name,
			$project->allocated_amount,
			$project->economicYear->start_date->format('Y-m-d'),
			$project->economicYear->end_date->format('Y-m-d'),
			$project->duration_in_days,
			$project->duration_in_months,
			$project->is_active ? 'Active' : ($project->is_completed ? 'Completed' : 'Upcoming'),
			$project->remarks ?? '',
			$project->createdBy->name ?? '',
			$project->created_at->format('Y-m-d H:i:s'),
			$project->updatedBy->name ?? '',
			$project->updated_at->format('Y-m-d H:i:s'),
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
					'startColor' => ['rgb' => 'E8F5E8'],
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
			'A' => 12, // Project ID
			'B' => 30, // Project Name
			'C' => 15, // Economic Year
			'D' => 20, // Relief Type
			'E' => 15, // Budget
			'F' => 12, // Start Date
			'G' => 12, // End Date
			'H' => 15, // Duration (Days)
			'I' => 15, // Duration (Months)
			'J' => 12, // Status
			'K' => 30, // Remarks
			'L' => 20, // Created By
			'M' => 20, // Created At
			'N' => 20, // Updated By
			'O' => 20, // Updated At
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
				$sheet->getStyle('A1:O' . ($sheet->getHighestRow()))
					->getBorders()
					->getAllBorders()
					->setBorderStyle(Border::BORDER_THIN);

				// Auto-fit columns
				foreach (range('A', 'O') as $column) {
					$sheet->getColumnDimension($column)->setAutoSize(true);
				}
			},
		];
	}
}
