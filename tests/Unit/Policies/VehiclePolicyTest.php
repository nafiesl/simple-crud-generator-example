<?php

namespace Tests\Unit\Policies;

use App\Vehicle;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\BrowserKitTest as TestCase;

class VehiclePolicyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_create_vehicle()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new Vehicle));
    }

    /** @test */
    public function user_can_view_vehicle()
    {
        $user = $this->createUser();
        $vehicle = factory(Vehicle::class)->create();
        $this->assertTrue($user->can('view', $vehicle));
    }

    /** @test */
    public function user_can_update_vehicle()
    {
        $user = $this->createUser();
        $vehicle = factory(Vehicle::class)->create();
        $this->assertTrue($user->can('update', $vehicle));
    }

    /** @test */
    public function user_can_delete_vehicle()
    {
        $user = $this->createUser();
        $vehicle = factory(Vehicle::class)->create();
        $this->assertTrue($user->can('delete', $vehicle));
    }
}
