<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $birth_day = $sex = $salary = $branch_id = "";
$name_err = $birth_day_err = $sex_err =  $salary_err = $branch_id_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate name
    $input_name = trim($_POST["name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $name_err = "Please enter a valid name.";
    } else {
        $name = $input_name;
    }

    // Validate birth_day
    $input_birth_day = trim($_POST["birth_day"]);
    if (empty($input_birth_day)) {
        $$birth_day_err = "Please enter the birthday (YYYY-MM-DD)";
    } else {
        $birth_day = $input_birth_day;
    }

    // Validate sex
    $input_sex = trim($_POST["sex"]);
    if (empty($input_sex)) {
        $sex_err = "Please enter the sex.";
    } elseif ((!$input_sex == 'M') or (!$input_sex == 'F')) {
        $sex_err = "Please enter M for Male or F for female.";
    } else {
        $sex = $input_sex;
    }

    // Validate salary
    $input_salary = trim($_POST["salary"]);
    if (empty($input_salary)) {
        $salary_err = "Please enter the salary amount.";
    } elseif (!ctype_digit($input_salary)) {
        $salary_err = "Please enter a positive integer value.";
    } else {
        $salary = $input_salary;
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
        empty($name_err)
        && empty($birth_day_err)
        &&  empty($sex_err)
        &&  empty($salary_err)
        &&  empty($branch_id_err)
    ) {
        // Prepare an insert statement
        $sql = "INSERT INTO employees (name, birth_day, sex, salary, branch_id) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_name, $param_birth_day, $param_sex, $param_salary, $param_branch_id);

            // Set parameters
            $param_name = $name;
            $param_birth_day = $birth_day;
            $param_sex = $sex;
            $param_salary = $salary;
            $param_branch_id = $branch_id;


            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: index.php");
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
        .translate{
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
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Birthday</label>
                            <textarea name="birth_day" class="form-control <?php echo (!empty($birth_day_err)) ? 'is-invalid' : ''; ?>"><?php echo $birth_day; ?></textarea>
                            <span class="invalid-feedback"><?php echo $birth_day_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>sex</label>
                            <textarea name="sex" class="form-control <?php echo (!empty($sex_err)) ? 'is-invalid' : ''; ?>"><?php echo $sex; ?></textarea>
                            <span class="invalid-feedback"><?php echo $sex_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Salary</label>
                            <input type="text" name="salary" class="form-control <?php echo (!empty($salary_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary; ?>">
                            <span class="invalid-feedback"><?php echo $salary_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Branch ID</label>
                            <input type="text" name="branch_id" class="form-control <?php echo (!empty($branch_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $branch_id; ?>">
                            <span class="invalid-feedback"><?php echo $branch_id_err; ?></span>
                        </div>

                        <input type="submit" class="btn btn-primary translate" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2 translate">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>