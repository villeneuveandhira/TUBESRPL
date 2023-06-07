<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

if(isset($_GET["id"]) && !empty($_GET["id"])){
    $id =  trim($_GET["id"]);

    $sql = "UPDATE t_request SET status_req = 'rejected' WHERE id_req = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = $id;
        
        if(mysqli_stmt_execute($stmt)){
            echo "
            <script>
                alert('Request telah direject');
                document.location.href = 'admin.php';
            </script>
            ";
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