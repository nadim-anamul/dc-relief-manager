<?php

namespace Database\Seeders;

use App\Models\EconomicYear;
use App\Models\OrganizationType;
use App\Models\Project;
use App\Models\ReliefApplication;
use App\Models\ReliefApplicationItem;
use App\Models\ReliefItem;
use App\Models\ReliefType;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Zilla;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ComprehensiveDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating comprehensive test data...');

        // Ensure base reference data exists (organization types and geo data)
        $this->call([
            OrganizationTypeSeeder::class,
            ZillaSeeder::class,
            UpazilaSeeder::class,
            UnionSeeder::class,
            WardSeeder::class,
        ]);

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

        // Create Roles and attach permissions
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full system access with all permissions',
                'permissions' => Permission::all()->pluck('name')->toArray(),
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
                ],
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
                ],
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
                ],
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Admin user with broad management rights',
                'permissions' => [
                    'relief-applications.view',
                    'relief-applications.create',
                    'relief-applications.update',
                    'relief-applications.delete',
                    'projects.view',
                    'projects.create',
                    'projects.update',
                    'projects.delete',
                    'zillas.manage',
                    'upazilas.manage',
                    'unions.manage',
                    'wards.manage',
                    'dashboard.view',
                ],
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Default user with minimal access',
                'permissions' => [
                    'dashboard.view',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $permissionNames = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::firstOrCreate(
                ['name' => $roleData['slug']],
                [
                    'name' => $roleData['slug'],
                    'guard_name' => 'web'
                ]
            );

            $permissionModels = Permission::whereIn('name', $permissionNames)->get();
            $role->syncPermissions($permissionModels);
        }

        // Create Users and assign roles
        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@dcrelief.com', 'password' => 'password123', 'role' => 'super-admin'],
            ['name' => 'District Admin', 'email' => 'districtadmin@dcrelief.com', 'password' => 'password123', 'role' => 'district-admin'],
            ['name' => 'Data Entry', 'email' => 'dataentry@dcrelief.com', 'password' => 'password123', 'role' => 'data-entry'],
            ['name' => 'Viewer User', 'email' => 'viewer@dcrelief.com', 'password' => 'password123', 'role' => 'viewer'],
            ['name' => 'Test User', 'email' => 'test@example.com', 'password' => 'password', 'role' => 'user'],
            ['name' => 'Admin User', 'email' => 'admin@dcrelief.com', 'password' => 'admin123', 'role' => 'admin'],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'is_approved' => true,
                ]
            );
            if (! $user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }
        }

        // Create Economic Years (2025-2026 active, 2024-2025, 2023-2024)
        $yearsConfig = [
            [
                'name' => '2025-2026',
                'start' => '2025-07-01',
                'end' => '2026-06-30',
                'active' => true,
                'usage_ratio' => 0.70,
            ],
            [
                'name' => '2024-2025',
                'start' => '2024-07-01',
                'end' => '2025-06-30',
                'active' => false,
                'usage_ratio' => 1.00,
            ],
            [
                'name' => '2023-2024',
                'start' => '2023-07-01',
                'end' => '2024-06-30',
                'active' => false,
                'usage_ratio' => 1.00,
            ],
        ];

        // Create Relief Types with proper units
        $reliefTypes = [
            [
                'name' => 'Cash Relief',
                'name_bn' => 'নগদ ত্রাণ',
                'unit' => 'Taka',
                'unit_bn' => 'টাকা',
                'color_code' => '#10B981',
                'description' => 'Direct cash assistance to beneficiaries',
                'is_active' => true,
            ],
            [
                'name' => 'Food Relief',
                'name_bn' => 'খাদ্য ত্রাণ',
                'unit' => 'KG',
                'unit_bn' => 'কেজি',
                'color_code' => '#F59E0B',
                'description' => 'Food items and essential supplies',
                'is_active' => true,
            ],
            [
                'name' => 'Medical Relief',
                'name_bn' => 'চিকিৎসা ত্রাণ',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#EF4444',
                'description' => 'Medical supplies and health assistance',
                'is_active' => true,
            ],
            [
                'name' => 'Shelter Relief',
                'name_bn' => 'আশ্রয় ত্রাণ',
                'unit' => 'Piece',
                'unit_bn' => 'পিস',
                'color_code' => '#3B82F6',
                'description' => 'Housing and shelter materials',
                'is_active' => true,
            ],
            [
                'name' => 'Educational Relief',
                'name_bn' => 'শিক্ষা ত্রাণ',
                'unit' => 'Set',
                'unit_bn' => 'সেট',
                'color_code' => '#8B5CF6',
                'description' => 'Educational materials and supplies',
                'is_active' => true,
            ],
        ];

        $createdReliefTypes = [];
        foreach ($reliefTypes as $reliefType) {
            $createdReliefTypes[] = ReliefType::create($reliefType);
        }

        // Create Relief Items
        $reliefItems = [
            // Food Items
            [
                'name' => 'Rice',
                'name_bn' => 'চাল',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'High quality rice for food relief',
                'is_active' => true,
            ],
            [
                'name' => 'Lentils',
                'name_bn' => 'ডাল',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'Nutritious lentils for food relief',
                'is_active' => true,
            ],
            [
                'name' => 'Cooking Oil',
                'name_bn' => 'রান্নার তেল',
                'type' => 'food',
                'unit' => 'liter',
                'description' => 'Cooking oil for food preparation',
                'is_active' => true,
            ],
            [
                'name' => 'Salt',
                'name_bn' => 'লবণ',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'Iodized salt for food relief',
                'is_active' => true,
            ],
            [
                'name' => 'Sugar',
                'name_bn' => 'চিনি',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'Refined sugar for food relief',
                'is_active' => true,
            ],
            // Medical Items
            [
                'name' => 'First Aid Kit',
                'name_bn' => 'প্রাথমিক চিকিৎসা কিট',
                'type' => 'medical',
                'unit' => 'package',
                'description' => 'Essential first aid supplies',
                'is_active' => true,
            ],
            [
                'name' => 'Medicine Kit',
                'name_bn' => 'ঔষধ কিট',
                'type' => 'medical',
                'unit' => 'package',
                'description' => 'Basic medicine supplies',
                'is_active' => true,
            ],
            // Shelter Items
            [
                'name' => 'Tent',
                'name_bn' => 'তাঁবু',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'Emergency shelter tent',
                'is_active' => true,
            ],
            [
                'name' => 'Blanket',
                'name_bn' => 'কম্বল',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'Warm blanket for shelter relief',
                'is_active' => true,
            ],
            [
                'name' => 'Mosquito Net',
                'name_bn' => 'মশারি',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'Mosquito net for protection',
                'is_active' => true,
            ],
            // Educational Items
            [
                'name' => 'School Bag',
                'name_bn' => 'স্কুল ব্যাগ',
                'type' => 'other',
                'unit' => 'piece',
                'description' => 'School bag for students',
                'is_active' => true,
            ],
            [
                'name' => 'Notebook Set',
                'name_bn' => 'খাতা সেট',
                'type' => 'other',
                'unit' => 'set',
                'description' => 'Set of notebooks for students',
                'is_active' => true,
            ],
            // Monetary
            [
                'name' => 'Cash Relief',
                'name_bn' => 'নগদ ত্রাণ',
                'type' => 'monetary',
                'unit' => 'BDT',
                'description' => 'Direct cash assistance',
                'is_active' => true,
            ],
        ];

        $createdReliefItems = [];
        foreach ($reliefItems as $reliefItem) {
            $createdReliefItems[] = ReliefItem::create($reliefItem);
        }

        // Get existing Organization Types
        $createdOrgTypes = OrganizationType::all();

        // Geographic scopes for applications
        $zillas = Zilla::take(10)->get();
        $upazilas = Upazila::take(25)->get();
        $unions = Union::take(40)->get();
        $wards = Ward::take(60)->get();

        $totalProjectsCreated = 0;
        $totalApplicationsCreated = 0;

        foreach ($yearsConfig as $yearCfg) {
            $year = EconomicYear::create([
                'name' => $yearCfg['name'],
                'start_date' => Carbon::parse($yearCfg['start']),
                'end_date' => Carbon::parse($yearCfg['end']),
                'is_active' => $yearCfg['active'],
            ]);

            // Create a set of projects per relief type for this year
            $projectsForYear = [
                [
                    'name' => 'Emergency Food Assistance Program ' . substr($yearCfg['name'], 0, 4) . '-' . substr($yearCfg['name'], -2),
                    'remarks' => 'Comprehensive food relief program for vulnerable communities',
                    'relief_type_id' => $createdReliefTypes[1]->id,
                    'economic_year_id' => $year->id,
                    'allocated_amount' => 5000000.00,
                    'available_amount' => 5000000.00,
                ],
                [
                    'name' => 'Cash Transfer Initiative ' . substr($yearCfg['name'], 0, 4) . '-' . substr($yearCfg['name'], -2),
                    'remarks' => 'Direct cash assistance for families in need',
                    'relief_type_id' => $createdReliefTypes[0]->id,
                    'economic_year_id' => $year->id,
                    'allocated_amount' => 3500000.00,
                    'available_amount' => 3500000.00,
                ],
                [
                    'name' => 'Medical Relief Program ' . substr($yearCfg['name'], 0, 4) . '-' . substr($yearCfg['name'], -2),
                    'remarks' => 'Medical supplies and health assistance program',
                    'relief_type_id' => $createdReliefTypes[2]->id,
                    'economic_year_id' => $year->id,
                    'allocated_amount' => 2500000.00,
                    'available_amount' => 2500000.00,
                ],
                [
                    'name' => 'Shelter Support Program ' . substr($yearCfg['name'], 0, 4) . '-' . substr($yearCfg['name'], -2),
                    'remarks' => 'Housing and shelter materials assistance',
                    'relief_type_id' => $createdReliefTypes[3]->id,
                    'economic_year_id' => $year->id,
                    'allocated_amount' => 4000000.00,
                    'available_amount' => 4000000.00,
                ],
                [
                    'name' => 'Education Support Program ' . substr($yearCfg['name'], 0, 4) . '-' . substr($yearCfg['name'], -2),
                    'remarks' => 'Educational materials and supplies assistance',
                    'relief_type_id' => $createdReliefTypes[4]->id,
                    'economic_year_id' => $year->id,
                    'allocated_amount' => 1800000.00,
                    'available_amount' => 1800000.00,
                ],
            ];

            $createdProjectsForYear = [];
            foreach ($projectsForYear as $projectData) {
                $createdProjectsForYear[] = Project::create($projectData);
            }
            $totalProjectsCreated += count($createdProjectsForYear);

            // Target usage for this year
            $yearUsageRatio = $yearCfg['usage_ratio'];

            // Create applications per project to meet usage target, with mixed statuses
            foreach ($createdProjectsForYear as $project) {
                $allocated = $project->allocated_amount;
                $targetApprovedAmount = round($allocated * $yearUsageRatio, 2);
                $approvedSoFar = 0.0;

                // We'll generate a base number of applications, ensuring enough approved to hit the target
                $applicationsToGenerate = 20; // per project

                for ($i = 0; $i < $applicationsToGenerate; $i++) {
                    $zilla = $zillas->random();
                    $upazilasForZilla = $upazilas->where('zilla_id', $zilla->id);
                    $upazila = $upazilasForZilla->count() > 0 ? $upazilasForZilla->random() : $upazilas->random();

                    $unionsForUpazila = $unions->where('upazila_id', $upazila->id);
                    $union = $unionsForUpazila->count() > 0 ? $unionsForUpazila->random() : $unions->random();

                    $wardsForUnion = $wards->where('union_id', $union->id);
                    $ward = $wardsForUnion->count() > 0 ? $wardsForUnion->random() : $wards->random();

                    $reliefType = collect($createdReliefTypes)->firstWhere('id', $project->relief_type_id);
                    $organizationType = $createdOrgTypes->random();

                    // Requested amount scale based on relief type unit
                    $requestedBase = match($reliefType->unit) {
                        'Taka' => rand(10000, 80000),
                        'KG' => rand(50, 300),
                        'Package' => rand(2, 15),
                        'Piece' => rand(10, 80),
                        'Set' => rand(5, 30),
                        default => rand(1000, 10000)
                    };

                    // Determine status with bias but ensure we can meet target
                    $statusPool = ['pending', 'approved', 'rejected'];
                    $statusWeights = [20, 60, 20];
                    $status = $statusPool[array_rand($statusPool, 1)];

                    // Force approval if we still need to reach target and we are near the end
                    $remainingNeeded = max(0, $targetApprovedAmount - $approvedSoFar);
                    $remainingApps = $applicationsToGenerate - $i;
                    if ($remainingNeeded > 0 && $remainingApps <= 3) {
                        $status = 'approved';
                    }

                    // Date within year
                    $createdAt = fake()->dateTimeBetween($year->start_date, $year->end_date);

                    $application = ReliefApplication::create([
                        'organization_name' => fake()->company(),
                        'organization_type_id' => $organizationType->id,
                        'date' => $createdAt,
                        'zilla_id' => $zilla->id,
                        'upazila_id' => $upazila->id,
                        'union_id' => $union->id,
                        'ward_id' => $ward->id,
                        'subject' => fake()->sentence(8),
                        'relief_type_id' => $reliefType->id,
                        'project_id' => $project->id,
                        'applicant_name' => fake()->name(),
                        'applicant_designation' => fake()->jobTitle(),
                        'applicant_phone' => fake()->phoneNumber(),
                        'applicant_address' => fake()->address(),
                        'organization_address' => fake()->address(),
                        'amount_requested' => $requestedBase,
                        'details' => fake()->sentence(10),
                        'admin_remarks' => fake()->sentence(8),
                        'status' => $status,
                        'created_at' => $createdAt,
                    ]);

                    if ($status === 'approved') {
                        // Approve an amount but keep within remaining target for precise control
                        $maxThisApproval = max(0.0, $targetApprovedAmount - $approvedSoFar);
                        if ($maxThisApproval <= 0.0) {
                            // If target met, convert to pending to avoid overshoot
                            $application->update(['status' => 'pending']);
                        } else {
                            $approvedAmount = 0.0;
                            if ($reliefType->unit === 'Taka') {
                                // Cash approval as money
                                $approvedAmount = min($requestedBase * (rand(80, 100) / 100), $maxThisApproval);
                            } else {
                                // For goods, translate quantity and unit price to total money for bookkeeping
                                $approvedAmount = min($requestedBase * (rand(50, 100) / 100) * rand(50, 300), $maxThisApproval);
                            }

                            $approvedAmount = round($approvedAmount, 2);
                            $approvedSoFar += $approvedAmount;
                            $application->update([
                                'approved_amount' => $approvedAmount,
                                'approved_at' => Carbon::parse($createdAt)->addDays(rand(1, 30)),
                            ]);

                            // Update project's available amount
                            $project->decrement('available_amount', $approvedAmount);
                        }
                    }

                    // Add relief items for non-cash applications
                    if ($reliefType->unit !== 'Taka' && $reliefType->unit !== 'টাকা') {
                        $itemType = match($reliefType->name) {
                            'Food Relief' => 'food',
                            'Medical Relief' => 'medical',
                            'Shelter Relief' => 'shelter',
                            'Educational Relief' => 'other',
                            default => 'food'
                        };

                        $relevantItems = collect($createdReliefItems)->where('type', $itemType);
                        if ($relevantItems->count() > 0) {
                            $item = $relevantItems->random();
                            $quantityRequested = rand(5, 80);
                            $quantityApproved = ($application->status === 'approved') ? rand(5, $quantityRequested) : 0;
                            $unitPrice = match($item->type) {
                                'food' => rand(20, 150),
                                'medical' => rand(500, 2000),
                                'shelter' => rand(300, 5000),
                                'other' => rand(100, 1000),
                                default => rand(50, 500)
                            };

                            ReliefApplicationItem::create([
                                'relief_application_id' => $application->id,
                                'relief_item_id' => $item->id,
                                'quantity_requested' => $quantityRequested,
                                'quantity_approved' => $quantityApproved,
                                'unit_price' => $unitPrice,
                                'total_amount' => $quantityApproved * $unitPrice,
                            ]);
                        }
                    }

                    $totalApplicationsCreated++;
                }

                // For 100% usage years, ensure available_amount is 0 (in case of rounding)
                if (abs($yearUsageRatio - 1.0) < 0.0001) {
                    $project->update(['available_amount' => 0.0]);
                }
            }
        }

        $this->command->info('Comprehensive test data created successfully!');
        $this->command->info('- Created ' . count($createdReliefTypes) . ' relief types');
        $this->command->info('- Created ' . count($createdReliefItems) . ' relief items');
        $this->command->info('- Created ' . count($createdOrgTypes) . ' organization types');
        $this->command->info('- Created ' . $totalProjectsCreated . ' projects');
        $this->command->info('- Created ' . $totalApplicationsCreated . ' relief applications with realistic data');
    }
}
