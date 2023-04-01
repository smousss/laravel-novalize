<?php

namespace Smousss\Laravel\Novalize;

use Spatie\LaravelPackageTools\Package;
use Smousss\Laravel\Novalize\Commands\NovalizeCommand;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class NovalizeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package) : void
    {
        $package
            ->name('novalize')
            ->hasConfigFile()
            ->hasCommand(NovalizeCommand::class);
    }
}
