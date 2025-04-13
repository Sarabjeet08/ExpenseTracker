<?php 
require_once "../Controller/controllerUserData.php"; ?>
<?php
if($_SESSION['info'] == false){
    header('Location: login-user.php');  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Changed</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="../Public/style.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #fdf6e3, #ff7043);
            color: white;
            font-family: 'Poppins', sans-serif;
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
        .form-container .alert {
            font-size: 14px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .form-container .button {
            background: linear-gradient(to right, #2ba073, #43a047);
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
            background: linear-gradient(to right, #1b5e20, #2e7d32);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Password Changed Successfully</h2>
        <?php 
        if(isset($_SESSION['info'])){
            ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['info']; ?>
            </div>
            <?php
        }
        ?>
        <form action="login-user.php" method="POST">
            <button type="submit" name="login-now" class="button">Login Now</button>
        </form>
    </div>
</body>
</html>