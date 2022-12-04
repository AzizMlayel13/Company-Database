<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="logo.css">

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light navbar-color">
        <a class="navbar-brand" href="#">Database Tables</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="welcome.php">Home<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Employees</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="indexB.php">Branch</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="indexC.php">Client</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="indexW.php">Works With</a>
                </li>
            </ul>
        </div>
    </nav>

    <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>

    <section>
        <svg viewBox="0 0 1950 600">
            <symbol id="s-text">
                <text text-anchor="middle" x="50%" y="60%">COMPANY DATABASE</text>
                <text text-anchor="middle" x="51%" y="60%">COMPANY DATABASE</text>
            </symbol>

            <g class="g-ants">
                <use xlink:href="#s-text" class="text-copy"></use>
                <use xlink:href="#s-text" class="text-copy"></use>
                <use xlink:href="#s-text" class="text-copy"></use>
                <use xlink:href="#s-text" class="text-copy"></use>
                <use xlink:href="#s-text" class="text-copy"></use>
            </g>
        </svg>
    </section>

    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
</body>

</html>