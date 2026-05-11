<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class UserRbacCleanupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_does_not_have_role_column_in_users_table()
    {
        $this->assertFalse(Schema::hasColumn('users', 'role'));
    }

    /** @test */
    public function test_it_does_not_have_role_in_fillable()
    {
        $user = new User();
        $this->assertNotContains('role', $user->getFillable());
    }

    /** @test */
    public function test_spatie_roles_still_work()
    {
        $role = Role::create(['name' => 'Test Role']);
        $user = User::factory()->create();
        
        $user->assignRole($role);

        $this->assertTrue($user->hasRole('Test Role'));
        $this->assertCount(1, $user->roles);
    }
}
