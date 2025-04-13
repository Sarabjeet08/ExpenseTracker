<?php 
require_once "../Controller/controllerUserData.php"; 
require_once "../Model/connection.php";

$email = $_SESSION['email'];
if(!$email){
    header('Location: login-user.php');
    exit();
}

// Fetch user data to populate $fetch_info
$query = "SELECT * FROM usertable WHERE email='$email'";
$result = mysqli_query($con, $query);
$fetch_info = mysqli_fetch_assoc($result);

if(isset($_POST['update-profile'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $image = $_FILES['image']['name'];
    $target = "../Public/uploads/" . basename($image);

    if($image){
        if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
            $query = "UPDATE usertable SET name='$name', image='$image' WHERE email='$email'";
        } else {
            $_SESSION['info'] = "Failed to upload image!";
        }
    } else {
        $_SESSION['info'] = "Please select a picture to upload!";
    }

    if(mysqli_query($con, $query)){
        $_SESSION['info'] = "Profile updated successfully!";
    } else {
        $_SESSION['info'] = "Failed to update profile!";
    }
}

if(isset($_POST['delete-account'])){
    $query = "DELETE FROM usertable WHERE email='$email'";
    if(mysqli_query($con, $query)){
        session_destroy();
        header('Location: signup-user.php');
        exit();
    } else {
        $_SESSION['info'] = "Failed to delete account!";
    }
}

$query = "SELECT * FROM usertable WHERE email='$email'";
$result = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f5f5dc, #d4a373); /* Warm, calm background */
        }
        .card-header {
            background: linear-gradient(90deg, #d4a373, #a67c52); /* Warm header style */
            color: white;
            font-weight: bold;
        }
        .btn-primary {
            background: linear-gradient(to right, #a67c52, #8c5a3c); /* Warm button gradient */
            border: none;
            color: white;
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #8c5a3c, #6b4226); /* Slightly darker hover */
        }
        .btn-danger {
            background-color: #df4b4b;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <?php include "nav.php"; ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>User Profile</h4>
                    </div>
                    <div class="card-body">
                        
                        <?php if(isset($_SESSION['info'])): ?>
                            <div class="alert alert-info text-center">
                                <?php echo $_SESSION['info']; unset($_SESSION['info']); ?>
                            </div>
                        <?php endif; ?>
                        <form action="user-profile.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                            <div class="form-group mb-3 text-center">
                                <img src="../Public/uploads/<?php echo $fetch_info['image'] ?: 'default.png'; ?>" alt="Profile Image" class="rounded-circle" width="150" height="150">
                            </div>
                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?php echo $fetch_info['name']; ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="image">Profile Image</label>
                                <input type="file" name="image" id="image" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" name="update-profile" class="btn btn-primary w-100">Update Profile</button>
                            </div>
                        </form>
                        <form action="../user-profile.php" method="POST">
                            <div class="form-group">
                                <button type="submit" name="delete-account" class="btn btn-danger w-100">Delete Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
