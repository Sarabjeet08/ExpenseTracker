<?php require_once "../Controller/controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="../Public/style.css">
    <style>
        .form-container {
            background: white;
            color: #333;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            margin: auto;
            margin-top: 50px;
        }
        .form-container h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-container .alert {
            font-size: 14px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .form-container .button {
            background: linear-gradient(to right, #a67c52, #8c5a3c); /* Warm button gradient */
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            transition: background 0.3s ease, transform 0.2s ease;
            cursor: pointer;
        }
        .form-container .button:hover {
            background: linear-gradient(to right, #8c5a3c, #6b4226); /* Slightly darker hover */
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form action="signup-user.php" method="POST" autocomplete="">
            <h2 class="text-center">Signup</h2>
            <p class="text-center">It's quick and easy.</p>
            <?php
            if(count($errors) == 1){
                ?>
                <div class="alert alert-danger text-center">
                    <?php
                    foreach($errors as $showerror){
                        echo $showerror;
                    }
                    ?>
                </div>
                <?php
            }elseif(count($errors) > 1){
                ?>
                <div class="alert alert-danger">
                    <?php
                    foreach($errors as $showerror){
                        ?>
                        <li><?php echo $showerror; ?></li>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
            <div class="form-group mt-3">
                <input class="form-control" type="text" name="name" placeholder="Full Name" required value="<?php echo $name ?>">
            </div>
            <div class="form-group mt-3">
                <input class="form-control" type="email" name="email" placeholder="Email Address" required value="<?php echo $email ?>">
            </div>
            <div class="form-group mt-3">
                <input class="form-control" type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group mt-3">
                <input class="form-control" type="password" name="cpassword" placeholder="Confirm password" required>
            </div>
            <div class="form-group mt-3">
                <input class="form-control button" type="submit" name="signup" value="Signup">
            </div>
            <div class="link login-link text-center mt-1">Already a member? <a href="login-user.php">Login here</a></div>
        </form>
    </div>
</body>
</html>