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
        $this->registerUser();
        $this->assertDatabaseHas('users', ['email' => 'some@email.com']);

        $result = $this->from('/login')->post('/login', ['email' => 'some@email.com', 'password' => 'somethingThatIsWrong']);
        $result->assertSessionHasErrors();
        $result->assertRedirect('/login');

        $result = $this->from('/login')->post('/login', ['email' => 'some@email.com', 'password' => 'password']);
        $result->assertSessionDoesntHaveErrors();
        $result->assertRedirect('/groups/' . \App\User::first()->primarygroup);
    }

    public function testRegisterAUserThatAlreadyExists()
    {
        $this->registerUser();
        $this->assertDatabaseHas('users', ['email' => 'some@email.com']);

        $result = $this->post('/register', [
            'email' => 'some@email.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $result->assertSessionHasErrors('email');
        $this->assertCount(1, \App\User::all());
    }

    public function testRegisterUserAndChangePasswordViaApi()
    {
        $this->registerUser();
        $this->loginUser();
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
        $this->registerUser();
        $this->loginUser();
        $this->get('/changepwd')->assertStatus(200)->assertSee('Old password');
        $result = $this->from('/changepwd')->post('/changepwd', ['oldpwd' => 'something', 'password' => 'short']);
        $result->assertRedirect('/changepwd')->assertSessionHasErrors();

        $result = $this->post('/changepwd', ['oldpwd' => 'password', 'password' => 'short', 'password_confirmation' => 'short']);
        $result->assertRedirect('/changepwd')->assertSessionHasErrors();

        $user = \App\User::first();
        $this->post("/groups/{$user->primarygroup}/add", [
            'site' => 'Site1',
            'user' => 'The username',
            'pass' => 'The super secret password',
            'notes' => 'Some notes here',
        ]);

        $cred = \App\Encryptedcredential::first();
        $olddata = $cred->data;
        $result = $this->post('/changepwd', ['oldpwd' => 'password', 'password' => 'longpassword', 'password_confirmation' => 'longpassword']);
        $result->assertRedirect('/changepwd')->assertSessionDoesntHaveErrors();

        $this->assertNotEquals($olddata, $cred->fresh()->data);
    }

    public function testLogout()
    {
        $this->registerUser();
        $this->loginUser();
        $this->assertAuthenticated();
        $this->post('/logout');
        $this->assertGuest();
    }

    public function testRedirectedToPrimaryGroup()
    {
        $this->registerUser();
        $this->loginUser();
        $user = \App\User::first();
        $this->get('/')->assertRedirect('/groups/' . $user->primarygroup);
    }

    public function testRegisterLogYouOutWhenVisitingAnyPage()
    {
        $user = [
            'email' => 'some@email.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        $this->post('/register', $user);
        $this->get('/');
        $this->assertGuest();
    }
}
