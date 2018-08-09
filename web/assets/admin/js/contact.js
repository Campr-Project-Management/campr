(function($) {
    "use strict";
    var _form = $("#contact-form"),
        _submit = $("#contact_submit"),
        _submitSuccessMessage = $("#alert_success"),
        _submitErrorMessage = $("#alert_danger"),
        _message_error = $("#message_error"),
        _email_error = $("#email_error"),
        _full_name_error = $("#full_name_error"),
        _input_message = $("#contact_message"),
        _input_email = $("#contact_email"),
        _input_full_name = $("#contact_full_name")
    ;

    _form.submit(function( event ) {
        event.preventDefault();
        clearErrorMessages();

        _submit.html('<i class="fas fa-spinner fa-spin"></i> Sending ...');
        _submit.attr('disabled', true);

        $.ajax({
            url: Routing.generate('main_contact_email'),
            method: 'POST',
            data: getFormData(),
        }).done(function(data) {
            if (data.ok) {
                clearForm();
                _submitSuccessMessage.text(data.message);
                _submitSuccessMessage.show(200);
                _submitSuccessMessage.delay(10000).hide(200);
            } else {
                showFieldErrors(data.errors);
                _submitErrorMessage.text(data.message);
                _submitErrorMessage.show();
            }

            _submit.html('Send');
            _submit.removeAttr('disabled');
        });
    });

    function getFormData() {
        let data = {};

        data['message'] = _input_message.val();
        data['email'] = _input_email.val();
        data['full_name'] = _input_full_name.val();

        return data;
    }

    function clearForm() {
        // clears input values
        _input_message.val("");
        _input_email.val("");
        _input_full_name.val("");
        // removes the input focus
        _input_message.parent().removeClass('input--filled');
        _input_email.parent().removeClass('input--filled');
        _input_full_name.parent().removeClass('input--filled');
    }

    function showFieldErrors(errors) {
        if (errors) {
            if (errors.message && errors.message.length) {
                _message_error.show();
                for (let i = 0; i < errors.message.length; i++) {
                    let errorMessageTemplate = '<div id="alert_danger" class="alert alert-danger">'+errors.message[i]+'</div>';
                    _message_error.append(errorMessageTemplate);
                }
            }
            if (errors.email && errors.email.length) {
                _email_error.show();
                for (let i = 0; i < errors.email.length; i++) {
                    let errorMessageTemplate = '<div id="alert_danger" class="alert alert-danger">'+errors.email[i]+'</div>';
                    _email_error.append(errorMessageTemplate);
                }
            }
            if (errors.full_name && errors.full_name.length) {
                _full_name_error.show();
                for (let i = 0; i < errors.full_name.length; i++) {
                    let errorMessageTemplate = '<div id="alert_danger" class="alert alert-danger">'+errors.full_name[i]+'</div>';
                    _full_name_error.append(errorMessageTemplate);
                }
            }
        }
    }

    function clearErrorMessages() {
        _submitSuccessMessage.hide();
        _submitErrorMessage.hide();
        _message_error.hide();
        _email_error.hide();
        _full_name_error.hide();
    }
})(jQuery);
