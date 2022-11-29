<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$branch_id = $branch_name = $mgr_id = "";
$branch_id_err = $branch_name_err = $mgr_id_err = "";


// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

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
        // Prepare an update statement
        $sql = "UPDATE branch SET branch_name=?, mgr_id=? WHERE branch_id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_branch_name, $param_mgr_id,$branch_id);

            // Set parameters
            $param_branch_id = $branch_id;
            $param_branch_name = $branch_name;
            $param_mgr_id = $mgr_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: indexB.php");
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
        $sql = "SELECT * FROM branch WHERE branch_id = ?";
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

                    $branch_id = $row["branch_id"];
                    $branch_name = $row["branch_name"];
                    $mgr_id = $row["mgr_id"];
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
                    <p>Please edit the input values and submit to update the Branch record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

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

                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary translate" value="Submit">
                        <a href="indexB.php" class="btn btn-secondary ml-2 translate">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>