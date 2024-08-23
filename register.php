<?php
// // Enable error reporting for debugging
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Database connection settings
$host = 'localhost';
$db = 'relasisdb'; // Your database name
$user = 'username'; // Your database username
$pass = 'password'; // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize it
    $name = trim($_POST['name']);
    $matric_number = trim($_POST['matric_number']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    // Debugging output
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Validate inputs
    if (empty($name) || empty($matric_number) || empty($email) || empty($password) || empty($repeat_password)) {
        echo "All fields are required.";
        exit;
    }

    if ($password !== $repeat_password) {
        echo "Passwords do not match.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

  // Prepare and execute SQL query to insert user
try {
    $stmt = $pdo->prepare("INSERT INTO users (name, matric_number, email, password, role) VALUES (:name, :matric_number, :email, :password, :role)");
    $stmt->execute([
        ':name' => $name,
        ':matric_number' => $matric_number,
        ':email' => $email,
        ':password' => $password_hash,
        ':role' => $_POST['role'] // Ensure this is being captured correctly
    ]);
    echo "Registration successful!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form method="POST" action="">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="name" placeholder="Full Name">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="matric_number" placeholder="Matric Number">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" name="email" placeholder="Email Address">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" name="password" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" name="repeat_password" placeholder="Repeat Password">
                                </div>
                                <div class="form-group">
                                    <label for="role">Select Role:</label>
                                    <select class="form-control form-control-user" id="role" name="role">
                                        <!-- <option value="admin">Admin</option> -->
                                        <option value="staf">Staf</option>
                                        <option value="student">Student</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">Register</button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.html">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.html">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
