<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Str;

class ControllerGenerator
{
    public function generate($name)
    {
        $controllerPath = app_path("Http/Controllers/{$name}Controller.php");

        if($controllerPath && file_exists($controllerPath)) {
            return [
                'status' => 'exists',
                'message' => "{$name}Controller already exists at:\n".$controllerPath,
            ];
        }
        if (!is_dir(dirname($controllerPath))) {
            mkdir(dirname($controllerPath), 0755, true);
        }

        $model = Str::studly($name);
        $modelLower = Str::camel($name);
        $plural = Str::plural($modelLower);
        $pluralMethod = Str::studly($plural);
        $viewFolder = Str::lower($name);

        $content = <<<PHP
<?php

namespace App\Http\Controllers;

use App\Http\Requests\\{$model}Request;
use App\Models\\{$model};
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class {$model}Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \${$plural} = self::get{$pluralMethod}();

        return view('pages.{$viewFolder}.index', compact('{$plural}'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store({$model}Request \$request)
    {
        try {

            DB::beginTransaction();

            {$model}::create(\$request->validated());

            DB::commit();

            return response()->json([
                'message' => '{$model} created successfully.',
                '{$plural}' => self::get{$pluralMethod}()
            ], 201);

        } catch (\\Exception \$e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create {$modelLower}.',
                'error' => \$e->getMessage()
            ], 500);

        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\${$modelLower}Id)
    {
        try {

            \${$modelLower} = {$model}::findOrFail(Crypt::decrypt(\${$modelLower}Id));

            return response()->json([
                '{$modelLower}' => \${$modelLower}
            ], 200);

        } catch (\\Exception \$e) {

            return response()->json([
                'message' => 'Failed to retrieve {$modelLower}.',
                'error' => \$e->getMessage()
            ], 500);

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update({$model}Request \$request, \${$modelLower}Id)
    {
        try {

            DB::beginTransaction();

            \${$modelLower} = {$model}::findOrFail(Crypt::decrypt(\${$modelLower}Id));

            \${$modelLower}->update(\$request->validated());

            DB::commit();

            return response()->json([
                'message' => '{$model} updated successfully.',
                '{$plural}' => self::get{$pluralMethod}()
            ], 200);

        } catch (\\Exception \$e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update {$modelLower}.',
                'error' => \$e->getMessage()
            ], 500);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\${$modelLower}Id)
    {
        try {

            DB::beginTransaction();

            \${$modelLower} = {$model}::findOrFail(Crypt::decrypt(\${$modelLower}Id));

            \${$modelLower}->delete();

            DB::commit();

            return response()->json([
                'message' => '{$model} deleted successfully.',
                '{$plural}' => self::get{$pluralMethod}()
            ], 200);

        } catch (\\Exception \$e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Failed to delete {$modelLower}.',
                'error' => \$e->getMessage()
            ], 500);

        }
    }

    public static function get{$pluralMethod}()
    {
        return {$model}::orderBy('id', 'desc')
            ->paginate(10)
            ->through(function (\${$modelLower}) {

                \${$modelLower}->encrypted_id = Crypt::encrypt(\${$modelLower}->id);
                \${$modelLower}->created_at_formatted = \${$modelLower}->created_at->format('Y-m-d H:i:s');

                \${$modelLower}->id = null;

                return \${$modelLower};

            });
    }
}
PHP;

        file_put_contents($controllerPath, $content);
        return [
            'status' => 'created',
            'message' => "{$name}Controller created at:\n".$controllerPath,
        ];
    }
}