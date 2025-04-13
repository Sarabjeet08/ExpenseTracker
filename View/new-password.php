<?php require_once "../Controller/controllerUserData.php"; ?>
<?php 
$email = $_SESSION['email'];
if($email == false){
  header('Location: login-user.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create a New Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="../Public/style.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f5dc, #d4a373); /* Warm, calm background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: white;
            color: #333;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .form-container h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
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
        <h2>Create a New Password</h2>
        <?php
        if(count($errors) > 0){
            ?>
            <div class="alert alert-danger">
                <?php
                foreach($errors as $showerror){
                    echo $showerror;
                }
                ?>
            </div>
            <?php
        }
        ?>
        <form action="new-password.php" method="POST" autocomplete="off">
            <div class="form-group mb-3">
                <input class="form-control" type="password" name="password" placeholder="Create new password" required>
            </div>
            <div class="form-group mb-3">
                <input class="form-control" type="password" name="cpassword" placeholder="Confirm your password" required>
            </div>
            <div class="form-group">
                <input class="form-control button" type="submit" name="change-password" value="Change">
            </div>
        </form>
    </div>
</body>
</html>