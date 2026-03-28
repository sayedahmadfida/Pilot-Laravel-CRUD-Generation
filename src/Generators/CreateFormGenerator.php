<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Str;

class CreateFormGenerator
{
    public function generate($name, $columns = [])
    {
        $model = Str::studly($name);
        $modelLower = Str::lower($name);

        $viewPath = resource_path("views/pages/{$modelLower}/create.blade.php");

        if (file_exists($viewPath)) {
            return [
                'status' => 'exists',
                'message' => "Create form for {$name} already exists at:\n" . $viewPath,
            ];
        }

        if (!is_dir(dirname($viewPath))) {
            mkdir(dirname($viewPath), 0755, true);
        }

        // ✅ Generate inputs
        $inputs = "";

        foreach ($columns as $col) {
            $label = Str::title(str_replace('_', ' ', $col['name']));
            $nameAttr = $col['name'];

            // Determine input type
            $type = 'text';

            switch ($col['type']) {
                case 'integer':
                case 'decimal':
                case 'float':
                    $type = 'number';
                    break;

                case 'email':
                    $type = 'email';
                    break;

                case 'password':
                    $type = 'password';
                    break;

                case 'date':
                    $type = 'date';
                    break;

                case 'text':
                    $type = 'textarea';
                    break;
            }

            // Required attribute
            $required = Str::contains($col['rules'] ?? '', 'required') ? 'required' : '';

            // Generate field
            if ($type === 'textarea') {
                $inputs .= <<<HTML
<div class="mb-3">
    <label class="form-label">{$label}</label>
    <textarea name="{$nameAttr}" class="form-control" {$required}></textarea>
</div>

HTML;
            } else {
                $inputs .= <<<HTML
<div class="mb-3">
    <label class="form-label">{$label}</label>
    <input type="{$type}" name="{$nameAttr}" placeholder="Enter {$label}" class="form-control rounded-0" {$required}>
</div>

HTML;
            }
        }

        if (empty($inputs)) {
            $inputs = "// No fields defined";
        }

        $content = <<<BLADE
<div class="modal fade" data-bs-backdrop="static" id="create-{$modelLower}-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Add New {$model}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="#" id="create-{$modelLower}-form" method="POST" novalidate>
                @csrf

                <div class="modal-body">

                    {$inputs}

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit" class="btn btn-sm btn-primary" id="save-{$modelLower}">
                        Save
                    </button>

                    <button class="btn btn-primary btn-sm d-none" type="button" disabled id="loader">
                        <span class="spinner-border spinner-border-sm"></span>
                        Loading...
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
BLADE;

        file_put_contents($viewPath, $content);

        return [
            'status' => 'created',
            'message' => "Create form for {$name} created at:\n" . $viewPath,
        ];
    }
}