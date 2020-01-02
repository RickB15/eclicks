function getFields(name) {
    //set fields per modal (case is modal name)
    switch (name.toLowerCase()) {
        case 'make':
        case 'edit':
            return {
                //form options
                form: {
                    validation: true
                },
                //field item
                title: {
                    label: true,
                    prepend: false,
                    append: false,
                    element: 'input',
                    attributes: {
                        type: 'text',
                        required: 'true',
                    },
                    small: globalLang.required,
                    validationRules: {
                        required: 'true',
                        min_length: 3,
                        alpha_numeric_spaces_dash: 'true'
                    }
                },
                //field item
                description: {
                    label: true,
                    prepend: false,
                    append: false,
                    element: 'textarea',
                    attributes: {
                        type: 'text'
                    }
                },
                //field item
                duration: {
                    groupLabel: true,
                    group: {
                        //group item
                        hours: {
                            label: false,
                            prepend: false,
                            append: true,
                            element: 'button',
                            attributes: {
                                type: 'button',
                                class: 'custom-select',
                                required: 'true',
                                value: 0,
                                data_toggle: 'modal',
                                data_target: 'timepicker',
                                data_backdrop: "static",
                                data_keyboard: "false"
                            },
                            inner: '0',
                            small: globalLang.required,
                            eventListeners: {
                                eventListener: {
                                    type: 'click',
                                    listener: function () {
                                        const modalName = 'timepicker';
                                        const _this = this;
                                        makeModal(
                                            modalName,
                                            toFirstUpperCase(globalLang.enter_an_hour),
                                            toFirstUpperCase(globalLang.ok),
                                            toFirstUpperCase(globalLang.cancel),
                                            toFirstUpperCase(globalLang.delete)
                                        );
                                        $('#' + modalName).modal({ backdrop: 'static' });
                                        $('#' + modalName).modal('show');
                                        setTimeout(() => {
                                            $("#jqxTimePicker").jqxTimePicker({ width: 400, height: 400, selection: 'hour', format: '24-hour' });
                                            $("#jqxTimePicker").css({ width: 'auto', height: 'auto' });
                                            if ($('#duration-hours__button').val()) {
                                                $('#jqxTimePicker').jqxTimePicker('setHours', $('#duration-hours__button').val());
                                            }
                                            if ($('#duration-minutes__button').val()) {
                                                $('#jqxTimePicker').jqxTimePicker('setMinutes', $('#duration-minutes__button').val());
                                            }
                                            $(".jqx-minute-container").click(false);
                                        }, 150);
                                        $("#jqxTimePicker").on("change", function (event) {
                                            const hour = event.args.value.getHours();
                                            $('#ok').click(function () {
                                                $(_this).val(hour);
                                                _this.innerHTML = hour;
                                            });
                                        });
                                    },
                                    options: false
                                }
                            },
                            validationRules: {
                                required: 'true',
                                numeric: 'true'
                            }
                        },
                        //group item
                        minutes: {
                            label: false,
                            prepend: false,
                            append: true,
                            element: 'button',
                            attributes: {
                                type: 'button',
                                class: 'custom-select',
                                required: 'true',
                                value: 0,
                                data_toggle: 'modal',
                                data_target: 'timepicker',
                                data_backdrop: "static",
                                data_keyboard: "false"
                            },
                            inner: '0',
                            small: globalLang.required,
                            eventListeners: {
                                eventListener: {
                                    type: 'click',
                                    listener: function () {
                                        const modalName = 'timepicker';
                                        const _this = this;
                                        makeModal(
                                            modalName,
                                            toFirstUpperCase(globalLang.enter_a_minute),
                                            toFirstUpperCase(globalLang.ok),
                                            toFirstUpperCase(globalLang.cancel),
                                            toFirstUpperCase(globalLang.delete)
                                        );
                                        $('#' + modalName).modal({ backdrop: 'static' });
                                        $('#' + modalName).modal('show');
                                        setTimeout(() => {
                                            $("#jqxTimePicker").jqxTimePicker({ width: 400, height: 400, selection: 'minute', minuteInterval: 5, format: '24-hour' });
                                            $("#jqxTimePicker").css({ width: 'auto', height: 'auto' });
                                            if ($('#duration-hours__button').val()) {
                                                $('#jqxTimePicker').jqxTimePicker('setHours', $('#duration-hours__button').val());
                                            }
                                            if ($('#duration-minutes__button').val()) {
                                                $('#jqxTimePicker').jqxTimePicker('setMinutes', $('#duration-minutes__button').val());
                                            }
                                            $(".jqx-hour-container").click(false);
                                        }, 150);;
                                        $("#jqxTimePicker").on("change", function (event) {
                                            const minute = event.args.value.getMinutes();
                                            $('#ok').click(function () {
                                                $(_this).val(minute);
                                                _this.innerHTML = minute;
                                            });
                                        })
                                    },
                                    options: false
                                }
                            },
                            validationRules: {
                                required: 'true',
                                numeric: 'true'
                            }
                        }
                    }
                }
            }
        case 'timepicker':
            return {
                //field item
                picker: {
                    label: false,
                    prepend: false,
                    append: false,
                    element: 'div',
                    attributes: {
                        id: 'jqxTimePicker'
                    }
                }
            }
        default:
            return false;
    }
}
function makeModal(name, title, saveText = 'save', cancelText = 'cancel', deleteText = 'delete', preventSubmit = false, eventId = 0) {
    //set elements
    let modal = document.createElement("DIV");
    let modalDialog = document.createElement("DIV");
    let modalContent = document.createElement("DIV");
    let modalHeader = document.createElement("DIV");
    let modalTitle = document.createElement("H5");
    let buttonClose = document.createElement("BUTTON");
    let closeIcon = document.createElement("SPAN");
    let modalFooter = document.createElement("DIV");
    let buttonSave = document.createElement("BUTTON");
    let buttonCancel = document.createElement("BUTTON");

    //set attributes
    setAttributes(modal, {
        id: name,
        class: 'modal fade',
        tabindex: '-1',
        role: 'dialog',
        aria_labelledby: 'modal',
        aria_hidden: 'true'
    });
    setAttributes(modalDialog, {
        class: 'modal-dialog modal-dialog-centered modal-dialog-scrollable',
        role: 'document'
    });
    setAttributes(modalContent, {
        class: 'modal-content'
    });
    setAttributes(modalHeader, {
        class: 'modal-header'
    });
    setAttributes(modalTitle, {
        id: 'modelLongTitle',
        class: 'modal-title'
    });
    setAttributes(buttonClose, {
        id: 'close',
        type: 'button',
        class: 'close',
        data_dismiss: 'modal',
        aria_label: 'Close'
    });
    setAttributes(closeIcon, {
        aria_hidden: 'true'
    });
    setAttributes(modalFooter, {
        class: 'modal-footer'
    });
    if (name === 'timepicker') {
        setAttributes(buttonSave, {
            id: 'ok',
            type: 'button',
            class: 'btn btn-primary'
        });
    } else {
        setAttributes(buttonSave, {
            id: 'save',
            type: 'submit',
            form: name + '-form',
            class: 'btn btn-primary'
        });
    }
    setAttributes(buttonCancel, {
        id: 'cancel',
        type: 'button',
        class: 'btn btn-secondary',
        data_dismiss: 'modal'
    });    

    //add event listeners
    modalRemoveEventListener([buttonClose, buttonCancel], name);
    if (preventSubmit === false) {
        modalRemoveEventListener([buttonSave], name);
    }

    //add text
    modalTitle.innerHTML = title;
    closeIcon.innerHTML = '&times;';
    buttonSave.innerHTML = saveText;
    buttonCancel.innerHTML = cancelText;

    //append
    buttonClose.appendChild(closeIcon);

    modalHeader.appendChild(modalTitle);
    modalHeader.appendChild(buttonClose);

    modalBody = makeModalBody(name, eventId);

    if (modalBody === false) {
        //TODO better error handling
        alert("The modal can't be rendered");
        return false;
    }

    if (!isEmpty(eventId) && parseInt(eventId) !== 0) {
        fillInFields(function(response){
            //TODO better error handling (now modal is been removed on ajax error)
            // console.log(response);
        }, modalBody, eventId);
    }

    if (name === 'edit') {
        let buttonDelete = document.createElement("BUTTON");
        setAttributes(buttonDelete, {
            id: 'delete',
            type: 'button',
            class: 'btn btn-danger mr-auto',
            form: name + '-form'
        });
        modalRemoveEventListener([buttonDelete], name);
        buttonDelete.innerHTML = deleteText;
        buttonDelete.addEventListener('click', function (event) {
            onDelete(buttonDelete);
        });
        modalFooter.appendChild(buttonDelete);
    }
    modalFooter.appendChild(buttonSave);
    modalFooter.appendChild(buttonCancel);

    modalContent.appendChild(modalHeader);
    modalContent.appendChild(modalBody);
    modalContent.appendChild(modalFooter);

    modalDialog.appendChild(modalContent);

    modal.appendChild(modalDialog);

    document.body.appendChild(modal);
}

