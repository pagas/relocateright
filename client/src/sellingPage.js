var REApp = window.REApp || {};

REApp.propertyPage = (function(REApp) {
    var sellingForm = $('#selling-form');

    sellingForm.submit(function(event) {
        event.preventDefault();
        var formData = REApp.formUtilities.objectifyForm(sellingForm.serializeArray());
        $.post( "selling/sendRequest",  formData).done(function( data ) {
            if (data.success === true) {

                REApp.formUtilities.clearFormField(sellingForm);
                REApp.formUtilities.showSuccessMessage();

            } else {
                REApp.formUtilities.showFormFieldErrors(sellingForm, data.errors);
            }
        });
    });
    return {
    }
})(REApp);