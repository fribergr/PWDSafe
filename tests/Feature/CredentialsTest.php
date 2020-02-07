<?php

namespace Tests\Feature;

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
            'currentgroupid' => $credential->groupid,
        ]);

        $this->assertDatabaseHas('credentials', ['site' => 'New site']);
        $this->assertCount(1, \App\Credential::all());

        $newpassword = 'Some other password';

        $this->json('POST', '/cred/' . $credential->id, [
            'creds' => 'New site',
            'credu' => $credential->username,
            'credp' => $newpassword,
            'currentgroupid' => $credential->groupid,
        ]);

        $this->assertEquals($newpassword, $this->getPassword($credential, $this->user));
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
        $pwd = \App\Encryptedcredential::where('credentialid', $credential->id)->first();
        $encryption = app(Encryption::class);

        return $encryption->decWithPriv(
            base64_decode($pwd->data),
            $encryption->dec($user->privkey, $password)
        );
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
