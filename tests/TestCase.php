<?php

namespace Smousss\Laravel\Novalize\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Smousss\Laravel\Novalize\NovalizeServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [NovalizeServiceProvider::class];
    }
}
