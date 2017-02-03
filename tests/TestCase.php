<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    protected $user;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function signIn($user = null)
    {
        if (! $user) {
            // build up a user
            $user = factory(App\User::class)->create();            
        }

        $this->user = $user;

        // and that user is logged in
        $this->actingAs($this->user);

        return $this;
    }
}
