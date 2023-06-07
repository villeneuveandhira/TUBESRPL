<?php
require_once "config.php";
 
$username = $nama_lengkap = $jenis_user = $profile_pic = $password_user = $confirm_password_user = "";
$username_err = $nama_lengkap_err = $jenis_user_err = $profile_pic_err = $password_user_err = $confirm_password_user_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["username"]))){
        $username_err = "Masukkan username.";
    } else{
        $sql = "SELECT id_user FROM t_user WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = trim($_POST["username"]);

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Username sudah terpakai.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }

    if(empty(trim($_POST["nama_lengkap"]))){
        $nama_lengkap_err = "Masukkan nama lengkap.";     
    } else{
        $nama_lengkap = trim($_POST["nama_lengkap"]);
    }

    if(empty(trim($_FILES["profile_pic"]["name"]))){
        $profile_pic = "default.jpg";
    } else{
        $profile_pic = $_FILES["profile_pic"]["name"];

        $target_dir = "profiles/";
        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
        $file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $file_size = $_FILES['profile_pic']['size'];
    
        if ($file_size > 3000000){
            echo "<strong>Gagal upload! Ukuran Maksimal 3MB</strong>";
            echo "<a href='home.php'>Upload ulang</a>";
            exit();
        }
    }
    
    if(empty(trim($_POST["jenis_user"]))){
        $jenis_user_err = "Pilih jenis user.";     
    } else{
        $jenis_user = trim($_POST["jenis_user"]);
    }

    if(empty(trim($_POST["password_user"]))){
        $password_user_err = "Masukkan password.";     
    } else{
        $password_user = trim($_POST["password_user"]);
    }
    
    if(empty(trim($_POST["confirm_password_user"]))){
        $confirm_password_user_err = "Masukkan ulang Password.";     
    } else{
        $confirm_password_user = trim($_POST["confirm_password_user"]);
        if(empty($password_user_err) && ($password_user != $confirm_password_user)){
            $confirm_password_user_err = "Password tidak sama.";
        }
    }
    
    if(empty($username_err) && empty($password_user_err) && empty($confirm_password_user_err)){
        
        $sql = "INSERT INTO t_user (username, nama_lengkap, jenis_user, password_user, profile_pic) VALUES (?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sssss", $param_username, $param_nama_lengkap, $param_jenis_user, $param_password_user, $param_profile_pic);

            $param_username = $username;
            $param_nama_lengkap = $nama_lengkap;
            $param_jenis_user = $jenis_user;
            $param_password_user = password_hash($password_user, PASSWORD_DEFAULT);
            $param_profile_pic = $profile_pic;

            if(mysqli_stmt_execute($stmt)){
                if ($profile_pic !== "default.jpg"){
                    $nm_file = $_FILES['profile_pic']['name'];
                    $dir = "profiles/$nm_file";
                    move_uploaded_file($_FILES['profile_pic']['tmp_name'], $dir);
                }
                echo "
                <script>
                    alert('Akun berhasil dibuat');
                    document.location.href = 'index.php';
                </script>
                ";
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>FRAVK</title>
    <link rel="icon" type="image/png" href="./assets/logo/fravk-icon.png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
    .wrapper {
        width: 100%;
        margin: 0 auto;
    }

    .help-block {
        color: red;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Register</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                        enctype="multipart/form-data">
                        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                            <span class="help-block"><?php echo $username_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($nama_lengkap_err)) ? 'has-error' : ''; ?>">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control"
                                value="<?php echo $nama_lengkap; ?>">
                            <span class="help-block"><?php echo $nama_lengkap_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($profile_pic_err)) ? 'has-error' : ''; ?>">
                            <label>Profile Picture</label>
                            <input type="file" name="profile_pic" class="form-control-file"
                                value="<?php echo $profile_pic; ?>">
                            <span class="help-block"><?php echo $profile_pic_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($jenis_user_err)) ? 'has-error' : ''; ?>">
                            <label for="jenis_user">Jenis User</label>
                            <div class="form-check">
                                <input type="radio" name="jenis_user" value="R" class="form-check-input">
                                <label for="jenis_user" class="form-check-label">
                                    Reviewer
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="jenis_user" value="D" class="form-check-input">
                                <label for="jenis_user" class="form-check-label">
                                    Developer
                                </label>
                            </div>
                        </div>
                        <div class="form-group <?php echo (!empty($password_user_err)) ? 'has-error' : ''; ?>">
                            <label>Password</label>
                            <input type="password" name="password_user" class="form-control"
                                value="<?php echo $password_user; ?>">
                            <span class="help-block"><?php echo $password_user_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($confirm_password_user_err)) ? 'has-error' : ''; ?>">
                            <label>Confirm password</label>
                            <input type="password" name="confirm_password_user" class="form-control"
                                value="<?php echo $confirm_password_user; ?>">
                            <span class="help-block"><?php echo $confirm_password_user_err; ?></span>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <input type="reset" class="btn btn-default" value="Reset">
                        </div>
                        <p>Sudah memiliki akun? <a href="login.php">Login disini</a>.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>