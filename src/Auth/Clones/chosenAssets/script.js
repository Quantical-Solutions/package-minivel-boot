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