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

    public function testRegisterUserAndChangePasswordViaWeb()
    {
        $this->json('POST', '/reg', ['user' => 'some@email.com', 'pass' => 'password']);
        $this->json('POST', '/login', ['email' => 'some@email.com', 'password' => 'password']);
        $this->get('/changepwd')->assertStatus(200)->assertSee('Old password');
        $result = $this->post('/changepwd', ['oldpwd' => 'something', 'password' => 'short']);
        $result->assertRedirect('/changepwd')->assertSessionHasErrors();

        $result = $this->post('/changepwd', ['oldpwd' => 'password', 'password' => 'short', 'password_confirmation' => 'short']);
        $result->assertRedirect('/changepwd')->assertSessionHasErrors();

        $result = $this->post('/changepwd', ['oldpwd' => 'password', 'password' => 'longpassword', 'password_confirmation' => 'longpassword']);
        $result->assertRedirect('/changepwd')->assertSessionDoesntHaveErrors();
    }

    public function testLogout()
    {
        $this->json('POST', '/reg', ['user' => 'some@email.com', 'pass' => 'password']);
        $this->json('POST', '/login', ['email' => 'some@email.com', 'password' => 'password']);
        $this->isAuthenticated();
        $this->post('/logout');
        $this->assertGuest();
    }

    public function testRedirectedToPrimaryGroup()
    {
        $this->json('POST', '/reg', ['user' => 'some@email.com', 'pass' => 'password']);
        $this->json('POST', '/login', ['email' => 'some@email.com', 'password' => 'password']);
        $user = \App\User::first();
        $this->get('/')->assertRedirect('/groups/' . $user->primarygroup);
    }
}
