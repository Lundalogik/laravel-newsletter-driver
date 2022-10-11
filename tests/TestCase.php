<?php

namespace Lundalogik\NewsletterDriver\Tests;

use \Orchestra\Testbench\TestCase as OrchestraTestCase;
use Lundalogik\NewsletterDriver\NewsletterMailServiceProvider;

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
