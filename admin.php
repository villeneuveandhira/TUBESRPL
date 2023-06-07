<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./style/style.css">
    <link rel="icon" type="image/png" href="./assets/logo/fravk-icon.png" />
    <style>
    .wrapper {
        width: 100%;
        margin: 0 auto;
    }

    table tr td:last-child {
        width: 120px;
    }

    .logo {
        width: 144px;
    }
    </style>
</head>

<body>
    <header>
        <div class="header-wrapper">
            <a href="home.php" class="logo_header"><i class="fa fa-arrow-left"></i></a>
        </div>
    </header>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">List Request</h2>
                    </div>

                    <?php

                    require_once "config.php";
                    
                    $sql = "SELECT * FROM t_request WHERE status_req='pending'";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            $no = 1;
                            echo '<table class="table">';
                                ?>
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Logo</th>
                            <th scope="col">Domain</th>
                            <th scope="col">Nama Website</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="col-2">Aksi</th>
                        </tr>
                    </thead>
                    <?php
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>$no</td>";
                                        echo "<td><img src='logos/" . $row['logo_req'] . "' alt='logo_req' class='img-fluid logo'></td>";
                                        echo "<td>" . $row['domain_req'] . "</td>";
                                        echo "<td>" . $row['nama_req'] . "</td>";
                                        echo "<td>" . $row['status_req'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="accept.php?id='. $row['id_req'] .'" class="btn btn-success btn-act">Terima</a>';
                                            echo '<a href="reject.php?id='. $row['id_req'] .'" class="btn btn-danger btn-act">Tolak</a>';
                                        echo "</td>";
                                    echo "</tr>";
                                    $no++;
                                }
                                echo "</tbody>";                            
                            echo "</table>";

                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>List request kosong</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
 
                    mysqli_close($link);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>