<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$id = $client_id = $total_sales =  "";
$id_err = $client_id_err = $total_sales_err =  "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
        &&  empty($total_sales_err)
    ) {
        // Prepare an insert statement
        $sql = "INSERT INTO works_with (id, client_id, total_sales) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_id, $param_client_id, $param_total_sales);



            // Set parameters
            $param_id = $id;
            $param_client_id = $client_id;
            $param_total_sales = $total_sales;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: indexW.php");
                exit();
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add Works With record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

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


                        <input type="submit" class="btn btn-primary translate" value="Submit">
                        <a href="indexW.php" class="btn btn-secondary ml-2 translate">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>