<?php

namespace Tests\Feature;

use App\Vehicle;
use Tests\BrowserKitTest as TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ManageVehicleTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_see_vehicle_list_in_vehicle_index_page()
    {
        $vehicle = factory(Vehicle::class)->create();

        $this->loginAsUser();
        $this->visitRoute('vehicles.index');
        $this->see($vehicle->name);
    }

    /** @test */
    public function user_can_create_a_vehicle()
    {
        $this->loginAsUser();
        $this->visitRoute('vehicles.index');

        $this->click(__('vehicle.create'));
        $this->seeRouteIs('vehicles.index', ['action' => 'create']);

        $this->submitForm(__('vehicle.create'), [
            'name'        => 'Vehicle 1 name',
            'description' => 'Vehicle 1 description',
        ]);

        $this->seeRouteIs('vehicles.index');

        $this->seeInDatabase('vehicles', [
            'name'        => 'Vehicle 1 name',
            'description' => 'Vehicle 1 description',
        ]);
    }

    private function getCreateFields(array $overrides = [])
    {
        return array_merge([
            'name'        => 'Vehicle 1 name',
            'description' => 'Vehicle 1 description',
        ], $overrides);
    }

    /** @test */
    public function validate_vehicle_name_is_required()
    {
        $this->loginAsUser();

        // Name empty
        $this->post(route('vehicles.store'), $this->getCreateFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_vehicle_name_is_not_more_than_60_characters()
    {
        $this->loginAsUser();

        // Name 70 characters
        $this->post(route('vehicles.store'), $this->getCreateFields([
            'name' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_vehicle_description_is_not_more_than_255_characters()
    {
        $this->loginAsUser();

        // Description 256 characters
        $this->post(route('vehicles.store'), $this->getCreateFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_edit_a_vehicle_within_search_query()
    {
        $this->loginAsUser();
        $vehicle = factory(Vehicle::class)->create(['name' => 'Testing 123']);

        $this->visitRoute('vehicles.index', ['q' => '123']);
        $this->click('edit-vehicle-'.$vehicle->id);
        $this->seeRouteIs('vehicles.index', ['action' => 'edit', 'id' => $vehicle->id, 'q' => '123']);

        $this->submitForm(__('vehicle.update'), [
            'name'        => 'Vehicle 1 name',
            'description' => 'Vehicle 1 description',
        ]);

        $this->seeRouteIs('vehicles.index', ['q' => '123']);

        $this->seeInDatabase('vehicles', [
            'name'        => 'Vehicle 1 name',
            'description' => 'Vehicle 1 description',
        ]);
    }

    private function getEditFields(array $overrides = [])
    {
        return array_merge([
            'name'        => 'Vehicle 1 name',
            'description' => 'Vehicle 1 description',
        ], $overrides);
    }

    /** @test */
    public function validate_vehicle_name_update_is_required()
    {
        $this->loginAsUser();
        $vehicle = factory(Vehicle::class)->create(['name' => 'Testing 123']);

        // Name empty
        $this->patch(route('vehicles.update', $vehicle), $this->getEditFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_vehicle_name_update_is_not_more_than_60_characters()
    {
        $this->loginAsUser();
        $vehicle = factory(Vehicle::class)->create(['name' => 'Testing 123']);

        // Name 70 characters
        $this->patch(route('vehicles.update', $vehicle), $this->getEditFields([
            'name' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_vehicle_description_update_is_not_more_than_255_characters()
    {
        $this->loginAsUser();
        $vehicle = factory(Vehicle::class)->create(['name' => 'Testing 123']);

        // Description 256 characters
        $this->patch(route('vehicles.update', $vehicle), $this->getEditFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_delete_a_vehicle()
    {
        $this->loginAsUser();
        $vehicle = factory(Vehicle::class)->create();
        factory(Vehicle::class)->create();

        $this->visitRoute('vehicles.index', ['action' => 'edit', 'id' => $vehicle->id]);
        $this->click('del-vehicle-'.$vehicle->id);
        $this->seeRouteIs('vehicles.index', ['action' => 'delete', 'id' => $vehicle->id]);

        $this->seeInDatabase('vehicles', [
            'id' => $vehicle->id,
        ]);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('vehicles', [
            'id' => $vehicle->id,
        ]);
    }
}
