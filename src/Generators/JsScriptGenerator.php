<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Str;

class JsScriptGenerator
{
    public function generate($name, $columns = [])
    {
        $model = ucfirst($name);
        $modelLower = Str::lower($name);
        $plural = Str::plural($modelLower);

        $jsPath = public_path("assets/js/{$modelLower}.js");

        if (file_exists($jsPath)) {
            return [
                'status' => 'exists',
                'message' => "JavaScript for {$name} already exists at:\n" . $jsPath,
            ];
        }

        if (!is_dir(dirname($jsPath))) {
            mkdir(dirname($jsPath), 0755, true);
        }

        /*
        |--------------------------------------------------------------------------
        | TABLE COLUMNS
        |--------------------------------------------------------------------------
        */
        $renderColumns = "";

        foreach ($columns as $col) {

            if (in_array($col['name'], ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $field = $col['name'];

            $renderColumns .= "<td>\${{$modelLower}.{$field} ?? ''}</td>";
        }

        /*
        |--------------------------------------------------------------------------
        | EDIT FIELDS
        |--------------------------------------------------------------------------
        */
        $editFields = "";

        foreach ($columns as $col) {

            if (in_array($col['name'], ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $field = $col['name'];

            if (Str::contains($col['type'], ['boolean'])) {

                $editFields .= "
                $('#edit-{$field}').prop('checked', data.{$field} ? true : false);
                ";

            } else {

                $editFields .= "
                $('#edit-{$field}').val(data.{$field} ?? '');
                ";
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FINAL SCRIPT
        |--------------------------------------------------------------------------
        */
        $content = "
const {$model} = {

    init() {
        this.events();
        this.create();
        this.update();
    },

    /*
    |--------------------------------------------------------------------------
    | EVENTS (SAFE BINDING)
    |--------------------------------------------------------------------------
    */
    events() {

        $(document).off('click', '.delete-{$modelLower}')
                   .on('click', '.delete-{$modelLower}', (e) => this.delete(e));

        $(document).off('click', '.edit-{$modelLower}')
                   .on('click', '.edit-{$modelLower}', (e) => this.edit(e));
    },

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    create() {

        if (!$('#create-{$modelLower}-form').length) return;

        $('#create-{$modelLower}-form').validate({

            submitHandler: (form) => {

                AjaxHelper.request({
                    url: '/{$plural}',
                    method: 'POST',
                    data: $(form).serialize(),
                    loader: '#loader',
                    button: '#save-{$modelLower}'
                })
                .then(res => {

                    UI.toast(res.message);

                    const modal = document.getElementById('create-{$modelLower}-modal');
                    if (modal) bootstrap.Modal.getInstance(modal)?.hide();

                    form.reset();
                    $(form).validate().resetForm();

                    {$model}.render(res.{$plural});

                });

            }

        });

    },

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    update() {

        if (!$('#edit-{$modelLower}-form').length) return;

        $('#edit-{$modelLower}-form').validate({

            submitHandler: (form) => {

                const id = $('#edit-{$modelLower}-id').val();

                AjaxHelper.request({
                    url: '/{$plural}/' + id,
                    method: 'PUT',
                    data: $(form).serialize(),
                    loader: '#edit-loader',
                    button: '#edit-save-{$modelLower}'
                })
                .then(res => {

                    UI.toast(res.message);

                    const modal = document.getElementById('edit-{$modelLower}-modal');
                    if (modal) bootstrap.Modal.getInstance(modal)?.hide();

                    form.reset();
                    $(form).validate().resetForm();

                    {$model}.render(res.{$plural});

                });

            }

        });

    },

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    delete(event) {

        event.preventDefault();

        const id = $(event.currentTarget).data('id');

        UI.confirm().then(result => {

            if (!result.isConfirmed) return;

            AjaxHelper.request({
                url: '/{$plural}/' + id,
                method: 'DELETE'
            })
            .then(res => {

                UI.toast(res.message);

                {$model}.render(res.{$plural});

            });

        });

    },

    /*
    |--------------------------------------------------------------------------
    | EDIT (SAFE + DYNAMIC)
    |--------------------------------------------------------------------------
    */
    edit(event) {

        event.preventDefault();

        const id = $(event.currentTarget).data('id');

        AjaxHelper.request({
            url: '/{$plural}/' + id + '/edit',
            method: 'GET'
        })
        .then(res => {

            const data = res.{$modelLower} || {};

            const form = $('#edit-{$modelLower}-form')[0];
            if (form) form.reset();

            {$editFields}

            $('#edit-{$modelLower}-id').val(id);

            const modalEl = document.getElementById('edit-{$modelLower}-modal');
            if (modalEl) {
                new bootstrap.Modal(modalEl).show();
            }

        })
        .catch(() => {
            UI.toast('Failed to load data');
        });

    },

    /*
    |--------------------------------------------------------------------------
    | RENDER TABLE
    |--------------------------------------------------------------------------
    */
    render({$plural}) {

        if (!{$plural} || !{$plural}.data) return;

        const tbody = $('#{$modelLower}-table-body');

        tbody.empty();

        let startIndex = {$plural}.from ? {$plural}.from - 1 : 0;

        {$plural}.data.forEach(({$modelLower}, index) => {

            tbody.append(`
                <tr>
                    <td>\${startIndex + index + 1}</td>
                    {$renderColumns}
                    <td>\${{$modelLower}.created_at_formatted ?? ''}</td>
                    <td>
                        <div class=\"d-flex justify-content-evenly\">

                            <a href=\"#\"
                               class=\"delete-{$modelLower}\"
                               data-id=\"\${{$modelLower}.encrypted_id}\">
                               <i class=\"fa-solid fa-trash text-danger\"></i>
                            </a>

                            <a href=\"#\"
                               class=\"edit-{$modelLower}\"
                               data-id=\"\${{$modelLower}.encrypted_id}\">
                               <i class=\"fa-solid fa-pen-to-square text-success\"></i>
                            </a>

                        </div>
                    </td>
                </tr>
            `);

        });

    }

};

// CSRF setup (Laravel)
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
    }
});

$(document).ready(function () {
    {$model}.init();
});
";

        file_put_contents($jsPath, $content);

        return [
            'status' => 'created',
            'message' => "JavaScript for {$name} created at:\n" . $jsPath,
        ];
    }
}