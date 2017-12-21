var REApp = window.REApp || {};

REApp.propertyPage = (function(REApp) {
    var propertyForm = $('#property-form');
    var imageField = propertyForm.find("input[name='images']");
    var gallery = $('.sp-wrap');
    if (gallery.length > 0) {
        gallery.smoothproducts();
    }
    var dropzone = $('div#mydropzone');
    if (dropzone.length > 0) {

        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("div#mydropzone", {
            url: "/property/save",
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 6,
            maxFiles: 6,
            maxFilesize: 10,
            acceptedFiles: ".png,.jpg",
            addRemoveLinks: true,
            init: function () {
                this.on('sending', function (file, xhr, formData) {
                    var propertyData = REApp.formUtilities.objectifyForm(propertyForm.serializeArray());
                    for (column in propertyData) {
                        formData.append(column, propertyData[column]);
                    }
                });
                this.on("success", function (file, response) {
                    processSubmitResponse(response);
                })
                this.on('removedfile', function(file, response) {
                    removeImage(file.name);
                })
            }
        });

        getImageList().forEach(function(image) {
            var mockFile = { name: image };
            myDropzone.options.addedfile.call(myDropzone, mockFile);
            myDropzone.options.thumbnail.call(myDropzone, mockFile, "server/uploads/"+image);
        })
    }

    propertyForm.submit(function(event) {
        event.preventDefault();
        var formData = REApp.formUtilities.objectifyForm(propertyForm.serializeArray());
        if (myDropzone.getQueuedFiles().length > 0) {
            myDropzone.processQueue();
        } else {
            $.post("property/save", formData).done(function (data) {
                processSubmitResponse(data);
            });
        }
    });

    $('#cancel-button').click(function() {
        var propertyId = propertyForm.find("input[name='id'][type='hidden']").val();
        if (propertyId) {
            window.location.href='/property/view/' + propertyId;
        } else {
            window.location.href='/property/';
        }
    });

    function processSubmitResponse(data) {
        var propertyId = propertyForm.find("input[name='id'][type='hidden']").val();
        if (data.success === true) {
            if (propertyId) {
                window.location.href='/property/view/' + propertyId + '?message=Property successfully updated.';
            } else {
                window.location.href='/property/create' + '?message=Property successfully created.';
            }
        } else {
            REApp.formUtilities.showFormFieldErrors(propertyForm, data.errors);
        }
    }

    var map = $('#map');
    if (map.length > 0) {
        REApp.formUtilities.updateMaps(map.data('lat'), map.data('lng'));
    }

    // images functions
    function removeImage(imageId) {
        var images = getImageList();
        const index = images.indexOf(imageId);
        if ( index >= 0) {
            images.splice(index, 1);
            imageField.val(images.join(','));
        }
    }

    function getImageList() {
        return imageField.val() ? imageField.val().split(',') : [];
    }



    return {
    }
})(REApp);