function makeModalBody(name, eventId = 0) {
    let fields = getFields(name);
    if (fields === false) {
        //TODO better error handling
        alert('no correct modal target');
        return false;
    }
    let formOptions = fields.form || '';
    delete fields.form;
    let modalBody = document.createElement("FORM");
    let buttonSubmit = document.createElement("BUTTON");

    setAttributes(modalBody, {
        id: name + '-form',
        name: 'event-data',
        class: 'modal-body',
        data_event: eventId
    });
    setAttributes(buttonSubmit, {
        id: 'form-submit',
        type: 'submit',
        hidden: 'true'
    });

    for (const [field, specs] of Object.entries(fields)) {
        if ('group' in specs && !isEmpty(specs.group)) {
            let formRow = document.createElement("DIV");

            setAttributes(formRow, {
                id: field,
                class: 'form-row'
            });

            for (const [id, groupspecs] of Object.entries(specs.group)) {
                formGroup = makeFormGroup(field, groupspecs, id);
                formRow.appendChild(formGroup);
            }

            if (specs.groupLabel === true) {
                let label = document.createElement("LABEL");

                setAttributes(label, {
                    for: field,
                    class: 'font-weight-bold'
                });

                if (!isEmpty(globalLang[field])) {
                    label.innerHTML = toFirstUpperCase(globalLang[field]);
                } else {
                    label.innerHTML = toFirstUpperCase(field);
                }

                modalBody.appendChild(label);
            }

            modalBody.appendChild(formRow);
        } else {
            formGroup = makeFormGroup(field, specs);
            modalBody.appendChild(formGroup);
        }
    }

    modalBody.appendChild(buttonSubmit);

    if (!isEmpty(formOptions.validation) && formOptions.validation === true) {
        formValidation(modalBody);
    } else {
        onSubmit(modalBody);
    }

    return modalBody;
}

