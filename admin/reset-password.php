<?php
session_start();

// Check if verification code is set in session
if (!isset($_SESSION['verification_code'])) {
    // Redirect to login page or show an error message
    header("Location: login.php");
    exit();
}

// Include the Dotenv library
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Manually load environment variables from the .env file
$env = array();
foreach (file(__DIR__ . '/../.env') as $line) {
    // Skip empty lines and comments
    if (trim($line) === '' || $line[0] === '#') {
        continue;
    }

    // Split the line into key and value
    $parts = explode('=', $line, 2);
    if (count($parts) === 2) {
        $env[trim($parts[0])] = trim($parts[1]);
    }
}

// Assigning environment variables to PHP variables using the parsed $env array
$host = $env['DB_HOST'] ?? 'localhost';
$port = $env['DB_PORT'] ?? 3306;
$dbname = $env['DB_NAME'] ?? '';
$user = $env['DB_USER'] ?? '';
$pass = $env['DB_PASS'] ?? '';

// Create a database connection
try {
    // Check if the username and password are not empty before establishing the connection
    if (!empty($user) && !empty($pass)) {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } else {
        // If the username or password is empty, display an error message
        throw new Exception("Database username or password not provided.");
    }
} catch(PDOException $e) {
    // Log and display the error message
    error_log("Database connection error: " . $e->getMessage());
    die("ERROR: Could not connect. " . $e->getMessage());
} catch(Exception $e) {
    // Handle any other exceptions (e.g., missing username/password)
    error_log("Database connection error: " . $e->getMessage());
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Check if the password reset form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve verification code and new password from the form
    $verificationCode = $_POST['verification_code'];
    $newPassword = $_POST['new_password'];

    // Verify the verification code
    if ($verificationCode === $_SESSION['verification_code']) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        try {
            // Prepare SQL statement to update the password
            $stmt = $pdo->prepare("UPDATE admin_users SET password = :hashedPassword");

            // Bind hashed password
            $stmt->bindParam(':hashedPassword', $hashedPassword);

            // Execute the SQL statement
            if ($stmt->execute()) {
                // Password updated successfully
                // Redirect the user to the login page
                header("Location: login.php");
                exit();
            } else {
                // Error executing the SQL statement
                $error = "Error updating password.";
            }
        } catch (PDOException $e) {
            // Handle database errors
            $error = "Error updating password: " . $e->getMessage();
        }
    } else {
        // Invalid verification code
        $error = "Invalid verification code ,double check your email inbox or spam.";
    }
}

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Access environment variables
$reCaptchaSiteKey = $_ENV['RECAPTCHA_SITE_KEY'] ?? '';
$reCaptchaSecretKey = $_ENV['RECAPTCHA_SECRET_KEY'] ?? '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if reCAPTCHA is enabled
    if (!empty($reCaptchaSecretKey)) {
        // Validate reCAPTCHA only if the secret key is not empty
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
        if (!empty($recaptchaResponse)) {
            $recaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$reCaptchaSecretKey&response=$recaptchaResponse");
            $recaptchaResult = json_decode($recaptcha);

            if (!$recaptchaResult->success) {
                $error = "reCAPTCHA verification failed. Please try again.";
            } else {
                
            }
        } else {
            $error = "reCAPTCHA challenge is required. Please complete the reCAPTCHA challenge.";
        }
    } else {
       
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="description" content="">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link type="text/css" href="/static/assets/css/styles.min.css" rel="stylesheet">
  </head>
  <body>
    <header class="pt20">
      <div class="container"><a class="button" href="#"><img class="logo" src="/admin/uploads/logo.png" alt="" height="24"></a></div>
    </header>
    <main>
      <div class="py72">
        <div class="container maw640">
          <div class="bgc12 p24 p72-M br12">
    <title>Reset Password</title>
    <meta name="robots" content="noindex, nofollow">
    <!-- Add the reCAPTCHA script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        // Function to validate reCAPTCHA before form submission
        function validateRecaptcha(event) {
            // Check if reCAPTCHA is enabled (i.e., site key is not empty)
            var siteKey = "<?php echo $reCaptchaSiteKey; ?>";
            if (siteKey !== '') {
                event.preventDefault(); // Prevent form submission if reCAPTCHA is enabled

                // Check if reCAPTCHA response is available
                var recaptchaResponse = grecaptcha.getResponse();
                if (recaptchaResponse.length == 0) {
                    // If reCAPTCHA challenge is not completed, display error message
                    document.getElementById('recaptcha-error').innerText = 'Please complete the reCAPTCHA challenge.';
                } else {
                    // If reCAPTCHA challenge is completed, submit the form
                    document.getElementById('reset-form').submit();
                }
            }
        }
    </script>
</head>
<body>
    <h2>Reset Your Password</h2>
    <?php if (isset($error)) : ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" id="reset-form" onsubmit="validateRecaptcha(event)">
        <label for="verification_code">Verification Code:</label>
        <input type="text" id="verification_code" name="verification_code" required><br>
       
        <!-- Error message for reCAPTCHA validation -->
        <p id="recaptcha-error" style="color: red;"></p>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br>
         <!-- Add the reCAPTCHA widget -->
        <div class="g-recaptcha" data-sitekey="<?php echo $reCaptchaSiteKey; ?>"></div>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>