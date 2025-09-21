<?php

namespace Database\Seeders;

use App\Models\ReliefApplication;
use App\Models\ReliefApplicationItem;
use App\Models\ReliefItem;
use App\Models\Project;
use App\Models\OrganizationType;
use App\Models\Zilla;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\Ward;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReliefApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing data
        $projects = Project::all();
        $reliefItems = ReliefItem::all();
        $organizationTypes = OrganizationType::all();
        $zillas = Zilla::all();
        $upazilas = Upazila::all();
        $unions = Union::all();
        $wards = Ward::all();
        $users = User::all();

        if ($projects->isEmpty() || $reliefItems->isEmpty() || $organizationTypes->isEmpty() || 
            $zillas->isEmpty() || $upazilas->isEmpty() || $unions->isEmpty() || $wards->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Required data not found. Please run other seeders first.');
            return;
        }

        // Create sample relief applications with multiple items
        $this->createSampleApplications($projects, $reliefItems, $organizationTypes, $zillas, $upazilas, $unions, $wards, $users);
    }

    private function createSampleApplications($projects, $reliefItems, $organizationTypes, $zillas, $upazilas, $unions, $wards, $users)
    {
        // Sample application 1: Flood Relief
        $application1 = ReliefApplication::create([
            'organization_name' => 'Bogura Flood Relief Committee',
            'organization_type_id' => $organizationTypes->first()->id,
            'date' => '2024-08-15',
            'zilla_id' => $zillas->first()->id,
            'upazila_id' => $upazilas->first()->id,
            'union_id' => $unions->first()->id,
            'ward_id' => $wards->first()->id,
            'subject' => 'Emergency Relief for Flood Victims',
            'relief_type_id' => 1,
            'project_id' => $projects->first()->id,
            'applicant_name' => 'Md. Abdul Rahman',
            'applicant_designation' => 'Secretary',
            'applicant_phone' => '01712345678',
            'applicant_address' => 'Village: Charpara, Bogura',
            'organization_address' => 'Office: Bogura Sadar, Bogura',
            'amount_requested' => 500000.00,
            'details' => 'Requesting relief for 200 flood-affected families in our area.',
            'status' => 'approved',
            'approved_amount' => 450000.00,
            'admin_remarks' => 'Approved with reduced amount due to budget constraints.',
            'approved_by' => $users->first()->id,
            'approved_at' => '2024-08-20 10:30:00',
            'created_by' => $users->first()->id,
        ]);

        // Add relief items for application 1
        $this->addReliefItems($application1, $reliefItems, [
            ['item' => 'Rice', 'quantity_requested' => 5.0, 'quantity_approved' => 4.5, 'unit_price' => 45000.00],
            ['item' => 'Cash Relief', 'quantity_requested' => 200000.00, 'quantity_approved' => 180000.00, 'unit_price' => 1.00],
            ['item' => 'Blanket', 'quantity_requested' => 200, 'quantity_approved' => 150, 'unit_price' => 800.00],
            ['item' => 'Tarpaulin', 'quantity_requested' => 100, 'quantity_approved' => 80, 'unit_price' => 1200.00],
        ]);

        // Sample application 2: Cyclone Relief
        $application2 = ReliefApplication::create([
            'organization_name' => 'Cyclone Preparedness Society',
            'organization_type_id' => $organizationTypes->first()->id,
            'date' => '2024-09-10',
            'zilla_id' => $zillas->first()->id,
            'upazila_id' => $upazilas->skip(1)->first()->id,
            'union_id' => $unions->skip(1)->first()->id,
            'ward_id' => $wards->skip(1)->first()->id,
            'subject' => 'Cyclone Preparedness Materials',
            'relief_type_id' => 2,
            'project_id' => $projects->skip(1)->first()->id,
            'applicant_name' => 'Fatema Begum',
            'applicant_designation' => 'President',
            'applicant_phone' => '01876543210',
            'applicant_address' => 'Village: Nandigram, Bogura',
            'organization_address' => 'Office: Nandigram Upazila, Bogura',
            'amount_requested' => 300000.00,
            'details' => 'Need materials for cyclone preparedness and emergency shelter.',
            'status' => 'pending',
            'created_by' => $users->first()->id,
        ]);

        // Add relief items for application 2
        $this->addReliefItems($application2, $reliefItems, [
            ['item' => 'Rice', 'quantity_requested' => 3.0, 'quantity_approved' => null, 'unit_price' => 46000.00],
            ['item' => 'Tent', 'quantity_requested' => 20, 'quantity_approved' => null, 'unit_price' => 15000.00],
            ['item' => 'First Aid Kit', 'quantity_requested' => 50, 'quantity_approved' => null, 'unit_price' => 2500.00],
            ['item' => 'Cash Relief', 'quantity_requested' => 100000.00, 'quantity_approved' => null, 'unit_price' => 1.00],
        ]);

        $this->command->info('Created 2 sample relief applications with multiple items.');
    }

    private function addReliefItems($application, $reliefItems, $itemsData)
    {
        foreach ($itemsData as $itemData) {
            $reliefItem = $reliefItems->where('name', $itemData['item'])->first();
            
            if ($reliefItem) {
                $totalAmount = null;
                if ($itemData['quantity_approved'] && $itemData['unit_price']) {
                    $totalAmount = $itemData['quantity_approved'] * $itemData['unit_price'];
                }

                ReliefApplicationItem::create([
                    'relief_application_id' => $application->id,
                    'relief_item_id' => $reliefItem->id,
                    'quantity_requested' => $itemData['quantity_requested'],
                    'quantity_approved' => $itemData['quantity_approved'],
                    'unit_price' => $itemData['unit_price'],
                    'total_amount' => $totalAmount,
                    'remarks' => 'Relief item for ' . $application->subject,
                ]);
            }
        }
    }
}
