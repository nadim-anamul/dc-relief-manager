<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DistributionPermissionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Create distribution permissions
		$permissions = [
			['name' => 'View Consolidated Distribution', 'slug' => 'distributions.consolidated', 'description' => 'View consolidated distribution analysis', 'resource' => 'distributions', 'action' => 'consolidated'],
			['name' => 'View Detailed Distribution', 'slug' => 'distributions.detailed', 'description' => 'View detailed distribution analysis', 'resource' => 'distributions', 'action' => 'detailed'],
			['name' => 'View Project Upazila Union Distribution', 'slug' => 'distributions.project-upazila-union', 'description' => 'View project upazila union distribution', 'resource' => 'distributions', 'action' => 'project-upazila-union'],
			['name' => 'View Project Upazila Distribution', 'slug' => 'distributions.project-upazila', 'description' => 'View project upazila distribution', 'resource' => 'distributions', 'action' => 'project-upazila'],
			['name' => 'View Union Summary', 'slug' => 'distributions.union-summary', 'description' => 'View union summary distribution', 'resource' => 'distributions', 'action' => 'union-summary'],
			['name' => 'View Area Summary', 'slug' => 'distributions.area-summary', 'description' => 'View area summary distribution', 'resource' => 'distributions', 'action' => 'area-summary'],
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

		// Assign permissions to roles
		$superAdminRole = Role::where('name', 'super-admin')->first();
		$districtAdminRole = Role::where('name', 'district-admin')->first();
		$dataEntryRole = Role::where('name', 'data-entry')->first();

		$distributionPermissions = Permission::whereIn('name', [
			'distributions.consolidated',
			'distributions.detailed',
			'distributions.project-upazila-union',
			'distributions.project-upazila',
			'distributions.union-summary',
			'distributions.area-summary',
		])->get();

		// Super admin gets all permissions (already handled by existing logic)
		if ($superAdminRole) {
			$superAdminRole->givePermissionTo($distributionPermissions);
		}

		// District admin gets all distribution permissions
		if ($districtAdminRole) {
			$districtAdminRole->givePermissionTo($distributionPermissions);
		}

		// Data entry gets view permissions for distributions
		if ($dataEntryRole) {
			$dataEntryRole->givePermissionTo($distributionPermissions);
		}

		$this->command->info('Distribution permissions created and assigned to roles successfully.');
	}
}
