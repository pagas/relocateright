var REApp = window.REApp || {};

REApp.loginDialog = (function(REApp) {
    // Get the modal
    var loginModal = $('#login-modal');
    var loginForm = loginModal.find('#login-form');
    var registerForm = loginModal.find('#register-form');
    var errorContainer = loginForm.find('.error-container');

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == loginModal) {
            loginModal.hide();
        }
    }

    function showDialog() {
        loginModal.css('display', 'block');
        showLoginModal();
    }

    loginForm.submit(function(event) {
        event.preventDefault();
        var formData = REApp.formUtilities.objectifyForm(loginForm.serializeArray());
        $.post( "admin/login",  formData).done(function( data ) {
            errorContainer.html('');
            if (data.success === true) {
                window.location.href = '/';
            } else {
                // find appropriate fields on the form and show errors below the input box
                errorContainer.html('Error: Incorrect credentials');
            }
        });
    });

    registerForm.submit(function(event) {
        event.preventDefault();
        var formData = REApp.formUtilities.objectifyForm(registerForm.serializeArray());

        $.post( "user/register",  formData).done(function( data ) {
            errorContainer.html('');
            if (data.success === true) {
                showLoginModal();
            } else {
                REApp.formUtilities.showFormFieldErrors(registerForm, data.errors);
            }
        });
    })

    loginModal.find('button.cancelbtn, span.close').click(function(event) {
        event.preventDefault();
        loginModal.hide();
    });

    loginModal.find('.singup').click(function(event) {
        event.preventDefault();
        loginForm.hide();
        registerForm.show();
        REApp.formUtilities.clearFormField(registerForm);
    });

    loginModal.find('.back-to-login-page').click(function(event) {
        event.preventDefault();
        showLoginModal();
    })

    function showLoginModal() {
        registerForm.hide()
        loginForm.show();
        REApp.formUtilities.clearFormField(loginForm);
    }

    return {
        showDialog: showDialog
    }
})(REApp);