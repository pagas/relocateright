var REApp = REApp || {};
REApp.formUtilities = (function() {

    // serialize data function
    function objectifyForm(formArray) {
        var returnArray = {};
        for (var i = 0; i < formArray.length; i++){
            returnArray[formArray[i]['name']] = formArray[i]['value'];
        }
        return returnArray;
    }

    function clearFormField(form) {
        form.find("input[type=text], input[type=email], textarea, input[type=password]").val('');
        form.find("select").val('');
    }

    function showFormFieldErrors(form, erros) {
        form.find('.error').html('').hide();
        for (error in erros) {
            var inputField = form.find("[name=" + error + "]");
            inputField.next('.error').html(erros[error]).show();
            inputField.parent('.form-group').addClass('has-error');
        }
    }

    function showSuccessMessage() {
        $("#sendmessage").show().delay(2000).fadeOut();
    }

    function updateMaps(lat, lng) {
        var map = new GMaps({
            div: '#map',
            lat: lat,
            lng: lng
        });
        map.addMarker({
            lat: lat,
            lng: lng
        });
    }

    return {
        objectifyForm: objectifyForm,
        clearFormField: clearFormField,
        showFormFieldErrors: showFormFieldErrors,
        showSuccessMessage:showSuccessMessage,
        updateMaps: updateMaps
    }

}());