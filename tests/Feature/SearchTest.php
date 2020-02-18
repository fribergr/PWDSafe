<?php

namespace Tests\Feature;

use App\Helpers\Encryption;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SearchTest extends TestCase
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

    public function testSearchingShouldReturnEmptyPage()
    {
        $this->get('/search/something')->assertStatus(200)->assertSee('No credentials found');
    }

    public function testSearchingOneItem()
    {
        $this->json('POST', '/cred/add', [
            'creds' => 'Site1',
            'credu' => 'The username',
            'credp' => 'The super secret password',
            'credn' => 'Some notes here',
            'currentgroupid' => $this->user->primarygroup,
        ]);

        $this->json('POST', '/cred/add', [
            'creds' => 'Site2',
            'credu' => 'The username',
            'credp' => 'The super secret password',
            'credn' => 'No notes here',
            'currentgroupid' => $this->user->primarygroup,
        ]);

        $this->get('/search/Site2')->assertStatus(200)->assertSee('Site2')->assertDontSee('Site1');
        $this->get('/search/site')->assertStatus(200)->assertSee('Site2')->assertSee('Site1');
    }
}
