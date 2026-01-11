<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\ValueObjects\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValueObjectCastingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the Address Value Object cast works correctly.
     */
    public function test_supplier_address_is_cast_to_value_object(): void
    {
        $supplier = Supplier::factory()->create([
            'office_address' => new Address(
                '123 Laravel Way',
                'Suite 100',
                'Taylor City',
                'PHP',
                '12345',
                'United States'
            ),
        ]);

        $supplier = $supplier->fresh();

        $this->assertInstanceOf(Address::class, $supplier->office_address);
        $this->assertEquals('123 Laravel Way', $supplier->office_address->lineOne);
        $this->assertEquals('Suite 100', $supplier->office_address->lineTwo);
        $this->assertEquals('Taylor City', $supplier->office_address->city);
        $this->assertEquals('PHP', $supplier->office_address->state);
        $this->assertEquals('12345', $supplier->office_address->postalCode);
        $this->assertEquals('United States', $supplier->office_address->country);
    }

    /**
     * Test that updating the Value Object properties syncs back to the database.
     */
    public function test_updating_value_object_properties_syncs_to_database(): void
    {
        $supplier = Supplier::factory()->create();

        $supplier->office_address->lineOne = 'Updated Street';
        $supplier->office_address->city = 'Updated City';
        $supplier->save();

        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'address_line_one' => 'Updated Street',
            'address_city' => 'Updated City',
        ]);
    }

    /**
     * Test that the Value Object is serialized correctly to array and JSON.
     */
    public function test_value_object_is_serialized_correctly(): void
    {
        $address = new Address(
            '123 Street',
            'Apt 1',
            'City',
            'State',
            '12345',
            'Country'
        );

        $array = $address->toArray();

        $this->assertEquals('123 Street', $array['line_one']);
        $this->assertEquals('City', $array['city']);

        $json = json_encode($address);
        $this->assertStringContainsString('"line_one":"123 Street"', $json);
        $this->assertStringContainsString('"city":"City"', $json);
    }

    /**
     * Test that assigning a new Value Object works.
     */
    public function test_assigning_new_value_object_works(): void
    {
        $supplier = Supplier::factory()->create();

        $newAddress = new Address(
            'New Street',
            '',
            'New City',
            'New State',
            '54321',
            'New Country'
        );

        $supplier->office_address = $newAddress;
        $supplier->save();

        $supplier = $supplier->fresh();
        $this->assertEquals('New Street', $supplier->office_address->lineOne);
        $this->assertEquals('54321', $supplier->office_address->postalCode);
    }
}
