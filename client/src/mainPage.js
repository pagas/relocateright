$(function() {
    var REApp = window.REApp;

    // Prevent default anchor behaviour and open login modal window
    $('#login-button').click(function(event) {
        event.preventDefault();
        REApp.loginDialog.showDialog();
    });
});