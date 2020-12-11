<?php

try {
    $token = bin2hex(random_bytes(64));
    if(isset($_SESSION['token'])) unset($_SESSION['token']);
    $_SESSION['token'] = $token;
} catch (Exception $e) {
    print $e->getMessage();
}
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php  isset($title) ? print $title : print 'Students Boards'; ?></title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="nav-link" href="/">Home</a> |
    <a class="nav-link" href="/boards/1/students">Board 1</a> |
    <a class="nav-link" href="/boards/2/students">Board 2</a>
</nav>

<div class="p-3">
    <?php
    if(isset($_SESSION['error']) && !empty($_SESSION['error'])){
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>ERROR! </strong> <?php print $_SESSION['error']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
    }

    if(isset($_SESSION['message']) && !empty($_SESSION['message'])){
        ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>SUCCESS! </strong> <?php print $_SESSION['message']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
    }
    $_SESSION['error'] = $_SESSION['message'] = null;
    ?>
</div>
