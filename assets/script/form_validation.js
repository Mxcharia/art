const form = document.querySelector('form')
const email = document.getElementById('email')
const password = document.getElementById('password')

// Regular expression for email validation
const emailRegExp =
  /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
// Function to validate email
function validateEmail() {
  const isValid = email.value.length === 0 || emailRegExp.test(email.value)
  const error = email.nextElementSibling
  if (isValid) {
    email.className = 'form-control'
    document.getElementById('email-error').textContent = ''
    error.className = 'error'
  } else {
    email.className = 'form-control'
    document.getElementById('email-error').textContent = 'Invalid email address'
  }
  return isValid
}

email.addEventListener('input', function() {
  const isValid = email.value.length === 0 || emailRegExp.test(email.value)
  const error = email.nextElementSibling
  if (isValid) {
    email.className = 'form-control'
    document.getElementById('email-error').textContent = ''
    error.className = 'error'
  } else {
    email.className = 'form-control'
    document.getElementById('email-error').textContent = 'Invalid email address'
  }
})
// Function to validate password
function validatePassword() {
  const confirm_password = document.getElementById('cpassword')
  const confirm_password_error = confirm_password.nextElementSibling
  const isPasswordValid = confirm_password.value === password.value
  if (isPasswordValid) {
    confirm_password_error.textContent = ''
    confirm_password_error.className = 'error'
  } else {
    confirm_password_error.textContent = "Passwords don't match"
    confirm_password_error.className = 'error active'
  }
  return isPasswordValid
}

// check the if it's in the loginpage route
function isLoginPage() {
  return window.location.pathname.includes('login.php')
}

// Function to validate all inputs
function validateInputs() {
  let isValid = true
  const inputs = form.querySelectorAll('input')
  inputs.forEach((input) => {
    if (input.value.trim() === '') {
      input.nextElementSibling.textContent = 'Required'
      input.nextElementSibling.className = 'error active'
      isValid = false
    } else {
      input.nextElementSibling.textContent = ''
      input.nextElementSibling.className = 'error'
    }
  })
  return isValid
}

// Add event listener for form submission
form.addEventListener('submit', (event) => {
  // Perform validation
  const isEmailValid = validateEmail()
  const isPasswordValid = validatePassword()
  const areInputsValid = validateInputs()
  // If any validation fails, prevent default form submission
  if (!isEmailValid || !isPasswordValid || !areInputsValid) {
    event.preventDefault() // Prevent default form submission
  }
})

const loginButton = document.querySelector('.submit-btn[name="login"]')

loginButton.addEventListener('click', (event) => {
  // Perform validation
  const isEmailValid = validateEmail()
  const areInputsValid = validateInputs()

  // If any validation fails, prevent default form submission
  if (!isEmailValid || !areInputsValid) {
    event.preventDefault() // Prevent default form submission
  }
})

const uploadartButton = document.querySelector('.submit-btn[name="submit-art"]')
uploadartButton.addEventListener('click', (event) => {
  // Perform validation
  const areInputsValid = validateInputs()

  // If any validation fails, prevent default form submission
  if (!areInputsValid) {
    event.preventDefault() // Prevent default form submission
  }
})
