<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Str;

class RequestGenerator
{
    /**
     * Generate a Form Request with validation rules
     *
     * @param string $name Model name
     * @param array $columns Columns with validation rules. Format:
        * [
        *  ['name' => 'name', 'rules' => 'required|string'],
        *  ['name' => 'price', 'rules' => 'required|numeric|min:0']
        * ]
     * @return array Status and message
     */
    public function generate(string $name, array $columns = []): array
    {
        $model = Str::studly($name);
        $modelLower = Str::lower($name);

        $requestPath = app_path("Http/Requests/{$model}Request.php");

        // Prevent overwrite
        if (file_exists($requestPath)) {
            return [
                'status' => 'exists',
                'message' => "{$model}Request already exists at:\n" . $requestPath,
            ];
        }

        // Ensure directory exists
        if (!is_dir(dirname($requestPath))) {
            mkdir(dirname($requestPath), 0755, true);
        }

        // Build validation rules
        $rulesArray = [];
        foreach ($columns as $col) {
            // Fallback to empty string if no rules provided
            $rules = $col['rules'] ?? '';
            $rulesArray[] = "            '{$col['name']}' => '{$rules}'";
        }
        $rulesCode = implode(",\n", $rulesArray);

        $content = <<<PHP
<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class {$model}Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
{$rulesCode}
        ];
    }
}
PHP;

        file_put_contents($requestPath, $content);

        return [
            'status' => 'created',
            'message' => "{$model}Request created at:\n" . $requestPath,
        ];
    }
}