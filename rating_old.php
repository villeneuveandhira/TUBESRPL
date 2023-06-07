<?php

session_start();

require_once "config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$rating = $review = "";
$rating_err = $review_err = "";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);

    $sql = "SELECT * FROM t_website WHERE id_web = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = $id;

        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                $_SESSION["id_web"] = $row["id_web"];
                $_SESSION["rating_web"] = $row["rating_web"];
                $_SESSION["r_count"] = $row["r_count"];
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }

    mysqli_stmt_close($stmt);

} else if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_web = $_SESSION["id_web"];
    $rating_web = $_SESSION["rating_web"];
    $r_count = $_SESSION["r_count"];

    if(empty(trim($_POST["rating"]))){
        $rating_err = "Masukkan jumlah rating.";   
    } else{
        $rating = trim($_POST["rating"]);
    }

    $review = trim($_POST["review"]);

    if(empty($rating_err)){
        $sql2 = "INSERT INTO t_rating (id_web, id_user, rating, review) VALUES (?, ?, ?, ?)";

        if($stmt2 = mysqli_prepare($link, $sql2)){
            mysqli_stmt_bind_param($stmt2, "iiss", $param_id_web, $param_id_user, $param_rating, $param_review);

            $param_id_web = $id_web;
            $param_id_user = $_SESSION["id_user"];
            $param_rating = $rating;
            $param_review = $review;

            if(mysqli_stmt_execute($stmt2)){
                $sql3 = "UPDATE t_website SET rating_web = ? WHERE id_web = ?";

                if ($stmt3 = mysqli_prepare($link, $sql3)){
                    mysqli_stmt_bind_param($stmt3, "di", $param_rating_web, $param_id_w);

                    $param_rating_web = ($rating_web * $r_count + $rating) / ($r_count + 1);
                    $param_id_w = $id_web;

                    if(mysqli_stmt_execute($stmt3)){
                        echo "
                        <script>
                            alert('Rating telah ditulis');
                            document.location.href = 'website.php?id=". $id_web ."';
                        </script>
                        ";  
                    } else{
                        echo "Something went wrong. Please try again later.";
                    }
                }
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        mysqli_stmt_close($stmt2);
    }
}

mysqli_close($link);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rating Form for <?= $row['domain_web'] ?></title>
    <link rel="icon" type="image/png" href="./assets/logo/fravk-icon.png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
    .wrapper {
        width: 100%;
        margin: 0 auto;
    }

    .checked {
        color: orange;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Rating & Review</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                        enctype="multipart/form-data">
                        <div class="form-group <?php echo (!empty($rating_err)) ? 'has-error' : ''; ?>">
                            <label for="rating">Rating</label>
                            <div class="form-check">
                                <input type="radio" name="rating" value="1" class="form-check-input">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="rating" value="2" class="form-check-input">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="rating" value="3" class="form-check-input">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="rating" value="4" class="form-check-input">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star"></span>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="rating" value="5" class="form-check-input">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                            </div>
                        </div>
                        <div class="form-group <?php echo (!empty($review_err)) ? 'has-error' : ''; ?>">
                            <label>Review</label>
                            <textarea name="review" class="form-control"
                                placeholder="Masukkan review Anda di sini"></textarea>
                            <span class="invalid-feedback"><?php echo $review_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Kirim">
                        <a href="website.php?id=<?php echo $id; ?>" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>