<?php

session_start();

require_once("config.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    $_SESSION["loggedin"] = false;
    $_SESSION["id_user"] = 0;
    $_SESSION["username"] = "";
    $_SESSION["jenis_user"] = 'V';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>FRAVK: Rates and Reviews</title>
    <link rel="icon" type="image/png" href="./assets/logo/fravk-icon.png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./style/style.css">
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
            <a href="home.php" class="logo_header">FRAVK</a>
            <nav>
                <ul class="nav_links">
                    <li><a href="#">Trending</a></li>
                    <li><a href="#">About</a></li>
                    <?php
                    if ($_SESSION["jenis_user"] == 'A') {
                        echo '<a href="admin.php"><button class="button-admin">Admin Page</button></a>';
                    } else if ($_SESSION["jenis_user"] == 'D') {
                        echo '<a href="request.php"><button class="button-request"><i class="fa fa-plus"></i> Ajukan Website</button></a>';
                    }
                    ?>
                    <?php
                    if ($_SESSION["loggedin"]) {
                    ?> <li>
                        <div class="dropdown">
                            <?php
                            $uname = $_SESSION["username"];
                            $sql = "SELECT * FROM t_user WHERE username = '$uname'";
                            $result = mysqli_query($link, $sql);
                            $row = mysqli_fetch_array($result);
                            ?>
                            <img class="dropbtn" src="./profiles/<?php echo $row['profile_pic']?>" width="40"
                                height="40">
                            <?php echo $_SESSION["username"] ?>
                            <div class="dropdown-content">
                                <a href="user.php">Sunting Profil</a>
                                <a href="logout.php">Logout</a>
                            </div>
                        </div>
                    <li><?php
                        } else {
                            echo '<a href="login.php"><button class="button-login">Login</button></a>';
                        }
                            ?>
                </ul>
            </nav>
        </div>
    </header>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left" class="fw-bold">List Website</h2>

                        <?php

                        // if ($_SESSION["loggedin"]) {
                        //     echo '<a href="logout.php" class="btn btn-danger pull-right ml-3">Logout</a>';
                        // } else {
                        //     echo '<a href="login.php" class="btn btn-primary pull-right ml-3">Login</a>';
                        // }


                        ?>
                    </div>

                    <?php

                    require_once "config.php";

                    $sql = "SELECT * FROM t_website";
                    $no = 1;
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo '<div class="row">';
                            while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <div class="col-sm-2 col-m-4">
                        <div class="card" style="width: 15rem;">
                            <div class="card-body text-center">
                                <a href="website.php?id=<?php echo $row['id_web']?>"><img
                                        src="logos/<?php echo $row['logo_web']?>" alt='logo_web' width='50%'
                                        style='margin: 10px 0;'></a>
                                <h4 class="card-title"><?php echo $row['nama_web']?></h4>
                                <p style="color: grey;" class="card-text"><?php echo $row['domain_web']?></p>
                                <p class="card-text"><?php echo $row['deskripsi_web']?></p>
                                <div class="rating-review" style="display: flex; justify-content: space-between;">
                                    <p>&#x2B50; <?php echo $row['rating_web']?>/5</p>
                                    <p><?php echo $row["r_count"]; ?> ulasan</p>
                                </div>
                                <a href="website.php?id=<?php echo $row['id_web']?>" class="text-center">Lihat
                                    Review</a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- <thead>
                        <tr>
                            <th scope=" col">No</th>
                                    <th scope="col">Logo</th>
                                    <th scope="col">Domain</th>
                                    <th scope="col">Nama Website</th>
                                    <th scope="col">Rating</th>
                                    </tr>
                                    </thead>
                                    <?php
                            // echo "<tbody>";
                            // while ($row = mysqli_fetch_array($result)) {
                            //     echo "<tr>";
                            //     echo "<td>$no</td>";
                            //     echo "<td><a href='website.php?id=" . $row['id_web'] . "'><img src='logos/" . $row['logo_web'] . "' alt='logo_web' width='100'></a></td>";
                            //     echo "<td>" . $row['domain_web'] . "</td>";
                            //     echo "<td>" . $row['nama_web'] . "</td>";
                            //     echo "<td>" . $row['rating_web'] . "/5 </td>";
                            //     echo "<td><a href='website.php?id=" . $row['id_web'] . "'><i class='fa fa-arrow-right fa-xl'></i></a></td>";
                            //     echo "</tr>";
                            //     $no++;
                            // }
                            // echo "</tbody>";
                            // echo "</table>";

                            // mysqli_free_result($result); -->
                    } else {
                    echo '<div class="alert alert-danger"><em>List website kosong</em></div>';
                    }
                    } else {
                    echo "Oops! Something went wrong. Please try again later.";
                    }

                    mysqli_close($link);
                    ?>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        $(' [data-toggle="tooltip" ]').tooltip();
    });
    </script>
</body>

</html>