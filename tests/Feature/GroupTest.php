<?php

namespace Tests\Feature;

use App\Helpers\Encryption;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GroupTest extends TestCase
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

    public function testAddingGroup()
    {
        $this->assertCount(1, $this->user->groups);
        $this->json('POST', '/groups/create', [
            'groupname' => 'testgroup',
        ]);
        $this->assertCount(2, $this->user->fresh()->groups);
        $this->assertDatabaseHas('groups', ['name' => 'testgroup']);
    }

    public function testVisitingCreate()
    {
        $this->get('/groups/create')->assertStatus(200)->assertSee('Create group');
    }

    public function testDeletingGroup()
    {
        $this->json('POST', '/groups/create', [
            'groupname' => 'testgroup',
        ]);
        $group = \App\Group::orderBy('id', 'desc')->first();
        $this->assertCount(2, $this->user->fresh()->groups);

        $response = $this->get('/groups/' . $group->id . '/delete');
        $response->assertStatus(200);
        $response->assertSee('Are you sure');

        $this->json('POST', '/groups/' . $group->id . '/delete', []);
        $this->assertDatabaseMissing('groups', ['name' => 'testgroup']);
        $this->assertCount(1, $this->user->fresh()->groups);
    }

    public function testDeletingPrimaryGroup()
    {
        $group = \App\Group::first();

        $response = $this->get('/groups/' . $group->id . '/delete');
        $response->assertStatus(403);

        $this->json('POST', '/groups/' . $group->id . '/delete', []);
        $this->assertDatabaseHas('groups', ['id' => $group->id]);
        $this->assertCount(1, $this->user->fresh()->groups);
    }

    public function testRenamingGroup()
    {
        $this->assertCount(1, $this->user->groups);
        $this->json('POST', '/groups/create', [
            'groupname' => 'testgroup',
        ]);
        $this->assertCount(2, $this->user->fresh()->groups);
        $this->assertDatabaseHas('groups', ['name' => 'testgroup']);

        $group = \App\Group::orderBy('id', 'desc')->first();

        $this->json('POST', '/groups/' . $group->id . '/changename', [
            'groupname' => 'new name',
        ]);

        $this->assertDatabaseMissing('groups', ['name' => 'testgroup']);
        $this->assertDatabaseHas('groups', ['name' => 'new name']);
    }

    public function testVisitingShareGroup()
    {
        $this->json('POST', '/groups/create', [
            'groupname' => 'testgroup',
        ]);

        $group = \App\Group::orderBy('id', 'desc')->first();
        $this->get('/groups/' . $group->id . '/share')->assertStatus(200)->assertSee('Share group');
    }

    public function testSharingGroup()
    {
        $this->json('POST', '/groups/create', [
            'groupname' => 'testgroup',
        ]);

        $group = \App\Group::orderBy('id', 'desc')->first();

        $this->json('POST', '/cred/add', [
            'creds' => 'Some site',
            'credu' => 'The username',
            'credp' => 'The super secret password',
            'credn' => 'Notes',
            'currentgroupid' => $group->id,
        ]);

        $this->json('POST', '/reg', ['user' => 'second@email.com', 'pass' => 'abitlongersecret']);
        $this->json('POST', '/groups/' . $group->id . '/share', ['email' => 'second@email.com']);

        $this->assertCount(2, $group->fresh()->users);

        $seconduser = \App\User::where('email', 'second@email.com')->first();
        $this->actingAs($seconduser);

        $credential = \App\Credential::first();

        $pwd = \App\Encryptedcredential::where('credentialid', $credential->id)
            ->where('userid', $seconduser->id)
            ->first();
        $encryption = app(Encryption::class);

        $decryptedcredential = $encryption->decWithPriv(
            base64_decode($pwd->data),
            $encryption->dec($seconduser->privkey, 'abitlongersecret')
        );

        $this->assertEquals('The super secret password', $decryptedcredential);
        $this->assertCount(2, \App\Encryptedcredential::all());

        $this->json('POST', '/groups/' . $group->id . '/share', ['email' => 'does@not.exist'])->assertStatus(404);
        $this->json('POST', '/groups/' . $group->id . '/share', ['email' => 'second@email.com'])->assertStatus(202);
    }

    public function testUnsharingGroup()
    {
        $this->json('POST', '/groups/create', [
            'groupname' => 'testgroup',
        ]);

        $group = \App\Group::orderBy('id', 'desc')->first();

        $this->json('POST', '/cred/add', [
            'creds' => 'Some site',
            'credu' => 'The username',
            'credp' => 'The super secret password',
            'credn' => 'Notes',
            'currentgroupid' => $group->id,
        ]);

        $this->json('POST', '/reg', ['user' => 'second@email.com', 'pass' => 'abitlongersecret']);
        $user2 = \App\User::where('email', 'second@email.com')->first();
        $this->json('POST', '/groups/' . $group->id . '/share', ['email' => $user2->email]);
        $this->assertCount(2, $user2->fresh()->groups);

        $res = $this->json('POST', '/groups/' . $group->id . '/unshare/' . $user2->id, []);
        $this->assertCount(1, $user2->fresh()->groups);
    }
}
