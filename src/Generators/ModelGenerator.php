<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Facades\File;

class ModelGenerator
{
    public function generate($name, $columns = [])
    {
        $modelPath = app_path("Models/{$name}.php");

        if (File::exists($modelPath)) {
            return [
                'status' => 'exists',
                'message' => "{$name} model already exists at:\n" . $modelPath,
            ];
        }

        // ✅ Generate fillable fields
        $fillable = "";

        foreach ($columns as $col) {
            // Skip id and timestamps if needed
            if (in_array($col['name'], ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $fillable .= "        '{$col['name']}',\n";
        }

        // fallback if empty
        if (empty($fillable)) {
            $fillable = "        // Add fillable fields here\n";
        }

        $content = "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class {$name} extends Model
{
    protected \$fillable = [
{$fillable}    ];
}
";

        file_put_contents($modelPath, $content);

        return [
            'status' => 'created',
            'message' => "{$name} model created at:\n" . $modelPath,
        ];
    }
}