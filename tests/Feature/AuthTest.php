<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_as_attendee()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => true,
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/dashboard/attendee');
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'role' => 'attendee',
        ]);
    }

    public function test_registration_requires_terms_acceptance()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => false,
        ];

        $response = $this->post('/register', $userData);

        $response->assertSessionHasErrors('terms');
        $this->assertDatabaseMissing('users', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard/attendee');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_guest_cannot_access_protected_routes()
    {
        $response = $this->get('/dashboard/attendee');
        $response->assertRedirect('/login');

        $response = $this->get('/dashboard/organizer');
        $response->assertRedirect('/login');
    }

    public function test_attendee_cannot_access_organizer_routes()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $this->actingAs($attendee);

        $response = $this->get('/events/create');
        $response->assertStatus(403);

        $response = $this->get('/events/manage');
        $response->assertStatus(403);
    }

    public function test_organizer_cannot_access_attendee_routes()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $this->actingAs($organizer);

        $response = $this->get('/dashboard/attendee');
        $response->assertStatus(403);

        $response = $this->get('/bookings');
        $response->assertStatus(403);
    }

    public function test_registration_validation_errors()
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'terms']);
    }

    public function test_login_validation_errors()
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_invalid_login_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}
