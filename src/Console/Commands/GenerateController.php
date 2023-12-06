<?php

namespace Velodome\Velodome\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'velodome:generate:controller {name : The name of the controller}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a controller with finished crud';

    public function handle()
    {
        $modelName = $this->argument('name');
        $controllerName = $modelName.'Controller';

        $this->call('make:controller', [
            'name' => $controllerName
        ]);
        $controllerFilePath = base_path("app/Http/Controllers/{$controllerName}.php");
        $this->addFinishedCrudFunctionality($controllerFilePath, $modelName);
        $this->info('Controller generated with finished CRUD Functionality.');
    }

    private function addFinishedCrudFunctionality($filePath, $modelName)
    {
        $indexFunction = "\n\tpublic function index()\t{\n\t\t".'$data'." = $modelName::all();\n\t\treturn response()->json(['data' =>".' $data'."]);\n\t}";
        $showFunction = "\n\tpublic function show(".'$id'.")\t{\n\t\t".'$data'." = $modelName::find(".'$id'.");\n\t\treturn response()->json(['data' =>".' $data'."]);\n\t}";
        $storeFunction = "\n\tpublic function store(Request ".'$request'.")\t{\n\t\t".'$data'." = $modelName::create(".'$request->all()'.");\n\t\treturn response()->json(['data' =>".' $data'."]);\n\t}";
        $updateFunction = "\n\tpublic function update(Request ".'$request, $id'.")\t{\n\t\t".'$data'." = $modelName::find(".'$id'.");\n\t\t".'$data->update($request->all());'."\n\t\treturn response()->json(['data' =>".' $data'."]);\n\t}";
        $destroyFunction = "\n\tpublic function destroy(".'$id'.")\t{\n\t\t$modelName::find(".'$id'.")->delete();\n\t\treturn response()->json(['status' => 'success']);\n\t}";
        $function = $indexFunction."\n".$showFunction."\n".$storeFunction."\n".$updateFunction."\n".$destroyFunction;
        $fileContent = File::get($filePath);
        $insertPosition = strpos($fileContent, '//') + strlen('//');
        $newFileContent = substr_replace($fileContent, $function . "\n", $insertPosition, 0);
        File::put($filePath, $newFileContent);

        $fileContent = File::get($filePath);
        $insertPositionModel = strpos($fileContent, 'use Illuminate\Http\Request;') + strlen('use Illuminate\Http\Request;');
        $newFileContentModel = substr_replace($fileContent, "\nuse App\\Models\\" . $modelName . ";", $insertPositionModel, 0);
        File::put($filePath, $newFileContentModel);
    }
}
