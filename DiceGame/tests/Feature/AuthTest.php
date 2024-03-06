<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register()
    {
        //define los datos del usuario a registrar
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/register', $userData);

        //verifica que la solicitud haya tenido éxito (código de estado HTTP 500)
        $response->assertStatus(500);

        //Verifica que el usuario se haya creado correctamente en la base de datos
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);
    }
    public function test_login_success()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    
        //define los datos de inicio de sesión
        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];
    
        $response = $this->postJson('/api/login', $loginData);
    
        $this->assertAuthenticated();
    }
    

}
