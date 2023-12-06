<?php

namespace Velodome\Velodome\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Velodome\Velodome\Traits\OpenAITrait;

class VelodomeAPIGenerator extends Controller
{
    use OpenAITrait;

    public function index() {
        return view('velodome::api-generator', ['props' => null, 'object_name' => null]);
    }

    public function analize(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $file->storeAs('uploads', $filename);
        $base64File = base64_encode(file_get_contents($file));
        $props = $this->completions($base64File);
        return view('velodome::api-generator', ['props' => $props, 'object_name' => null]);
    }

    public function generate(Request $request) {
        $validator = Validator::make($request->all(), [
            'object_name' => 'required',
            'props' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $fieldsString = $request->props;
        $fieldsArray = explode(',', $fieldsString);
        $fieldNames = [];
        foreach ($fieldsArray as $field) {
            $fieldName = trim(explode(':', $field)[0]);
            $fieldNames[] = $fieldName;
        }
        $fieldNamesString = implode(',', $fieldNames);

        try {
            $modelName = $this->toPascalCase($request->object_name);
            $migrationName = $this->toSnakeCase($request->object_name);
            $routeName = $this->toKebabCase($request->object_name);
            $controllerName = $modelName.'Controller';
            Artisan::call(str_replace(", ", ",", "velodome:generate:migration $migrationName --fields=$fieldsString"));
            Artisan::call("velodome:generate:model $modelName --fillable=$fieldNamesString");
            Artisan::call("velodome:generate:controller $modelName");
            Artisan::call("velodome:generate:route $routeName $controllerName");
            Artisan::call("migrate");
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
        return response()->json(['success' =>'ok'], 200);

    }

    function toPascalCase($string) {
        $string = str_replace(' ', '', ucwords(preg_replace('/[^a-zA-Z0-9\x7f-\xff]++/', ' ', $string)));
        return $string;
    }

    function toSnakeCase($string) {
        $string = preg_replace('/\s+/u', '', ucwords($string)); // Menghilangkan spasi dan membuat huruf pertama menjadi huruf kapital
        $string = lcfirst($string); // Mengubah huruf pertama menjadi huruf kecil
        $string = preg_replace('/\B([A-Z])/', '_$1', $string); // Menambahkan garis bawah sebelum huruf kapital kecuali di awal kata
    
        return strtolower($string); // Mengonversi semua huruf menjadi huruf kecil
    }

    function toKebabCase($string) {
        $string = preg_replace('/[^a-zA-Z0-9]/', ' ', $string); // Menghapus karakter khusus selain huruf dan angka
        $string = preg_replace('/\s+/', ' ', $string); // Menghapus spasi berlebih
        $string = trim($string); // Menghapus spasi di awal dan akhir string
        $string = strtolower($string); // Mengubah semua huruf menjadi huruf kecil
        return str_replace(' ', '-', $string); // Mengganti spasi dengan dash (-)
    }
}
