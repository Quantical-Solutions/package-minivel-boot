validateFormInputs();

if (document.querySelector('#translate-script')) {
    document.head.removeChild(document.querySelector('#translate-script'));
}

function translate(str) {

    if (trans[str]) {
        return trans[str];
    }
    return str;
}

function displayChosenImagePreview(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            input.parentElement.style.backgroundImage = 'url("' + e.target.result + '")';
        }

        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

function resetChosenImagePreview(elmt) {

    elmt.nextElementSibling.value = '';
    elmt.parentElement.style.backgroundImage = 'url("/chosen/default-avatar.png")';
}

function displayPassword(elmt) {

    let input = elmt.previousElementSibling;
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}

function verifyEmail(email) {

    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function verifyPassword(pwd, pattern) {

    const re = new RegExp(pattern, 'g');
    return re.test(String(pwd));
}

function validateFormInputs() {

    let chosen = document.querySelector('.chosen-container-body');
    let form = document.querySelector('#loginForm') || document.querySelector('#forgotForm') || document.querySelector('#registerForm') || document.querySelector('#resetForm');

    if (chosen && form) {

        let prefix = 'login_';
        prefix = (document.querySelector('#forgotForm')) ? 'forgot_' : prefix;
        prefix = (document.querySelector('#registerForm')) ? 'register_' : prefix;
        prefix = (document.querySelector('#resetForm')) ? 'reset_' : prefix;
        let inputs = document.querySelectorAll('input:not([type="hidden"]):not([type="checkbox"])');
        let toChecks = [];

        for (let i = 0; i < inputs.length; i++) {

            let input = inputs[i];
            if (input.id.startsWith(prefix) && input.name !== prefix + 'remember') {

                toChecks.push(input);
            }
        }

        evaluateForm(form, toChecks, prefix);
    }
}

function evaluateForm(form, inputs, prefix) {

    let submit = form.querySelector('button[type="submit"]'),
        check = [];

    for (let i = 0; i < inputs.length; i++) {
        check.push(false);
    }

    for (let i = 0; i < inputs.length; i++) {

        let input = inputs[i];
        input.oninput = function (e) {

            let target = e.currentTarget,
                previous = input.previousElementSibling,
                type = target.type,
                value = target.value,
                verif = validateEntry(target),
                error = document.createElement('span');

            let name = target.id.replace(prefix, ''),
                first = name.slice(0, 1).toUpperCase(),
                rest = name.slice(1),
                full = first + rest;

            error.setAttribute('class', 'chosen-not-valid');
            error.innerHTML = '<i class="quantic-icon-warning"></i>' + translate('Wrong format') + ' "' + translate(full) + '"';

            check[i] = verif;
            if (verif === false) {
                if (!previous.querySelector('.chosen-not-valid')) {
                    previous.appendChild(error);
                }
            } else {
                if (previous.querySelector('.chosen-not-valid')) {
                    previous.removeChild(previous.querySelector('.chosen-not-valid'));
                }
            }
            activeSubmit(check, submit);
        }
    }
}

function validateEntry(input) {

    if (input.type === 'email') {
        return verifyEmail(input.value);
    } else if (input.id.includes('password')) {
        return verifyPassword(input.value, input.pattern);
    }
    return true;
}

function activeSubmit(check, submit) {

    let verif = 0;
    for (let i = 0; i < check.length; i++) {

        if (check[i] === true) {
            verif++;
        }
    }

    if (verif === check.length) {
        submit.disabled = false;
    } else {
        submit.disabled = true;
    }
}