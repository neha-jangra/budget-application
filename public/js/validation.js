// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
    'use strict';
    window.addEventListener('load', function() {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName('needs-validation');
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
          $(".submitBtn").attr("disabled", false);
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
          window.setTimeout(function () {
              var errors = $('.invalid-feedback:visible:first').prev();
              if (errors.length) {
                
                event.preventDefault();
                event.stopPropagation();
                $('html, body').animate({ scrollTop: errors.offset().top - 150 }, 500);
              }else{
                $('#loading').show();
              }
          }, 0);
        }, false);
      });
    }, false);
  })();

 
  window.validateEmail = function(current) {
    $.ajax({
        method: 'GET',
        url: '/validate-email/'+current.value,
        beforeSend:function(){
            //$('#loading').show();
        },
        success: function(response) {
            $(current).next().css('display', 'none');
            $(current).next().text('Please add a correct email.');
        },
        error: function (data) {
            $(current).next().css('display', 'block');
            $(current).next().text('Email already exists.');
        }
    });
}