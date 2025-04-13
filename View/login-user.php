<?php require_once "../Controller/controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="../Public/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f5dc, #d4a373); /* Warm, calm background */
        }
        .btn-primary {
            background: linear-gradient(to right, #a67c52, #8c5a3c); /* Warm button gradient */
            border: none;
            color: white;
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #8c5a3c, #6b4226); /* Slightly darker hover */
        }
    </style>
</head>
<body>
    <h1 class="h1 ms-5 pt-5">Expense Tracker</h1>
    <h1 class="h4 ms-5 ps-5 fst-italic"> - The perfect tool to manage your finances</h1>
    <div class="container">
        <div class="row">
            <div class="col-md-7">
               <img src="../images/wallet.svg" alt="wallet" height="400"/>
            </div>
            <div class="col-md-4 form login-form">
                <form action="login-user.php" method="POST" autocomplete="">
                    <h2 class="text-center">Login</h2>
                    <p class="text-center">Login with your email and password.</p>
                    <?php
                    if(count($errors) > 0){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="form-group mt-3">
                        <input class="form-control" type="email" name="email" placeholder="Email Address" required value="<?php echo $email ?>">
                    </div>
                    <div class="form-group mt-3">
                        <input class="form-control" type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="link forget-pass text-left mt-2"><a href="forgot-password.php">Forgot password?</a></div>
                    <div class="form-group">
                        <input class="form-control button btn-primary" type="submit" name="login" value="Login">
                    </div>
                    <div class="link login-link text-center mt-1">Don't have an account? <a href="signup-user.php">Signup now</a></div>
                </form>
            </div>
        </div>
    </div>
    <?php
    if(isset($_POST['login'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $check_email = "SELECT * FROM usertable WHERE email = '$email'";
        $res = mysqli_query($con, $check_email);
        if(mysqli_num_rows($res) > 0){
            $fetch = mysqli_fetch_assoc($res);
            $fetch_pass = $fetch['password'];
            $status = $fetch['status'];
            $code = $fetch['code'];
            if(password_verify($password, $fetch_pass)){
                if($status == "verified"){
                    if($code == 0){
                        $_SESSION['email'] = $email;
                        $_SESSION['name'] = $fetch['name'];
                        $_SESSION['is_logged_in'] = true;
                        header('Location: home.php');
                    } else {
                        $_SESSION['email'] = $email;
                        $_SESSION['requires_otp'] = true; // Set OTP validation flag
                        header('Location: user-otp.php'); // Redirect to OTP verification page
                    }
                } else {
                    $errors['email'] = "Please verify your email to login.";
                }
            } else {
                $errors['email'] = "Incorrect email or password!";
            }
        } else {
            $errors['email'] = "You're not a member! Click on the bottom link to signup.";
        }
    }
    ?>
</body>
</html>