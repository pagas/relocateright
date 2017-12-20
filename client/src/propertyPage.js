var REApp = window.REApp || {};

REApp.propertyPage = (function(REApp) {
    var propertyForm = $('#property-form');


    // Dropzone.autoDiscover = false;
    // var myDropzone = new Dropzone("div#mydropzone", {
    //     url: "/property/save",
    //     autoProcessQueue: false,
    //     uploadMultiple: true,
    //     parallelUploads: 6,
    //     maxFiles: 6,
    //     addRemoveLinks: true,
    //     init: function() {
    //         this.on('sending', function(file, xhr, formData){
    //             var propertyData = REApp.formUtilities.objectifyForm(propertyForm.serializeArray());
    //             for(column in propertyData) {
    //                 formData.append(column, propertyData[column]);
    //             }
    //         });
    //         this.on("success", function(file, response) {
    //             processSubmitResponse(response);
    //         })
    //     }
    // });

    propertyForm.submit(function(event) {
        // event.preventDefault();
        // var formData = REApp.formUtilities.objectifyForm(propertyForm.serializeArray());
        // if (myDropzone.getUploadingFiles().length === 0 && myDropzone.files.length === 0) {
        //     $.post("property/save", formData).done(function (data) {
        //         processSubmitResponse(data);
        //     });
        // } else {
        //     myDropzone.processQueue();
        // }

        event.preventDefault();
        var formData = REApp.formUtilities.objectifyForm(propertyForm.serializeArray());
        $.post("property/save", formData).done(function (data) {
            processSubmitResponse(data);
        });
    });

    $('#cancel-button').click(function() {
        var propertyId = propertyForm.find("input[name='id'][type='hidden']").val();
        if (propertyId) {
            window.location.href='/property/view/' + propertyId;
        } else {
            window.location.href='/property/';
        }

    })

    function processSubmitResponse(data) {
        var propertyId = propertyForm.find("input[name='id'][type='hidden']").val();
        if (propertyId) {
            window.location.href='/property/view/' + propertyId;
        }

        if (data.success === true) {
            REApp.formUtilities.clearFormField(propertyForm);
            REApp.formUtilities.showSuccessMessage();
        } else {
            REApp.formUtilities.showFormFieldErrors(propertyForm, data.errors);
        }
    }
    $('#edit-property-button').click(function() {
        window.location.href = 'property/edit/' + $(this).data('propertyId');
    })

    var map = $('#map');
    if (map.length > 0) {
        REApp.formUtilities.updateMaps(map.data('lat'), map.data('lng'));
    }



    return {
    }
})(REApp);