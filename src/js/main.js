
(function (exp, $) {
     function sentMessage($buttonSend) {
         var messageForm = document.getElementById("message-form");
         var successBorderColor = "#ced4da";
         document.getElementById("name").style.borderColor = successBorderColor;
         document.getElementById("email").style.borderColor = successBorderColor;
         document.getElementById("message").style.borderColor = successBorderColor;
         document.getElementById("invalid_email").style.display = "none";
         document.getElementById("email-text").style.display = "block";
         document.getElementById("empty-field").style.display = "none";
         if (messageForm.checkValidity() && messageValidate() && nameValidate()) {
             $('#message-form').addClass('was-validated');
             $('.message-form-btn-send-message').empty();
             $('<span>Sending...<span><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').appendTo($('.message-form-btn-send-message'));

             var form_data = {};
             $.each($('#message-form').serializeArray(), function () {
                 form_data[this.name] = this.value;
             });

             var formData = {
                 mailType: 'contact_form_message',
                 form_data: form_data
             };

             $.ajax({
                 type: 'POST',
                 url: cnArgs.ajaxurl + '?action=send_mail',
                 data: JSON.stringify(formData),
                 contentType: "application/json; charset=utf-8",
                 dataType: "json",
                 success: function success(data) {
                     if (data.result) {
                         $buttonSend.addClass('is-valid');
                         $('.message-form-btn-send-message').off('click').empty();
                         $('<span>Send<span> <span class="px-1 text-success"> <i class="fas fa-check"></i> </span>').appendTo($('.message-form-btn-send-message'));
                         $(location).attr('href', window.location.href.match(/^.*\//) + '/message-successfully-sent');
                     } else {
                         $('#message-form').removeClass('was-validated');
                         $buttonSend.addClass('is-invalid');
                         $('.message-form-btn-send-message').empty();
                         $('<span>Send<span> <span class="px-1"> <i class="fab fa-telegram-plane"></i> </span>').appendTo($('.message-form-btn-send-message'));
                     }
                 },
                 error: function error() {
                     $('#message-form').removeClass('was-validated');
                     $buttonSend.addClass('is-invalid');
                     $('.message-form-btn-send-message').empty();
                     $('Send <span class="px-1"> <i class="fab fa-telegram-plane"></i> </span>').appendTo($('.message-form-btn-send-message'));
                 }
             });
         } else {
             var email = $('#email').val();
             if (IsEmail(email) == false) {
                 document.getElementById("email").style.borderColor = "rgb(220, 53, 69)";
                 document.getElementById("invalid_email").style.display = "block";
                 document.getElementById("email-text").style.display = "none";
             }
             if (!nameValidate()) {
                 document.getElementById("name").style.borderColor = "rgb(220, 53, 69)";
             }
             if (!messageValidate()) {
                 document.getElementById("message").style.borderColor = "rgb(220, 53, 69)";
             }
             document.getElementById("empty-field").innerHTML = "All fields are required";
             document.getElementById("empty-field").style.display = "block";
         }

         function IsEmail(email) {
             var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
             return regex.test(email);
         }

         function messageValidate() {
             var message = $('#message').val();
             return message.trim().replace(/\s+/, '').length;
         }

         function nameValidate() {
             var name = $('#name').val();
             return name.trim().replace(/\s+/, '').length;
         }
}


    $(document).ready(function ($) {
        $('.message-form-btn-send-message').on('click', function (event) {
            sentMessage($(event.target));
        });
        $('form > input').keyup(function() {

            var empty = false;
            $('form > input').each(function() {
                if ($(this).val() == '') {
                    empty = true;
                }
            });

            if (empty) {
                $('#search').attr('disabled', 'disabled');
            } else {
                $('#search').removeAttr('disabled');
            }
        });
        $('.carousel').carousel();
    });


})(window, jQuery);
