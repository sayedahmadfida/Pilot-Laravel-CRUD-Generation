<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Str;

class CreateFormGenerator
{
    public function generate($name)
    {
        $model = Str::studly($name);
        $modelLower = Str::lower($name);

        $viewPath = resource_path("views/pages/{$modelLower}/create.blade.php");

        // Prevent overwrite
        if (file_exists($viewPath)) {
            return;
        }

        // Ensure directory exists
        if (!is_dir(dirname($viewPath))) {
            mkdir(dirname($viewPath), 0755, true);
        }

        $content = <<<BLADE
<div class="modal fade" data-bs-backdrop="static" id="create-{$modelLower}-modal" tabindex="-1"
    aria-labelledby="create-{$modelLower}-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="create-{$modelLower}-modalLabel">Add New {$model}</h1>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <form action="#" id="create-{$modelLower}-form" method="POST" novalidate>
                @csrf
                <div class="modal-body">

                    // Write your form fields here

                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit"
                            class="btn btn-sm btn-primary"
                            id="save-{$modelLower}">
                        Save
                    </button>
                    <button class="btn btn-primary btn-sm d-none"
                            type="button"
                            disabled
                            id="loader">
                        <span class="spinner-border spinner-border-sm"
                              aria-hidden="true"></span>
                        <span role="status">Loading...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
BLADE;

        file_put_contents($viewPath, $content);
    }
}