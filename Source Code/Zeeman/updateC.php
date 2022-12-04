<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$client_id = $client_name = $branch_id = "";
$client_id_err = $client_name_err = $branch_id_err = "";


// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    // Validate client_id
    $input_client_id = trim($_POST["client_id"]);
    if (empty($input_client_id)) {
        $client_id_err = "Please enter the client_id.";
    } elseif (!ctype_digit($input_client_id)) {
        $client_id_err = "Please enter a positive integer value.";
    } else {
        $client_id = $input_client_id;
    }

    // Validate client_name
    $input_client_name = trim($_POST["client_name"]);
    if (empty($input_client_name)) {
        $client_name_err = "Please enter a client name.";
    } elseif (!filter_var($input_client_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $client_name_err = "Please enter a valid client name.";
    } else {
        $client_name = $input_client_name;
    }

    // Validate branch_id
    $input_branch_id = trim($_POST["branch_id"]);
    if (empty($input_branch_id)) {
        $branch_id_err = "Please enter the branch_id.";
    } elseif (!ctype_digit($input_branch_id)) {
        $branch_id_err = "Please enter a positive integer value.";
    } else {
        $branch_id = $input_branch_id;
    }

    // Check input errors before inserting in database
    if (
        empty($client_id_err)
        && empty($client_name_err)
        &&  empty($branch_id_err)
    ) {
        // Prepare an update statement
        $sql = "UPDATE client SET client_name=?, branch_id=? WHERE client_id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi",$param_client_name, $param_branch_id,$param_client_id);

            // Set parameters
            $param_client_id = $client_id;
            $param_client_name = $client_name;
            $param_branch_id = $branch_id;


            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: indexC.php");
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
        $sql = "SELECT * FROM client WHERE client_id = ?";
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
                    $client_name = $row["client_name"];
                    $branch_id = $row["branch_id"];
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
                            <label>Client ID</label>
                            <input type="text" name="client_id" class="form-control <?php echo (!empty($client_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $client_id; ?>">
                            <span class="invalid-feedback"><?php echo $client_id_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Client Name</label>
                            <input type="text" name="client_name" class="form-control <?php echo (!empty($client_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $client_name; ?>">
                            <span class="invalid-feedback"><?php echo $client_name_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Branch ID</label>
                            <input type="text" name="branch_id" class="form-control <?php echo (!empty($branch_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $branch_id; ?>">
                            <span class="invalid-feedback"><?php echo $branch_id_err; ?></span>
                        </div>

                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary translate" value="Submit">
                        <a href="indexC.php" class="btn btn-secondary ml-2 translate">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>