const callback = (function (response) {
    if (!isEmpty(response)) {
        const formGroup = document.getElementById(response.id).closest('.form-group');

        //make success message
        let alert = document.createElement("DIV");
        let button = document.createElement("BUTTON");
        let icon = document.createElement("SPAN");
        icon.innerHTML = '&times;';

        if (response.executed === true) {
            timeout = 6000 //6 sec in ms
            alert.innerHTML = toFirstUpperCase(globalLang.the) + " <strong>" + formGroup.id + "</strong> " + globalLang.field_correct;
            setAttributes(alert, {
                'class': 'alert alert-success alert-dismissible fade show',
                'role': 'alert',
                'id': 'alert-' + formGroup.id
            });
        } else {
            timeout = 15000; //15 sec in ms
            alert.innerHTML = toFirstUpperCase(globalLang.the) + " <strong>" + formGroup.id + "</strong> "+ globalLang.field_error;
            if (!isEmpty(response.error)) {
                alert.innerHTML += "<br><strong>Error:</strong> " + response.error;
                if (formGroup.id === 'start-times') {
                    document.getElementById(response.id).checked = true;
                }
            }
            setAttributes(alert, {
                'class': 'alert alert-danger alert-dismissible fade show',
                'role': 'alert',
                'id': 'alert-' + formGroup.id
            });
        }

        setAttributes(button, {
            'type': 'button',
            'class': 'close',
            'data-dismiss': 'alert',
            'aria-label': 'Close'
        });

        setAttributes(icon, {
            'aria-hidden': 'true'
        });

        button.appendChild(icon);

        alert.appendChild(button);

        formGroup.appendChild(alert);

        setTimeout(() => {
            $("#alert-" + formGroup.id).alert('close');
        }, timeout);
    } else {
        alert(globalLang.something_went_wrong);
    }
});

function autoSubmit(input) {
    let value = input.value;
    if (isNumeric(value)) {
        value = parseInt(value);
    }
    const data = JSON.stringify({
        notifications_id: input.id,
        value: value,
        fieldId: document.getElementById(input.id).closest('.form-group').id
    });

    $.ajax({
        type: 'POST',
        data: { setting: data },
        url: base_url + 'settings/json_update_notification',
        dataType: 'json',
        error: function (xhr, errorType, exception) {
            if (xhr.status && xhr.status == 400) {
                alert(xhr.responseText);
            } else {
                //TODO better error handling
                alert(globalLang.something_went_wrong);
            }
        },
        success: callback
    });
}

function availableSubmit(input) {
    let checked = input.checked;
    let value;
    if (checked === true) {
        value = 1;
    } else {
        value = 0;
    }
    const data = JSON.stringify({
        notifications_id: input.id,
        value: value,
        fieldId: document.getElementById(input.id).closest('.form-check').id
    });

    $.ajax({
        type: 'POST',
        data: { setting: data },
        url: base_url + 'settings/json_update_notification',
        dataType: 'json',
        error: function (xhr, errorType, exception) {
            if (xhr.status && xhr.status == 400) {
                alert(xhr.responseText);
            } else {
                //TODO better error handling
                alert(globalLang.something_went_wrong);
            }
        },
        success: callback
    });
}