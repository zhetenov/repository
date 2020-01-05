<?php

namespace Zhetenov\Repository\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;

class MakeRepositoryCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * @var string
     */
    protected $type = 'Repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $interfacePath;

    /**
     * @var string
     */
    protected $classPath;

    /**
     * MakeRepositoryCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/repository.stub';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getInterfaceStub(): string
    {
        return __DIR__ . '/stubs/interface.stub';
    }

    /**
     * @return bool|void|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->preorder();
        $this->info('Interface created successfully');
        $this->info('Class created successfully');
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = $this->cutApp($name);
        return $this->laravel->basePath('app/Repositories/').$this->cutRepository($name).'/'.$name.'.php';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getInterfacePath($name)
    {
        $name = $this->cutApp($name);
        return $this->laravel->basePath('app/Repositories/').$this->cutRepository($name).'/'.$name.'Interface.php';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getClassPath($name)
    {
        $name = $this->cutApp($name);
        return $this->laravel->basePath('app/Repositories/').$this->cutRepository($name).'/'.$name.'Repository.php';
    }

    protected function getNamespace($name)
    {
        return 'app\\Repositories\\'.$this->cutApp($this->cutRepository($name));
    }

    protected function cutRepository($name)
    {
        return str_replace("Repository","", $name);
    }

    protected function cutApp($name)
    {
        return str_replace("App\\", "", $name);
    }

    public function getInterfaceName(string $name)
    {
        return $this->cutApp($name).'Interface';
    }

    public function getClassName(string $name)
    {
        return $this->cutApp($name).'Repository';
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function preorder()
    {
        $this->name             = $this->qualifyClass($this->getNameInput());
        $this->interfacePath    = $this->getInterfacePath($this->name);
        $this->classPath        = $this->getClassPath($this->name);

        $this->makeDirectory($this->interfacePath);
        $this->makeDirectory($this->classPath);

        $this->files->put($this->interfacePath, $this->sortImports($this->buildInterface($this->name)));
        $this->files->put($this->classPath, $this->sortImports($this->buildClass($this->name)));
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel'],
            [$this->getNamespace($name), $this->rootNamespace(), $this->userProviderModel()],
            $stub
        );

        return $this;
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceInterface(&$stub, $name)
    {
        $stub = str_replace(
            'DummyInterface',
            $this->getInterfaceName($name),
            $stub
        );

        return $this;
    }
        /**
     *
     * @param string $name
     * @return mixed|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildInterface($name)
    {
        $stub = $this->files->get($this->getInterfaceStub());
        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $this->getInterfaceName($name));
    }

    /**
     * @param string $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
        return $this->replaceInterface($stub, $name)->replaceNamespace($stub, $name)->replaceClass($stub, $this->getClassName($name));
    }
}
