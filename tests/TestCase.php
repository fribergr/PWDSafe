<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function registerUser($user = [
        'email' => 'some@email.com',
        'password' => 'password',
        'password_confirmation' => 'password'
    ]) {
        $this->post('/register', $user);
        $this->post('/logout');
    }

    protected function loginUser($user = [
        'email' => 'some@email.com',
        'password' => 'password'
    ]) {
        return $this->from('/login')->post('/login', $user);
    }
}
