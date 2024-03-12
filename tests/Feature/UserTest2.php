<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Race;
use App\Models\Specie;
use App\Models\User;
use Database\Seeders\InitialUser;
use Database\Seeders\Profiles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{


    /*

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    */

    public function test_user_can_login_with_correct_credentials(): void
    {

        $this->seed(Profiles::class);
        $this->seed(InitialUser::class);

        $user = User::factory()->create(['profile_id' => 1, 'password' => bcrypt($password = 'supersecretpassword'),]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(201)->assertJson([
            'message' => 'Autorizado',
            'status' => 201,
            'data' => [
                'token' => true,
                'name' => $user->name,
                'permissions' => true,
                'profile' => 'ADMIN'
            ],
        ]);
    }

    public function test_user()
    {
        $this->seed(Profiles::class);
        $this->seed(InitialUser::class);

        $user = User::factory()->create(['profile_id' => 1]);

        $response = $this->actingAs($user)->post('/api/races', [
            'name' => 'Gato'
        ]);

        $response->assertStatus(201)->assertJson([
            'name' => 'Gato',
            'updated_at' => true,
            'created_at' => true,
            'id' => true,
        ]);
    }

    public function test_list_races()
    {

        $this->seed(Profiles::class);

        $user = User::factory()->create(['profile_id' => 1]);

        $race = Race::factory()->create(['name' => 'teste']);

        $response = $this->actingAs($user)->get('/api/races');

        $response->assertStatus(200)->assertJsonStructure([
            '*' => [
                'created_at',
                'updated_at',
                'name',
                'id'
            ]
        ]);
    }
}
