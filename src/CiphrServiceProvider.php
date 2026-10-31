<?php

namespace spkm\Ciphr;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CiphrServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('ciphr')
            ->hasConfigFile('ciphr')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->publishConfigFile();
            });

        $this->app->singleton('ciphr', fn ($app) => new CiphrService);
    }
}
