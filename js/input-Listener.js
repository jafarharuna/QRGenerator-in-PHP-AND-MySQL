document.addEventListener('DOMContentLoaded', function() {
    var qrForm = document.getElementById('qrForm');
    var submitButton = document.querySelector('input[type="submit"]');
    var inputFields = document.querySelectorAll('#dataInput input');
    var selectFields = document.querySelectorAll('#dataInput select');
    var colorInputFields = document.querySelectorAll('input[type="color"]');
    var logoInputField = document.getElementById('logo');
    var logoSizeInputField = document.getElementById('logoSize');
    var logoMarginInputField = document.getElementById('logoMargin');

    var sizeInputField = document.getElementById('size');
    var ecLevelInputField = document.getElementById('ecLevel');
    var formatInputField = document.getElementById('format');
    var dotsStyleInputField = document.getElementById('dotsStyle');
    var cornersSquareStyleInputField = document.getElementById('cornersSquareStyle');
    var cornersDotStyleInputField = document.getElementById('cornersDotStyle');

    var changesMade = false;

    inputFields.forEach(function(inputField) {
        inputField.addEventListener('input', handleInputChange);
    });

    selectFields.forEach(function(selectField) {
        selectField.addEventListener('change', handleInputChange);
    });

    colorInputFields.forEach(function(colorInputField) {
        colorInputField.addEventListener('input', handleInputChange);
    });

    logoMarginInputField.addEventListener('input', handleInputChange);
    logoSizeInputField.addEventListener('input', handleInputChange);
    logoInputField.addEventListener('change', handleInputChange);
    sizeInputField.addEventListener('change', handleInputChange);
    ecLevelInputField.addEventListener('change', handleInputChange);
    formatInputField.addEventListener('change', handleInputChange);
    dotsStyleInputField.addEventListener('change', handleInputChange);
    cornersSquareStyleInputField.addEventListener('change', handleInputChange);
    cornersDotStyleInputField.addEventListener('change', handleInputChange);

    function handleInputChange() {
        changesMade = true;
        submitButton.disabled = false;
    }

    qrForm.addEventListener('submit', function(event) {
        event.preventDefault();

        if (changesMade) {
            handleFormSubmission();
        } else {
            submitButton.disabled = true;
        }
    });

    function handleFormSubmission() {
        changesMade = false;
        submitButton.disabled = true;
    }
});

