<?php

use Livewire\Livewire;
use Modules\AdminUser\Models\User;
use App\Livewire\Auth\LoginPage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('super admin can log in successfully via Livewire LoginPage component', function () {
    $this->seed(\Database\Seeders\AscendWorkspaceSeeder::class);

    $user = User::where('email', 'admin@ascendsystems.ng')->first();

    // Perform Livewire authentication call
    Livewire::test(LoginPage::class)
        ->set('identifier', 'admin@ascendsystems.ng')
        ->set('password', 'Password123!')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('portal.dashboard'));

    $this->assertAuthenticatedAs($user);
});
