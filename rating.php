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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating Form for <?= $row['domain_web'] ?></title>
    <link rel="icon" type="image/png" href="./assets/logo/fravk-icon.png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    @import url(https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css);

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html,
    body {
        width: 100%;
        height: 100%;
    }

    body {
        font-family: Arial, sans-serif;
    }

    .container-stars {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .rating-wrap {
        max-width: 480px;
        margin: auto;
        padding: 15px;
        box-shadow: 0 0 3px 0 rgba(0, 0, 0, .2);
        text-align: center;
    }

    .center {
        width: 162px;
        margin: auto;
    }


    #rating-value {
        width: 110px;
        margin: 40px auto 0;
        padding: 10px 5px;
        text-align: center;
        box-shadow: inset 0 0 2px 1px rgba(46, 204, 113, .2);
    }

    /*styling star rating*/
    .rating {
        border: none;
        float: left;
    }

    .rating>input {
        display: none;
    }

    .rating>label:before {
        content: '\f005';
        font-family: FontAwesome;
        margin: 5px;
        font-size: 1.5rem;
        display: inline-block;
        cursor: pointer;
    }

    .rating>.half:before {
        content: '\f089';
        position: absolute;
        cursor: pointer;
    }


    .rating>label {
        color: #ddd;
        float: right;
        cursor: pointer;
    }

    .rating>input:checked~label,
    .rating:not(:checked)>label:hover,
    .rating:not(:checked)>label:hover~label {
        color: #FFEA00;
    }

    .rating>input:checked+label:hover,
    .rating>input:checked~label:hover,
    .rating>label:hover~input:checked~label,
    .rating>input:checked~label:hover~label {
        color: #FDDA0D;
    }
    </style>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <header>
        <div class="header-wrapper">
            <a href="website.php?id=<?= $row['id_web'] ?>" class="logo_header"><i class="fa fa-arrow-left"></i></a>
        </div>
    </header>

    <div class="container">
        <div class="container-stars">
            <div class="rating-wrap">
                <h2>Berikan Rating untuk <?= $row['domain_web'] ?></h2>
                <form action=" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                    enctype="multipart/form-data">
                    <div class="center">
                        <fieldset class="rating">
                            <input type="radio" id="star5" name="rating" value="5" /><label for="star5" class="full"
                                title="Awesome"></label>
                            <input type="radio" id="star4" name="rating" value="4" /><label for="star4"
                                class="full"></label>
                            <input type="radio" id="star3" name="rating" value="3" /><label for="star3"
                                class="full"></label>
                            <input type="radio" id="star2" name="rating" value="2" /><label for="star2"
                                class="full"></label>
                            <input type="radio" id="star1" name="rating" value="1" /><label for="star1"
                                class="full"></label>
                        </fieldset>
                    </div>
                    <h4 id="rating-value"></h4>
            </div>
            <div class="form-group <?php echo (!empty($review_err)) ? 'has-error' : ''; ?>">
                <label>Review</label>
                <textarea name="review" class="form-control" placeholder="Tuliskan review di sini.."></textarea>
                <span class="invalid-feedback"><?php echo $review_err;?></span>
            </div>
            <input type="submit" class="btn btn-primary" value="Kirim">
            <a href="website.php?id=<?php echo $id; ?>" class="btn btn-secondary ml-2">Batal</a>
            </form>
        </div>
    </div>


    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    let star = document.querySelectorAll('input');
    let showValue = document.querySelector('#rating-value');

    for (let i = 0; i < star.length; i++) {
        star[i].addEventListener('click', function() {
            i = this.value;

            showValue.innerHTML = i + " out of 5";
        });
    }
    </script>
</body>

</html>