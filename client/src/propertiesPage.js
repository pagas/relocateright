$(function() {
    var REApp = window.REApp || {};
    var searchForm = $("form[name='searchForm']");
    var resultSection = $('.result');
    var rentalPropertyField = searchForm.find('#rentalProperty');
    var propertyListMenu = $('#property-list-menu');
    var showMapButton = $('#show-map-button');
    var showListButton = $('#show-list-button');
    var propertyListItemTemplate = $('<div>').load('server/views/property/property-list-item.html', function(r) {});
    showOneOfThePriceRanges(rentalPropertyField);
    var propertiesMap = $('#google-map').hide();

    $("#sendmessage.fade-out").css({'display':'block'}).fadeOut(2000);

    searchForm.submit(function(event) {
        // stop browser default behaviour - refreshing the page
        event.preventDefault();
        var searchData = REApp.formUtilities.objectifyForm(searchForm.serializeArray());
        $.get( "property/find",  searchData).done(function( data ) {
            updatePropertyList(data.properties);

            if (data.properties.length > 0) {
                propertyListMenu.show();
            } else {
                propertyListMenu.hide();
            }
        });
    });

    rentalPropertyField.change(function() {
        showOneOfThePriceRanges($(this));
    });

    function showOneOfThePriceRanges(rentalProperty){
        if (rentalProperty.val() === 'rent') {
            searchForm.find('#buyPriceRange').hide();
            searchForm.find('#rentPriceRange').show();
        } else {
            searchForm.find('#buyPriceRange').show();
            searchForm.find('#rentPriceRange').hide();
        }
    }

    /**
     * Refill the property list with provided properties
     * @param properties
     */
    function updatePropertyList(properties){
        var list = $("<div class='property-list'>");
        properties.forEach(function(property) {
            addPropertyToListElement(list, property)
        });
        resultSection.html(list);
    }

    function addPropertyToListElement(elem, property) {
        propertyListItemTemplate.find("span[data-content='title']").html(property.title);
        propertyListItemTemplate.find("span[data-content='price']").html(property.price);
        propertyListItemTemplate.find("span[data-content='status']").html(property.status);
        propertyListItemTemplate.find("img").attr('src',"server/uploads/" + property.image);
        propertyListItemTemplate.find("a").attr('href',"/property/view/" + property.id);
        elem.append(propertyListItemTemplate.clone().children());
    }

    showMapButton.click(function() {
        propertiesMap.show();
        resultSection.hide();
        REApp.formUtilities.updateMaps();
    });

    showListButton.click(function() {
        propertiesMap.hide();
        resultSection.show();
    });
});