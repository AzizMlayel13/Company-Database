<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$branch_id = $branch_name = $mgr_id = "";
$branch_id_err = $branch_name_err = $mgr_id_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // Validate branch_id
    $input_branch_id = trim($_POST["branch_id"]);
    if (empty($input_branch_id)) {
        $branch_id_err = "Please enter the branch_id.";
    } elseif (!ctype_digit($input_branch_id)) {
        $branch_id_err = "Please enter a positive integer value.";
    } else {
        $branch_id = $input_branch_id;
    }

    // Validate branch_name
    $input_branch_name = trim($_POST["branch_name"]);
    if (empty($input_branch_name)) {
        $branch_name_err = "Please enter a Branch name.";
    } elseif (!filter_var($input_branch_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $branch_name_err = "Please enter a valid branch name.";
    } else {
        $branch_name = $input_branch_name;
    }

    // Validate mgr_id
    $input_mgr_id = trim($_POST["mgr_id"]);
    if (empty($input_mgr_id)) {
        $mgr_id_err = "Please enter the mgr_id.";
    } elseif (!ctype_digit($input_mgr_id)) {
        $mgr_id_err = "Please enter a positive integer value.";
    } else {
        $mgr_id = $input_mgr_id;
    }

    // Check input errors before inserting in database
    if (
        empty($branch_id_err)
        && empty($branch_name_err)
        &&  empty($mgr_id_err)
    ) {
        // Prepare an insert statement
        $sql = "INSERT INTO branch (branch_id, branch_name, mgr_id) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_branch_id, $param_branch_name, $param_mgr_id);



            // Set parameters
            $param_branch_id = $branch_id;
            $param_branch_name = $branch_name;
            $param_mgr_id = $mgr_id;


            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: indexB.php");
                exit();
            } else {
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
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
                    <p>Please fill this form and submit to add Branch record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Branch ID</label>
                            <input type="text" name="branch_id" class="form-control <?php echo (!empty($branch_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $branch_id; ?>">
                            <span class="invalid-feedback"><?php echo $branch_id_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Branch Name</label>
                            <input type="text" name="branch_name" class="form-control <?php echo (!empty($branch_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $branch_name; ?>">
                            <span class="invalid-feedback"><?php echo $branch_name_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Manager ID</label>
                            <input type="text" name="mgr_id" class="form-control <?php echo (!empty($mgr_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $mgr_id; ?>">
                            <span class="invalid-feedback"><?php echo $mgr_id_err; ?></span>
                        </div>


                        <input type="submit" class="btn btn-primary translate" value="Submit">
                        <a href="indexB.php" class="btn btn-secondary ml-2 translate">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>