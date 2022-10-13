<?php

namespace Lundalogik\NewsletterDriver\Tests;

use Lundalogik\NewsletterDriver\NewsletterMailServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // additional setup ..
    }

    protected function getPackageProviders($app)
    {
        return [
            NewsletterMailServiceProvider::class,
        ];
    }
}
