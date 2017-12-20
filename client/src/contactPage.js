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


    //Google Map
    var get_latitude = $('#google-map').data('latitude');
    var get_longitude = $('#google-map').data('longitude');

    function initialize_google_map() {
        var myLatlng = new google.maps.LatLng(get_latitude, get_longitude);
        var mapOptions = {
            zoom: 14,
            scrollwheel: false,
            center: myLatlng
        };
        var map = new google.maps.Map(document.getElementById('google-map'), mapOptions);
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize_google_map);

})();



