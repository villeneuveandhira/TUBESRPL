<?php

session_start();

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);

    require_once "config.php";

    $sql = "SELECT * FROM t_website WHERE id_web = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        $param_id = $id;
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                $domain_web = $row["domain_web"];
                $rating_web = $row["rating_web"];
                $nama_web = $row["nama_web"];
                $deksripsi_web = $row["deskripsi_web"];
                $kategori_web = $row["kategori_web"];
                $logo_web = $row["logo_web"];
                $id_user = $row["id_user"];
                $r_count = $row["r_count"];
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    mysqli_stmt_close($stmt);

    
} else{
    echo "Oops! Something went wrong. Please try again later.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= ucwords($domain_web) ?> Reviews</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./style/style.css">
    <style>
    .wrapper {
        width: 100%;
        margin: 0 auto;
    }

    .logo {
        width: 144px;
    }

    .profile {
        object-fit: cover;
        width: 72px;
        height: 72px;
        border-radius: 50%;
    }

    .checked {
        color: orange;
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
                    <h1 class="mt-5 mb-3"><?php echo $row["nama_web"]; ?></h1>
                    <div class="form-group">
                        <label><b>Logo Website</b></label>
                        <img src="logos/<?php echo $row["logo_web"]; ?>" alt="logo_web" class="img-fluid logo">
                    </div>
                    <div class="form-group">
                        <label><b>Domain Website</b></label>
                        <p><?php echo $row["domain_web"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Deskripsi Website</b></label>
                        <p><?php echo $row["deskripsi_web"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Kategori Website</b></label>
                        <p><?php echo $row["kategori_web"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Rating Website</b></label>
                        <br>
                        <?php
                        $counter = $row["rating_web"];
                        $remainder = 5;
                        while($counter >= 1){
                            echo "<span class='fa fa-star checked'></span>";
                            $counter = $counter - 1;
                            $remainder = $remainder - 1;
                        }
                        while($remainder > 0){
                            echo "<span class='fa fa-star'></span>";
                            $remainder = $remainder - 1;
                        }
                        ?>
                        <p>Rata-rata <?php echo $row["rating_web"]; ?> dihitung dari <?php echo $r_count; ?> rating</p>
                    </div>
                    <?php

                    require_once "config.php";

                    $profile_pic = $username = "";

                    $sql2 = "SELECT * FROM t_rating WHERE id_web = $id AND id_user != ". $_SESSION["id_user"] ." LIMIT 10";
                    if($result2 = mysqli_query($link, $sql2)){
                        if($r_count > 0){
                            echo "<table class='table'>";
                                echo "<tbody>";
                                while($row2 = mysqli_fetch_array($result2)){
                                    $sql3 = "SELECT * FROM t_user WHERE id_user = ". $row2["id_user"] ."";
                                    if($result3 = mysqli_query($link, $sql3)){
                                        $row3 = mysqli_fetch_array($result3);

                                        $profile_pic = $row3["profile_pic"];
                                        $username = $row3["username"];

                                        mysqli_free_result($result3);
                                    }
                                    echo "<tr>";
                                        echo "<td><img src='profiles/" . $profile_pic . "' alt='profile_pic' class='img-fluid profile'></td>";
                                        echo "<td>". $username ."</td>";
                                        echo "<td>";
                                        $counter = $row2["rating"];
                                        $remainder = 5;
                                        while($counter >= 1){
                                            echo "<span class='fa fa-star checked'></span>";
                                            $counter = $counter - 1;
                                            $remainder = $remainder - 1;
                                        }
                                        while($remainder > 0){
                                            echo "<span class='fa fa-star'></span>";
                                            $remainder = $remainder - 1;
                                        }
                                        echo "</td>";
                                        echo "<td>". $row2["review"] ."</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                            echo "</table>";

                            mysqli_free_result($result2);
                        } else{
                            echo '<div class="alert alert-danger"><em>Website ini belum memiliki rating</em></div>';
                        }
                    }

                    $flag = 0;
                    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                        $sql4 = "SELECT * FROM t_rating WHERE id_web = $id AND id_user = ". $_SESSION["id_user"] ."";

                        if($result4 = mysqli_query($link, $sql4)){
                            if(mysqli_num_rows($result4) == 1){
                                echo "<p>Rating Anda</p>";
                                echo "<table class='table'>";
                                echo "<tbody>";
                                while($row4 = mysqli_fetch_array($result4)){
                                    $sql5 = "SELECT * FROM t_user WHERE id_user = ". $row4["id_user"] ."";
                                    if($result5 = mysqli_query($link, $sql5)){
                                        $row5 = mysqli_fetch_array($result5);

                                        $profile_pic = $row5["profile_pic"];
                                        $username = $row5["username"];

                                        mysqli_free_result($result5);
                                    }
                                    echo "<tr>";
                                        echo "<td><img src='profiles/" . $profile_pic . "' alt='profile_pic' class='img-fluid profile'></td>";
                                        echo "<td>". $username ."</td>";
                                        echo "<td>";
                                        $counter = $row4["rating"];
                                        $remainder = 5;
                                        while($counter >= 1){
                                            echo "<span class='fa fa-star checked'></span>";
                                            $counter = $counter - 1;
                                            $remainder = $remainder - 1;
                                        }
                                        while($remainder > 0){
                                            echo "<span class='fa fa-star'></span>";
                                            $remainder = $remainder - 1;
                                        }
                                        echo "</td>";
                                        echo "<td>". $row4["review"] ."</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
                                echo '<a href="edit_rating.php?id='. $id .'" class="btn btn-success"><i class="fa fa-edit"></i> Edit Rating</a>';
                                $flag = 1;
                            }
                        }
                    }
                    if($flag == 0){
                        echo '<a href="rating.php?id='. $id .'" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Rating</a>';
                    }
                    
                    mysqli_close($link);

                    ?>
                    <p><a href="home.php" class="btn btn-primary">Home</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>