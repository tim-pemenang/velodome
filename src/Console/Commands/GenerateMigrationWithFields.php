<?php

namespace Velodome\Velodome\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateMigrationWithFields extends Command
{
    protected $signature = 'velodome:generate:migration {table} {--fields=}';

    protected $description = 'Create a migration file with specified fields';

    public function handle()
    {
        $table = $this->argument('table');
        $fields = str_replace("', '", "','", $this->option('fields'));

        $migrationName = 'create_' . $table . '_table';

        $this->call('make:migration', [
            'name' => $migrationName,
            '--create' => $table,
        ]);

        $migrationFilePath = $this->getMigrationFilePath($migrationName);

        if ($migrationFilePath !== null) {
            $this->addFieldsToMigration($migrationFilePath, $fields);

            $this->info('Migration created with specified fields.');
        } else {
            $this->error('Migration file not found.');
        }
    }

    private function getMigrationFilePath($migrationName)
    {
        $files = File::glob(database_path('migrations/*_' . $migrationName . '.php'));
        return count($files) > 0 ? $files[0] : null;
    }

    private function addFieldsToMigration($filePath, $fields)
    {
        $migrationContent = File::get($filePath);

        $fieldDefinitions = '';
        $fieldsArray = explode(',', $fields);
        foreach ($fieldsArray as $field) {
            $fieldParts = explode(':', trim($field));
            $fieldName = trim($fieldParts[0]);
            $fieldType = trim($fieldParts[1]);

            $fieldDefinitions .= "\$table->$fieldType('$fieldName');\n            ";
        }

        $insertPosition = strpos($migrationContent, '$table->timestamps();');
        if ($insertPosition !== false) {
            $newMigrationContent = substr_replace(
                $migrationContent,
                $fieldDefinitions,
                $insertPosition,
                0
            );
            File::put($filePath, $newMigrationContent);
        }
    }
}
