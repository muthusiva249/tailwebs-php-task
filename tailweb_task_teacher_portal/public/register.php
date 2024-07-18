<?php
// session_start();
// if (isset($_SESSION['user_id'])) {
//     header('Location: home.php');
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Teacher Portal | Register</title>
    <!-- Include jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Toastr CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- css  -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <h1 class="heading">tailwebs.</h1>
    <div class="login-container">
        <form>
            <label for="username">Username:</label>
            <div class="inputWithIcon">
                <input type="text" placeholder="Username" id="username" name="username" required>
                <i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i>
            </div>
            
            <label for="password">Password:</label>
            <div class="inputWithIcon">
                <input type="password" placeholder="Password" id="password" name="password" required>
                <i class="fa fa-lock fa-lg fa-fw" aria-hidden="true"></i>
            </div>
            <input type="button" class="register" value="Register">
        </form>
        <button onclick="window.location.href='login.php'">Back to Login</button>
    </div>
</body>

<script>

    // window.onload = function() {
    //             if (localStorage.getItem('registerSuccess') === 'Flase') {
    //                 toastr.success('Registered successfully');
    //                 localStorage.removeItem('registerSuccess');
    //             }
    //         };

$(".register").click(function() {
    // Code to execute on click
    var name = document.getElementById('username').value;
    var password = document.getElementById('password').value;

    if(name == "" || password== "" )
    {
        toastr.error('Please fill the deatils.');
    }
    var url = "process_register.php";
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

                if (data === "Username already exists.") {
                    toastr.error(data);
                } 
                else {
                    // console.log("Register successful:", data);
                    localStorage.setItem('registerSuccess', 'true');
                    window.location.href = 'login.php';
                }
            },
            error: function(xhr, status, error) {
                console.error("Registration failed:", error);
                toastr.error('Registration failed');
            }
        });
    }
});

</script>
</html>
