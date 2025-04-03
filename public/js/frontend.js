// Toggle password visibility
function togglePasswordVisibility() {
  var passwordInput = document.getElementById('password')
  var eyeIcon = document.getElementById('eyeIcon')

  if (passwordInput.type === 'password') {
    passwordInput.type = 'text'
    eyeIcon.src = 'images/icons/Eye.svg'
  } else {
    passwordInput.type = 'password'
    eyeIcon.src = 'images/icons/eye-slash.svg'
  }
}

// Confirm Toggle password visibility
function confirmTogglePasswordVisibility() {
  var passwordInput = document.getElementById('confirm_password')
  var eyeIcon = document.getElementById('confirm_pass_eyeIcon')

  if (passwordInput.type === 'password') {
    passwordInput.type = 'text'
    eyeIcon.src = 'images/icons/Eye.svg'
  } else {
    passwordInput.type = 'password'
    eyeIcon.src = 'images/icons/eye-slash.svg'
  }
}


function togglePasswordVisibilitySetting(fieldId) {
    var passwordInput = document.getElementById(fieldId);
    var eyeIcon = document.getElementById(fieldId + '_eyeIcon'); // Assuming your eye icon IDs follow this pattern

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.src = 'images/icons/Eye.svg';
    } else {
        passwordInput.type = 'password';
        eyeIcon.src = 'images/icons/eye-slash.svg';
    }
}



$('#prevent_accordion_toggle_past-present .accordion-button').click(function (
  event,
) {
  event.preventDefault()
})
$('#prevent_accordion_toggle_rev .accordion-button').click(function (event) {
  event.preventDefault()
})
$('#prevent_accordion_toggle_future .accordion-button').click(function (event) {
  event.preventDefault()
})
