<?php
session_start();
// if (isset($_SESSION['user_id'])) {
//     header('Location: home.php');
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Portal | Login</title>
    <!-- Include jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Toastr CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <h1 class="heading">tailwebs.</h1>
    <div class="login-container">
        <form >
            <label for="username">Username:</label>
            <div class="inputWithIcon">
                <input type="text" placeholder="Username" id="username" name="username" required>
                <i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i>
            </div>

            <label for="password">Password:</label>
            <div class="inputWithIcon">
                <input type="password" placeholder="Password" id="password" name="password" required>
                <i class="fa fa-lock fa-lg fa-fw" aria-hidden="true"></i>
                <i class="fa fa-eye fa-lg fa-fw toggle-password" aria-hidden="true" onclick="togglePassword()"></i>
            </div>
            <a href="#" class="forgot-pass">Forgot Password?</a>
            <input type="button" class="login" value="Login">
        </form>
        <button onclick="window.location.href='register.php'">Register</button>
    </div>
</body>

<script>
var flashMessage = "<?php echo isset($_SESSION['flash_message']) ? addslashes($_SESSION['flash_message']) : ''; ?>";

window.onload = function() {
    if (localStorage.getItem('registerSuccess') === 'true') {
        toastr.success('User Registerd successfully');
        localStorage.removeItem('registerSuccess');
    }

    // Check for logout flash message
    if (flashMessage) {
        toastr.success(flashMessage);
        flashMessage = '';
    }
};

$(".login").click(function() {
    // Code to execute on click
    var name = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    // console.log(name);
    // console.log(password);
    if(name == "" || password== "" )
    {
        flashMessage = '';
        toastr.error('Please fill the login deatils.');
    }
    
    var url = "process_login.php";
    // console.log(url);
    if(name != "" && password != "")
    {
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                username: name,
                password: password
            },
            success: function(data) {

                if (data === "No user found with that username.") {
                    toastr.error(data);
                } 
                else if(data === "Invalid password."){
                    toastr.error(data);
                }
                else {
                    // console.log(data);
                    localStorage.setItem('loginSuccess', 'true');
                    window.location.href = 'home.php';
                    // location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error("Login failed:", error);
                toastr.error('Login failed');
            }

        });
    }
});

function togglePassword() {
    var passwordField = document.getElementById("password");
    var eyeIcon = document.querySelector(".toggle-password");
    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }
}
</script>
</html>
