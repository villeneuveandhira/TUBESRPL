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

<?php

session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}

require_once "config.php";

$username = $password_user = "";
$username_err = $password_user_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST") {
 
    if(empty(trim($_POST["username"]))) {
        $username_err = "Masukkan username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password_user"]))){
        $password_user_err = "Masukkan password_user.";
    } else{
        $password_user = trim($_POST["password_user"]);
    }
    
    if(empty($username_err) && empty($password_user_err)) {
        $sql = "SELECT id_user, username, password_user, jenis_user FROM t_user WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = $username;
            
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1) {   
                    mysqli_stmt_bind_result($stmt, $id_user, $username, $hashed_password_user, $jenis_user);
                    if(mysqli_stmt_fetch($stmt)) {
                        if(password_verify($password_user, $hashed_password_user)){
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id_user"] = $id_user;
                            $_SESSION["username"] = $username;   
                            $_SESSION["jenis_user"] = $jenis_user;                        
                            header("location: home.php");
                        } else{ echo "
                            <script>
                    alert('Username atau password salah.');
                </script>";
                            $password_user_err = "Password salah.";
                        }
                    }
                } else {
                    $username_err = "Username tidak ditemukan.";
                }
            } else {
                echo "ERROR: Query tidak dapat dijalankan.";
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
    <title>Sign Up</title>
    <link rel="icon" type="image/png" href="./assets/logo/fravk-icon.png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    .wrapper {
        width: 100%;
        margin: 0 auto;
    }

    @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

    * {
        box-sizing: border-box;
    }

    body {
        background: #f6f5f7;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        font-family: 'Montserrat', sans-serif;
        height: 100vh;
        margin: -20px 0 50px;
    }

    h1 {
        font-weight: bold;
        margin: 0;
    }

    h2 {
        text-align: center;
    }

    p {
        font-size: 14px;
        font-weight: 100;
        line-height: 20px;
        letter-spacing: 0.5px;
        margin: 20px 0 30px;
    }

    span {
        font-size: 12px;
    }

    a {
        color: #333;
        font-size: 14px;
        text-decoration: none;
        margin: 15px 0;
    }

    button {
        border-radius: 20px;
        border: 1px solid white;
        background-color: #000032;
        color: #FFFFFF;
        font-size: 12px;
        font-weight: bold;
        padding: 12px 45px;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: transform 80ms ease-in;
    }

    button:active {
        transform: scale(0.95);
    }

    button:focus {
        outline: none;
    }

    button.ghost {
        background-color: transparent;
        border-color: #FFFFFF;
    }

    form {
        background-color: #FFFFFF;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        flex-direction: column;
        padding: 0 50px;
        height: 100%;
        text-align: center;
    }

    input {
        background-color: #eee;
        border: none;
        padding: 8px 28px;
        margin: 8px 0;
        width: 100%;
    }

    .container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25),
            0 10px 10px rgba(0, 0, 0, 0.22);
        position: relative;
        overflow: hidden;
        width: 768px;
        max-width: 100%;
        min-height: 600px;
    }

    .form-container {
        position: absolute;
        top: 0;
        height: 100%;
        transition: all 0.6s ease-in-out;
    }

    .sign-in-container {
        left: 0;
        width: 50%;
        z-index: 2;
    }

    .sign-up-container {
        left: 0;
        width: 50%;
        opacity: 0;
        z-index: 1;
    }

    .overlay-container {
        position: absolute;
        top: 0;
        left: 50%;
        width: 50%;
        height: 100%;
        overflow: hidden;
        transition: transform 0.6s ease-in-out;
        z-index: 100;
    }

    .container.right-panel-active .overlay-container {
        transform: translateX(-100%);
    }

    .overlay {
        background: #000032;
        background: -webkit-linear-gradient(to right, #000032, #000032);
        background: linear-gradient(to right, #000032, #000032);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: 0 0;
        color: #FFFFFF;
        position: relative;
        left: -100%;
        height: 100%;
        width: 200%;
        transform: translateX(0);
        transition: transform 0.6s ease-in-out;
    }

    .container.right-panel-active .overlay {
        transform: translateX(50%);
    }

    .overlay-panel {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 40px;
        text-align: center;
        top: 0;
        height: 100%;
        width: 50%;
        transform: translateX(0);
        transition: transform 0.6s ease-in-out;
    }

    .overlay-left {
        transform: translateX(-20%);
    }

    .container.right-panel-active .overlay-left {
        transform: translateX(0);
    }

    .overlay-right {
        right: 0;
        transform: translateX(0);
    }

    .container.right-panel-active .overlay-right {
        transform: translateX(20%);
    }
    </style>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-in-container">
            <h1 class="text-center mt-5" style="margin-bottom: -30px;">Sign Up</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                enctype="multipart/form-data">
                <div class=" form-group <?php echo (!empty($nama_lengkap_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="nama_lengkap" placeholder="Nama Lengkap"
                        value="<?php echo $nama_lengkap; ?>">
                    <span class="help-block"><?php echo $nama_lengkap_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="username" placeholder="Username" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <!-- <div class="form-group <?php echo (!empty($profile_pic_err)) ? 'has-error' : ''; ?>">
                    <label for="jenis_user">Foto Profil</label>
                    <input type="file" name="profile_pic" class="form-control-file" value="<?php echo $profile_pic; ?>">
                    <span class="help-block"><?php echo $profile_pic_err;?></span>
                </div> -->
                <div class="form-group <?php echo (!empty($jenis_user_err)) ? 'has-error' : ''; ?>">
                    <label for="jenis_user">Jenis User</label>
                    <div class="div_jenis_user" style="display: flex; justify-content: space-between;">
                        <div class="form-check">
                            <input type="radio" name="jenis_user" value="R" class="form-check-input">
                            <label for="jenis_user" class="form-check-label mt-3 mr-4">
                                Reviewer
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="jenis_user" value="D" class="form-check-input">
                            <label for="jenis_user" class="form-check-label mt-3 mr-2">
                                Developer
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group <?php echo (!empty($password_user_err)) ? 'has-error' : ''; ?>">
                    <input type="password" name="password_user" value="<?php echo $password_user; ?>"
                        placeholder="Password">
                    <span class="help-block"><?php echo $password_user_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_user_err)) ? 'has-error' : ''; ?>">
                    <input type="password" name="confirm_password_user" value="<?php echo $confirm_password_user; ?>"
                        placeholder="Confirm Password">
                    <span class="help-block"><?php echo $confirm_password_user_err; ?></span>
                </div>
                <div class="form-group">
                    <button type="submit">Daftar</button>
                    <input type="reset" class="btn btn-default" value="Reset">
                </div>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-right">
                    <h1>Sudah punya akun?</h1>
                    <a href="login.php"><button class="ghost">Masuk</button></a>
                    <div>
                        <a href="home.php"><button style="color: #000032; background-color: white;">Kembali</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
    </script>
</body>



</html>