<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Str;

class RequestGenerator
{
    public function generate($name)
    {
        $model = Str::studly($name);
        $modelLower = Str::lower($name);

        $requestPath = app_path("Http/Requests/{$model}Request.php");

        // Prevent overwrite
        if (file_exists($requestPath)) {
            return;
        }

        // Ensure directory exists
        if (!is_dir(dirname($requestPath))) {
            mkdir(dirname($requestPath), 0755, true);
        }

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
            // Define your validation rules here
        ];
    }
}
PHP;

        file_put_contents($requestPath, $content);
    }
}