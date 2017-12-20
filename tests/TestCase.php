<?php

namespace Tests;

use App\User;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $baseUrl = 'http://localhost';

    use CreatesApplication;

    protected function loginAsUser()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        return $user;
    }
}
