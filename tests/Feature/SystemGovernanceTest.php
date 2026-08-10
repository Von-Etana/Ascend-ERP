<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminUser\Models\User;
use Modules\AppAscend\Livewire\AscendModuleViewer;
use Tests\TestCase;

class SystemGovernanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_corporate_organization_profile_with_abuja_hq(): void
    {
        $user = User::factory()->create(['is_super_admin' => true]);

        Livewire::actingAs($user)
            ->test(AscendModuleViewer::class, ['moduleKey' => 'administration'])
            ->set('activeTab', 'organization')
            ->set('orgProfileForm.company_name', 'Ascend Systems Nigeria Ltd')
            ->set('orgProfileForm.headquarters', 'Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.')
            ->call('saveOrgProfile')
            ->assertSee('Corporate Organization Profile updated successfully');
    }

    public function test_can_trigger_enterprise_database_backup(): void
    {
        $user = User::factory()->create(['is_super_admin' => true]);

        Livewire::actingAs($user)
            ->test(AscendModuleViewer::class, ['moduleKey' => 'administration'])
            ->set('activeTab', 'security')
            ->call('triggerDatabaseBackup')
            ->assertSee('Full Enterprise Database Backup completed successfully');
    }
}
