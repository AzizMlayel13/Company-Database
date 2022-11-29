<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$client_id = $client_name = $branch_id = "";
$client_id_err = $client_name_err = $branch_id_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


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
        // Prepare an insert statement
        $sql = "INSERT INTO client (client_id, client_name, branch_id) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_client_id, $param_client_name, $param_branch_id);



            // Set parameters
            $param_client_id = $client_id;
            $param_client_name = $client_name;
            $param_branch_id = $branch_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: indexC.php");
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
                    <p>Please fill this form and submit to add Client record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

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


                        <input type="submit" class="btn btn-primary translate" value="Submit">
                        <a href="indexC.php" class="btn btn-secondary ml-2 translate">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>