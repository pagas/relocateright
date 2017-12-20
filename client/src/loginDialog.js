var REApp = window.REApp || {};

REApp.loginDialog = (function(REApp) {
    // Get the modal
    var loginModal = $('#login-modal');
    var userRegisterModal = $('#user-register-modal');
    var loginForm = loginModal.find('#login-form');
    var registerForm = userRegisterModal.find('#register-form');
    var errorContainer = loginForm.find('.error-container');

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == loginModal) {
            loginModal.hide();
        }
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
                errorContainer.html('** Incorrect credentials');
            }
        });
    });

    registerForm.submit(function(event) {
        event.preventDefault();
        var formData = REApp.formUtilities.objectifyForm(registerForm.serializeArray());

        $.post( "user/register",  formData).done(function( data ) {
            errorContainer.html('');
            if (data.success === true) {
                window.location.href = '/';
            } else {
                REApp.formUtilities.showFormFieldErrors(registerForm, data.errors);
            }
        });
    })

    loginModal.find('button.cancelbtn, span.close').click(function(event) {
        event.preventDefault();
        loginModal.hide();
    });

    userRegisterModal.find('button.cancelbtn, span.close').click(function(event) {
        event.preventDefault();
        userRegisterModal.hide();
    });


    function showLoginDialog() {
        REApp.formUtilities.clearFormField(loginForm);
        loginModal.show();
    }

    function showRegisterDialog() {
        REApp.formUtilities.clearFormField(registerForm);
        userRegisterModal.show();
    }

    return {
        showLoginDialog: showLoginDialog,
        showRegisterDialog:showRegisterDialog
    }
})(REApp);