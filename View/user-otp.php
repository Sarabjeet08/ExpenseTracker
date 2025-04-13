<?php require_once "../Controller/controllerUserData.php"; ?>
<?php 
$email = $_SESSION['email'];
if($email == false){
  header('Location: login-user.php');
}

if(isset($_POST['check-otp'])){
    $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
    $email = $_SESSION['email'];
    $check_code = "SELECT * FROM usertable WHERE email = '$email' AND code = '$otp_code'";
    $code_res = mysqli_query($con, $check_code);
    if(mysqli_num_rows($code_res) > 0){
        $update_code = "UPDATE usertable SET code = 0 WHERE email = '$email'";
        $update_res = mysqli_query($con, $update_code);
        if($update_res){
            $_SESSION['requires_otp'] = false; // Clear OTP validation flag
            $_SESSION['is_logged_in'] = true;
            header('Location: home.php');
            exit();
        } else {
            $errors['otp-error'] = "Failed to verify OTP. Please try again.";
        }
    } else {
        $errors['otp-error'] = "Invalid OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Code Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="../Public/style.css">
    <style>
        .card-header {
            background: linear-gradient(90deg, #ff8a65, #ff7043); /* Consistent header style */
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="user-otp.php" method="POST" autocomplete="off">
                    <h2 class="text-center">Code Verification</h2>
                    <?php 
                    if(isset($_SESSION['info'])){
                        ?>
                        <div class="alert alert-success text-center">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                    }
                    ?>
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
                    <div class="form-group">
                        <input class="form-control" type="number" name="otp" placeholder="Enter verification code" required>
                    </div>
                    <div class="form-group mt-3">
                        <input class="form-control button" type="submit" name="check-otp" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>