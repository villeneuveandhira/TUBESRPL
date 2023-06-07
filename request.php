<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$id_user = $_SESSION["id_user"];

require_once "config.php";
 
$domain_req = $nama_req = $deskripsi_req = $kategori_req = $logo_req = "";
$domain_req_err = $nama_req_err = $deskripsi_req_err = $kategori_req_err = $logo_req_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $input_domain_req = trim($_POST["domain_req"]);
    if(empty($input_domain_req)){
        $domain_req_err = "Masukkan nama domain.";
    } else{
        $domain_req = $input_domain_req;
    }
    
    $input_nama_req = trim($_POST["nama_req"]);
    if(empty($input_nama_req)){
        $nama_req_err = "Masukkan nama website.";     
    } else{
        $nama_req = $input_nama_req;
    }
    
    $deskripsi_req = trim($_POST["deskripsi_req"]);
    
    $kategori_req = trim($_POST["kategori_req"]);

    $input_logo_req = trim($_FILES["logo_req"]["name"]);
    if(empty($input_logo_req)){
        $logo_req = "default.png";     
    } else{
        $logo_req = $input_logo_req;

        $target_dir = "logos/";
        $target_file = $target_dir . basename($_FILES["logo_req"]["name"]);
        $file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $file_size = $_FILES['logo_req']['size'];
    
        if ($file_size > 3000000){
            echo "<strong>Gagal upload! Ukuran Maksimal 3MB</strong>";
            echo "<a href='home.php'>Upload ulang</a>";
            exit();
        }
    }
    

    if(empty($domain_req_err) && empty($nama_req_err)){
        $sql = "INSERT INTO t_request (domain_req, nama_req, deskripsi_req, kategori_req, logo_req, id_user, status_req) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sssssss", $param_domain_req, $param_nama_req, $param_deskripsi_req, $param_kategori_req , $param_logo_req, $param_id_user, $param_status_req);
            
            $param_domain_req = $domain_req;
            $param_nama_req = $nama_req;
            $param_deskripsi_req = $deskripsi_req;
            $param_kategori_req = $kategori_req;
            $param_logo_req = $logo_req;
            $param_id_user = $id_user;
            $param_status_req = "pending";

            if(mysqli_stmt_execute($stmt)){
                if (!empty($input_logo_req)){
                    $nm_file = $_FILES['logo_req']['name'];
                    $dir = "logos/$nm_file";
                    move_uploaded_file($_FILES['logo_req']['tmp_name'], $dir);
                }
                echo "
                <script>
                    alert('Website berhasil diajukan. Mohon tunggu konfirmasi selanjutnya.');
                    document.location.href = 'index.php';
                </script>
                ";
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    .wrapper {
        width: 100%;
        margin: 0 auto;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Pengajuan Website</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                        enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Domain Website</label>
                            <input type="text" name="domain_req"
                                class="form-control <?php echo (!empty($domain_req_err)) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $domain_req; ?>">
                            <span class="invalid-feedback"><?php echo $domain_req_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Nama Website</label>
                            <input type="text" name="nama_req"
                                class="form-control <?php echo (!empty($nama_req_err)) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $nama_req; ?>">
                            <span class="invalid-feedback"><?php echo $nama_req_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Website</label>
                            <textarea name="deskripsi_req"
                                class="form-control <?php echo (!empty($deskripsi_req_err)) ? 'is-invalid' : ''; ?>"><?php echo $deskripsi_req; ?></textarea>
                            <span class="invalid-feedback"><?php echo $deskripsi_req_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Kategori Website</label>
                            <input type="text" name="kategori_req"
                                class="form-control <?php echo (!empty($kategori_req_err)) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $kategori_req; ?>">
                            <span class="invalid-feedback"><?php echo $kategori_req_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Logo Website</label>
                            <input type="file" name="logo_req"
                                class="form-control-file <?php echo (!empty($logo_req_err)) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $logo_req; ?>">
                            <span class="invalid-feedback"><?php echo $logo_req_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="home.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>