function makeFormGroup(field, specs, id = null) {
    let formGroup = document.createElement("DIV");
    let small = document.createElement("SMALL");
    let inputGroup = document.createElement("DIV");

    let formGroupClass = 'form-group';
    let formGroupId = field;
    let labelText = field;

    if (id !== null) {
        formGroupClass += ' col';
        formGroupId += '-' + id;
        labelText = field + ' ' + id;
    }

    setAttributes(formGroup, {
        id: formGroupId,
        class: formGroupClass
    });
    setAttributes(small, {
        id: formGroupId + '__help',
        class: 'form-text text-muted'
    });

    element = makeElement(specs, formGroupId);

    if (!isEmpty(specs.label) && specs.label === true) {
        let label = document.createElement("LABEL");

        if (!isEmpty(globalLang[labelText]) ){
            label.innerHTML = toFirstUpperCase(globalLang[labelText]);
        } else {
            label.innerHTML = toFirstUpperCase(labelText);
        }

        setAttributes(label, {
            for: element.id,
            class: 'font-weight-bold'
        });

        formGroup.appendChild(label);
    }

    if ((!isEmpty(specs.prepend) || !isEmpty(specs.append)) && (specs.prepend === true || specs.append === true)) {
        setAttributes(inputGroup, {
            class: 'input-group'
        })
    } else {
        formGroup.appendChild(element);
    }
    if (specs.prepend === true) {
        let prepend = document.createElement("DIV");
        let prependText = document.createElement("SPAN");

        setAttributes(prepend, {
            class: 'input-group-prepend'
        });
        setAttributes(prependText, {
            id: element.id + '--prepend',
            class: 'input-group-text'
        });

        if (!isEmpty(globalLang[id])) {
            prependText.innerHTML = globalLang[id];
        } else {
            prependText.innerHTML = id;
        }

        prepend.appendChild(prependText);
        inputGroup.prepend(prepend);
        inputGroup.appendChild(element);

        formGroup.appendChild(inputGroup);
    }
    if (specs.append === true) {
        let append = document.createElement("DIV");
        let appendText = document.createElement("SPAN");

        setAttributes(append, {
            class: 'input-group-append'
        });
        setAttributes(appendText, {
            id: element.id + '--append',
            class: 'input-group-text'
        });

        if (!isEmpty(globalLang[id])) {
            appendText.innerHTML = globalLang[id];
        } else {
            appendText.innerHTML = id;
        }

        append.appendChild(appendText);
        inputGroup.appendChild(element);
        inputGroup.appendChild(append);

        formGroup.appendChild(inputGroup);
    }

    if (!isEmpty(specs.small)) {
        if (!isEmpty(globalLang[specs.small])) {
            small.innerHTML = globalLang[specs.small];
        } else {
            small.innerHTML = specs.small;
        }
        formGroup.appendChild(small);
    }

    return formGroup;
}

