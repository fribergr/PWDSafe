<?php

namespace Tests\Feature;

use App\Credential;
use App\Helpers\Encryption;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CredentialsTest extends TestCase
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

    public function testAddingCredentials()
    {
        $this->addTestCredential();
        $this->assertDatabaseHas('credentials', ['site' => 'Some site']);
        $credential = \App\Credential::first();
        $this->assertDatabaseHas('encryptedcredentials', ['credentialid' => $credential->id, 'userid' => $this->user->id]);

        $this->get('/groups/' . $this->user->primarygroup)->assertSee('Some site');
    }

    public function testAddingCredentialsToAGroupYouDoNotHaveAccessToWhichEndsUpInYourPrimaryGroup()
    {
        $this->json('POST', '/cred/add', [
            'creds' => 'Some site',
            'credu' => 'The username',
            'credp' => 'The super secret password',
            'credn' => 'Notes',
            'currentgroupid' => 9317,
        ]);
        $this->assertDatabaseHas('credentials', ['site' => 'Some site']);
        $credential = \App\Credential::first();
        $this->assertDatabaseHas('encryptedcredentials', ['credentialid' => $credential->id, 'userid' => $this->user->id]);

        $this->get('/groups/' . $this->user->primarygroup)->assertSee('Some site');
    }

    public function testUpdatingCredentials()
    {
        $this->addTestCredential();
        $this->assertDatabaseHas('credentials', ['site' => 'Some site']);
        $credential = \App\Credential::first();
        $this->assertDatabaseHas('encryptedcredentials', ['credentialid' => $credential->id, 'userid' => $this->user->id]);

        $this->json('POST', '/cred/' . $credential->id, [
            'creds' => 'New site',
            'credu' => $credential->username,
            'credp' => $this->getPassword($credential, $this->user),
            'credn' => '',
            'currentgroupid' => $credential->groupid,
        ]);

        $this->assertDatabaseHas('credentials', ['site' => 'New site']);
        $this->assertCount(1, \App\Credential::all());

        $newpassword = 'Some other password';

        $this->json('POST', '/cred/' . $credential->id, [
            'creds' => 'New site',
            'credu' => $credential->username,
            'credp' => $newpassword,
            'credn' => '',
            'currentgroupid' => $credential->groupid,
        ]);

        $this->assertEquals($newpassword, $this->getPassword($credential, $this->user));

        $this->json('POST', '/groups/create', [
            'groupname' => 'testgroup',
        ]);

        $group = \App\Group::where('name', 'testgroup')->first();

        $result = $this->json('POST', '/cred/' . $credential->id, [
            'creds' => 'New site',
            'credu' => $credential->username,
            'credp' => $newpassword,
            'credn' => '',
            'currentgroupid' => $group->id,
        ]);
        $credential = \App\Credential::first();
        $this->assertEquals($group->id, $credential->groupid);
        $this->assertEquals('New site', $credential->site);
    }

    public function testRemovingCredentials()
    {
        $this->json('POST', '/cred/add', [
            'creds' => 'Some site',
            'credu' => 'The username',
            'credp' => 'The super secret password',
            'credn' => 'Notes',
            'currentgroupid' => $this->user->primarygroup,
        ]);

        $this->assertDatabaseHas('credentials', ['site' => 'Some site']);
        $credential = \App\Credential::first();

        $this->json('GET', '/cred/' . $credential->id . '/remove');
        $this->assertDatabaseMissing('credentials', ['site' => 'Some site']);
    }

    public function testImportingCredentials()
    {
        $filename = 'credentials_to_import.csv';
        $path = base_path('tests/assets/') . $filename;
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile ($path, $filename, 'text/csv', null, null, true);
        $res = $this->post('/import', [
            'csvfile' => $file,
            'group' => $this->user->primarygroup,
        ]);

        $this->assertCount(2, \App\Credential::all());
    }

    private function getPassword($credential, $user, $password = 'password')
    {
        return json_decode($this->get('/pwdfor/' . $credential->id)->getContent(), true)['pwd'];
    }

    private function addTestCredential()
    {
        $this->json('POST', '/cred/add', [
            'creds' => 'Some site',
            'credu' => 'The username',
            'credp' => 'The super secret password',
            'credn' => 'Notes',
            'currentgroupid' => $this->user->primarygroup,
        ]);
    }
}
