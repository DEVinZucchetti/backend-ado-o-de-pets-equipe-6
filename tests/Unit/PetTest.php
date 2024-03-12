<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\Race;
use App\Models\Specie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PetTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_edit_one_pet(): void
    {
        $specie = Specie::factory()->create();
        $race = Race::factory()->create();
        $pet  = Pet::factory()->create(['race_id' => $race->id, 'specie_id' => $specie->id]);

       // Pet::factory()->create(['race_id' => $race->id, 'specie_id' => $specie->id, 'size' => 'LARGE', 'weight' => 12.5]);

        $user = User::factory()->create(['profile_id' => 2, 'password' => '12345678']);

        $body = ['size' => 'LARGE', 'weight' => 12.5];
        $response = $this->actingAs($user)->put("/api/pets/$pet->id", $body);

        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'size' => $body['size'],
            'weight' => $body['weight']
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => true,
            'name' => true,
            'weight' => $body['weight'],
            'size' => $body['size'],
            'age' => true,
            'race_id' => true,
            'specie_id' => true,
            'client_id' => null
        ]);
    }
}
