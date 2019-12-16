function isEmpty(string) {
    return (!string || 0 === string.length);
}

function isNumeric(number) {
    return !isNaN(number)
}

function hasId(element) {
    return typeof element.id != 'undefined' && !isEmpty(element.id);
}

function toFirstUpperCase(string) {
    return string.replace(/^./, string[0].toUpperCase());
}

function leftPad(number, targetLength) {
    var output = number + '';
    while (output.length < targetLength) {
        output = '0' + output;
    }
    return output;
}

function setAttributes(element, attributes) {
    for (let key in attributes) {
        element.setAttribute(`${key.replace('_', '-')}`, attributes[key])
    }
}

function modalRemoveEventListener(elements, modalName) {
    elements.forEach(element => {
        element.addEventListener('click', function () {
            $('#' + modalName).modal('hide');
            setTimeout(function () {
                $('#' + modalName).remove();
            }, 300);
        });
    });
}

function insertAfter(referenceNode, newNode) {
    if (newNode.id === referenceNode.parentNode.lastChild.id ) {
        referenceNode.parentNode.lastChild.remove();
    }
    referenceNode.parentNode.appendChild(newNode);
}

function errorFeedback(element, errorMsg) {
    let error = document.createElement('DIV');

    setAttributes(error, {
        id: element.name + '__feedback',
        class: 'invalid-feedback'
    });
    element.classList.remove('is-valid');
    element.classList.add('is-invalid');

    error.innerHTML = errorMsg;

    insertAfter(element, error);
}

function checkValidationRules(rules, element) {
    let errorMsg = '';
    let isCorrect = true;
    for (const [rule, parameter] of Object.entries(rules)) {
        switch (rule) {
            case 'required':
                if (isEmpty(element.value)) {
                    errorMsg = 'This ' + element.name + ' field is required <br>';
                    isCorrect = false;
                    return errorMsg;
                }
            case 'matches'://TODO make this working
                break;
            case 'differs'://TODO make this working
                break;
            case 'is_unique'://TODO make this working
                break;
            case 'min_length':
                if (element.value.length < parameter) {
                    errorMsg += 'This ' + element.name + ' field needs to be longer than ' + parameter + ' characters <br>';
                    isCorrect = false;
                }
                break;
            case 'max_length':
                if (element.value.length > parameter) {
                    errorMsg += 'This ' + element.name + ' field needs to be shorter than ' + parameter + ' characters <br>';
                    isCorrect = false;
                }
                break;
            case 'exact_length':
                if (element.value.length !== parameter) {
                    errorMsg += 'This ' + element.name + ' field needs to be exact ' + parameter + ' characters long <br>';
                    isCorrect = false;
                }
                break;
            case 'greater_than':
                if (element.value < parameter) {
                    errorMsg += 'This ' + element.name + ' field needs to be greater as ' + parameter + ' <br>';
                    isCorrect = false;
                }
                break;
            case 'greater_than_equal_to':
                if (element.value <= parameter) {
                    errorMsg += 'This ' + element.name + ' field needs to be greater or the same as ' + parameter + ' <br>';
                    isCorrect = false;
                }
                break;
            case 'less_than':
                if (element.value > parameter) {
                    errorMsg += 'This ' + element.name + ' field needs to be smaller or as ' + parameter + ' <br>';
                    isCorrect = false;
                }
                break;
            case 'less_than_equal_to':
                if (element.value >= parameter) {
                    errorMsg += 'This ' + element.name + ' field needs to be smaller or the same as ' + parameter + ' <br>';
                    isCorrect = false;
                }
                break;
            case 'in_list'://TODO check if it works
                if( !element.value in parameter ){
                    errorMsg += 'This ' + element.name + ' field needs to consist of one of the following: ' + parameter + ' <br>';
                    isCorrect = false;
                }
                break;
            case 'alpha':
                if (!element.value.match(/^[a-zA-Z]+$/)) {
                    errorMsg += 'This ' + element.name + ' field can only consist of alpha characters (a-zA-Z) <br>';
                    isCorrect = false;
                }
                break;
            case 'alpha_numeric':
                if (!element.value.match(/^[\w]+$/)) {
                    errorMsg += 'This ' + element.name + ' field can only consist of alpha and numeric characters (a-zA-Z0-9) <br>';
                    isCorrect = false;
                }
                break;
            case 'alpha_numeric_spaces':
                if (!element.value.match(/^[\w\s]+$/)) {
                    errorMsg += 'This ' + element.name + ' field can only consist of alpha, numeric and spaces characters (a-zA-Z0-9 ) <br>';
                    isCorrect = false;
                }
                break;
            case 'alpha_dash':
                if (!element.value.match(/^[\w\-\_]+$/)) {
                    errorMsg += 'This ' + element.name + ' field can only consist of alpha and dash characters (a-zA-Z-_) <br>';
                    isCorrect = false;
                }
                break;
            case 'alpha_numeric_spaces_dash':
                if (!element.value.match(/^[\w\-\_\s]+$/)) {
                    errorMsg += 'This ' + element.name + ' field can only consist of alpha, numeric, spaces and dash characters (a-zA-Z0-9 -_) <br>';
                    isCorrect = false;
                }
                break;
            case 'numeric':
                if (!element.value.match(/^[0-9]+$/)) {
                    errorMsg += 'This ' + element.name + ' field can only consist of numbers (0-9) <br>';
                    isCorrect = false;
                }
                break;
            case 'integer':
                if (Number.isInteger(element.value) === false) {
                    errorMsg += 'This ' + element.name + ' field can only consist of integers (+- 0-9) <br>';
                    isCorrect = false;
                }
                break;
            case 'decimal':
                if (!element.value.match(/^[-+]?[0-9]+\.[0-9]+$/)) {
                    errorMsg += 'This ' + element.name + ' field can only consist of decimals (+- 0.0-9.9) <br>';
                    isCorrect = false;
                }
                break;
            case 'is_natural'://TODO make this working
                break;
            case 'is_natural_no_zero'://TODO make this working
                break;
            case 'valid_url'://TODO make this working
                break;
            case 'valid_email'://TODO check if it works
                if (validateEmail(element.value) === false) {
                    errorMsg += 'This ' + element.name + ' field is not a valid email address <br>';
                    isCorrect = false;
                }
                break;
            case 'valid_ip'://TODO make this working
                break;
            case 'valid_base64'://TODO make this working
                break;
        }
    }

    if (isCorrect === true) {
        return true;
    } else {
        return { 'errorMsg': errorMsg };
    }
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}