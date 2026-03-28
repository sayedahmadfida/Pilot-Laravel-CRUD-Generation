<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ViewGenerator
{
    public function generate($name, $columns = [])
    {
        $nameLower = Str::lower($name);
        $plural = Str::plural($nameLower);
        $pageTitle = Str::title($plural);

        $title = Str::title($nameLower);

        $folder = resource_path("views/pages/{$nameLower}");

        $viewPath = resource_path("views/pages/{$nameLower}/index.blade.php");

        if ($viewPath && file_exists($viewPath)) {
            return [
                'status' => 'exists',
                'message' => "{$name} view already exists at:\n" . $viewPath,
            ];
        }

        File::ensureDirectoryExists($folder);

        /*
        |--------------------------------------------------------------------------
        | INDEX
        |--------------------------------------------------------------------------
        */

        $indexContent = <<<BLADE
@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-xl-12 col-lg-12 col-sm-12 layout-top-spacing layout-spacing">

        @include('pages.{$nameLower}.create')
        @include('pages.{$nameLower}.edit')

        <div class="card mt-2">
            <div class="card-header">
                <div class="d-flex inv-list-top-section justify-content-between">
                    <h5>{$pageTitle}</h5>
                    <div class="dt-buttons">
                        <button
                            class="btn btn-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#create-{$nameLower}-modal">
                            <span>Add New</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive mt-2 px-2">
                    @include('pages.{$nameLower}.table')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/js/{$nameLower}.js') }}"></script>
@endsection
BLADE;

        File::put($folder . '/index.blade.php', $indexContent);


        /*
        |--------------------------------------------------------------------------
        | TABLE
        |--------------------------------------------------------------------------
        */


        $thead = "";
        $tbody = "";

        // Always include ID
        $thead .= "<th>ID</th>\n";

        // Loop columns
        foreach ($columns as $col) {

            // Skip unwanted
            if (in_array($col['name'], ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $label = Str::title(str_replace('_', ' ', $col['name']));

            $thead .= "<th>{$label}</th>\n";

            $tbody .= "<td>{{ \${$nameLower}->{$col['name']} }}</td>\n";
        }

        // Add created_at manually if you want
        $thead .= "<th>Created At</th>\n";
        $tbody .= "<td>{{ \${$nameLower}->created_at }}</td>\n";

        // Action column
        $thead .= '<th style="width:130px">Action</th>';

        $tableContent = <<<BLADE
<table id="{$nameLower}-table" class="table table-bordered" style="width:100%">
    <thead>
        <tr>
             $thead 
        </tr>
    </thead>
    <tbody id="{$nameLower}-table-body">
         @foreach (\${$plural} as \${$nameLower})
        <tr>
            <td>{{ \$loop->iteration }}</td>
            $tbody
            <td>
                <div class="d-flex justify-content-evenly">
                    <a href="javascript:void(0)"
                       data-id="{{ \${$nameLower}->encrypted_id }}"
                       class="delete-{$nameLower}">
                       <i class="fa-solid fa-trash text-danger"></i>
                    </a>
                    <a href="javascript:void(0)"
                       data-id="{{ \${$nameLower}->encrypted_id }}"
                       class="edit-{$nameLower}">
                       <i class="fa-solid fa-pen-to-square text-success"></i>
                    </a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="pagenate">
    {{ \${$plural}->links('pagination::bootstrap-5') }}
</div>
BLADE;

        File::put($folder . '/table.blade.php', $tableContent);


        /*
        |--------------------------------------------------------------------------
        | EDIT MODAL
        |--------------------------------------------------------------------------
        */

$formFields = "";

foreach ($columns as $col) {

    // Skip unwanted fields
    if (in_array($col['name'], ['id', 'created_at', 'updated_at'])) {
        continue;
    }

    $fieldName = $col['name'];
    $label = Str::title(str_replace('_', ' ', $fieldName));

    // Detect input type
    $type = 'text';

    if (Str::contains($col['type'], ['int', 'decimal', 'float'])) {
        $type = 'number';
    } elseif (Str::contains($col['type'], ['date'])) {
        $type = 'date';
    } elseif (Str::contains($col['type'], ['text'])) {
        $type = 'textarea';
    } elseif (Str::contains($col['type'], ['boolean'])) {
        $type = 'checkbox';
    }

    // Generate field HTML
    if ($type === 'textarea') {

        $formFields .= <<<HTML
<div class="mb-3">
    <label class="form-label">{$label}</label>
    <textarea 
        class="form-control" 
        name="{$fieldName}" 
        id="edit-{$fieldName}"></textarea>
</div>

HTML;

    } elseif ($type === 'checkbox') {

        $formFields .= <<<HTML
<div class="form-check mb-3">
    <input 
        class="form-check-input" 
        type="checkbox" 
        name="{$fieldName}" 
        id="edit-{$fieldName}">
    <label class="form-check-label">
        {$label}
    </label>
</div>

HTML;

    } else {

        $formFields .= <<<HTML
<div class="mb-3">
    <label class="form-label">{$label}</label>
    <input 
        type="{$type}" 
        class="form-control" 
        name="{$fieldName}" 
        id="edit-{$fieldName}">
</div>

HTML;

    }
}

$editContent = <<<BLADE
<div class="modal fade" data-bs-backdrop="static" id="edit-{$nameLower}-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit {$title}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="edit-{$nameLower}-form" method="POST" novalidate>
                @csrf
                <input type="hidden" id="edit-{$nameLower}-id" name="{$nameLower}_id">

                <div class="modal-body">
                    $formFields
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary" id="edit-save-{$nameLower}">
                        Save
                    </button>
                    <button class="btn btn-primary btn-sm d-none" type="button" disabled id="edit-loader">
                        <span class="spinner-border spinner-border-sm"></span>
                        Loading...
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
BLADE;

        File::put($folder . '/edit.blade.php', $editContent);
        return [
            'status' => 'created',
            'message' => "{$name} view created at:\n" . $viewPath,
        ];
    }
}
