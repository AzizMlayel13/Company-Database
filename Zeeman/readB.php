<?php
// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Include config file
    require_once "config.php";

    // Prepare a select statement
    $sql = "SELECT * FROM branch WHERE branch_id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $branch_id = $row["branch_id"];
                $branch_name = $row["branch_name"];
                $mgr_id = $row["mgr_id"];

                mysqli_stmt_close($stmt);

                // Close connection
                mysqli_close($link);
            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement

} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
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
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">View Record</h1>


                    <div class="form-group">
                        <label>Branch ID</label>
                        <p><b><?php echo $row["branch_id"]; ?></b></p>
                    </div>

                    <div class="form-group">
                        <label>Branch Name</label>
                        <p><b><?php echo $row["branch_name"]; ?></b></p>
                    </div>


                    <div class="form-group">
                        <label>Manager ID</label>
                        <p><b><?php echo $row["mgr_id"]; ?></b></p>
                    </div>

                    <p><a href="indexB.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>