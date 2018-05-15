<?php

namespace Laravel\Spark\Console\Installation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class InstallResources
{
    /**
     * The console command instance.
     *
     * @var \Illuminate\Console\Command  $command
     */
    protected $command;

    /**
     * Create a new installer instance.
     *
     * @param  \Illuminate\Console\Command  $command
     * @return void
     */
    public function __construct($command)
    {
        $this->command = $command;

        $this->command->line('Installing JavaScript & Sass Assets: <info>✔</info>');
        $this->command->line('Installing Language Files: <info>✔</info>');
        $this->command->line('Installing Views: <info>✔</info>');
    }

    /**
     * Install the components.
     *
     * @return void
     */
    public function install()
    {
        $this->installFrontEndDirectories();

        if (! is_dir(resource_path('lang/ar'))) {
            mkdir(resource_path('lang/ar'));
        }

        $files = [
            SPARK_STUB_PATH.'/terms.md' => base_path('terms.md'),
            SPARK_STUB_PATH.'/webpack.mix.js' => base_path('webpack.mix.js'),
            SPARK_STUB_PATH.'/package.json' => base_path('package.json'),
            SPARK_STUB_PATH.'/resources/assets/sass/app.scss' => resource_path('assets/sass/app.scss'),
            SPARK_STUB_PATH.'/resources/assets/sass/app-rtl.scss' => resource_path('assets/sass/app-rtl.scss'),
            SPARK_STUB_PATH.'/resources/lang/en/validation.php' => resource_path('lang/en/validation.php'),
            SPARK_STUB_PATH.'/resources/lang/ar/validation.php' => resource_path('lang/ar/validation.php'),
            SPARK_STUB_PATH.'/resources/lang/en/teams.php' => resource_path('lang/en/teams.php'),
            SPARK_STUB_PATH.'/resources/lang/ar/teams.php' => resource_path('lang/ar/teams.php'),
            SPARK_STUB_PATH.'/resources/lang/en.json' => resource_path('lang/en.json'),
            SPARK_STUB_PATH.'/resources/lang/ar.json' => resource_path('lang/ar.json'),
            SPARK_STUB_PATH.'/resources/views/welcome.blade.php' => resource_path('views/welcome.blade.php'),
            SPARK_STUB_PATH.'/resources/views/home.blade.php' => resource_path('views/home.blade.php'),
        ];

        foreach ($files as $from => $to) {
            copy($from, $to);
        }

        (new Filesystem)->copyDirectory(
            SPARK_STUB_PATH.'/resources/assets/js', resource_path('/assets/js')
        );

        Artisan::call('vendor:publish', ['--tag' => ['spark-views']]);
    }

    /**
     * Install the front-end directories.
     *
     * @return void
     */
    protected function installFrontEndDirectories()
    {
        (new Filesystem)->deleteDirectory(resource_path('/assets/sass'));

        if (! is_dir(resource_path('/assets/js/components'))) {
            (new Filesystem)->makeDirectory(
                resource_path('/assets/js/components'), $mode = 0755, $recursive = true
            );
        }

        if (! is_dir(resource_path('/assets/js/spark-components'))) {
            (new Filesystem)->makeDirectory(
                resource_path('/assets/js/spark-components'), $mode = 0755, $recursive = true
            );
        }

        if (! is_dir(resource_path('/assets/sass'))) {
            (new Filesystem)->makeDirectory(resource_path('/assets/sass'));
        }
    }
}
