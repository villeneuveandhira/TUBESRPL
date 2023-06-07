<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

$domain_web = $nama_web = $deskripsi_web = $kategori_web = $logo_web = $id_user = "";

if(isset($_GET["id"]) && !empty($_GET["id"])){
    $id =  trim($_GET["id"]);

    $sql = "SELECT * from t_request WHERE id_req = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = $id;

        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                $domain_web = $row["domain_req"];
                $nama_web = $row["nama_req"];
                $deskripsi_web = $row["deskripsi_req"];
                $kategori_web = $row["kategori_req"];
                $logo_web = $row["logo_req"];
                $id_user = $row["id_user"];

                $sql2 = "UPDATE t_request SET status_req = 'accepted' WHERE id_req = ?";
                if ($stmt2 = mysqli_prepare($link, $sql2)){
                    mysqli_stmt_bind_param($stmt2, "i", $param_id2);

                    $param_id2 = $id;

                    mysqli_stmt_execute($stmt2);
                }
                mysqli_stmt_close($stmt2);

                $sql3 = "INSERT INTO t_website (domain_web, nama_web, deskripsi_web, kategori_web, logo_web, id_user) VALUES (?, ?, ?, ?, ?, ?)";
                if ($stmt3 = mysqli_prepare($link, $sql3)){
                    mysqli_stmt_bind_param($stmt3, "ssssss", $param_domain_web, $param_nama_web, $param_deskripsi_web, $param_kategori_web, $param_logo_web, $param_id_user);

                    $param_domain_web = $domain_web;
                    $param_nama_web = $nama_web;
                    $param_deskripsi_web = $deskripsi_web;
                    $param_kategori_web = $kategori_web;
                    $param_logo_web = $logo_web;
                    $param_id_user = $id_user;

                    mysqli_stmt_execute($stmt3);
                }
                mysqli_stmt_close($stmt3);

                echo "
                <script>
                    alert('Website telah diaccept');
                    document.location.href = 'admin.php';
                </script>
                ";
            } else{
                echo "
                <script>
                    alert('Request dengan id ". $id ." tidak ditemukan');
                    document.location.href = 'admin.php';
                </script>
                ";
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    mysqli_stmt_close($stmt);

    mysqli_close($link);
} else{
    echo "Oops! Something went wrong. Please try again later.";
}

?>