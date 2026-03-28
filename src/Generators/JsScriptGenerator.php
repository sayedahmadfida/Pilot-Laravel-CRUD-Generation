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
            return[
                'status' => 'exists',
                'message' => "JavaScript for {$name} already exists at:\n".$jsPath,
            ];
        }

        // Create directory if not exists
        if (!is_dir(dirname($jsPath))) {
            mkdir(dirname($jsPath), 0755, true);
        }

        $renderColumns = "";

foreach ($columns as $col) {

    // Skip ID (already using loop index)
    if ($col['name'] === 'id') {
        continue;
    }

    // Format created_at nicely
    if ($col['name'] === 'created_at') {
        $renderColumns .= "<td>\${{$modelLower}.created_at_formatted}</td>";
    } else {
        $renderColumns .= "<td>\${{$modelLower}.{$col['name']}}</td>";
    }
}



        $content = "
const {$model} = {

    init() {
        this.create();
        this.update();
        this.events();
    },

    events() {

        $(document).on('click', '.delete-{$modelLower}', (e) => this.delete(e));
        $(document).on('click', '.edit-{$modelLower}', (e) => this.edit(e));

    },

    create() {

        $('#create-{$modelLower}-form').validate({

            rules: {
                {$modelLower}: { required: true }
            },

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

                    $('#create-{$modelLower}-modal').modal('hide');

                    form.reset();
                    $(form).validate().resetForm();

                    {$model}.render(res.{$plural});

                });

            }

        });

    },

    update() {

        $('#edit-{$modelLower}-form').validate({

            rules: {
                {$modelLower}: { required: true }
            },

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

                    $('#edit-{$modelLower}-modal').modal('hide');

                    form.reset();
                    $(form).validate().resetForm();

                    {$model}.render(res.{$plural});

                });

            }

        });

    },

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

    edit(event) {

        event.preventDefault();

        const id = $(event.currentTarget).data('id');

        AjaxHelper.request({
            url: '/{$plural}/' + id + '/edit',
            method: 'GET'
        })

        .then(res => {

            $('#edit-{$modelLower}-name').val(res.{$modelLower}.name);
            $('#edit-{$modelLower}-id').val(id);

            $('#edit-{$modelLower}-modal').modal('show');

        });

    },

    render({$plural}) {

    const tbody = $('#{$modelLower}-table-body');

    tbody.empty();

    let startIndex = {$plural}.from || 0;

    {$plural}.data.forEach(({$modelLower}, index) => {

        tbody.append(`
            <tr>
                <td>\${startIndex + index}</td>
                {$renderColumns}
                
                <td>\${{$modelLower}.created_at_formatted}</td>
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

$(document).ready(function () {
    {$model}.init();
});";
        file_put_contents($jsPath, $content);
        return [
            'status' => 'created',
            'message' => "JavaScript for {$name} created at:\n".$jsPath,
        ];
    }
}