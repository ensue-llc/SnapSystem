<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 1/2/2017
 * Time: 10:20 PM
 */

namespace Ensue\NicoSystem\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Pluralizer;
use Symfony\Component\Console\Input\InputArgument;

class ModuleGenerateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:nicomodule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new nico module';


    protected $moduleContainer;

    protected $moduleSystem;

    protected string $moduleSystempath;

    protected string $_modulename;

    protected string $_modulenamePlural;

    protected string $namespacePrefix;

    protected string $namespace;

    protected string $stubPath;

    protected string $modulePath;

    /**
     * @var array|array[]
     */
    protected array $moduleStubFiles = [
        'api.stub' => ['Routes', true, 'none'],
        'Controller.stub' => ['Controllers', true, 'prefixed'],
        'Filter.stub' => ['Filters', true, 'prefixed'],
        'CreateRequest.stub' => ['Requests', false, 'prefixed'],
        'Interface.stub' => ['Interfaces', false, 'prefixed'],
        'Model.stub' => ['Database/Models', false, 'replaced'],
        'ServiceProvider.stub' => ['', false, 'none'],
        'Repository.stub' => ['Repositories', false, 'prefixed'],
        'UpdateRequest.stub' => ['Requests', false, 'prefixed'],
        'web.stub' => ['Routes', false, 'none']
    ];

    /**
     * @var array|array[]
     */
    protected array $systemStubFiles = [
        'SystemModel.stub' => ['Database/Models', false, 'replaced'], ////folder, needs pluralization, prefixed/replaced/none
    ];

    /**
     * ModuleGenerateCommand constructor.
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(protected Filesystem $files, protected Application $app)
    {
        parent::__construct();
        $this->moduleContainer = $this->app['config']->get('nicoSystem.module');
        $this->moduleSystem = $this->app['config']->get('nicoSystem.system');
        $this->namespacePrefix = '\App\\' . $this->moduleContainer;
        $this->stubPath = realpath(__DIR__ . "/../Stubs/") . "/";
    }

    public function handle()
    {
        $this->fire();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->makeModule();
    }

    /**
     * Generate the desired migration.
     */
    protected function makeModule()
    {
        $name = ucfirst($this->argument('name'));
        $this->_modulename = Pluralizer::singular($name);
        $this->_modulenamePlural = Pluralizer::plural($name);
        $this->namespace = $this->namespacePrefix . "\\" . $this->_modulenamePlural;

        if ($this->files->exists($this->modulePath = $this->getPath($this->_modulenamePlural))) {
            $this->error('Module ' . $name . '  already exists!');
            return;
        }
        $this->moduleSystempath = $this->getPath($this->_modulename, 'system');
        $this->copyModuleStructureFromStubs();
        $this->info("Module $name successfully generated");
    }

    /**
     * Get the path to where we should store the migration.
     *
     * @param string $name
     * @param string $type
     * @return string
     */
    protected function getPath(string $name, $type = "module"): string
    {
        if ($type == 'module') {
            return app_path($this->moduleContainer . "/" . $name);
        }
        return app_path($this->moduleSystem . "/" . $name);
    }

    /**
     * Build the directory for the class if necessary.
     */
    protected function copyModuleStructureFromStubs()
    {
        $this->copyModule();
        $this->copySystem();
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function copyModule()
    {
        if (!$this->files->isDirectory(dirname($this->modulePath))) {
            $this->files->makeDirectory(dirname($this->modulePath), 0777, true, true);
        }

        $this->files->copyDirectory(realpath($this->stubPath . "Module"), $this->modulePath);
        $this->generateCodeFromStubs();
    }

    /**
     * Generate Cod from stubs
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function generateCodeFromStubs()
    {
        foreach ($this->moduleStubFiles as $stubName => $stubConfig) {
            list($foldername, $plural, $action) = $stubConfig;
            $this->generateCodeFromStubFile($stubName, $foldername, $plural, $action);
        }
    }

    /**
     * @param $stubName
     * @param $foldername
     * @param $plural
     * @param $action
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function generateCodeFromStubFile($stubName, $foldername, $plural, $action)
    {
        $stubPath = $this->stubPath . $stubName;
        $destination = $this->modulePath . "/" . $foldername;
        $stub = $this->files->get($stubPath);
        //replace the placeholders in the stubs
        $name = $plural ? $this->_modulenamePlural : $this->_modulename;
        $stub = str_replace("{{routemodule}}", strtolower($this->_modulenamePlural), $stub);
        $stub = str_replace("{{module}}", $this->_modulenamePlural, $stub);
        $stub = str_replace("{module}", $this->_modulename, $stub);
        $stub = str_replace("{moduleContainer}", $this->moduleContainer, $stub);
        $stub = str_replace("{system}", $this->moduleSystem, $stub);
        $filename = str_replace(".stub", ".php", $stubName);
        switch ($action) {
            case 'prefixed':
                $filename = $name . $filename;
                break;
            case "replaced":
                $filename = $name . ".php";
                break;
        }
        $this->files->put($destination . "/" . $filename, $stub);
    }

    protected function copySystem()
    {
        if (!$this->files->isDirectory(dirname($this->moduleSystempath))) {
            $this->files->makeDirectory(dirname($this->moduleSystempath), 0777, true, true);
        }

        $this->files->copyDirectory(realpath($this->stubPath . "System"), $this->moduleSystempath);
        $this->generateSystemCodeFromStubs();
    }

    protected function generateSystemCodeFromStubs()
    {
        foreach ($this->systemStubFiles as $stubName => $stubConfig) {
            list($foldername, $plural, $action) = $stubConfig;
            $this->generateSystemCodeFromStubFile($stubName, $foldername, $plural, $action);
        }
    }

    protected function generateSystemCodeFromStubFile($stubName, $foldername, $plural, $action)
    {
        $stubPath = $this->stubPath . $stubName;
        $destination = $this->moduleSystempath . "/" . $foldername;
        $stub = $this->files->get($stubPath);
        //replace the placeholders in the stubs
        $name = $plural ? $this->_modulenamePlural : $this->_modulename;
        $stub = str_replace("{{routemodule}}", strtolower($this->_modulenamePlural), $stub);
        $stub = str_replace("{{module}}", $this->_modulenamePlural, $stub);
        $stub = str_replace("{module}", $this->_modulename, $stub);
        $stub = str_replace("{moduleContainer}", $this->moduleContainer, $stub);
        $stub = str_replace("{system}", $this->moduleSystem, $stub);

        $filename = str_replace(".stub", ".php", $stubName);
        switch ($action) {
            case 'prefixed':
                $filename = $name . $filename;
                break;
            case "replaced":
                $filename = $name . ".php";
                break;
        }

        $this->files->put($destination . "/" . $filename, $stub);
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the module'],
        ];
    }

}
