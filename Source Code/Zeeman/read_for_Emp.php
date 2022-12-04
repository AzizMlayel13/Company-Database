<?php
session_start();
$emp_id = $_SESSION['id'];
$ei = strval($emp_id);

// Include config file
require_once "config.php";
// Prepare a select statement
$sql = "SELECT * FROM employees WHERE id = $ei";

if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            // Retrieve individual field value
            $name = $row["name"];
            $birth_day = $row["birth_day"];
            $sex = $row["sex"];
            $salary = $row["salary"];
            $branch = $row["branch_id"];
        }
    }
} else {
    echo "Oops! Something went wrong. Please try again later.";
}


// Close statement
mysqli_stmt_close($stmt);

// Close connection
mysqli_close($link);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url("https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900");

        body {
            background-color: rgb(16, 16, 16);
        }

        .wrapper {
            background: linear-gradient(90deg, #e3ffe7 0%, #d9e7ff 100%);
            border-radius: 30px 30px 30px 30px;
            width: 600px;
            margin: 0 auto;
            margin-top: 100px;
        }

        .navbar-color {
            font: 14px Poppins;
            font-weight: 600;
            background: linear-gradient(90deg, rgb(241, 0, 139), rgb(19, 0, 95));
        }
    </style>
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
                    <a class="nav-link" href="welcomeE.php">Home<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index_for_Emp.php">Employees</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="indexB_for_Emp.php">Branch</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="indexC_for_Emp.php">Client</a>
                </li>
                <li class="nav-item">
                    <a href="read_for_Emp.php" class="nav-link">View Record</a>
                </li>

            </ul>
        </div>
    </nav>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">View Record</h1>

                    <div class="form-group">
                        <label>Name</label>
                        <p><b><?php echo $row["name"]; ?></b></p>
                    </div>

                    <div class="form-group">
                        <label>Birthday</label>
                        <p><b><?php echo $row["birth_day"]; ?></b></p>
                    </div>

                    <div class="form-group">
                        <label>Sex</label>
                        <p><b><?php echo $row["sex"]; ?></b></p>
                    </div>

                    <div class="form-group">
                        <label>Salary</label>
                        <p><b><?php echo $row["salary"]; ?></b></p>
                    </div>

                    <div class="form-group">
                        <label>Branch ID</label>
                        <p><b><?php echo $row["branch_id"]; ?></b></p>
                    </div>

                    <p><a href="welcomeE.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>