<?php

namespace Laravilt\Infolists\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeInfolistCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:infolist {name : The name of the infolist}
                            {--force : Overwrite existing file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new infolist class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Infolist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->components->info("Infolist [{$this->argument('name')}] created successfully.");

        // Show usage example
        $this->newLine();
        $this->components->bulletList([
            'Import: use App\Infolists\\'.str_replace('/', '\\', $this->argument('name')).';',
            'Usage: '.class_basename($this->argument('name')).'::make()->schema([...])',
        ]);
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return __DIR__.'/../../stubs/infolist.stub';
    }

    /**
     * Get the default namespace for the class.
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Infolists';
    }

    /**
     * Build the class with the given name.
     */
    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);

        return $this->replaceInfolistName($stub);
    }

    /**
     * Replace the infolist name in the stub.
     */
    protected function replaceInfolistName(string $stub): string
    {
        $name = class_basename($this->argument('name'));
        $kebabName = Str::kebab($name);
        $snakeName = Str::snake($name);

        $stub = str_replace('{{ infolistKebab }}', $kebabName, $stub);
        $stub = str_replace('{{ infolistSnake }}', $snakeName, $stub);

        return $stub;
    }

    /**
     * Get the destination class path.
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }
}
