$(document).ready(function () {
    $('#frm-register').on('submit', function (e) {
        e.preventDefault();
        let frm = $(this)[0];
        let emailMsg = $(document).find('.email-msg');
        let passMsg = $(document).find('.pass-msg');
        let confMsg = $(document).find('.conf-msg');
        emailMsg.text('');
        passMsg.text('');
        confMsg.text('');
        emailMsg.hide();
        passMsg.hide();
        confMsg.hide();

        let email = $(document).find('input[name=email]');
        let pass = $(document).find('input[name=password]');
        let conf = $(document).find('input[name=password_confirmation]');

        if (pass.val().trim().length < 8){
            passMsg.text("Password must contain at least 8 characters");
            passMsg.show();
        }else if (pass.val() !== conf.val()) {
            confMsg.text("Password confirmation don't match");
            confMsg.show();
        } else {

            $.ajax({
                type: 'post',
                url: '/checkAccount',
                dataType: 'json',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'email': email.val().trim()
                },
                success: function (response) {
                    if (response.result === 1){
                        emailMsg.text("This email already match our records");
                        emailMsg.show();
                    }else{
                        frm.submit();
                    }
                },
                error: function (a, b, c) {
                    console.log('Error');
                }
            });
        }
    });
});