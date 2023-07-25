<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- <link rel="stylesheet" href="//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"> -->

    <title>Welcome to ForumCode - Coding Forums</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php';?>
    <?php include 'partials/_header.php';?>
    <?php
    $id = $_GET['catid'];
    $sql = "SELECT * FROM `iinfo` WHERE iinfo_id=$id";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $catname = $row['iinfo_name'];
        $catdesc = $row['iinfo_description'];
    }
    ?>
    <?php
        $showAlert = false;
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method=='POST') {
        $th_title = $_POST['title'];
        $th_desc = $_POST['desc'];

        $th_title = str_replace("<", "&lt;", $th_title);
        $th_title = str_replace("<", "&lt;", $th_title);
        
        $th_desc = str_replace("<", "&lt;", $th_desc);
        $th_desc = str_replace("<", "&lt;", $th_desc);
        
        $sno = $_POST['sno']; 
        $sql = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) VALUES ('$th_title', '$th_desc', '$id', '$sno', current_timestamp())";

        $result = mysqli_query($conn, $sql);
        $showAlert = true;
        if ($showAlert) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Your thread has been successfully added. Please Wait for community to respond.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }

    }
    ?>


    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Welcome to <?php echo $catname;?> forum</h1>
            <p class="col-md-8 fs-4"><?php echo $catdesc;?></p>
            <hr class="my4">
            <p>This is a peer to peer forum. No Spam / Advertising / Self-promote in the forums. Do not post
                copyright-infringing material.Do not post “offensive” posts, links or images. Do not cross post
                questions. Do not PM users asking for help. Remain respectful of other members at all times.</p>
            <button class="btn btn-success btn-lg" type="button">Learn more</button>
        </div>
    </div>
    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true) {
    echo '<div class="container">
        <h1 class="py-2">Start a Discussion</h1>
    <form action="'. $_SERVER["REQUEST_URI"] . '" method="post">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Problem Title</label>
            <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">Keep your title as short and crisp as possible.</div>
        </div>
        <input type="hidden" name="sno" value="'. $_SESSION["sno"]. '">
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Elaborate Your Problem</label>
            <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>';
    }
    else {
        echo'
        <div class="container">
        <h1 class="py-2">Start a Discussion</h1>
        <p class="lead">You are not loggged in. Please Login to be able to start a discussion</p>
        </div>';
    }
?>
    <div class="container my-5">
        <h2>Browse Questions</h2>
        <?php
            $id = $_GET['catid'];
            $sql = "SELECT * FROM `threads` WHERE thread_cat_id=$id";
            $result = mysqli_query($conn, $sql);
            $noResult = true;
            while ($row = mysqli_fetch_assoc($result)) {
            $noResult = false;
            $id = $row['thread_id'];
            $title = $row['thread_title'];
            $desc = $row['thread_desc'];
            $thread_time = $row['timestamp'];
            $thread_user_id = $row['thread_user_id'];
            $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            
    
    
            echo '<div class="media my-3 d-flex">
            <img src="img/userdefault.png" width="37px" height="40px" class="mr-3 mx-3" alt="...">
            <div class="media-body">'.
            '<h5 class="mt-0"> <a class="text-decoration-none text-dark" href="thread.php?threadid=' . $id. '">'. $title . ' </a></h5>
            ' . $desc . '<div class="fw-bold my-0">'.$row2['user_email'].' at '. $thread_time. '</div>'.
                '</div> 
                </div>';
            }
            if ($noResult) {
                echo '<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </symbol>
                <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
            </symbol>
            <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </symbol>
            </svg>

            <div class="alert alert-primary d-flex align-items-center my-4" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
            <div>
                Be The first person to Ask a Question
            </div>
            </div>';
            }
            ?>
            
    </div>




    <?php include 'partials/_footer.php';?>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <!-- <script src="//cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
<!--   
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
     -->
</body>

</html>