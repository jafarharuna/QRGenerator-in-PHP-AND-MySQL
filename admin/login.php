<?php
use PHPMailer\PHPMailer\PHPMailer;

session_start();
$error = '';





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
// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Access environment variables
$reCaptchaSiteKey = $_ENV['RECAPTCHA_SITE_KEY'] ?? '';
$reCaptchaSecretKey = $_ENV['RECAPTCHA_SECRET_KEY'] ?? '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if reCAPTCHA is enabled
    if (!empty($reCaptchaSiteKey)) {
        // Validate reCAPTCHA only if the key is not empty
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
        if (!empty($recaptchaResponse)) {
            $recaptchaSecret = $_ENV['RECAPTCHA_SECRET_KEY'];
            $recaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
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



// Assigning environment variables to PHP variables using the parsed $env array
$host = $env['DB_HOST'] ?? 'localhost';
$port = $env['DB_PORT'] ?? 3306;
$dbname = $env['DB_NAME'] ?? '';
$user = $env['DB_USER'] ?? '';
$pass = $env['DB_PASS'] ?? '';

// Create a database connection
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Log and display the error message
    error_log("Database connection error: " . $e->getMessage());
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

// Validate and sanitize input
$username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);



    // Prepare SQL statement to fetch the user
    $stmt = $pdo->prepare("SELECT id, username, password FROM admin_users WHERE username = :username");
    $stmt->bindParam(':username', $username);

    if($stmt->execute()) {
        if($stmt->rowCount() == 1) {
            if($row = $stmt->fetch()) {
                $id = $row['id'];
                $hashedPassword = $row['password'];
                if(password_verify($password, $hashedPassword)) {
                    // Password is correct, start a new session
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $username;
                    $_SESSION["loggedin"] = true;

                    // Redirect user to the dashboard page
                    header('Location: dashboard.php');
                    exit();
                } else {
                    // Display an error message if password is not valid
                    $error = 'Invalid username or password.';
                }
            }
        } else {
            // Display an error message if username doesn't exist
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Oops! Something went wrong. Please try again later.';
    }
}

// Function to generate a random verification code
function generateVerificationCode($length = 6) {
    return bin2hex(random_bytes($length));
}

// Function to send email with verification code
function sendVerificationEmail($email, $verification_code) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer();

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST']; // SMTP server address
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME']; // SMTP username
        $mail->Password = $_ENV['SMTP_PASSWORD']; // SMTP password
        $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
        $mail->Port = $_ENV['SMTP_PORT']; // Port number

        // Sender info
        $mail->setFrom($_ENV['SMTP_USERNAME'], 'Reset Your password! QRGen');
        
        // Recipient
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Verification Code';
        $mail->Body = 'Your verification code for password reset is: ' . $verification_code;

        // Send email
        if ($mail->send()) {
            return true; // Email sent successfully
        } else {
            return false; // Email could not be sent
        }
    } catch (Exception $e) {
        return false; // Exception occurred, email could not be sent
    }
}

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Access SMTP environment variables
$smtpHost = $_ENV['SMTP_HOST'] ?? '';
$smtpUsername = $_ENV['SMTP_USERNAME'] ?? '';
$smtpPassword = $_ENV['SMTP_PASSWORD'] ?? '';
$smtpPort = $_ENV['SMTP_PORT'] ?? '';
$smtpEncryption = $_ENV['SMTP_ENCRYPTION'] ?? '';

