<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Teacher Portal | Forget Password</title>
    <!-- Include jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Toastr CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- CSS -->
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
            
            <label for="password">New Password:</label>
            <div class="inputWithIcon">
                <input type="password" placeholder="Password" id="password" name="password" required>
                <i class="fa fa-lock fa-lg fa-fw" aria-hidden="true"></i>
            </div>
            <input type="button" class="update_password" value="Update">
        </form>
    </div>
</body>

<script>
    // window.onload = function() {
    //     if (localStorage.getItem('updateSuccess') === 'true') {
    //         toastr.success('Password updated successfully');
    //         localStorage.removeItem('updateSuccess');
    //     }
    // };

    $(".update_password").click(function() 
    {
        var name = document.getElementById('username').value;
        var password = document.getElementById('password').value;

        if(name == "" || password == "") {
            toastr.error('Please fill in all the details.');
            return;
        }

        var url = "update_password.php";
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                username: name,
                password: password
            },
            success: function(data) {
                if (data.success === false) {
                    if (data.message === "User not found. Please register first.") {
                        toastr.error(data.message);
                    } else {
                        toastr.error('An error occurred: ' + data.error);
                    }
                } else {
                    localStorage.setItem('updateSuccess', 'true');
                    // window.location.href = 'login.php';
                }
            },
            error: function(xhr, status, error) {
                console.error("Password update failed:", error);
                toastr.error('Password update failed');
            }
        });
    });

</script>

</html>