function makeElement(specs, id) {
    let element = document.createElement(specs.element.toUpperCase());

    setAttributes(element, {
        id: id + '__' + specs.element,
        name: id,
        ...specs.attributes
    });
    if (element.id !== 'jqxTimePicker') {
        element.classList.add('form-control');
    }

    if (!isEmpty(specs.inner)) {
        if (!isEmpty(globalLang[specs.inner])) {
            element.innerHTML = globalLang[specs.inner];
        } else {
            element.innerHTML = specs.inner;
        }
    }

    if (!isEmpty(specs.eventListeners)) {
        for (const eventListener of Object.values(specs.eventListeners)) {
            element.addEventListener(eventListener.type, eventListener.listener, eventListener.options);
        }
    } else if (!isEmpty(specs.eventListener)) {
        element.addEventListener(eventListener.type, eventListener.listener, eventListener.options);
    }

    return element;
}

function fillInFields(callback, form = null, eventId = null) {
    const data = JSON.stringify({ 
        event_id: eventId
    });

    $.ajax({
        type: 'POST',
        data: { event: data },
        url: base_url + 'settings/json_get_event',
        dataType: 'json',
        error: function (xhr, error, errorThrown) {
            if (xhr.status && xhr.status == 400) {
                alert(xhr.responseText);
            } else {
                //TODO better error handling                
                setTimeout(function () {
                    $.when($('#edit').modal('hide')).done(
                        function(){
                            setTimeout(function () {
                                $('#edit').remove();
                                alert("Wrong event id");
                            }, 300);
                        }
                    );
                }, 600);
            }
        },
        success: function (response) {
            let correct = false;
            if (!isEmpty(response)) {
                let inputs = form.getElementsByClassName('form-control');
                for (let index = 0; index < inputs.length; index++) {
                    const input = inputs[index];
                    const key = input.id.split('__')[0];
                    const supKey = key.split('-')[0];
                    const subKey = key.split('-')[1];

                    if (key in response) {
                        input.value = response[key];
                    } else if (supKey in response) {
                        //custom fill
                        if (subKey === 'hours') {
                            const hours = parseInt(response[supKey].split(':')[0], 10);
                            input.innerHTML = hours;
                            input.value = hours;
                        } else if (subKey === 'minutes') {
                            let minutes = parseInt(response[supKey].split(':')[1], 10);
                            input.innerHTML = minutes;
                            input.value = minutes;
                        } else if (subKey === 'seconds') {
                            //Not used
                            let seconds = parseInt(response[supKey].split(':')[2], 10);
                            input.innerHTML = seconds;
                            input.value = seconds
                        } 
                        //default
                        else {
                            input.value = response[subKey];
                        }
                    }
                }
                correct = true;
            }
            callback(correct);
        }
    });
}

function formValidation(form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        event.stopPropagation();
        if (validateAll(form) === true) {
            formSubmit(form);
        }
    });
}

//TODO make this so every input can be validated when input changes
// function validate(input) {

// }