// Check if any of the SMTP variables are empty
$smtpConfigEmpty = empty($smtpHost) || empty($smtpUsername) || empty($smtpPassword) || empty($smtpPort) || empty($smtpEncryption);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['forgot_password_email'])) {
        // Process forgot password request
        $email = $_POST['forgot_password_email'];

        // Check if the email exists in the database
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generate a unique verification code
            $verification_code = generateVerificationCode();

            // Store verification code in session
            $_SESSION['verification_code'] = $verification_code;
            $_SESSION['verification_email'] = $email;
            $_SESSION['verification_time'] = time(); // Store current time for expiration check

            // Send email with verification code
            if (!sendVerificationEmail($email, $verification_code)) {
                $error = 'Error: Email could not be sent.';
            }

            // Redirect to verification.php for code verification
            header("Location: reset-password.php");
            exit();
        } else {
            $error = 'Error: Your email is not registered with us.';
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="shortcut icon" type="image/png" href="/admin/uploads/favicon.png" />
  <link type="text/css" href="/static/assets/css/styles.min.css" rel="stylesheet">
  <meta name="robots" content="noindex, nofollow">
  <!-- Add the reCAPTCHA script -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>

<header class="pt20">
  <div class="container">
    <a class="button" href="#"><img class="logo" src="/admin/uploads/logo.png" alt="" height="24"></a>
  </div>
</header>

<main>
  <div class="py72">
    <div class="container maw640">
      <div class="bgc12 p24 p72-M br12">
        <div class="R df jcsb aife mb20 mb32-M gy8">
          <div class="C-S">
            <div id="recaptcha-error" class="text-danger"></div> <!-- Error message for reCAPTCHA -->

            <?php if($error): ?>
              <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Login form with reCAPTCHA -->
            <form id="login-form" class="login-form" method="post" action="login.php" onsubmit="validateRecaptcha(event)">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control input" id="username" name="username" aria-describedby="emailHelp" required>
              </div>
              <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                  <input type="password" class="form-control input pr32" id="password" name="password" required>
                  <button class="button c3 pa t8 r8" type="button">
                    <svg class="icon wh20">
                      <use xlink:href="/static/assets/img/icons/sprite/svg/sprite.svg#m-19"></use>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- reCAPTCHA widget -->
              <div class="g-recaptcha" data-sitekey="<?php echo $reCaptchaSiteKey; ?>"></div>

              <button type="submit" class="button button-lg bgc3 w100p fs32 fs40-M fw6 lh1">Sign In</button>
            </form>

            <!-- Forgot Password section -->
           <?php if (!$smtpConfigEmpty): ?>
  <p class="tar"><a class="link fs14" href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot Password?</a></p>
<?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- HTML code for Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot Password?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php if(!isset($show_reset_password_form)) { ?>
        <!-- Form to enter email for password reset -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <div class="mb-3">
            <label for="forgot_password_email" class="form-label">Enter your email address:</label>
            <input type="email" class="form-control" id="forgot_password_email" name="forgot_password_email" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
        

<!-- Include Bootstrap JavaScript (you can change the version as needed) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script src="../assets/libs/jquery/dist/jquery.min.js"></script>
<script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="/static/assets/js/scripts.min.js"></script>

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
        document.getElementById('login-form').submit();
      }
    }
  }
</script>
<style>

/* Style for Forgot Password Modal */
.modal-content {
  background-color: #fff;
  border-radius: 12px;
}

.modal-header {
  background-color: #f8f9fa; /* Light gray */
  border-bottom: none;
}

.modal-title {
  color: #343a40; /* Dark gray */
}

.modal-body {
  padding: 20px;
}

.form-label {
  font-weight: bold;
}

.form-control {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ced4da; /* Gray border */
  border-radius: 5px;
}

.btn-primary {
  background-color: #007bff; /* Blue */
  border: none;
  border-radius: 5px;
  color: #fff; /* White */
  padding: 10px 20px;
  cursor: pointer;
}

.btn-primary:hover {
  background-color: #0056b3; /* Darker blue on hover */
}

/* Close button (X) of the modal */
.btn-close {
  color: #6c757d; /* Gray */
  font-size: 1.5rem;
  margin-top: -10px;
  position: fixed;
  right: 10px;
  top: 10px;
  z-index: 1;
}

.btn-close:hover {
  color: #343a40; /* Dark gray on hover */
}



/* Responsive styles */
@media (max-width: 576px) {
  .modal-content {
    border-radius: 0;
  }
}
</style>


</body>
</html>
