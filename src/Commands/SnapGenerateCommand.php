<?php

namespace Ensue\Snap\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Pluralizer;
use Symfony\Component\Console\Input\InputArgument;

class SnapGenerateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'snap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new snap module';

    protected $moduleContainer;

    protected $moduleSystem;

    protected string $moduleSystemPath;

    protected string $_moduleName;

    protected string $_moduleNamePlural;

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
        'SystemModel.stub' => ['Database/Models', false, 'replaced'], // folder, needs pluralization, prefixed/replaced/none
    ];

    /**
     * ModuleGenerateCommand constructor
     * @param Filesystem $files
     * @param Application $app
     */
    public function __construct(protected Filesystem $files, protected Application $app)
    {
        parent::__construct();
        $this->moduleContainer = $this->app['config']->get('snap.module');
        $this->moduleSystem = $this->app['config']->get('snap.system');
        $this->namespacePrefix = '\App\\' . $this->moduleContainer;
        $this->stubPath = dirname(__DIR__) . "/Stubs/";
    }

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->fire();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function fire(): void
    {
        $this->makeModule();
    }

    /**
     * Generate the desired migration.
     * @throws FileNotFoundException
     */
    protected function makeModule(): void
    {
        $name = ucfirst($this->argument('name'));
        $this->_moduleName = Pluralizer::singular($name);
        $this->_moduleNamePlural = Pluralizer::plural($name);
        $this->namespace = $this->namespacePrefix . "\\" . $this->_moduleNamePlural;

        if ($this->files->exists($this->modulePath = $this->getPath($this->_moduleNamePlural))) {
            $this->error('Module ' . $name . '  already exists!');
            return;
        }
        $this->moduleSystemPath = $this->getPath($this->_moduleName, 'system');
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
    protected function getPath(string $name, string $type = "module"): string
    {
        if ($type === 'module') {
            return app_path($this->moduleContainer . "/" . $name);
        }
        return app_path($this->moduleSystem . "/" . $name);
    }

    /**
     * Build the directory for the class if necessary.
     * @throws FileNotFoundException
     */
    protected function copyModuleStructureFromStubs(): void
    {
        $this->copyModule();
        $this->copySystem();
    }

    /**
     * @throws FileNotFoundException
     */
    protected function copyModule(): void
    {
        if (!$this->files->isDirectory(dirname($this->modulePath))) {
            $this->files->makeDirectory(dirname($this->modulePath), 0777, true, true);
        }

        $this->files->copyDirectory(realpath($this->stubPath . "Module"), $this->modulePath);
        $this->generateCodeFromStubs();
    }

    /**
     * Generate Cod from stubs
     * @throws FileNotFoundException
     */
    protected function generateCodeFromStubs(): void
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
     * @throws FileNotFoundException
     */
    protected function generateCodeFromStubFile($stubName, $foldername, $plural, $action): void
    {
        $stubPath = $this->stubPath . $stubName;
        $destination = $this->modulePath . "/" . $foldername;
        $this->generateFileInDestination($stubPath, $plural, $stubName, $action, $destination);
    }

    protected function copySystem(): void
    {
        if (!$this->files->isDirectory(dirname($this->moduleSystemPath))) {
            $this->files->makeDirectory(dirname($this->moduleSystemPath), 0777, true, true);
        }

        $this->files->copyDirectory(realpath($this->stubPath . "System"), $this->moduleSystemPath);
        $this->generateSystemCodeFromStubs();
    }

    protected function generateSystemCodeFromStubs(): void
    {
        foreach ($this->systemStubFiles as $stubName => $stubConfig) {
            list($foldername, $plural, $action) = $stubConfig;
            $this->generateSystemCodeFromStubFile($stubName, $foldername, $plural, $action);
        }
    }

    protected function generateSystemCodeFromStubFile($stubName, $foldername, $plural, $action): void
    {
        $stubPath = $this->stubPath . $stubName;
        $destination = $this->moduleSystemPath . "/" . $foldername;
        $this->generateFileInDestination($stubPath, $plural, $stubName, $action, $destination);
    }

    /**
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the module'],
        ];
    }

    /**
     * @param string $stubPath
     * @param $plural
     * @param $stubName
     * @param $action
     * @param string $destination
     * @return void
     * @throws FileNotFoundException
     */
    protected function generateFileInDestination(string $stubPath, $plural, $stubName, $action, string $destination): void
    {
        $stub = $this->files->get($stubPath);
        //replace the placeholders in the stubs
        $name = $plural ? $this->_moduleNamePlural : $this->_moduleName;
        $stub = str_replace(array("{{routemodule}}", "{{module}}", "{module}", "{moduleContainer}", "{system}"), array(strtolower($this->_moduleNamePlural), $this->_moduleNamePlural, $this->_moduleName, $this->moduleContainer, $this->moduleSystem), $stub);
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

}
