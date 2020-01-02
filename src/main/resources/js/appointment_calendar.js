window.addEventListener('load', function () {
    vanillaCalendar.init({
        disablePastDays: true,
        sundayFirst: false
    });
});

function getFields() {
    return {
        //form options
        form: {
            validation: true
        },
        //field item
        name: {
            label: true,
            prepend: false,
            append: false,
            element: 'input',
            attributes: {
                id: 'name',
                type: 'text',
                required: 'true'
            },
            small: globalLang.required,
            validationRules: {
                required: 'true',
                alpha_numeric_spaces_dash: 'true'
            }
        },
        //field item
        email: {
            label: true,
            prepend: false,
            append: false,
            element: 'input',
            attributes: {
                id: 'email',
                type: 'email',
                required: 'true'
            },
            small: globalLang.required,
            validationRules: {
                required: 'true'
            }
        },
        //field item
        phone: {
            label: true,
            prepend: false,
            append: false,
            element: 'input',
            attributes: {
                id: 'phone',
                type: 'number',
                required: 'true'
            },
            small: globalLang.required,
            validationRules: {
                required: 'true',
                numeric: 'true'
            }
        }
    }
}

function makeForm(name) {
    let fields = getFields();
    if (fields === false) {
        //TODO better error handling
        alert('no correct modal target');
        return false;
    }
    let formOptions = fields.form || '';
    delete fields.form;
    let form = document.createElement("FORM");
    let buttonSubmit = document.createElement("BUTTON");
    let buttonSave = document.createElement("BUTTON");

    setAttributes(form, {
        id: name + '-form',
        name: 'event-data',
        class: 'modal-body'
    });
    setAttributes(buttonSubmit, {
        id: 'form-submit',
        type: 'submit',
        class: 'hidden'
    });
    setAttributes(buttonSave, {
        id: 'submit',
        class: 'btn btn-dark hidden',
        type: 'submit',
        form: name + '-form'
    });

    buttonSave.innerHTML = toFirstUpperCase(globalLang.make + ' ' + globalLang.appointment);

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

                form.appendChild(label);
            }

            form.appendChild(formRow);
        } else {
            formGroup = makeFormGroup(field, specs);
            form.appendChild(formGroup);
        }
    }

    form.addEventListener('input', function () {
        const elements = this.elements;
        let isValid = true;
        for (var i = 0, element; element = elements[i++];) {
            if (element.required === true && element.value === "") {
                isValid = false;
            }
        }
        if( isValid === true ) {
            buttonSave.classList.remove('hidden');
        } else {
            buttonSave.classList.add('hidden');
        }
    });

    form.appendChild(buttonSubmit);
    document.getElementById('carousel-nav-bottom').appendChild(buttonSave);

    if (!isEmpty(formOptions.validation) && formOptions.validation === true) {
        formValidation(form);
    } else {
        onSubmit(form);
    }

    document.getElementById('step-2').appendChild(form);
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

        if (!isEmpty(globalLang[labelText])) {
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
            prependText.innerHTML = toFirstUpperCase(globalLang[id]);
        } else {
            prependText.innerHTML = toFirstUpperCase(id);
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
            appendText.innerHTML = toFirstUpperCase(globalLang[id]);
        } else {
            appendText.innerHTML = toFirstUpperCase(id);
        }

        append.appendChild(appendText);
        inputGroup.appendChild(element);
        inputGroup.appendChild(append);

        formGroup.appendChild(inputGroup);
    }

    if (!isEmpty(specs.small)) {
        if (!isEmpty(globalLang[specs.small])) {
            small.innerHTML = toFirstUpperCase(globalLang[specs.small]);
        } else {
            small.innerHTML = toFirstUpperCase(specs.small);
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

    element.classList.add('form-control');

    if (!isEmpty(specs.inner)) {
        if (!isEmpty(globalLang[specs.inner])) {
            element.innerHTML = toFirstUpperCase(globalLang[specs.inner]);
        } else {
            element.innerHTML = toFirstUpperCase(specs.inner);
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

//TODO make this so every input can be validated when input changes
// function validate(input) {

// }

function validateAll(form) {
    let inputs = form.getElementsByClassName('form-control');
    let fields = getFields();
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

function formSubmit(form) {
    document.getElementById('submit').remove();
    const inputs = form.getElementsByClassName('form-control');
    const children = form.children;
    let inputData = {};

    for (let inputIndex = 0; inputIndex < inputs.length; inputIndex++) {
        const input = inputs[inputIndex];
        for (let index = 0; index < children.length; index++) {
            const child = children[index];
            if (hasId(child) && child.id !== 'form-submit') {
                if (input.name === child.id) {
                    inputData[child.id] = input.value;
                } else if (input.name.split('-')[0] === child.id) {
                    if (child.id in inputData) {
                        inputData[child.id][input.name] = input.value;
                    } else {
                        inputData[child.id] = { [input.name]: input.value };
                    }
                }
            }
        }
    }

    attendeeData = JSON.stringify({
        ...inputData
    });
    appointmentData = JSON.stringify({
        date: vanillaCalendar.datePicked,
        start_time: vanillaCalendar.startTime,
        end_time: vanillaCalendar.endTime
    });

    const callback = (function (response) {
        if (!isEmpty(response)) {
            if (response.executed === true) {
                var api = 'Basic ' + api_key;
                //Posting value to relation table bizzmail
                fetch(b_url, {
                    method: 'POST',
                    body: JSON.stringify({
                        email: attendeeData.email,
                        firstname: attendeeData.name,
                        phonenumber_mobile: attendeeData.phone
                    }),
                    headers: {
                        'Authorization': api,
                        'Content-Type': 'application/json'
                    }
                })
                .then(function (res) {
                    return res.json();
                })
                .then(function (data) {
                    guestID = data.id;

                    // Adding the relation in group
                    fetch(`${global.b_url}group/add/${groupId}`, {
                        method: 'POST',
                        body: JSON.stringify({
                            relation: guestID
                        }),
                        headers: {
                            'Authorization': api,
                            'Content-Type': 'application/json'
                        }
                    }).then(function (res) {
                        return res.json();
                    }).catch(function (error) {
                        console.log(error);
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });

                //redirect attendee to user redirect url (from general settings)
                window.location.replace(vanillaCalendar.settings.redirect_url);
            } else {
                alert(response.error)
            }
        }
    });

    $.ajax({
        type: 'POST',
        data: { attendee: attendeeData, appointment: appointmentData },
        url: base_url + 'appointment/json_make_appointment/' + cs_username + '/' + eventTitle,
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

function formValidation(form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        event.stopPropagation();
        if (validateAll(form) === true) {
            formSubmit(form);
        }
    });
}    

function onSubmit(form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        event.stopPropagation();
        formSubmit(form);
    });
}

//jQuery
$(function () {
    $('[data-toggle="popover"]').popover()
    $('.popover-dismiss').popover({
        trigger: 'focus'
    })
})