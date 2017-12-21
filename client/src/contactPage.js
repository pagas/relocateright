(function() {


    var REApp = window.REApp || {};

    REApp.propertyPage = (function(REApp) {
        var contactForm = $('#contact-form');

        contactForm.submit(function(event) {
            event.preventDefault();
            var formData = REApp.formUtilities.objectifyForm(contactForm.serializeArray());
            $.post( "contact/sendEnquiry",  formData).done(function( data ) {
                if (data.success === true) {
                    REApp.formUtilities.clearFormField(contactForm);
                    REApp.formUtilities.showSuccessMessage();
                } else {
                    REApp.formUtilities.showFormFieldErrors(contactForm, data.errors);
                }
            });
        });
        return {
        }
    })(REApp);

    var map = $('#map');
    if (map.length > 0) {
        REApp.formUtilities.updateMaps(map.data('lat'), map.data('lng'));
    }

})();



