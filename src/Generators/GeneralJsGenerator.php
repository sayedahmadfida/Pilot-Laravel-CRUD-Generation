<?php

namespace Fida\Crud\Generators;

class GeneralJsGenerator
{
    public function generate()
    {
        $path = public_path('assets/js/general.js');

        // Prevent overwrite
        if (file_exists($path)) {
            return;
        }

        // Ensure directory exists
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $content = <<<'JS'
class AjaxHelper {

    static request({ url, method = 'GET', data = {}, loader = null, button = null }) {

        if (loader) $(loader).removeClass('d-none');
        if (button) $(button).addClass('d-none');

        return new Promise((resolve, reject) => {

            $.ajax({
                url: url,
                type: method,
                data: data,

                success: (response) => {

                    if (loader) $(loader).addClass('d-none');
                    if (button) $(button).removeClass('d-none');

                    resolve(response);

                },

                error: (xhr) => {

                    if (loader) $(loader).addClass('d-none');
                    if (button) $(button).removeClass('d-none');

                    AjaxHelper.handleError(xhr);

                    reject(xhr);

                }

            });

        });

    }

    static handleError(error) {

        if (error.status === 422 && error.responseJSON?.errors) {

            let message = '<ul style="text-align:left;">';

            $.each(error.responseJSON.errors, function (key, value) {
                message += `<li>${value[0]}</li>`;
            });

            message += '</ul>';

            Toast.fire({
                icon: 'error',
                title: message
            });

        } else {

            Toast.fire({
                icon: 'error',
                title: error.responseJSON?.message || 'Something went wrong'
            });

        }

    }

}


class UI {

    static toast(message, icon = 'success') {

        Toast.fire({
            icon: icon,
            title: message
        });

    }

    static confirm(options = {}) {

        return Swal.fire({
            title: options.title || "Are you sure?",
            text: options.text || "You won't be able to revert this!",
            icon: options.icon || 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: options.confirmText || "Yes",
            cancelButtonText: options.cancelText || "Cancel"
        });

    }

}


function numberFormat(number, decimals = 2) {

    const n = parseFloat(number);

    if (isNaN(n)) return '';

    return n.toLocaleString(undefined, {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    });

}


const Toast = Swal.mixin({

    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,

    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }

});


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
JS;

        file_put_contents($path, $content);
    }
}