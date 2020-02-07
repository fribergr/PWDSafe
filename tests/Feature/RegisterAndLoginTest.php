<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterAndLoginTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRedirectToLogin()
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function testRegisterAndLogin()
    {
        $this->json('POST', '/reg', ['user' => 'some@email.com', 'pass' => 'password']);
        $this->assertDatabaseHas('users', ['email' => 'some@email.com']);

        $this->get('/login');

        $result = $this->json('POST', '/login', ['email' => 'some@email.com', 'password' => 'somethingThatIsWrong']);
        $result->assertSessionHasErrors();
        $result->assertRedirect('/login');

        $result = $this->json('POST', '/login', ['email' => 'some@email.com', 'password' => 'password']);
        $result->assertSessionDoesntHaveErrors();
        $result->assertRedirect('/groups/' . \App\User::first()->primarygroup);
    }

    public function testRegisterAUserThatAlreadyExists()
    {
        $this->json('POST', '/reg', ['user' => 'some@email.com', 'pass' => 'password']);
        $this->assertDatabaseHas('users', ['email' => 'some@email.com']);

        $result = $this->json('POST', '/reg', ['user' => 'some@email.com', 'pass' => 'password']);
        $result->assertJsonValidationErrors(['user']);
        $this->assertCount(1, \App\User::all());
    }

    public function testRegisterUserAndChangePasswordViaApi()
    {
        $this->json('POST', '/reg', ['user' => 'some@email.com', 'pass' => 'password']);
        $this->post('/api/pwdchg', [
            'username' => 'some@email.com',
            'old_password' => 'wrongpass',
            'new_password' => 'SecretPassword',
        ])->assertStatus(403);

        $this->post('/api/pwdchg', [
            'username' => 'some@email.com',
            'old_password' => 'password',
            'new_password' => 'SecretPassword',
        ])->assertStatus(200);

        $this->assertTrue(Hash::check('SecretPassword', \App\User::first()->password));
    }
}
