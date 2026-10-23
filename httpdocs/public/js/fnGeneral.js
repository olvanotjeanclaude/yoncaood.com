function translate(dict) {
    const lang = $('html').attr('lang') || 'tr'; // fallback to 'tr'
    return dict[lang] || dict['tr'];
}

function showConfirmModal(callback) {
    $("#confirmModal").dialog({
        resizable: false,
        modal: true,
        buttons: {
            "Yes": function () {
                $(this).dialog("close");
                if (callback) callback(true);
            },
            "Cancel": function () {
                $(this).dialog("close");
                if (callback) callback(false);
            }
        }
    });
}

function clearFormErrors(formSelector) {
    $(`${formSelector} .form-control`).removeClass('is-invalid');

    $(`${formSelector} .error-text`).remove();

    $(`${formSelector} .form-error-box`).remove();
}


function handleAjaxError(xhr, formSelector = '') {
    $(`${formSelector} .form-control`).removeClass('is-invalid');
    $(`${formSelector} .error-text`).remove();
    $(`${formSelector} .form-error-box`).remove();

    if (xhr.status === 422 && xhr.responseJSON?.errors) {
        // Laravel validation errors
        const errors = xhr.responseJSON.errors;
        for (const key in errors) {
            if (errors[key]) {
                const input = $(`${formSelector} #${key}`);
                input.addClass('is-invalid');
                const errorEl = $('<div class="error-text text-danger small mt-1"></div>').text(errors[key][0]);
                errorEl.insertAfter(input);
            }
        }
    } else {
        // General or manual errors
        let message = 'Bilinmeyen bir hata oluştu.';
        if (xhr.responseJSON?.message) {
            message = xhr.responseJSON.message;
        } else if (xhr.responseText) {
            try {
                const parsed = JSON.parse(xhr.responseText);
                if (parsed.message) {
                    message = parsed.message;
                }
            } catch (e) {
                message = xhr.responseText;
            }
        }

        const errorBox = $(`
            <div class="form-error-box alert alert-danger" role="alert">
                ${message}
            </div>
        `);
        $(formSelector).prepend(errorBox);
    }

    return false;
}
