<?php                      
session_start();

require_once "config.php";

$id = $_SESSION["id_user"];
$sql = "SELECT * FROM t_user WHERE id_user='$id'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

$username = $nama_lengkap = $jenis_user = $profile_pic = $password_user = $confirm_password_user = "";
$username_err = $nama_lengkap_err = $jenis_user_err = $profile_pic_err = $password_user_err = $confirm_password_user_err = "";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    .header-wrapper {
        background-color: #000032;
        display: flex;
        color: white;
        justify-content: flex-start;
        align-items: center;
        padding: 16px 30px;
    }

    .logo_header {
        text-decoration: none;
        color: white;
        cursor: pointer;
        margin-right: auto;
        font-size: 28px;
    }

    .labels {
        color: grey;
    }
    </style>
</head>

<body>
    <header>
        <div class="header-wrapper">
            <a href="home.php" class="logo_header"><i class="fa fa-arrow-left"></i></a>
        </div>
    </header>
    <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5"
                        width="150px" src="./profiles/<?php echo $row['profile_pic'];?>"><span
                        class="font-weight-bold"><?php echo $row['nama_lengkap'];?></span><span
                        class="text-black-50"><?php echo $row['email'];?></span><span> </span></div>
            </div>
            <div class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Profile Settings</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12 mt-3"><label class="labels">Update Foto Profil</label>
                            <input type="file" name="profile_pic" class="form-control-file"
                                value="<?php echo $profile_pic; ?>">
                            <span class="help-block"><?php echo $profile_pic_err;?></span>
                        </div>
                        <div class="col-md-12 mt-3"><label class="labels">Username</label><input type="text"
                                name="username" class="form-control" value="<?php echo $row['username'];?>" disabled>
                        </div>
                        <div class="col-md-12 mt-3"><label class="labels">Nama Lengkap</label><input type="text"
                                name="nama_lengkap" class="form-control" value="<?php echo $row['nama_lengkap'];?>">
                        </div>
                        <!-- <div class="col-md-12 mt-3"><label class="labels">Nomor Telepon</label><input type="text"
                                name="no_telepon" class="form-control" value="<?php echo $row['no_telepon'];?>">
                        </div> -->
                        <div class="col-md-12 mt-3"><label class="labels">Tentang Saya</label><input type="text"
                                name="deskripsi_user" class="form-control "
                                value="<?php echo $row['deskripsi_user'];?>">
                        </div>
                        <!-- <div class="col-md-12 mt-3"><label class="labels">Tanggal Lahir</label><input type="date"
                                name="tanggal_lahir" class="form-control" value="<?php echo $row['tanggal_lahir'];?>">
                        </div>
                        <div class="col-md-12 mt-3"><label class="labels">Alamat</label><input type="date" name="alamat"
                                class="form-control" value="<?php echo $row['alamat'];?>">
                        </div> -->
                        <div class="mt-5 text-center"><button class="btn btn-primary profile-button"
                                type="button">Simpan
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>