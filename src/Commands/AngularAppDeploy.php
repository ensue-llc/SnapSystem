<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/31/2017
 * Time: 7:54 PM
 */

namespace NicoSystem\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * Class AngularAppDeploy
 * @package NicoSystem\Commands
 */
class AngularAppDeploy extends Command
{
    const BUILD_MODE_SIMPLE = 'simple';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nicoAngularAppDeploy {--advanced} {--build-option=} {--angular-prod}';

    /**
     * @var string
     */
    protected string $appRoot = "hr-angular";

    /**
     * @var string
     */
    protected string $appPath;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy angular application to laravel application';

    protected mixed $app = null;
    protected $buildOption = null;

    /**
     * AngularAppDeploy constructor.
     * @param \Illuminate\Filesystem\Filesystem $fileSystem
     */
    public function __construct(protected Filesystem $fileSystem)
    {
        parent::__construct();
        $this->appRoot = base_path('resources/' . config('nicoSystem.angular_app'));
        if (!$this->appRoot) {
            $this->info("Angular app not found.");
            exit(1);
        }
        $this->appPath = $this->appRoot . "/dist";
        $this->app = app();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        try {
            if ($this->option('advanced')) {
                $this->setBuildOption();
                $this->runNpmAngularBuildCommand();
            }
            //lets copy the content of resources/angular-app/dist/prod/ to public folder
            if (!$this->fileSystem->isDirectory($this->appPath)) {
                $this->info("Production version angular app not found");
                throw new \RuntimeException("Production version app angular not found");
            }

            $this->removeOldBuild();
            $result = $this->fileSystem->copyDirectory($this->appPath, public_path());

            if ($result) {
                $this->info("Application successfully deployed.\r\n");
            } else {
                $this->info("Couldn't deploy application. Please contact the developer to sort out the issue\r\n");
            }
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        } finally {
            $this->cleanupBuilds();
        }
    }

    protected function setBuildOption()
    {
        $option = $this->option('build-option');
        if (!$option) {
            $option = "";
        }

        $this->buildOption = $option;
    }

    /**
     * run npm build command
     */
    protected function runNpmAngularBuildCommand()
    {
        $this->setAngularAppEnvironment();
        $this->setIndexFile();

        if ($this->option('angular-prod')) {
            $command = "npm run build-prod";
        } else {
            $command = "npm run build";
        }

        $tt = exec("cd " . $this->appRoot . " && {$command}", $output, $status);

        if (!$tt) {
            throw new \RuntimeException("Couldn't build app. Please use simple deployment, or install npm and required dependencies");
        }
    }

    protected function setAngularAppEnvironment()
    {
        //lets copy the code to prod specific to environment
        $file = "environment." . ($this->buildOption != "" ? $this->buildOption . ".ts" : "ts");
        $path = $this->appRoot . '/src/environments/';
        if (!$this->fileSystem->exists($path . $file)) {
            $this->info("Required file {$path}{$file} doesn't exist.");
            exit;
        };
        //backup the current environment

        if (!($this->fileSystem->copy($path . 'environment.ts', $path . 'environment.old.ts'))) {
            $this->info("Cannot copy file. Make sure you have read/write permission to {$path}.");
            exit;
        }
        //copy the file to environment.ts
        if (!($this->fileSystem->copy($path . $file, $path . "environment.ts")

        )) {
            $this->info("Cannot copy file. Make sure you have read/write permission to {$path}");
            exit;
        }
    }

    protected function setIndexFile()
    {
        //lets copy the code to prod specific to index file
        $file = "index." . ($this->buildOption != "" ? $this->buildOption . ".html" : "html");
        $path = $this->appRoot . '/src/';
        if (!$this->fileSystem->exists($path . $file)) {
            $this->info("Required file {$path}{$file} doesn't exist. Proceeding with normal build.");
            return; // run as usual
        };
        //backup the current index file

        if (!($this->fileSystem->copy($path . 'index.html', $path . 'index.old.html'))) {
            $this->info("Cannot copy file. Make sure you have read/write permission to {$path}.");
            exit;
        }
        //copy the file to index.html
        if (!($this->fileSystem->copy($path . $file, $path . "index.html")

        )) {
            $this->info("Cannot copy file. Make sure you have read/write permission to {$path}");
            exit;
        }
    }

    public function removeOldBuild()
    {
        $fileExtension = ['js', 'js.map', 'css', 'woff', 'woff2', 'eot', 'ttf', 'svg'];
        foreach ($fileExtension as $ext) {
            // for production code
            foreach (glob(public_path() . '\\*.*.' . $ext) as $filename) {
                unlink($filename);
            }

            //for development code
            foreach (glob(public_path() . '\\*.' . $ext) as $filename) {
                unlink($filename);
            }
        }
    }

    protected function cleanupBuilds()
    {
        $path = $this->appRoot . '/src/environments/';
        //finally restore the previous environment file
        $this->fileSystem->copy($path . 'environment.old.ts', $path . 'environment.ts');
        $this->fileSystem->delete($path . 'environment.old.ts');

        if ($this->fileSystem->exists($this->appRoot . "/src/index.old.html")) {
            $this->fileSystem->copy($this->appRoot . "/src/index.old.html", $this->appRoot . "/src/index.html");
            $this->fileSystem->delete($this->appRoot . "/src/index.old.html");
        }
    }

}
