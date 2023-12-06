<?php

namespace Velodome\Velodome\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateFillableModel extends Command
{
    protected $signature = 'velodome:generate:model {name : The name of the model} {--fillable= : Comma-separated fillable fields}';

    protected $description = 'Generate a model with fillable fields';

    public function handle()
    {
        $modelName = $this->argument('name');
        $fillableFields = $this->option('fillable');
        $this->call('make:model', [
            'name' => $modelName
        ]);
        $modelFilePath = base_path("app/Models/{$modelName}.php");
        $this->addFillableProperty($modelFilePath, $fillableFields);
        $this->info('Model generated with fillable fields.');
    }

    private function addFillableProperty($filePath, $fields)
    {
        $fillable = explode(',', $fields);
        $fillableString = "\n\tprotected \$fillable = ['" . implode("', '", $fillable) . "'];";
        $fileContent = File::get($filePath);
        $insertPosition = strpos($fileContent, '//') + strlen('//');
        $newFileContent = substr_replace($fileContent, $fillableString . "\n", $insertPosition, 0);
        File::put($filePath, $newFileContent);
    }
}
