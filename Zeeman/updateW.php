<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$id = $client_id = $total_sales =  "";
$id_err = $client_id_err = $otal_sales_err =  "";


// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    // Validate Employee id
    $input_id = trim($_POST["id"]);
    if (empty($input_id)) {
        $id_err = "Please enter the id.";
    } elseif (!ctype_digit($input_id)) {
        $id_err = "Please enter a positive integer value.";
    } else {
        $id = $input_id;
    }

    // Validate client_id
    $input_client_id = trim($_POST["client_id"]);
    if (empty($input_client_id)) {
        $client_id_err = "Please enter the client_id.";
    } elseif (!ctype_digit($input_client_id)) {
        $client_id_err = "Please enter a positive integer value.";
    } else {
        $client_id = $input_client_id;
    }

    // Validate total_sales
    $input_total_sales = trim($_POST["total_sales"]);
    if (empty($input_total_sales)) {
        $total_sales_err = "Please enter total sales.";
    } elseif (!ctype_digit($input_total_sales)) {
        $total_sales_err = "Please enter a positive integer value.";
    } else {
        $total_sales = $input_total_sales;
    }

    // Check input errors before inserting in database
    if (
        empty($id_err)
        && empty($client_id_err)
        &&  empty($otal_sales_err)
    ) {
        // Prepare an update statement
        $sql = "UPDATE works_with SET id=?, total_sales=? WHERE client_id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_id, $param_total_sales, $param_client_id);

            // Set parameters
            $param_client_id = $client_id;
            $param_id = $id;
            $param_total_sales = $total_sales;


            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: indexW.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM works_with WHERE client_id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $client_id = $row["client_id"];
                    $id = $row["id"];
                    $total_sales = $row["total_sales"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: rgb(16, 16, 16);
        }

        .wrapper {
            background: linear-gradient(90deg, #e3ffe7 0%, #d9e7ff 100%);
            border-radius: 30px 30px 30px 30px;
            width: 600px;
            margin: 0 auto;
            margin-top: 50px;
        }

        .translate {
            transform: translate(0px, -11px);
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the Client record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">


                        <div class="form-group">
                            <label>Employee ID</label>
                            <input type="text" name="id" class="form-control <?php echo (!empty($id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $id; ?>">
                            <span class="invalid-feedback"><?php echo $id_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Client ID</label>
                            <input type="text" name="client_id" class="form-control <?php echo (!empty($client_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $client_id; ?>">
                            <span class="invalid-feedback"><?php echo $client_id_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Total Sales</label>
                            <input type="text" name="total_sales" class="form-control <?php echo (!empty($total_sales_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $total_sales; ?>">
                            <span class="invalid-feedback"><?php echo $total_sales_err; ?></span>
                        </div>

                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary translate" value="Submit">
                        <a href="indexW.php" class="btn btn-secondary ml-2 translate">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>