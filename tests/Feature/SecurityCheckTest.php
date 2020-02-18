<?php

namespace Tests\Feature;

use App\Helpers\Encryption;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SecurityCheckTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->json('POST', '/reg', ['user' => 'some@email.com', 'pass' => 'password']);
        $this->json('POST', '/login', ['email' => 'some@email.com', 'password' => 'password']);
        $this->user = \App\User::first();
    }

    public function testEmptySecurityCheck()
    {
        $this->get('/securitycheck')->assertStatus(200)->assertSee('This means that your credentials all have different passwords');
    }

    public function testTwoDifferentPasswords()
    {
        $this->json('POST', '/cred/add', [
            'creds' => 'Site2',
            'credu' => 'The username',
            'credp' => 'The super secret password',
            'credn' => 'No notes here',
            'currentgroupid' => $this->user->primarygroup,
        ]);

        $this->json('POST', '/cred/add', [
            'creds' => 'Site2',
            'credu' => 'The username',
            'credp' => 'The super not so secret password',
            'credn' => 'No notes here',
            'currentgroupid' => $this->user->primarygroup,
        ]);

        $this->get('/securitycheck')->assertStatus(200)->assertSee('This means that your credentials all have different passwords');
    }

    public function testTwoSamePasswords()
    {
        $this->json('POST', '/cred/add', [
            'creds' => 'Site2',
            'credu' => 'The username',
            'credp' => 'The super secret password',
            'credn' => 'No notes here',
            'currentgroupid' => $this->user->primarygroup,
        ]);

        $this->json('POST', '/cred/add', [
            'creds' => 'Site2',
            'credu' => 'The username',
            'credp' => 'The super secret password',
            'credn' => 'No notes here',
            'currentgroupid' => $this->user->primarygroup,
        ]);

        $this->get('/securitycheck')->assertStatus(200)->assertSee('Password group');
    }
}
