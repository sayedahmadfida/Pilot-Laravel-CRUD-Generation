<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TableGenerator
{
    public function generate($name)
    {
        $nameLower = Str::lower($name);
        $plural = Str::plural($nameLower);

        $folder = resource_path("views/pages/{$nameLower}");

        File::ensureDirectoryExists($folder);


        

        /*
        |--------------------------------------------------------------------------
        | Index View
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

                    <h5>{$plural}</h5>

                    <div class="dt-action-buttons align-self-center">

                        <div class="dt-buttons">

                            <button
                                class="dt-button btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#create-{$nameLower}-modal">

                                <span>Add New</span>

                            </button>

                        </div>

                    </div>

                </div>

            </div>

            <div class="card-body p-0">

                <div class="widget-content widget-content-area br-8">

                    <div class="table-responsive mt-2">

                        @include('pages.{$nameLower}.table')

                    </div>

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
        | Table View
        |--------------------------------------------------------------------------
        */

        $tableContent = <<<BLADE
<table id="{$nameLower}-table" class="table table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Created At</th>
            <th style="width:130px">Action</th>
        </tr>
    </thead>

    <tbody id="{$nameLower}-table-body">

        @foreach (\${$plural} as \${$nameLower})
            <tr>

                <td>{{ \$loop->iteration }}</td>

                <td>{{ \${$nameLower}->created_at_formatted }}</td>

                <td>

                    <div class="d-flex justify-content-evenly">

                        <a href="javascript:void(0);"
                           data-id="{{ \${$nameLower}->encrypted_id }}"
                           class="delete-{$nameLower}">
                           <i class="fa-solid fa-trash text-danger"></i>
                        </a>

                        <a href="javascript:void(0);"
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
    }
}