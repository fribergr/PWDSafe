<?php

namespace Tests\Feature;

use App\Helpers\Encryption;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->post('/register', [
            'email' => 'some@email.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $this->actingAs(\App\User::first());
        session()->put('password', 'password');
        $this->user = \App\User::first();
    }

    public function testSearchingShouldReturnEmptyPage()
    {
        $this->get('/search/something')->assertStatus(200)->assertSee('No credentials found');
    }

    public function testSearchByPost()
    {
        $this->post('/search', ['search' => 'Something'])->assertRedirect('/search/Something');
    }

    public function testSearchingOneItem()
    {
        $this->post("/groups/{$this->user->primarygroup}/add", [
            'site' => 'Site1',
            'user' => 'The username',
            'pass' => 'The super secret password',
            'notes' => 'Some notes here',
        ]);

        $this->post("/groups/{$this->user->primarygroup}/add", [
            'site' => 'Site2',
            'user' => 'The username',
            'pass' => 'The super secret password',
            'notes' => 'No notes here',
        ]);

        $this->get('/search/Site2')->assertStatus(200)->assertSee('Site2')->assertDontSee('Site1');
        $this->get('/search/site')->assertStatus(200)->assertSee('Site2')->assertSee('Site1');
    }
}