function validateAll(form) {
    let inputs = form.getElementsByClassName('form-control');
    let fields = getFields(form.id.replace('-form', ''));
    let valid = true;

    for (let index = 0; index < inputs.length; index++) {
        const input = inputs[index];
        for (const [field, specs] of Object.entries(fields)) {
            if ('group' in specs && !isEmpty(specs.group)) {
                for (const [id, groupspecs] of Object.entries(specs.group)) {
                    if (field + '-' + id === input.name && !isEmpty(groupspecs.validationRules)) {
                        const validationRules = groupspecs.validationRules;
                        let validation = checkValidationRules(validationRules, input);

                        //custom validation for duration buttons
                        if (input.id === 'duration-minutes__button') {
                            if (parseInt(input.value) === 0 && parseInt(document.getElementById('duration-hours__button').value) === 0) {
                                const errorMsg = "Duration hours and minutes can't both be 0";
                                validation = { 'errorMsg': errorMsg };
                            }
                        }
                        if (input.id === 'duration-hours__button') {
                            if (parseInt(input.value) === 0 && parseInt(document.getElementById('duration-minutes__button').value) === 0) {
                                const errorMsg = "";
                                validation = { 'errorMsg': errorMsg };
                            }
                        }

                        if (validation !== true) {
                            errorFeedback(input, validation.errorMsg);
                            valid = false;
                        } else {
                            input.classList.remove('is-invalid');
                            input.classList.add('is-valid');
                        }
                    } else {
                        input.classList.add('is-valid');
                    }
                }
            } else {
                if (field === input.name && !isEmpty(specs.validationRules)) {
                    const validationRules = specs.validationRules;
                    let validation = checkValidationRules(validationRules, input);
                    if (validation !== true) {
                        errorFeedback(input, validation.errorMsg);
                        valid = false;
                    } else {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    }
                } else {
                    input.classList.add('is-valid');
                }
            }
        }
    }

    if (valid === true) {
        return true;
    }

    return false;
}

function onSubmit(form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        event.stopPropagation();
        formSubmit(form);
    });
}

function formSubmit(form) {
    const inputs = form.getElementsByClassName('form-control');
    const children = form.children;
    let data = {};

    for (let inputIndex = 0; inputIndex < inputs.length; inputIndex++) {
        const input = inputs[inputIndex];
        for (let index = 0; index < children.length; index++) {
            const child = children[index];
            if (hasId(child) && child.id !== 'form-submit') {
                if(input.name === child.id) {
                    data[child.id] = input.value;
                } else if(input.name.split('-')[0] === child.id) {
                    if( child.id in data ) {
                        data[child.id][input.name] = input.value;
                    } else {
                        data[child.id] = {[input.name]: input.value};
                    }
                }
            }
        }
    }

    if (!isEmpty(form.dataset.event) && parseInt(form.dataset.event) !== 0) {
        data = JSON.stringify({
            ...data,
            event_id: form.dataset.event
        });
    } else {
        data = JSON.stringify({
            ...data
        });
    }

    const callback = (function (response) {
        if (!isEmpty(response)) {
            //TODO better feedback
            // console.log(response);
            form.submit();
            location.reload();
        }
    });

    $.ajax({
        type: 'POST',
        data: { event: data },
        url: base_url + 'settings/json_set_event',
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

function onDelete(deleteBtn) {
    const data = JSON.stringify({
        event_id: deleteBtn.form.dataset.event
    });

    const callback = (function (response) {
        console.log(response);
        if (!isEmpty(response)) {
            //TODO better feedback
            if (response.executed === true) {
                location.reload();
            } else if (!isEmpty(response.error)) {
                alert(response.error);
            } else {
                alert(globalLang.something_went_wrong);
            }
        }
    });

    $.ajax({
        type: 'POST',
        data: { event: data },
        url: base_url + 'settings/json_delete_event',
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

//jQuery
$(function () {
    $('button[data-toggle=modal]').click(function () {
        let name = $(this).data('target').replace('#', '');
        let eventId = this.dataset.eventId;

        makeModal(
            name,
            toFirstUpperCase(globalLang[name]) + ' ' + globalLang.an + ' ' + globalLang.event,
            toFirstUpperCase(globalLang.save_changes),
            toFirstUpperCase(globalLang.cancel),
            toFirstUpperCase(globalLang.delete),
            true,
            eventId
        );
        $('#' + name).modal({ backdrop: 'static', keyboard: false });
        $('#' + name).modal('show');
    });
});