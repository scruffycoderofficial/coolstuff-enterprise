<?php

namespace App\Tools\Frontend;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Ui\Presets\Preset;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class TailwindCssPreset
 *
 * @package App\Tools\Frontend
 */
class TailwindCssPreset extends Preset
{
    public static function install()
    {
        static::updatePackages();
        static::updateStyles();
        static::updateBootstrapping();
        static::updateWelcomePage();
        static::removeNodeModules();
    }

    public static function installAuth()
    {
        static::scaffoldController();
        static::scaffoldAuth();
    }

    protected static function updatePackageArray(array $packages)
    {
        /**
         * @TODO Handle this array differently to allow for dependencies and
         *       devDependencies correct entry merging with package.json.
         *
         *       NOTE: It might not even be necessary to do this again,
         *             unless on a fresh install, in with the package.json
         *             file would require an update to fix any discrepancies.
         */
        return array_merge([
            'tailwindcss' => '^3.3.3',
            'postcss' => '^8.4.27',
            'autoprefixer' => '^10.4.4',
            'laravel-mix' => '^6.0.49',
            '@tailwindcss/aspect-ratio' => '^0.4.2',
            '@tailwindcss/forms' => '^0.5.4',
            '@tailwindcss/typography' => '^0.5.9',
        ], Arr::except($packages, [
            'laravel-mix',
        ]));
    }

    protected static function updateStyles()
    {
        tap(new Filesystem, function ($filesystem) {
            $filesystem->deleteDirectory(resource_path('sass'));
            $filesystem->delete(public_path('js/app.js'));
            $filesystem->delete(public_path('css/app.css'));

            if (! $filesystem->isDirectory($directory = resource_path('css'))) {
                $filesystem->makeDirectory($directory, 0755, true);
            }
        });

        copy(static::tailwindStubsRootPath() .'/stubs/resources/css/app.css', resource_path('css/app.css'));
    }

    protected static function updateBootstrapping()
    {
        copy(static::tailwindStubsRootPath() .'/tailwind.config.js', base_path('tailwind.config.js'));

        copy(static::tailwindStubsRootPath() .'/webpack.mix.js', base_path('webpack.mix.js'));
    }

    protected static function updateWelcomePage()
    {
        (new Filesystem)->delete(resource_path('views/welcome.blade.php'));

        copy(static::tailwindStubsRootPath() .'/stubs/resources/views/welcome.blade.php', resource_path('views/welcome.blade.php'));
    }

    protected static function scaffoldController()
    {
        if (! is_dir($directory = app_path('Http/Controllers/Auth'))) {
            mkdir($directory, 0755, true);
        }

        $filesystem = new Filesystem;

        collect($filesystem->allFiles(base_path('vendor/laravel/ui/stubs/Auth')))
            ->each(function (SplFileInfo $file) use ($filesystem) {
                $filesystem->copy(
                    $file->getPathname(),
                    app_path('Http/Controllers/Auth/'.Str::replaceLast('.stub', '.php', $file->getFilename()))
                );
            });
    }

    protected static function scaffoldAuth()
    {
        file_put_contents(app_path('Http/Controllers/HomeController.php'), static::compileControllerStubs());

        file_put_contents(
            base_path('routes/web.php'),
            "Auth::routes();\n\nRoute::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');\n\n",
            FILE_APPEND
        );

        tap(new Filesystem, function ($filesystem) {
            $filesystem->copyDirectory(static::tailwindStubsRootPath() .'/stubs/resources/views', resource_path('views'));

            collect($filesystem->allFiles(base_path('vendor/laravel/ui/stubs/migrations')))
                ->each(function (SplFileInfo $file) use ($filesystem) {
                    $filesystem->copy(
                        $file->getPathname(),
                        database_path('migrations/'.$file->getFilename())
                    );
                });
        });
    }

    protected static function compileControllerStubs()
    {
        return str_replace(
            '{{namespace}}',
            Container::getInstance()->getNamespace(),
            file_get_contents(static::tailwindStubsRootPath() .'/stubs/controllers/HomeController.stub')
        );
    }

    private static function tailwindStubsRootPath()
    {
        return __DIR__ . '/../../Resources/tailwind';
    }
}
