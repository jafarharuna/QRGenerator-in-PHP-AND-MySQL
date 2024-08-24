<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure the "uploads" directory exists
$uploadDirectory = "uploads/";

if (!is_dir($uploadDirectory)) {
    mkdir($uploadDirectory, 0755, true);
}

// Ensure the "uploads" directory exists
$uploadDirectory = "pages/";

if (!is_dir($uploadDirectory)) {
    mkdir($uploadDirectory, 0755, true);
}


// Load existing .env values
$envPath = __DIR__ . '/../.env';
$env = array();
if (file_exists($envPath)) {
    foreach (file($envPath) as $line) {
        if (trim($line) === '' || $line[0] === '#') {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $env[trim($key)] = trim($value, "\" \t\n\r\0\x0B");
    }
}












// Initialize variables with default values
$tawkToDirectLink = $env['TAWKTO_WIDGET_LINK']?? '';
$reCaptchaSiteKey = $env['RECAPTCHA_SITE_KEY'] ?? '';
$reCaptchaSecretKey = $env['RECAPTCHA_SECRET_KEY'] ?? '';
$googleAnalyticsMeasurementID = $env['GOOGLE_ANALYTICS']?? '';
$smtp_host = $env['SMTP_HOST'] ?? '';
$smtp_username = $env['SMTP_USERNAME'] ?? '';
$smtp_password = $env['SMTP_PASSWORD'] ?? '';
$smtp_port = $env['SMTP_PORT'] ?? '';
$smtp_encryption = $env['SMTP_ENCRYPTION'] ?? '';





$gtranslater_enabled = $env['GTRANSLATER'] === 'true';
$darkmode_enabled = $env['DARKMODE'] === 'true';
$blog_enabled = $env['BLOG'] === 'true';
$faq_enabled = $env['FAQ'] === 'true';
$featureboxes_enabled = $env['FEATURE_BOXES'] === 'true';
$contact = $env['contact'] === 'true';







// Ensure the "uploads" directory exists
$uploadDirectory = "uploads/";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_logo_favicon"])) {
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    // Handle Logo Upload
    if (isset($_FILES["logo"]) && $_FILES["logo"]["error"] == UPLOAD_ERR_OK) {
        $logoFileName = "logo.png"; // Rename the file to logo.png
        $logoPath = $uploadDirectory . $logoFileName;
        move_uploaded_file($_FILES["logo"]["tmp_name"], $logoPath);
    }

    // Handle Light Logo Upload
    if (isset($_FILES["logodark"]) && $_FILES["logodark"]["error"] == UPLOAD_ERR_OK) {
        $logodarkFileName = "logodark.png"; // Rename the file to logodark.png
        $logodarkPath = $uploadDirectory . $logodarkFileName;
        move_uploaded_file($_FILES["logodark"]["tmp_name"], $logodarkPath);
    }

    // Handle Favicon Upload
    if (isset($_FILES["favicon"]) && $_FILES["favicon"]["error"] == UPLOAD_ERR_OK) {
        $faviconFileName = "favicon.png"; // Rename the file to favicon.png
        $faviconPath = $uploadDirectory . $faviconFileName;
        move_uploaded_file($_FILES["favicon"]["tmp_name"], $faviconPath);
    }

    // Handle Open Graph Image Upload
    if (isset($_FILES["open_graph_image"]) && $_FILES["open_graph_image"]["error"] == UPLOAD_ERR_OK) {
        $openGraphFileName = "opengraph.png"; // Rename the file to opengraph.png
        $openGraphPath = $uploadDirectory . $openGraphFileName;
        move_uploaded_file($_FILES["open_graph_image"]["tmp_name"], $openGraphPath);
    }
}




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
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Log and display the error message
    error_log("Database connection error: " . $e->getMessage());
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Initialize $username variable
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$newUsername = $_POST['username'] ?? '';
$newPassword = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Update the username in the database
if (!empty($newUsername)) {
    // Add your database update logic here for the username
    // Example:
    $queryUsername = "UPDATE admin_users SET username = :newUsername WHERE id = :userId";
    $stmtUsername = $pdo->prepare($queryUsername);
    $stmtUsername->bindParam(':newUsername', $newUsername);
    $stmtUsername->bindParam(':userId', $_SESSION['user_id']);
    $stmtUsername->execute();

    // Display success message
    $profileMessage = "Username updated successfully.";
    $profileMessageClass = "alert-success";
    $username = $newUsername; // Update the $username variable for display
}


// Update the password in the database
if (!empty($newPassword)) {
    if ($newPassword === $confirmPassword) {
        // Add your database update logic here for the password
        // Example:
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $queryPassword = "UPDATE admin_users SET password = :hashedPassword WHERE id = :userId";
        $stmtPassword = $pdo->prepare($queryPassword);
        $stmtPassword->bindParam(':hashedPassword', $hashedPassword);
        $stmtPassword->bindParam(':userId', $_SESSION['user_id']);
        $stmtPassword->execute();

        // Display success message
        $profileMessage = "Password updated successfully.";
        $profileMessageClass = "alert-success";
    } else {
        // Display error message for password mismatch
        $profileMessage = "Password and confirm password do not match.";
        $profileMessageClass = "alert-danger";
    }
}

// This ensures that the correct username is displayed in the form
$username = !empty($newUsername) ? $newUsername : $username;
// Make sure to update $username even if the password is not changed

// Initialize $email variable
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

// Fetch and display the email in the form
if (isset($_SESSION['user_id'])) {
    $queryEmail = "SELECT email FROM admin_users WHERE id = :userId";
    $stmtEmail = $pdo->prepare($queryEmail);
    $stmtEmail->bindParam(':userId', $_SESSION['user_id']);
    $stmtEmail->execute();
    $row = $stmtEmail->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $email = $row['email'];
    }
}

// Update the email in the database
if (isset($_POST['update_email'])) {
    $newEmail = $_POST['new_email'] ?? '';

    // Validate and update the email
    if (!empty($newEmail)) {
        // Add your database update logic here for the email
        // Example:
        $queryUpdateEmail = "UPDATE admin_users SET email = :newEmail WHERE id = :userId";
        $stmtUpdateEmail = $pdo->prepare($queryUpdateEmail);
        $stmtUpdateEmail->bindParam(':newEmail', $newEmail);
        $stmtUpdateEmail->bindParam(':userId', $_SESSION['user_id']);
        $stmtUpdateEmail->execute();

        // Update the session variable with the new email
        $_SESSION['email'] = $newEmail;

        // Display success message
        $profileMessage = "Email updated successfully.";
        $profileMessageClass = "alert-success";

        // Update the $email variable for display
        $email = $newEmail;
    } else {
        // Display error message for empty email
        $profileMessage = "Email cannot be empty.";
        $profileMessageClass = "alert-danger";
    }
}


// Handling the form submission for SMTP settings update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update_smtp_settings"])) {
        // Update specific .env values with double quotes
        $env['SMTP_HOST'] = isset($_POST['smtp_host']) ? "\"" . $_POST['smtp_host'] . "\"" : $env['SMTP_HOST'];
        $env['SMTP_USERNAME'] = isset($_POST['smtp_username']) ? "\"" . $_POST['smtp_username'] . "\"" : $env['SMTP_USERNAME'];
        $env['SMTP_PASSWORD'] = isset($_POST['smtp_password']) ? "\"" . $_POST['smtp_password'] . "\"" : $env['SMTP_PASSWORD'];
        $env['SMTP_PORT'] = isset($_POST['smtp_port']) ? "\"" . $_POST['smtp_port'] . "\"" : $env['SMTP_PORT'];
        $env['SMTP_ENCRYPTION'] = isset($_POST['smtp_encryption']) ? "\"" . $_POST['smtp_encryption'] . "\"" : $env['SMTP_ENCRYPTION'];
    }
}








// Load environment variables from the .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Assigning environment variables to PHP variables
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$dbname = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';

// Create a database connection using PDO
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Log and display the error message
    error_log("Database connection error: " . $e->getMessage());
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Check if form for adding/editing/deleting pages is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check which form is submitted
    if (isset($_POST["add_page"])) {
        // Handle adding a new page
        $title = $_POST["title"];
        $content = $_POST["content"];
        $metaDescription = $_POST["meta_description"];
        $keywords = $_POST["keywords"];
        $includeHeader = isset($_POST["include_header"]) ? 1 : 0;
        $includeFooter = isset($_POST["include_footer"]) ? 1 : 0;
        
        // Insert the data into the database
        $stmt = $pdo->prepare("INSERT INTO pages (title, content, meta_description, keywords, include_header, include_footer) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $content, $metaDescription, $keywords, $includeHeader, $includeFooter]);
        
        // Redirect back to dashboard or show success message
        header("Location: dashboard.php");
        exit();
    } elseif (isset($_POST["edit_page"])) {
        // Handle editing an existing page
        $pageId = $_POST["page_id"];
        $title = $_POST["title"];
        $content = $_POST["content"];
        $metaDescription = $_POST["meta_description"];
        $keywords = $_POST["keywords"];
        $includeHeader = isset($_POST["include_header"]) ? 1 : 0;
        $includeFooter = isset($_POST["include_footer"]) ? 1 : 0;
        
        // Prepare statement for editing an existing page
        $stmt = $pdo->prepare("UPDATE pages SET title = :title, content = :content, meta_description = :meta_description, keywords = :keywords, include_header = :include_header, include_footer = :include_footer WHERE id = :id");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':meta_description', $metaDescription);
        $stmt->bindParam(':keywords', $keywords);
        $stmt->bindParam(':include_header', $includeHeader);
        $stmt->bindParam(':include_footer', $includeFooter);
        $stmt->bindParam(':id', $pageId);
        
        // Execute statement
        $stmt->execute();
        
        // Redirect back to dashboard or show success message
        header("Location: dashboard.php");
        exit();
    } elseif (isset($_POST["delete_page"])) {
        // Handle deleting an existing page
        $pageId = $_POST["page_id"];
        
        // Prepare statement for deleting an existing page
        $stmt = $pdo->prepare("DELETE FROM pages WHERE id = :id");
        $stmt->bindParam(':id', $pageId);
        
        // Execute statement
        $stmt->execute();
        
        // Redirect back to dashboard or show success message
        header("Location: dashboard.php");
        exit();
    }
}

// Fetch list of pages
$stmt = $pdo->query("SELECT id, title FROM pages");
$pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Database connection
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$dbname = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';

try {
    // Create a database connection using PDO
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Log and display the error message
    error_log("Database connection error: " . $e->getMessage());
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Check if form for adding/editing/deleting articles is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_article"])) {
        // Handle adding a new article
        $title = $_POST["title"];
        $content = $_POST["content"];
        $author = $_POST["author"];
        $metaDescription = $_POST["meta_description"];
        $keywords = $_POST["keywords"];
        
        // Handle file upload for featured image
        $targetDir = "blog/";
        $targetFile = $targetDir . basename($_FILES["featured_image"]["name"]);

        // Create the blog directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Move the uploaded file to the blog directory
        if (move_uploaded_file($_FILES["featured_image"]["tmp_name"], $targetFile)) {
            $featuredImage = $targetFile;
        } else {
            echo "Error uploading file.";
            exit();
        }

        // Insert the data into the database
        $stmt = $pdo->prepare("INSERT INTO blog_articles (title, content, author, meta_description, keywords, featured_image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $content, $author, $metaDescription, $keywords, $featuredImage]);
        // Redirect back to dashboard or show success message
        header("Location: dashboard.php");
        exit();
    } elseif (isset($_POST["edit_article"])) {
        // Handle editing an existing article
        $articleId = $_POST["article_id"];
        $title = $_POST["title"];
        $content = $_POST["content"];
        $author = $_POST["author"];
        $metaDescription = $_POST["meta_description"];
        $keywords = $_POST["keywords"];

        // Check if a new featured image is uploaded
        if ($_FILES["featured_image"]["name"] !== '') {
            // Handle file upload for featured image
            $targetDir = "blog/";
            $targetFile = $targetDir . basename($_FILES["featured_image"]["name"]);

            // Create the blog directory if it doesn't exist
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Move the uploaded file to the blog directory
            if (move_uploaded_file($_FILES["featured_image"]["tmp_name"], $targetFile)) {
                $featuredImage = $targetFile;
            } else {
                echo "Error uploading file.";
                exit();
            }
        } else {
            // If no new image uploaded, retain the existing one
            $stmt = $pdo->prepare("SELECT featured_image FROM blog_articles WHERE id = ?");
            $stmt->execute([$articleId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $featuredImage = $row['featured_image'];
        }

        // Prepare statement for editing an existing article
        $stmt = $pdo->prepare("UPDATE blog_articles SET title = ?, content = ?, author = ?, meta_description = ?, keywords = ?, featured_image = ? WHERE id = ?");
        $stmt->execute([$title, $content, $author, $metaDescription, $keywords, $featuredImage, $articleId]);
        // Redirect back to dashboard or show success message
        header("Location: dashboard.php");
        exit();
    } elseif (isset($_POST["delete_article"])) {
        // Handle deleting an existing article
        $articleId = $_POST["article_id"];
        // Prepare statement for deleting an existing article
        $stmt = $pdo->prepare("DELETE FROM blog_articles WHERE id = ?");
        $stmt->execute([$articleId]);
        // Redirect back to dashboard or show success message
        header("Location: dashboard.php");
        exit();
    }
}

// Fetch list of articles
$stmt = $pdo->query("SELECT id, title, content, author, meta_description, keywords, featured_image FROM blog_articles");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Handling the form submission for .env updates
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update_env"])) {
        // Update specific .env values with double quotes
              
        $env['TAWKTO_WIDGET_LINK'] = isset($_POST['TAWKTO_WIDGET_LINK']) ? "\"" . $_POST['TAWKTO_WIDGET_LINK'] . "\"" : $env['TAWKTO_WIDGET_LINK'];
        
         $env['RECAPTCHA_SITE_KEY'] = isset($_POST['RECAPTCHA_SITE_KEY']) ? "\"" . $_POST['RECAPTCHA_SITE_KEY'] . "\"" : $env['RECAPTCHA_SITE_KEY'];
         
            $env['RECAPTCHA_SECRET_KEY'] = isset($_POST['RECAPTCHA_SECRET_KEY']) ? "\"" . $_POST['RECAPTCHA_SECRET_KEY'] . "\"" : $env['RECAPTCHA_SECRET_KEY'];
         
         $env['GOOGLE_ANALYTICS'] = isset($_POST['GOOGLE_ANALYTICS']) ? "\"" . $_POST['GOOGLE_ANALYTICS'] . "\"" : $env['GOOGLE_ANALYTICS'];
           $env['SMTP_HOST'] = isset($_POST['SMTP_HOST']) ? "\"" . $_POST['SMTP_HOST'] . "\"" : $env['SMTP_HOST'];
 $env['SMTP_USERNAME'] = isset($_POST['SMTP_USERNAME']) ? "\"" . $_POST['SMTP_USERNAME'] . "\"" : $env['SMTP_USERNAME'];
 $env['SMTP_PASSWORD'] = isset($_POST['SMTP_PASSWORD']) ? "\"" . $_POST['SMTP_PASSWORD'] . "\"" : $env['SMTP_PASSWORD'];
  $env['SMTP_PORT'] = isset($_POST['SMTP_PORT']) ? "\"" . $_POST['SMTP_PORT'] . "\"" : $env['SMTP_PORT'];
    $env['SMTP_ENCRYPTION'] = isset($_POST['SMTP_ENCRYPTION']) ? "\"" . $_POST['SMTP_ENCRYPTION'] . "\"" : $env['SMTP_ENCRYPTION'];
        // Update GTRANSLATER and DARKMODE settings
        $env['GTRANSLATER'] = isset($_POST['gtranslater_enabled']) ? 'true' : 'false';
        $env['DARKMODE'] = isset($_POST['darkmode_enabled']) ? 'true' : 'false';
        $env['BLOG'] = isset($_POST['blog_enabled']) ? 'true' : 'false';
         $env['FAQ'] = isset($_POST['faq_enabled']) ? 'true' : 'false';
           $env['FEATURE_BOXES'] = isset($_POST['featureboxes_enabled']) ? 'true' : 'false';
  $env['contact'] = isset($_POST['contact']) ? 'true' : 'false';

        // Preserve existing .env variables without double quotes
        $existingEnv = array(
            

           
           'DB_HOST' => $env['DB_HOST'] ?? '',
            'DB_NAME' => $env['DB_NAME'] ?? '',
            'DB_USER' => $env['DB_USER'] ?? '',
            'DB_PASS' => $env['DB_PASS'] ?? '',
                      



            
                                    'BLOG' => $env['BLOG']
                                    ?? '',
                                    'FAQ' => $env['FAQ']
                                    ?? '',
                                     'FEATURE_BOXES' => $env['FEATURE_BOXES'] ?? '',
             'GTRANSLATER' => $env['GTRANSLATER'] ?? '',
                          'TAWKTO_WIDGET_LINK' => $env['TAWKTO_WIDGET_LINK'] ?? '',
                           'RECAPTCHA_SITE_KEY' => $env['RECAPTCHA_SITE_KEY'] ?? '',
                            'RECAPTCHA_SECRET_KEY' => $env['RECAPTCHA_SECRET_KEY'] ?? '',
                             'contact' => $env['contact'] ?? '',
   'GOOGLE_ANALYTICS' => $env['GOOGLE_ANALYTICS'] ?? '',
            'DARKMODE' => $env['DARKMODE'] ?? ''

            
            


        );
        

      // Update .env file with both updated and existing variables
$fileContent = 
    "SMTP_HOST={$env['SMTP_HOST']}\n" .
    "SMTP_USERNAME={$env['SMTP_USERNAME']}\n" .
    "SMTP_PASSWORD={$env['SMTP_PASSWORD']}\n" .
    "SMTP_PORT={$env['SMTP_PORT']}\n" .
    "SMTP_ENCRYPTION={$env['SMTP_ENCRYPTION']}\n" .
    "TAWKTO_WIDGET_LINK={$env['TAWKTO_WIDGET_LINK']}\n" .
    "GOOGLE_ANALYTICS={$env['GOOGLE_ANALYTICS']}\n" .
    "RECAPTCHA_SITE_KEY={$env['RECAPTCHA_SITE_KEY']}\n" .
    "RECAPTCHA_SECRET_KEY={$env['RECAPTCHA_SECRET_KEY']}\n";

foreach ($existingEnv as $key => $value) {
    if (!empty($value)) {
        $fileContent .= "$key=$value\n";
    }
}

file_put_contents($envPath, $fileContent);

$message = "Environment variables updated successfully.";
// Redirect to dashboard.php after processing the form
header("Location: dashboard.php");
exit; // Make sure to exit after the redirect to prevent further execution
} elseif (isset($_POST["logout"])) {
    // Logout logic
    $_SESSION = array();
    session_destroy();
    header("location: login.php");
    exit;
} elseif (isset($_POST[""])) {
    // Handle other post requests if needed
}

    
    
}

require_once __DIR__ . '/vendor/autoload.php';

function getSupportRequestCount() {
    

    // Access environment variables
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $port = $_ENV['DB_PORT'] ?? 3306;
    $dbname = $_ENV['DB_NAME'] ?? '';
    $user = $_ENV['DB_USER'] ?? '';
    $pass = $_ENV['DB_PASS'] ?? '';

    try {
        // Create a database connection using PDO
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve the count of support requests from the database
        $stmt = $pdo->query("SELECT COUNT(*) FROM support_requests");
        $count = $stmt->fetchColumn();

        // Return the count
        return $count;
    } catch(PDOException $e) {
        // Log and display the error message
        error_log("Database connection error: " . $e->getMessage());
        return 0; // Return 0 if there's an error
    }
}


// Create a database connection using PDO
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$dbname = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the ID parameter is set
    if (isset($_POST['id'])) {
        // Prepare and execute the delete query
        $stmt = $pdo->prepare("DELETE FROM support_requests WHERE id = ?");
        $stmt->execute([$_POST['id']]);

        // Redirect back to the same page after deletion
        header('Location: dashboard.php');
        exit();
    }
} catch(PDOException $e) {
    // Log and display the error message
    error_log("Database connection error: " . $e->getMessage());
    http_response_code(500);
    echo "Database error: " . $e->getMessage();
}

// Fetch support requests from the database
try {
    $stmt = $pdo->query("SELECT * FROM support_requests");
    $supportRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Log and display the error message
    error_log("Error fetching support requests: " . $e->getMessage());
  
    $supportRequests = [];
}

require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables from the .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Database connection
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$dbname = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';

try {
    // Create a database connection using PDO
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Log and display the error message
    error_log("Database connection error: " . $e->getMessage());
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Initialize variables to store SEO data
$title = '';
$seoDescription = '';
$seoKeywords = '';
$openGraphTitle = '';
$openGraphDescription = '';
$indexPageTitle = '';
$indexPageLead = '';
$footerContent = '';

try {
    // Fetch SEO data from the database
    $stmt = $pdo->query("SELECT * FROM seo_metadata WHERE id = 2");
    $seoData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if SEO data is fetched successfully
    if ($seoData) {
        // Assign fetched data to individual variables
        $title = $seoData['title'] ?? '';
        $seoDescription = $seoData['seo_description'] ?? '';
        $seoKeywords = $seoData['seo_keywords'] ?? '';
        $openGraphTitle = $seoData['open_graph_title'] ?? '';
        $openGraphDescription = $seoData['open_graph_description'] ?? '';
        $indexPageTitle = $seoData['index_page_title'] ?? '';
        $indexPageLead = $seoData['index_page_lead_paragraph'] ?? '';
        $footerContent = $seoData['footer_content'] ?? '';
    } else {
        // Handle case when no SEO data is found
        echo "No SEO data found.";
    }
} catch (PDOException $e) {
    // Log and display the error message
    error_log("Error fetching SEO data: " . $e->getMessage());
    // Handle error gracefully
}

// Check if the form for updating SEO settings is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_seo_appearance"])) {
    // Retrieve form data
    $title = $_POST["title"];
    $seoDescription = $_POST["seo_description"];
    $seoKeywords = $_POST["seo_keywords"];
    $openGraphTitle = $_POST["open_graph_title"];
    $openGraphDescription = $_POST["open_graph_description"];
    $indexPageTitle = $_POST["index_page_title"];
    $indexPageLead = $_POST["index_page_lead_paragraph"]; // Update to correct name attribute
    $footerContent = $_POST["footer_content"];

    try {
        // Update SEO metadata in the database
        $stmt = $pdo->prepare("UPDATE seo_metadata SET title = ?, seo_description = ?, seo_keywords = ?, open_graph_title = ?, open_graph_description = ?, index_page_title = ?, index_page_lead_paragraph = ?, footer_content = ? WHERE id = 2");
        $stmt->execute([$title, $seoDescription, $seoKeywords, $openGraphTitle, $openGraphDescription, $indexPageTitle, $indexPageLead, $footerContent]);

        // Redirect back to dashboard or show success message
        header("Location: dashboard.php");
        exit();
    } catch(PDOException $e) {
        // Log and display the error message
        error_log("Error updating SEO metadata: " . $e->getMessage());
        // Redirect back to dashboard with error message
    }
}

$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$dbname = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';

try {
    // Create a database connection using PDO
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Log and display the error message
    error_log("Database connection error: " . $e->getMessage());
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Initialize variables to store banners and AdSense content
$banner1_html = '';
$banner2_html = '';
$adsense_html = '';

try {
    // Fetch banners and AdSense content from the database
    $stmt = $pdo->query("SELECT * FROM banners_adsense");
    $contentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if content data is fetched successfully
    if ($contentData) {
        foreach ($contentData as $row) {
            switch ($row['type']) {
                case 'banner_top':
                    $banner1_html = $row['content'];
                    break;
                case 'banner_bottom':
                    $banner2_html = $row['content'];
                    break;
                case 'adsense':
                    $adsense_html = $row['content'];
                    break;
                default:
                    // Handle unknown type
                    break;
            }
        }
    } else {
        // Handle case when no content data is found
        echo "No content data found.";
    }
} catch (PDOException $e) {
    // Log and display the error message
    error_log("Error fetching content data: " . $e->getMessage());
    // Handle error gracefully
}

// Check if the form for updating banners and AdSense content is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_banners_adsense"])) {
    // Retrieve form data
    $banner1_html = $_POST["banner1_html"];
    $banner2_html = $_POST["banner2_html"];
    $adsense_html = $_POST["adsense_html"];

    try {
        // Update banners and AdSense content in the database
        $stmt = $pdo->prepare("UPDATE banners_adsense SET content = ? WHERE type = ?");
        $stmt->execute([$banner1_html, 'banner_top']);
        $stmt->execute([$banner2_html, 'banner_bottom']);
        $stmt->execute([$adsense_html, 'adsense']);

        // Redirect back to dashboard or show success message
        header("Location: dashboard.php");
        exit();
    } catch(PDOException $e) {
        // Log and display the error message
        error_log("Error updating banners and AdSense content: " . $e->getMessage());
        // Redirect back to dashboard with error message
    }
}

// Database connection
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$dbname = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';

try {
    // Create a database connection using PDO
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Log and display the error message
    error_log("Database connection error: " . $e->getMessage());
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Fetch contact info data from the database
try {
    $stmt = $pdo->query("SELECT * FROM contact_info WHERE id = 1"); // Assuming only one row in the table
    $contactInfo = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log and display the error message
    error_log("Error fetching contact info: " . $e->getMessage());
}

// Check if the form for updating contact info is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_contact_info"])) {
    // Retrieve form data
    $title = $_POST["title"];
    $email = $_POST["email"];

    try {
        // Update contact info in the database
        $stmt = $pdo->prepare("UPDATE contact_info SET title = ?, email = ? WHERE id = 1"); // Assuming only one row in the table
        $stmt->execute([$title, $email]);

        // Redirect back to dashboard or show success message
        header("Location: dashboard.php");
        exit();
    } catch(PDOException $e) {
        // Log and display the error message
        error_log("Error updating contact info: " . $e->getMessage());
        // Redirect back to dashboard with error message
    }
}
// Function to generate XML sitemap
function generateSitemap() {
    // Start XML document
    $xml = '<?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    // Add allowed URLs to sitemap
    $allowedUrls = [
        '/index.php',
        '/blog.php',
        '/contact.php',
        
       
    ];

    foreach ($allowedUrls as $url) {
        // Check if the URL is allowed or matches the pattern
        if (strpos($url, '*') !== false) { // Handle wildcard pattern
            // Replace wildcard with a placeholder for demonstration
            $url = str_replace('*', 'placeholder', $url);
            $xml .= '
            <url>
                <loc>' . htmlspecialchars('http://' . $_SERVER['HTTP_HOST'] . $url) . '</loc>
                <lastmod>' . date("Y-m-d") . '</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>';
        } else {
            $xml .= '
            <url>
                <loc>' . htmlspecialchars('http://' . $_SERVER['HTTP_HOST'] . $url) . '</loc>
                <lastmod>' . date("Y-m-d") . '</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>';
        }
    }

    // Add URLs for articles from the database
    $articleUrls = getArticleUrlsFromDatabase();
    foreach ($articleUrls as $articleUrl) {
        $xml .= '
        <url>
            <loc>' . htmlspecialchars('http://' . $_SERVER['HTTP_HOST'] . '/article.php?id=' . $articleUrl['id']) . '</loc>
            <lastmod>' . date("Y-m-d") . '</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>';
    }

    // Add URLs for pages from the database
    $pageUrls = getPageUrlsFromDatabase();
    foreach ($pageUrls as $pageUrl) {
        $xml .= '
        <url>
            <loc>' . htmlspecialchars('http://' . $_SERVER['HTTP_HOST'] . '/page.php?id=' . $pageUrl['id']) . '</loc>
            <lastmod>' . date("Y-m-d") . '</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>';
    }

    // Close XML document
    $xml .= '</urlset>';

    // Save XML sitemap to a file (overwrite if it already exists)
    $filename = $_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml';
    file_put_contents($filename, $xml);

    return $filename;
}

// Function to retrieve article URLs from the database
function getArticleUrlsFromDatabase() {
    // Create a database connection using PDO
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $port = $_ENV['DB_PORT'] ?? 3306;
    $dbname = $_ENV['DB_NAME'] ?? '';
    $user = $_ENV['DB_USER'] ?? '';
    $pass = $_ENV['DB_PASS'] ?? '';

    try {
        // Create a database connection using PDO
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve article URLs from the database
        $stmt = $pdo->query("SELECT id FROM blog_articles");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        // Log and display the error message
        error_log("Database connection error: " . $e->getMessage());
        die("ERROR: Could not connect. " . $e->getMessage());
    }
}

// Function to retrieve page URLs from the database
function getPageUrlsFromDatabase() {
    // Create a database connection using PDO
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $port = $_ENV['DB_PORT'] ?? 3306;
    $dbname = $_ENV['DB_NAME'] ?? '';
    $user = $_ENV['DB_USER'] ?? '';
    $pass = $_ENV['DB_PASS'] ?? '';

    try {
        // Create a database connection using PDO
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve page URLs from the database
        $stmt = $pdo->query("SELECT id FROM pages");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        // Log and display the error message
        error_log("Database connection error: " . $e->getMessage());
        die("ERROR: Could not connect. " . $e->getMessage());
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["generate_sitemap"])) {
    // Generate the sitemap
    $sitemapFile = generateSitemap();

    // Provide feedback to the user
    if ($sitemapFile) {
        // Extract the domain from the URL
        $domain = $_SERVER['HTTP_HOST'];
        echo '<p>Sitemap generated successfully. <a href="http://' . $domain . '/sitemap.xml">Download</a></p>';
    } else {
        echo '<p>Error generating sitemap.</p>';
    }
}


// Database connection
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$dbname = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form is submitted to add new social media link
    if (isset($_POST['add_social_media'])) {
        $name = $_POST['socialMediaTitle'];
        $iconClass = $_POST['socialMediaClass'];
        $link = $_POST['socialMediaLink'];

        // Insert new social media link into the database
        $stmt = $pdo->prepare("INSERT INTO `social_media` (name, icon_class, link) VALUES (?, ?, ?)");
        $stmt->execute([$name, $iconClass, $link]);

        // Redirect to dashboard after adding
        header("Location: dashboard.php");
        exit();
    }

    // Check if form is submitted to delete social media link
    if (isset($_POST['delete_social_media'])) {
        $socialMediaId = $_POST['social_media_id'];

        // Delete social media link from the database
        $stmt = $pdo->prepare("DELETE FROM `social_media` WHERE id = ?");
        $stmt->execute([$socialMediaId]);

        // Redirect to dashboard after deleting
        header("Location: dashboard.php");
        exit();
    }

    // Check if form is submitted to edit social media link
    if (isset($_POST['edit_social_media'])) {
        $socialMediaId = $_POST['social_media_id'];
        $name = $_POST['socialMediaTitle'];
        $iconClass = $_POST['socialMediaClass'];
        $link = $_POST['socialMediaLink'];

        // Update social media link in the database
        $stmt = $pdo->prepare("UPDATE `social_media` SET name = ?, icon_class = ?, link = ? WHERE id = ?");
        $stmt->execute([$name, $iconClass, $link, $socialMediaId]);

        // Redirect to dashboard after editing
        header("Location: dashboard.php");
        exit();
    }

    // Fetch social media links from the database
    $stmt = $pdo->query("SELECT * FROM `social_media`");
    $socialMediaLinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Log and display the error message
    error_log("Database connection error: " . $e->getMessage());
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Check if a file was uploaded and it's not empty
if (!empty($_FILES['update_file']['name']) && $_FILES['update_file']['error'] === UPLOAD_ERR_OK) {
    $zipFile = $_FILES['update_file']['tmp_name'];

    // Get the path to the parent directory of /admin
    $parentDirectory = realpath(__DIR__ . '/..');

    // Extract the contents of the zip file
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === TRUE) {
        $zip->extractTo($parentDirectory); // Extract outside the /admin folder
        $zip->close();
        echo 'Update applied successfully.';
    } else {
        echo 'Failed to extract update file.';
    }
} elseif (!empty($_FILES['update_file']['name'])) {
    // File uploaded but with errors
    echo 'Error uploading file.';
} else {
    // No file uploaded
    // You can choose to do nothing here, or display a message like "Please select a file to upload."
}

// Database connection using environment variables
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$dbname = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle Add FAQ form submission
    if (isset($_POST['add_faq'])) {
        $question = $_POST['question'];
        $answer = $_POST['answer'];

        // Insert new FAQ into the database
        $stmt = $pdo->prepare("INSERT INTO `faqs` (question, answer) VALUES (?, ?)");
        $stmt->execute([$question, $answer]);

        // Redirect to dashboard after adding
        header("Location: dashboard.php");
        exit();
    }

    // Handle Delete FAQ form submission
    if (isset($_POST['delete_faq'])) {
        $faqId = $_POST['faq_id'];

        // Delete FAQ from the database
        $stmt = $pdo->prepare("DELETE FROM `faqs` WHERE id = ?");
        $stmt->execute([$faqId]);

        // Redirect to dashboard after deleting
        header("Location: dashboard.php");
        exit();
    }

    // Handle Edit FAQ form submission
    if (isset($_POST['edit_faq'])) {
        $faqId = $_POST['faq_id'];
        $question = $_POST['question'];
        $answer = $_POST['answer'];

        // Update FAQ in the database
        $stmt = $pdo->prepare("UPDATE `faqs` SET question = ?, answer = ? WHERE id = ?");
        $stmt->execute([$question, $answer, $faqId]);

        // Redirect to dashboard after editing
        header("Location: dashboard.php");
        exit();
    }

    // Fetch all FAQs from the database
    $stmt = $pdo->query("SELECT * FROM `faqs`");
    $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log and display the error message
    error_log("Database connection error: " . $e->getMessage());
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Database connection and functions for managing header links
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$dbname = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Function to fetch header links
    function getHeaderLinks() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM `header_links`");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Function to add a new header link
    function addHeaderLink($title, $url) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO `header_links` (`title`, `url`, `enabled`) VALUES (?, ?, 1)");
        $stmt->execute([$title, $url]);
    }

    // Function to toggle the visibility of a header link
    function toggleHeaderLink($linkId, $enabled) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE `header_links` SET `enabled` = ? WHERE `id` = ?");
        $stmt->execute([$enabled, $linkId]);
    }

    // Function to delete a header link
    function deleteHeaderLink($linkId) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM `header_links` WHERE `id` = ?");
        $stmt->execute([$linkId]);
    }

    // Function to update a header link
    function updateHeaderLink($linkId, $title, $url) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE `header_links` SET `title` = ?, `url` = ? WHERE `id` = ?");
        $stmt->execute([$title, $url, $linkId]);
    }

} catch(PDOException $e) {
    // Log and display the error message
    error_log("Database connection error: " . $e->getMessage());
    die("ERROR: Could not connect. " . $e->getMessage());
}
// Check if form is submitted to edit a header link
if (isset($_POST['edit_link'])) {
    $linkId = $_POST['link_id'];
    $title = $_POST['title'];
    $url = $_POST['url'];

    // Update the header link in the database
    updateHeaderLink($linkId, $title, $url);

    // Redirect to dashboard after editing
    header("Location: dashboard.php");
    exit();
}



// Check if form is submitted to add a new header link
if (isset($_POST['add_link'])) {
    $title = $_POST['title'];
    $url = $_POST['url'];

    // Add the new header link to the database
    addHeaderLink($title, $url);

    // Redirect to dashboard after adding
    header("Location: dashboard.php");
    exit();
}

// Check if form is submitted to toggle visibility of a header link
if (isset($_POST['toggle_link'])) {
    $linkId = $_POST['link_id'];
    $enabled = $_POST['enabled'];

    // Toggle the visibility of the header link in the database
    toggleHeaderLink($linkId, $enabled);

    // Redirect to dashboard
    header("Location: dashboard.php");
    exit();
}

// Check if form is submitted to delete a header link
if (isset($_POST['delete_link'])) {
    $linkId = $_POST['link_id'];

    // Delete the header link from the database
    deleteHeaderLink($linkId);

    // Redirect to dashboard
    header("Location: dashboard.php");
    exit();
}

// Check if the form for updating CSS styles is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_css"])) {
    // Retrieve form data
    $bodyBackground = $_POST["body_background"];
    $c3Color = $_POST["c3_color"];
    $darkC8Color = $_POST["dark_c8_color"];

    try {
        // Begin a transaction
        $pdo->beginTransaction();

        // Update CSS styles in the database
        $stmt = $pdo->prepare("UPDATE css_styles SET value = ? WHERE selector = ? AND property = ?");
        
        // Update body background color
        $stmt->execute([$bodyBackground, 'body', 'background']);

        // Update .c3 color
        $stmt->execute([$c3Color, '.c3', 'color']);

        // Update .dark .dark-c8 color
        $stmt->execute([$darkC8Color, '.dark .dark-c8', 'color']);

        // Commit the transaction
        $pdo->commit();

        // Redirect back to dashboard or show success message
        header("Location: dashboard.php");
        exit();
    } catch(PDOException $e) {
        // Rollback the transaction on error
        $pdo->rollBack();
        
        // Log and display the error message
        error_log("Error updating CSS styles: " . $e->getMessage());
        // Redirect back to dashboard with error message
    }
}
try {
    $stmt = $pdo->query("SELECT * FROM css_styles");
    $cssStyles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Extract CSS style values
    $bodyBackground = '';
    $c3Color = '';
    $darkC8Color = '';

    foreach ($cssStyles as $style) {
        if ($style['selector'] === 'body' && $style['property'] === 'background') {
            $bodyBackground = $style['value'];
        } elseif ($style['selector'] === '.c3' && $style['property'] === 'color') {
            $c3Color = $style['value'];
        } elseif ($style['selector'] === '.dark .dark-c8' && $style['property'] === 'color') {
            $darkC8Color = $style['value'];
        }
    }
} catch(PDOException $e) {
    // Log and display the error message
    error_log("Error fetching CSS styles: " . $e->getMessage());
    // Handle the error as needed
}

// Database connection using environment variables
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$dbname = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle Add How It Works item form submission
    if (isset($_POST['add_how_it_works'])) {
        $iconTitle = $_POST['iconTitle'] ?? '';
        $iconSVG = $_POST['iconSVG'] ?? '';
        $iconDescription = $_POST['iconDescription'] ?? '';

        if (!empty($iconTitle) && !empty($iconSVG) && !empty($iconDescription)) {
            // Insert new How It Works item into the database
            $stmt = $pdo->prepare("INSERT INTO `how_it_works` (icon_title, icon_svg, icon_description) VALUES (?, ?, ?)");
            $stmt->execute([$iconTitle, $iconSVG, $iconDescription]);

            // Redirect to dashboard after adding
            header("Location: dashboard.php");
            exit();
        }
    }

    // Handle Delete How It Works item form submission
    if (isset($_POST['delete_how_it_works'])) {
        $itemId = $_POST['how_it_works_id'] ?? 0;

        if ($itemId) {
            // Delete How It Works item from the database
            $stmt = $pdo->prepare("DELETE FROM `how_it_works` WHERE id = ?");
            $stmt->execute([$itemId]);

            // Redirect to dashboard after deleting
            header("Location: dashboard.php");
            exit();
        }
    }

    // Handle Edit How It Works item form submission
    if (isset($_POST['edit_how_it_works'])) {
        $itemId = $_POST['editItemId'] ?? 0;
        $iconTitle = $_POST['editIconTitle'] ?? '';
        $iconSVG = $_POST['editIconSVG'] ?? '';
        $iconDescription = $_POST['editIconDescription'] ?? '';

        if ($itemId && !empty($iconTitle) && !empty($iconSVG) && !empty($iconDescription)) {
            // Perform update query
            $stmt = $pdo->prepare("UPDATE `how_it_works` SET icon_title = ?, icon_svg = ?, icon_description = ? WHERE id = ?");
            $stmt->execute([$iconTitle, $iconSVG, $iconDescription, $itemId]);

            // Redirect to dashboard after editing
            header("Location: dashboard.php");
            exit();
        }
    }

    // Fetch existing How It Works items
    $stmt = $pdo->query("SELECT * FROM `how_it_works`");
    $howItWorksItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}


// Database connection
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$dbname = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';

try {
    // Create a database connection using PDO
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_maintenance'])) {
        // Update maintenance mode settings
        $maintenanceTitle = $_POST['title'] ?? '';
        $maintenanceMessage = $_POST['message'] ?? '';
        $maintenanceActive = $_POST['active'] ?? 0;

        // Prepare and execute the SQL statement
        $stmt = $pdo->prepare("UPDATE maintenance_mode SET title = :title, message = :message, active = :active");
        $stmt->execute(['title' => $maintenanceTitle, 'message' => $maintenanceMessage, 'active' => $maintenanceActive]);

        // Redirect to avoid resubmission when refreshing the page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Retrieve current maintenance mode settings from the database
    $stmt = $pdo->query("SELECT * FROM maintenance_mode LIMIT 1");
    $maintenanceMode = $stmt->fetch(PDO::FETCH_ASSOC);

    // Initialize variables for form fields
    $maintenanceTitle = $maintenanceMode['title'] ?? '';
    $maintenanceMessage = $maintenanceMode['message'] ?? '';
    $maintenanceActive = $maintenanceMode['active'] ?? 0;
} catch(PDOException $e) {
    // Log and display the error message
    error_log("Database connection error: " . $e->getMessage());
    die("ERROR: Could not update maintenance mode settings. " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Your existing head content -->
    <title>Dashboard</title>
    
    <!-- Add your stylesheet links here -->
    <link rel="stylesheet" href="styles.min.css" />
    <link rel="stylesheet" href="styles.css" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link type="text/css" href="/static/assets/css/styles.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    // Check if there is a hash in the URL
    if(window.location.hash) {
        // Get the hash from the URL
        var hash = window.location.hash;
        
        // Activate the tab corresponding to the hash
        $('.nav-link[href="' + hash + '"]').tab('show');
    }
    
    // When a tab is shown, update the URL hash
    $('.nav-link').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });
});



</script>

    
    
  <header class="header">
        <div>
<img src="/admin/uploads/logodark.png" alt="Logo" class="logo" style="width: auto; height: auto;">
        </div>
        <div class="buttons">
    <a href="../index.php" class="button">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
            <path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/>
        </svg> Home Page
    </a>
            </button>
                        <button class="button">

            <form method="post" class="generate-sitemap-button">
                <button type="submit" class="button" name="generate_sitemap">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <path d="M208 80c0-26.5 21.5-48 48-48h64c26.5 0 48 21.5 48 48v64c0 26.5-21.5 48-48 48h-8v40H464c30.9 0 56 25.1 56 56v32h8c26.5 0 48 21.5 48 48v64c0 26.5-21.5 48-48 48H464c-26.5 0-48-21.5-48-48V368c0-26.5 21.5-48 48-48h8V288c0-4.4-3.6-8-8-8H312v40h8c26.5 0 48 21.5 48 48v64c0 26.5-21.5 48-48 48H256c-26.5 0-48-21.5-48-48V368c0-26.5 21.5-48 48-48h8V280H112c-4.4 0-8 3.6-8 8v32h8c26.5 0 48 21.5 48 48v64c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V368c0-26.5 21.5-48 48-48h8V288c0-30.9 25.1-56 56-56H264V192h-8c-26.5 0-48-21.5-48-48V80z"/>
                    </svg> Generate Sitemap
                </button>
            </form>
        </div>
        <div id="real-time-clock"></div>
        <!-- Logout Button -->
        
        <div class="logout-container">
            
            <form method="post">
                <button type="submit"class="button bgc3 w100p button-outline button-md" name="logout">Logout</button>
            </form>
        </div>
    </header>

    <meta name="robots" content="noindex, nofollow">
    <script>
        // Update the clock every second
        setInterval(function() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
            var amPm = hours >= 12 ? 'PM' : 'AM';

            // Convert hours to 12-hour format
            hours = hours % 12;
            hours = hours ? hours : 12; // 12-hour clock, 0 is 12

            // Format single digits with leading zero
            hours = (hours < 10 ? "0" : "") + hours;
            minutes = (minutes < 10 ? "0" : "") + minutes;
            seconds = (seconds < 10 ? "0" : "") + seconds;

            // Update the clock element
            document.getElementById("real-time-clock").innerHTML = "Welcome Back, Admin! | Today is <?php echo date('l, F jS, Y'); ?> | Current time: " + hours + ":" + minutes + ":" + seconds + " " + amPm;
        }, 1000); // Update every second
    </script>

    <script>
        function showInfo(field) {
            var noteId = field + '-note';
            var note = document.getElementById(noteId);
            if (note) {
                note.style.display = 'block';
            } else {
                console.error('Note element not found:', noteId);
            }
        }
    </script>
<style>
        /* Adjust the styles as needed */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }
        .logo {
            height: 24px; /* Adjust logo height as needed */
        }
        .buttons {
            display: flex;
            align-items: center;
        }
        .button {
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px; /* Adjust margin between buttons */
        }
        .icon {
            width: 24px;
            height: 24px;
            margin-right: 5px; /* Adjust margin between icon and text */
        }
        #real-time-clock {
            margin-left: 10px; /* Push the clock to the right */
        }
        .logout-container {
            margin-left: 10px; /* Adjust margin between sitemap and logout button */
        }
    </style>

</head>

<body>
    <main class="py-16">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="bgc12 br12 p-3">
                        <div class="mb-4">
                        </div>
                        <ul class="nav flex-column nav-pills">
                            <!-- General Settings Tab -->
                            <li class="nav-item">
                                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general">
                                    <svg class="icon" width="16" height="16">
                                        <use xlink:href="sprite.svg#m-20"></use>
                                    </svg>
                                    General Settings
                                </a>
                            </li>
                            <!-- Logo & Favicon Tab -->
                            <li class="nav-item">
                                <a class="nav-link " id="logo-favicon-tab" data-toggle="tab" href="#logo-favicon">
                                    <svg class="icon" width="16" height="16">
                                        <use xlink:href="sprite.svg#m-21"></use>
                                    </svg>
                                    Logo & Favicon
                                </a>
                            </li>
                            <!-- SEO Tab -->
                            <li class="nav-item">
                                <a class="nav-link" id="seo-appearance-tab" data-toggle="tab" href="#seo-appearance">
                                    <svg class="icon" width="16" height="16">
                                        <use xlink:href="sprite.svg#m-22"></use>
                                    </svg>
                                    SEO & Appearance
                                </a>
                            </li>
                          
                            <!-- Banners & Adsense Tab -->
                            <li class="nav-item">
                                <a class="nav-link" id="banners-adsense-tab " data-toggle="tab" href="#banners-adsense">
                                    <svg class="icon" width="16" height="16">
                                        <use xlink:href="sprite.svg#m-24"></use>
                                    </svg>
                                    Banners & Adsense
                                </a>
                            </li>
                          
<!-- Pages Tab -->
<li class="nav-item">
    <a class="nav-link" id="page-pages-tab" data-toggle="tab" href="#page-pages-content">
        <svg class="icon" width="16" height="16">
            <use xlink:href="sprite.svg#m-26"></use>
        </svg>
        Pages
    </a>
</li>
<!-- Blog Tab -->
<li class="nav-item">
    <a class="nav-link" id="blog-articles-tab" data-toggle="tab" href="#blog-articles-content">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16">
            <path d="M162.4 196c4.8-4.9 6.2-5.1 36.4-5.1 27.2 0 28.1 .1 32.1 2.1 5.8 2.9 8.3 7 8.3 13.6 0 5.9-2.4 10-7.6 13.4-2.8 1.8-4.5 1.9-31.1 2.1-16.4 .1-29.5-.2-31.5-.8-10.3-2.9-14.1-17.7-6.6-25.3zm61.4 94.5c-53.9 0-55.8 .2-60.2 4.1-3.5 3.1-5.7 9.4-5.1 13.9 .7 4.7 4.8 10.1 9.2 12 2.2 1 14.1 1.7 56.3 1.2l47.9-.6 9.2-1.5c9-5.1 10.5-17.4 3.1-24.4-5.3-4.7-5-4.7-60.4-4.7zm223.4 130.1c-3.5 28.4-23 50.4-51.1 57.5-7.2 1.8-9.7 1.9-172.9 1.8-157.8 0-165.9-.1-172-1.8-8.4-2.2-15.6-5.5-22.3-10-5.6-3.8-13.9-11.8-17-16.4-3.8-5.6-8.2-15.3-10-22C.1 423 0 420.3 0 256.3 0 93.2 0 89.7 1.8 82.6 8.1 57.9 27.7 39 53 33.4c7.3-1.6 332.1-1.9 340-.3 21.2 4.3 37.9 17.1 47.6 36.4 7.7 15.3 7-1.5 7.3 180.6 .2 115.8 0 164.5-.7 170.5zm-85.4-185.2c-1.1-5-4.2-9.6-7.7-11.5-1.1-.6-8-1.3-15.5-1.7-12.4-.6-13.8-.8-17.8-3.1-6.2-3.6-7.9-7.6-8-18.3 0-20.4-8.5-39.4-25.3-56.5-12-12.2-25.3-20.5-40.6-25.1-3.6-1.1-11.8-1.5-39.2-1.8-42.9-.5-52.5 .4-67.1 6.2-27 10.7-46.3 33.4-53.4 62.4-1.3 5.4-1.6 14.2-1.9 64.3-.4 62.8 0 72.1 4 84.5 9.7 30.7 37.1 53.4 64.6 58.4 9.2 1.7 122.2 2.1 133.7 .5 20.1-2.7 35.9-10.8 50.7-25.9 10.7-10.9 17.4-22.8 21.8-38.5 3.2-10.9 2.9-88.4 1.7-93.9z"/>
        </svg>
        Blog
    </a>
</li>

<!-- FAQ Management Tab -->
<li class="nav-item">
    <a class="nav-link" id="faq-management-tab" data-toggle="tab" href="#faq-management">
        <svg class="icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
        </svg>
        FAQ Management
    </a>
</li>
<!-- How It Works Management Tab -->
<li class="nav-item">
    <a class="nav-link" id="how-it-works-tab" data-toggle="tab" href="#how-it-works">
        <svg class="icon" width="16" height="16">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M247.6 8C389.4 8 496 118.1 496 256c0 147.1-118.5 248-248.4 248C113.6 504 0 394.5 0 256 0 123.1 104.7 8 247.6 8zm.8 44.7C130.2 52.7 44.7 150.6 44.7 256c0 109.8 91.2 202.8 203.7 202.8 103.2 0 202.8-81.1 202.8-202.8 .1-113.8-90.2-203.3-202.8-203.3zm94 144.3v42.5H162.1V197h180.3zm0 79.8v42.5H162.1v-42.5h180.3z"/></svg>
            <use xlink:href="sprite.svg#m-xx"></use>
        </svg>
        How It Works Management
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" id="support-requests-tab" data-toggle="tab" href="#support-requests">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16">
            <path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/>
        </svg>
        Support Requests <?php echo getSupportRequestCount(); ?>
    </a>
</li>

<!-- Social Media Icons Tab -->
<li class="nav-item">
    <a class="nav-link" id="social-icons-tab" data-toggle="tab" href="#social-icons">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16">
            <path d="M181.3 32.4c17.4 2.9 29.2 19.4 26.3 36.8L197.8 128h95.1l11.5-69.3c2.9-17.4 19.4-29.2 36.8-26.3s29.2 19.4 26.3 36.8L357.8 128H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H347.1L325.8 320H384c17.7 0 32 14.3 32 32s-14.3 32-32 32H315.1l-11.5 69.3c-2.9 17.4-19.4 29.2-36.8 26.3s-29.2-19.4-26.3-36.8l9.8-58.7H155.1l-11.5 69.3c-2.9 17.4-19.4 29.2-36.8 26.3s-29.2-19.4-26.3-36.8L90.2 384H32c-17.7 0-32-14.3-32-32s14.3-32 32-32h68.9l21.3-128H64c-17.7 0-32-14.3-32-32s14.3-32 32-32h68.9l11.5-69.3c2.9-17.4 19.4-29.2 36.8-26.3zM187.1 192L165.8 320h95.1l21.3-128H187.1z"/>
        </svg>
        Social Media Icons
    </a>
</li>

<!-- Header Links Management Tab -->
<li class="nav-item">
    <a class="nav-link" id="header-links-management-tab" data-toggle="tab" href="#header-links-management">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="16" height="16">
            <path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/>
        </svg>
         Links Management
    </a>
</li>

    <!-- CSS Style Tab -->
    <li class="nav-item">
        <a class="nav-link" id="css-tab" data-toggle="tab" href="#css">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16">
<path d="M0 32l34.9 395.8L192 480l157.1-52.2L384 32H0zm313.1 80l-4.8 47.3L193 208.6l-.3 .1h111.5l-12.8 146.6-98.2 28.7-98.8-29.2-6.4-73.9h48.9l3.2 38.3 52.6 13.3 54.7-15.4 3.7-61.6-166.3-.5v-.1l-.2 .1-3.6-46.3L193.1 162l6.5-2.7H76.7L70.9 112h242.2z"/></svg>            </svg>
            Website Style
        </a>
    </li>


                            <!-- Profile Tab -->
<li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16">
            <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
        </svg>
        Admin
    </a>
</li>
<!-- Maintenance Mode Tab -->
<li class="nav-item">
    <a class="nav-link" id="maintenance-mode-tab" data-toggle="tab" href="#maintenance-mode">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="16" height="16">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M343.9 213.4L245.3 312l65.4 65.4c7.9 7.9 11.1 19.4 8.4 30.3s-10.8 19.6-21.5 22.9l-256 80c-11.4 3.5-23.8 .5-32.2-7.9S-2.1 481.8 1.5 470.5l80-256c3.3-10.7 12-18.9 22.9-21.5s22.4 .5 30.3 8.4L200 266.7l98.6-98.6c-14.3-14.6-14.2-38 .3-52.5l95.4-95.4c26.9-26.9 70.5-26.9 97.5 0s26.9 70.5 0 97.5l-95.4 95.4c-14.5 14.5-37.9 14.6-52.5 .3z"/></svg>
        </svg>
         Maintenance Mode
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" id="script-update-tab" data-toggle="tab" href="#script-update">
        <svg class="icon" width="16" height="16" viewBox="0 0 512 512">
            <path d="M352 320c88.4 0 160-71.6 160-160c0-15.3-2.2-30.1-6.2-44.2c-3.1-10.8-16.4-13.2-24.3-5.3l-76.8 76.8c-3 3-7.1 4.7-11.3 4.7H336c-8.8 0-16-7.2-16-16V118.6c0-4.2 1.7-8.3 4.7-11.3l76.8-76.8c7.9-7.9 5.4-21.2-5.3-24.3C382.1 2.2 367.3 0 352 0C263.6 0 192 71.6 192 160c0 19.1 3.4 37.5 9.5 54.5L19.9 396.1C7.2 408.8 0 426.1 0 444.1C0 481.6 30.4 512 67.9 512c18 0 35.3-7.2 48-19.9L297.5 310.5c17 6.2 35.4 9.5 54.5 9.5zM80 408a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
        </svg>
        Script Update
    </a>
</li>

<!-- Support Link with Styled Exclamation Mark -->
<li class="nav-item">
    <a class="nav-link support-link" href="https://support.riftzilla.com" target="_blank">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512">
            <path d="M48 80a48 48 0 1 1 96 0A48 48 0 1 1 48 80zM0 224c0-17.7 14.3-32 32-32H96c17.7 0 32 14.3 32 32V448h32c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H64V256H32c-17.7 0-32-14.3-32-32z"/>
        </svg>
        Support
    </a>
</li>

<li class="nav-item">
    <a class="" id="script-version-tab" data-toggle="tab" href="#script-version">
        <span class="script-container">
            <span class="script-name">QRGen</span> 
            <span class="script-version">V1.0</span>
        </span>
    </a>
</li>


                        </ul>
                    </div>
                    
                </div>
                
                <div class="col-md-9">
                    <div class="tab-content">
                        <!-- General Settings Tab -->
                        <div class="tab-pane fade show active" href="#general" id="general">
                                <form method="post">
               <!-- General Settings Form Content -->




       





<div class="form-group">
    <label for="GOOGLE_ANALYTICS">
        Google Analytics MEASUREMENT ID 
        <!-- Information symbol that shows a note when hovered over -->
        <span class="info-symbol" title="Get your Google Analytics Measurement ID here! Click to get your Measurement ID." onmouseover="showInfo('google-analytics')">ℹ️</span>
    </label>
    <!-- Input field for Google Analytics Measurement ID -->
    <input type="text" class="form-control" id="GOOGLE_ANALYTICS" name="GOOGLE_ANALYTICS" value="<?php echo htmlspecialchars($googleAnalyticsMeasurementID); ?>" placeholder="Enter your Google Analytics Measurement ID">
    <!-- Link to obtain the Google Analytics Measurement ID -->
    <div class="note" id="google-analytics-note" style="display: none;">Get your Google Analytics Measurement ID <a href="https://analytics.google.com/analytics/web/" target="_blank">here!</a></div>
</div>


<div class="form-group">
    <label for="RECAPTCHA_SITE_KEY">Recaptcha v2 robot challenge</label>
    <!-- Input field for reCAPTCHA site key -->
      <span class="info-symbol" title="Need an API key? Click here to get one!" onmouseover="showInfo('recaptcha')">ℹ️</span>
    <input type="text" class="form-control" id="RECAPTCHA_SITE_KEY" name="RECAPTCHA_SITE_KEY" value="<?php echo htmlspecialchars($reCaptchaSiteKey); ?>" placeholder="Enter reCAPTCHA site key">
    <!-- Input field for reCAPTCHA secret key -->
    <input type="text" class="form-control" id="RECAPTCHA_SECRET_KEY" name="RECAPTCHA_SECRET_KEY" value="<?php echo htmlspecialchars($reCaptchaSecretKey); ?>" placeholder="Enter reCAPTCHA secret key">
    <!-- Information symbol and link for reCAPTCHA -->
  
    <div id="recaptcha-note" class="info-note" style="display: none;">
        Need an API key? <a href="https://www.google.com/recaptcha/about/" target="_blank">Click here</a> to get one!
    </div>
</div>

<div class="form-group">
    <label for="TAWKTO_WIDGET_LINK">
        Tawk.to Direct Chat Link 
        <!-- Information symbol that shows a note when hovered over -->
        <span class="info-symbol" title="Get your Tawk.to chat link here! Click to get your chat link." onmouseover="showInfo('tawk')">ℹ️</span>
    </label>
    <!-- Input field for Tawk.to chat link -->
    <input type="text" class="form-control" id="TAWKTO_WIDGET_LINK" name="TAWKTO_WIDGET_LINK" value="<?php echo htmlspecialchars($tawkToDirectLink); ?>" placeholder="Enter your Tawk.to chat link">
    <!-- Link to obtain the Tawk.to chat link -->
    <div class="note" id="tawk-note" style="display: none;">Get your Tawk.to chat link <a href="https://www.tawk.to/" target="_blank">here!</a></div>
</div>

                
                <h3>Display/Hide</h3>

                <!-- GTRANSLATER Setting -->
                <div class="form-group">
                    <label for="gtranslater_enabled">
                    Translation widget</label>
                    <input type="checkbox" id="gtranslater_enabled" name="gtranslater_enabled" <?php echo $gtranslater_enabled ? 'checked' : ''; ?> value="on">
                </div>

                <!-- DARKMODE Setting -->
                <div class="form-group">
                    <label for="darkmode_enabled"> Darkmode widget</label>
                    <input type="checkbox" id="darkmode_enabled" name="darkmode_enabled" <?php echo $darkmode_enabled ? 'checked' : ''; ?> value="on">
                </div>
                 <div class="form-group">
                    <label for="featureboxes_enabled">
                    How it works
 </label>
                    <input type="checkbox" id="featureboxes_enabled" name="featureboxes_enabled" <?php echo $featureboxes_enabled? 'checked' : ''; ?> value="on">
                </div>
                <!-- everydomain Setting -->
                <div class="form-group">
                    <label for="blog_enabled">
                    Blogs on homepage </label>
                    <input type="checkbox" id="blog_enabled" name="blog_enabled" <?php echo $blog_enabled ? 'checked' : ''; ?> value="on">
                </div>
                
                 <!-- FAQ Setting -->
                <div class="form-group">
                    <label for="everydomaincomeswith_enabled">
                    FAQ </label>
                    <input type="checkbox" id="faq_enabled" name="faq_enabled" <?php echo $faq_enabled ? 'checked' : ''; ?> value="on">
                </div>
                 <!-- contact form -->
                <div class="form-group">
                    <label for="everydomaincomeswith_enabled">
                    Contact us Form </label>
                    <input type="checkbox" id="contact" name="contact" <?php echo $contact ? 'checked' : ''; ?> value="on">
                </div>
               
               <h2>SMTP SETTING</h2>
               
    <div class="form-group">
        <label for="smtp_host">SMTP Host</label>
        <input type="text" class="form-control" id="SMTP_HOST" name="SMTP_HOST" value="<?php echo htmlspecialchars($smtp_host); ?>" placeholder="Enter SMTP Host">
    </div>
    <div class="form-group">
        <label for="smtp_username">SMTP Username</label>
        <input type="text" class="form-control" id="SMTP_USERNAME" name="SMTP_USERNAME" value="<?php echo htmlspecialchars($smtp_username); ?>" placeholder="Enter SMTP Username">
    </div>
    <div class="form-group">
        <label for="smtp_password">SMTP Password</label>
        <input type="password" class="form-control" id="SMTP_PASSWORD" name="SMTP_PASSWORD" value="<?php echo htmlspecialchars($smtp_password); ?>" placeholder="Enter SMTP Password">
    </div>
    <div class="form-group">
        <label for="smtp_port">SMTP Port</label>
        <input type="number" class="form-control" id="SMTP_PORT" name="SMTP_PORT" value="<?php echo htmlspecialchars($smtp_port); ?>" placeholder="Enter SMTP Port">
    </div>
    <div class="form-group">
        <label for="smtp_encryption">SMTP Encryption</label>
        <input type="text" class="form-control" id="SMTP_ENCRYPTION" name="SMTP_ENCRYPTION" value="<?php echo htmlspecialchars($smtp_encryption); ?>" placeholder="Enter SMTP Encryption">
    </div>

                <button type="submit" class="btn btn-primary mt-2" name="update_env">Update General Settings</button>
            </form>
            

        </div>



<div class="tab-pane fade"  href="#seo-appearance" id="seo-appearance">
    <form method="post">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($title) ?>">
        </div>
        <div class="form-group">
            <label for="seo_description">SEO Description</label>
            <input type="text" class="form-control" id="seo_description" name="seo_description" value="<?= htmlspecialchars($seoDescription) ?>">
        </div>
        <div class="form-group">
            <label for="seo_keywords">SEO Keywords</label>
            <input type="text" class="form-control" id="seo_keywords" name="seo_keywords" value="<?= htmlspecialchars($seoKeywords) ?>">
        </div>
        <div class="form-group">
            <label for="open_graph_title">Open Graph Title</label>
            <input type="text" class="form-control" id="open_graph_title" name="open_graph_title" value="<?= htmlspecialchars($openGraphTitle) ?>">
        </div>
        <div class="form-group">
            <label for="open_graph_description">Open Graph Description</label>
            <input type="text" class="form-control" id="open_graph_description" name="open_graph_description" value="<?= htmlspecialchars($openGraphDescription) ?>">
        </div>
        <div class="form-group">
            <label for="index_page_title">Index Page Title</label>
            <input type="text" class="form-control" id="index_page_title" name="index_page_title" value="<?= htmlspecialchars($indexPageTitle) ?>">
        </div>
        <div class="form-group">
            <label for="index_page_lead_paragraph">Index Page Lead Paragraph</label>
            <textarea class="form-control" id="index_page_lead_paragraph" name="index_page_lead_paragraph"><?= htmlspecialchars($indexPageLead) ?></textarea>
        </div>
        <div class="form-group">
            <label for="footer_content">Footer Content</label>
            <textarea class="form-control" id="footer_content" name="footer_content"><?= htmlspecialchars($footerContent) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-2" name="update_seo_appearance">Update SEO Settings</button>
    </form>
</div>

    <!-- CSS Style Form -->
    <div class="tab-pane fade" id="css" role="tabpanel">
        <form method="post">
            <!-- Body Background Color -->
            <div class="form-group">
                <label for="body_background"> Background Color</label>
                <input type="color" class="form-control" id="body_background" name="body_background" value="<?= htmlspecialchars($bodyBackground) ?>">
            </div>
            <!-- .c3 Color -->
            <div class="form-group">
                <label for="c3_color">Site Base Color</label>
                <input type="color" class="form-control" id="c3_color" name="c3_color" value="<?= htmlspecialchars($c3Color) ?>">
            </div>
            <!-- .dark .dark-c8 Color -->
            <div class="form-group">
                <label for="dark_c8_color">Site Base Color on Night Mode </label>
                <input type="color" class="form-control" id="dark_c8_color" name="dark_c8_color" value="<?= htmlspecialchars($darkC8Color) ?>">
            </div>
            <button type="submit" class="btn btn-primary mt-2" name="update_css">Update website Style</button>
        </form>
    </div>

<div class="tab-pane fade" id="social-icons">
    <!-- Form for adding new social media link -->
    <form method="post">
        <div class="form-group">
            <label for="socialMediaTitle">Title</label>
            <input type="text" id="socialMediaTitle" name="socialMediaTitle" class="form-control"placeholder="eg: Instagram...">
        </div>
     <div class="form-group">
    <label for="socialMediaClass">SVG Icon</label>
    <input type="text" id="socialMediaClass" name="socialMediaClass" class="form-control" placeholder="SVG icon from fontawesome.com">
</div>
<a> <a href="https://fontawesome.com/" target="_blank">Get your Icon SVG Here!</a>
        <div class="form-group">
            <label for="socialMediaLink">Link</label>
            <input type="text" id="socialMediaLink" name="socialMediaLink" class="form-control"placeholder="eg: https://twitter.com/">
        </div>
                <p>Icons will appear in the footer and mobile navigation.</p>

        <button type="submit" class="btn btn-primary" name="add_social_media">Add Social Media</button>
    </form>

  <!-- Display list of social media links -->
<ul>
    <?php foreach ($socialMediaLinks as $socialMediaLink): ?>
        <li>
            <span class="<?php echo htmlspecialchars($socialMediaLink['icon_class']); ?>"></span>
            <a href="<?php echo htmlspecialchars($socialMediaLink['link']); ?>"><?php echo htmlspecialchars($socialMediaLink['name']); ?></a>
            <!-- Button to delete social media link -->
            <form method="post" style="display: inline;">
                <input type="hidden" name="social_media_id" value="<?php echo $socialMediaLink['id']; ?>">
                <button type="submit" name="delete_social_media">Delete</button>
            </form>
            <!-- Button to edit social media link (opens modal) -->
            <button type="button" class="edit-social-media-btn" data-toggle="modal" data-target="#editSocialMediaModal<?php echo $socialMediaLink['id']; ?>">Edit</button>
        </li>
    <?php endforeach; ?>
</ul>

<!-- Modal for editing social media link -->
<?php foreach ($socialMediaLinks as $socialMediaLink): ?>
<div class="modal fade" id="editSocialMediaModal<?php echo $socialMediaLink['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editSocialMediaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSocialMediaModalLabel">Edit Social Media Link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="social_media_id" value="<?php echo $socialMediaLink['id']; ?>">
                    <div class="form-group">
                        <label for="socialMediaTitle<?php echo $socialMediaLink['id']; ?>">Title</label>
                        <input type="text" id="socialMediaTitle<?php echo $socialMediaLink['id']; ?>" name="socialMediaTitle" class="form-control" value="<?php echo htmlspecialchars($socialMediaLink['name']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="socialMediaClass<?php echo $socialMediaLink['id']; ?>">Icon Class</label>
                        <input type="text" id="socialMediaClass<?php echo $socialMediaLink['id']; ?>" name="socialMediaClass" class="form-control" value="<?php echo htmlspecialchars($socialMediaLink['icon_class']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="socialMediaLink<?php echo $socialMediaLink['id']; ?>">Link</label>
                        <input type="text" id="socialMediaLink<?php echo $socialMediaLink['id']; ?>" name="socialMediaLink" class="form-control" value="<?php echo htmlspecialchars($socialMediaLink['link']); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary" name="edit_social_media">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
<!-- New Tab: Logo & Favicon Content -->
<div class="tab-pane fade" href="#logo-favicon" id="logo-favicon">
    <!-- Include your form for Logo & Favicon here -->
    <form method="post" action="" enctype="multipart/form-data">
    <!-- Your form fields go here -->
    <div class="form-group">
        <label for="logo">Dark Logo</label>
        <input type="file" class="form-control" id="logo" name="logo">
        <!-- Display current logo preview -->
        <?php if (file_exists("uploads/logo.png")) : ?>
            <img src="uploads/logo.png" alt="Current Logo" class="logo-preview">
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="logodark">Light Logo</label>
        <input type="file" class="form-control" id="logodark" name="logodark">
        <!-- Display current logo preview -->
        <?php if (file_exists("uploads/logodark.png")) : ?>
            <img src="uploads/logodark.png" alt="Current Logo" class="logo-preview">
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="favicon">Favicon</label>
        <input type="file" class="form-control" id="favicon" name="favicon">
        <!-- Display current favicon preview -->
        <?php if (file_exists("uploads/favicon.png")) : ?>
            <img src="uploads/favicon.png" alt="Current Favicon" class="favicon-preview">
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="open_graph_image">Open Graph Image</label>
        <input type="file" class="form-control" id="open_graph_image" name="open_graph_image">
        <!-- Display current Open Graph Image preview -->
        <?php if (file_exists("uploads/opengraph.png")) : ?>
            <img src="uploads/opengraph.png" alt="Current Open Graph Image" class="open-graph-image-preview">
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary mt-2" name="update_logo_favicon">Update Logo & Favicon</button>
</form>
</div>



<!-- FAQ Management Content -->
<div class="tab-pane fade" id="faq-management" role="tabpanel">
    <div class="container">
        <h2>FAQ Management</h2>
        <form action="dashboard.php" method="post">
            <input type="hidden" name="add_faq" value="1">
            <div class="form-group">
                <label for="question">Question:</label>
                <input type="text" class="form-control" id="question" name="question" required>
            </div>
            <div class="form-group">
                <label for="answer">Answer:</label>
                <textarea class="form-control" id="answer" name="answer" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add FAQ</button>
        </form>

        <h2 class="mt-4">Existing FAQs</h2>
        <?php if ($faqs): ?>
            <ul class="list-group">
                <?php foreach ($faqs as $faq): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo htmlspecialchars($faq['question']); ?></strong>
                            <p><?php echo htmlspecialchars($faq['answer']); ?></p>
                        </div>
                        <div>
                            <button class="btn btn-warning btn-sm mr-2" onclick="editFAQ(<?php echo $faq['id']; ?>, '<?php echo htmlspecialchars(addslashes($faq['question'])); ?>', '<?php echo htmlspecialchars(addslashes($faq['answer'])); ?>')">Edit</button>
                            <form action="dashboard.php" method="post" style="display:inline-block;">
                                <input type="hidden" name="delete_faq" value="1">
                                <input type="hidden" name="faq_id" value="<?php echo $faq['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No FAQs found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Edit FAQ Modal -->
<div class="modal fade" id="editFAQModal" tabindex="-1" aria-labelledby="editFAQModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="dashboard.php" method="post">
                <input type="hidden" name="edit_faq" value="1">
                <input type="hidden" name="faq_id" id="editFaqId">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFAQModalLabel">Edit FAQ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editQuestion">Question:</label>
                        <input type="text" class="form-control" id="editQuestion" name="question" required>
                    </div>
                    <div class="form-group">
                        <label for="editAnswer">Answer:</label>
                        <textarea class="form-control" id="editAnswer" name="answer" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editFAQ(id, question, answer) {
        document.getElementById('editFaqId').value = id;
        document.getElementById('editQuestion').value = question;
        document.getElementById('editAnswer').value = answer;
        $('#editFAQModal').modal('show');
    }
</script>

<!-- HTML Form for Managing Header Links -->
<div id="header-links-management" class="tab-pane fade">

    <!-- Form to Add a New Header Link -->
    <h4>Add New Link (will appear in header, footer, and mobile navigation)</h4>
    <form method="post">
        <label for="new_link_title">Title:</label>
        <input type="text" id="new_link_title" name="title" required>
        <label for="new_link_url">URL:</label>
        <input type="text" id="new_link_url" name="url" required>
        <button type="submit" name="add_link">Add Link</button>
    </form>

    <!-- Display Existing Header Links -->
    <h4>Existing Links</h4>
    <ul>
        <?php
        $headerLinks = getHeaderLinks();
        foreach ($headerLinks as $link) {
            echo '<li>';
            if ($link['title'] === 'Home' || $link['title'] === 'Blog' || $link['title'] === 'Contact Us') {
                // If it's one of the default links, only show hide/display option
                echo '<span>' . $link['title'] . '</span>';
                echo '<form method="post" style="display: inline;">
                          <input type="hidden" name="link_id" value="' . $link['id'] . '">';
                if ($link['enabled']) {
                    echo '<input type="hidden" name="enabled" value="0">
                          <button type="submit" name="toggle_link">Hide</button>';
                } else {
                    echo '<input type="hidden" name="enabled" value="1">
                          <button type="submit" name="toggle_link">Display</button>';
                }
                echo '</form>';
            } else {
                // For other links, show edit and delete options
                echo '<a href="' . $link['url'] . '">' . $link['title'] . '</a>';
                echo '<button class="edit-link btn btn-primary" data-id="' . $link['id'] . '" data-title="' . $link['title'] . '" data-url="' . $link['url'] . '">Edit</button>';
                echo '<form method="post" style="display: inline;">
                          <input type="hidden" name="link_id" value="' . $link['id'] . '">
                          <button type="submit" name="delete_link" class="btn btn-danger" >Delete</button>
                      </form>';
            }
            echo '</li>';
        }
        ?>
    </ul>
</div>

<!-- Modal for Editing Header Link -->
<div id="editLinkModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Header Link</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editLinkForm" method="post">
                    <input type="hidden" id="edit_link_id" name="link_id">
                    <div class="form-group">
                        <label for="edit_link_title">Title:</label>
                        <input type="text" id="edit_link_title" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_link_url">URL:</label>
                        <input type="text" id="edit_link_url" name="url" class="form-control" required>
                    </div>
                    <button type="submit" name="edit_link" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.edit-link').click(function() {
            var linkId = $(this).data('id');
            var title = $(this).data('title');
            var url = $(this).data('url');

            $('#edit_link_id').val(linkId);
            $('#edit_link_title').val(title);
            $('#edit_link_url').val(url);

            $('#editLinkModal').modal('show');
        });
    });
</script>
<style>
    #how-it-works .icon-svg {
        width: 30px; /* Adjust the width as needed */
        height: 30px; /* Adjust the height as needed */
    }
</style>

<!-- Maintenance Mode Form -->
<div class="tab-pane fade" id="maintenance-mode" role="tabpanel">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="maintenance_title">Title:</label>
            <input type="text" class="form-control" id="maintenance_title" name="title" value="<?php echo htmlspecialchars($maintenanceTitle); ?>" required>
        </div>
        <div class="form-group">
            <label for="maintenance_message">Message:</label>
            <textarea class="form-control" id="maintenance_message" name="message" rows="4" required><?php echo htmlspecialchars($maintenanceMessage); ?></textarea>
        </div>
        <div class="form-group">
            <label for="maintenance_active">Maintenance Mode:</label>
            <select class="form-control" id="maintenance_active" name="active" required>
                <option value="1" <?php echo ($maintenanceActive == 1) ? 'selected' : ''; ?>>Enable</option>
                <option value="0" <?php echo ($maintenanceActive == 0) ? 'selected' : ''; ?>>Disable</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" name="update_maintenance">Update Maintenance Mode</button>
    </form>
</div>


<!-- How It Works Management Tab -->
<div class="tab-pane fade" id="how-it-works" role="tabpanel" aria-labelledby="how-it-works-tab">
    <!-- Form for adding new How It Works item -->
    <form method="post">
        <div class="form-group">
            <label for="iconTitle"> Title</label>
            <input type="text" id="iconTitle" name="iconTitle" class="form-control" placeholder=" Title" required>
        </div>
        <div class="form-group">
            <label for="iconSVG">Icon SVG</label>
            <textarea id="iconSVG" name="iconSVG" class="form-control" placeholder="Icon SVG" required></textarea>
            <a> <a href="https://fontawesome.com/" target="_blank">Get your Icon SVG Here!</a>

        </div>
        
        <div class="form-group">
            <label for="iconDescription"> Description</label>
            <input type="text" id="iconDescription" name="iconDescription" class="form-control" placeholder=" Description" required>
        </div>
        <button type="submit" class="btn btn-primary" name="add_how_it_works">Add How It Works</button>
    </form>

    <!-- Existing How It Works Items -->
    <h2 class="mt-4">Existing How It Works Items</h2>
    <?php if ($howItWorksItems): ?>
        <ul class="list-group">
            <?php foreach ($howItWorksItems as $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo htmlspecialchars($item['icon_title']); ?></strong>
                        <p><?php echo htmlspecialchars($item['icon_description']); ?></p>
                        <div class="icon-svg"><?php echo $item['icon_svg']; ?></div>
                    </div>
                    <div>
                        <button class="btn btn-warning btn-sm mr-2 edit-btn" data-toggle="modal" data-target="#editHowItWorksModal" data-item-id="<?php echo htmlspecialchars($item['id']); ?>" data-icon-title="<?php echo htmlspecialchars($item['icon_title']); ?>" data-icon-svg="<?php echo htmlspecialchars($item['icon_svg']); ?>" data-icon-description="<?php echo htmlspecialchars($item['icon_description']); ?>">Edit</button>
                        <form action="dashboard.php" method="post" style="display:inline-block;">
                            <input type="hidden" name="delete_how_it_works" value="1">
                            <input type="hidden" name="how_it_works_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No How It Works items found.</p>
    <?php endif; ?>
</div>

<!-- Modal for editing How It Works item -->
<div class="modal fade" id="editHowItWorksModal" tabindex="-1" role="dialog" aria-labelledby="editHowItWorksModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editHowItWorksModalLabel">Edit How It Works Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="post">
                    <input type="hidden" id="editItemId" name="editItemId">
                    <div class="form-group">
                        <label for="editIconTitle">Icon Title</label>
                        <input type="text" id="editIconTitle" name="editIconTitle" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editIconSVG">Icon SVG</label>
                        <textarea id="editIconSVG" name="editIconSVG" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editIconDescription">Icon Description</label>
                        <input type="text" id="editIconDescription" name="editIconDescription" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary" name="edit_how_it_works">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.edit-btn', function () {
        var itemId = $(this).data('item-id');
        var iconTitle = $(this).data('icon-title');
        var iconSVG = $(this).data('icon-svg');
        var iconDescription = $(this).data('icon-description');

        $('#editItemId').val(itemId);
        $('#editIconTitle').val(iconTitle);
        $('#editIconSVG').val(iconSVG);
        $('#editIconDescription').val(iconDescription);
    });
</script>


<!-- Script Update Content -->
<div class="tab-pane fade" id="script-update" role="tabpanel">
    <div class="container">
        <h2>Script Update</h2>
       <p>Please make sure to backup your current script before applying the update.</p>
        <p>Upload the update file found in your purchase files folder named <strong>update x.xx</strong> along with the number of the update.</p>
        <p><strong>Note:</strong> Please upload only the <strong>update.zip</strong> file.</p>
        <form action="dashboard.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="updateFile">Upload Update Zip File:</label>
                <input type="file" class="form-control" id="updateFile" name="update_file" accept=".zip">
            </div>
            <button type="submit" class="btn btn-primary">Apply Update</button>
        </form>
    </div>
</div>





<div class="tab-pane fade" href="#page-pages-content" id="page-pages-content">
    <!-- Display list of pages -->
    <ul>
        <?php foreach ($pages as $page): ?>
        <li>
            <!-- Check if the page title exists -->
            <?php if (!empty($page["title"])): ?>
                <!-- Display the page title and link -->
                <a href="/page.php?id=<?php echo $page['id']; ?>"><?php echo htmlspecialchars($page["title"]); ?></a>
            <?php else: ?>
                <!-- If the page title is empty, display a placeholder text -->
                <span>Untitled Page</span>
            <?php endif; ?>
            <!-- Button to open edit modal -->
            <button type="button" class="edit-page-btn" data-toggle="modal" data-target="#editPageModal<?php echo $page['id']; ?>">Edit</button>
            <!-- Button to delete page -->
          <form method="post" style="display: inline;">
    <input type="hidden" name="page_id" value="<?php echo $page['id']; ?>">
    <button type="submit" name="delete_page" class="btn btn-danger">Delete</button>
</form>

        </li>
        <?php endforeach; ?>
    </ul>

    <!-- Modal for editing page -->
    <?php foreach ($pages as $page): ?>
    <div class="modal fade" id="editPageModal<?php echo $page['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editPageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPageModalLabel">Edit Page</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" name="page_id" value="<?php echo $page['id']; ?>">
                        <div class="form-group">
                            <label for="title">Page Title</label>
                            <input type="text" name="title" class="form-control" id="title" value="<?php echo htmlspecialchars($page["title"]); ?>" placeholder="Page Title">
                        </div>
                        <div class="form-group">
                            <label for="content">Page Content</label>
                            <!-- Use CKEditor for editing content -->
                            <?php
                            // Retrieve page content from the database
                            $stmt = $pdo->prepare("SELECT content, meta_description, keywords, include_header, include_footer FROM pages WHERE id = ?");
                            $stmt->execute([$page['id']]);
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <textarea name="content" id="content-<?php echo $page['id']; ?>" class="form-control" placeholder="Page Content"><?php echo htmlspecialchars($row['content']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="meta_description">SEO Meta Description</label>
                            <textarea name="meta_description" class="form-control" placeholder="Meta Description"><?php echo isset($row['meta_description']) ? htmlspecialchars($row['meta_description']) : ''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="keywords">SEO Keywords</label>
                            <input type="text" name="keywords" class="form-control" value="<?php echo isset($row['keywords']) ? htmlspecialchars($row['keywords']) : ''; ?>" placeholder="Keywords">
                        </div>
                        <div class="form-group">
                            <label for="include_header">Include in Header</label>
                            <input type="checkbox" name="include_header" value="1" <?php echo $row['include_header'] ? 'checked' : ''; ?>>
                        </div>
                        <div class="form-group">
                            <label for="include_footer">Include in Footer</label>
                            <input type="checkbox" name="include_footer" value="1" <?php echo $row['include_footer'] ? 'checked' : ''; ?>>
                        </div>
                        <button type="submit" name="edit_page" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Form for adding a new page -->
    <form method="post">
        <input type="text" name="title" placeholder="Page Title">
        <!-- Use CKEditor for adding new page content -->
        <textarea name="content" id="content-new" placeholder="Page Content"></textarea>
        <div class="form-group">
            <label for="meta_description">SEO Meta Description</label>
            <textarea name="meta_description" class="form-control" placeholder="Meta Description"><?php echo isset($page['meta_description']) ? htmlspecialchars($page['meta_description']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="keywords">SEO Keywords</label>
            <input type="text" name="keywords" class="form-control" value="<?php echo isset($page['keywords']) ? htmlspecialchars($page['keywords']) : ''; ?>" placeholder="Keywords">
        </div>
        <div class="form-group">
            <label for="include_header">Include in Header</label>
            <input type="checkbox" name="include_header" value="1">
        </div>
        <div class="form-group">
            <label for="include_footer">Include in Footer</label>
            <input type="checkbox" name="include_footer" value="1">
        </div>
        <button type="submit" name="add_page">Add Page</button>
    </form>

    <!-- Create CKEditor instance for editing existing pages -->
    <script>
        <?php foreach ($pages as $page): ?>
        ClassicEditor
            .create(document.querySelector('#content-<?php echo $page['id']; ?>'), {
                // Enable image uploads
                image: {
                    toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight'],
                    styles: ['full', 'alignLeft', 'alignRight']
                },
                // Define the upload URL for images
                ckfinder: {
                    uploadUrl: '/upload.php', // Adjust the path if necessary
                    options: {}
                }
            })
            .then(editor => {
                console.log('Editor for editing page <?php echo $page['id']; ?> was initialized', editor);
            })
            .catch(error => {
                console.error('Error initializing CKEditor for editing page <?php echo $page['id']; ?>', error);
            });
        <?php endforeach; ?>
    </script>

    <!-- Create CKEditor instance for adding new pages -->
    <script>
        ClassicEditor
            .create(document.querySelector('#content-new'), {
                // Enable image uploads
                image: {
                    toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight'],
                    styles: ['full', 'alignLeft', 'alignRight']
                },
                // Define the upload URL for images
                ckfinder: {
                    uploadUrl: '/upload.php', // Adjust the path if necessary
                    options: {}
                }
            })
            .then(editor => {
                console.log('Editor for adding new page was initialized', editor);
            })
            .catch(error => {
                console.error('Error initializing CKEditor for adding new page', error);
            });
    </script>
</div>


<!-- Include CKEditor script -->
<script src="https://cdn.ckeditor.com/ckeditor5/33.0.0/classic/ckeditor.js"></script>

<div class="tab-pane fade" href="#blog-articles-content" id="blog-articles-content">

    <!-- Display list of blog articles -->
    <?php foreach ($articles as $article): ?>
        <div class="article">
               <!-- Display the featured image -->
            <img src="<?php echo htmlspecialchars($article['featured_image']); ?>" alt="Featured Image" style=" width: 80px;">
            <!-- Display the article title and link -->
            <h3><a href="/article.php?id=<?php echo $article['id']; ?>"><?php echo htmlspecialchars($article['title']); ?></a></h3>
         

            <!-- Button to open edit modal -->
            <button type="button" class="edit-article-btn" data-toggle="modal" data-target="#editArticleModal<?php echo $article['id']; ?>">Edit</button>
     <!-- Button to delete article -->
<form method="post" style="display: inline;">
    <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
    <button type="submit" name="delete_article" class="btn btn-danger">Delete</button>
</form>
        </div>


        <!-- Modal for editing article -->
        <div class="modal fade" id="editArticleModal<?php echo $article['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editArticleModalLabel" aria-hidden="true">
            <!-- Modal content -->
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editArticleModalLabel">Edit Article</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" enctype="multipart/form-data"> <!-- Add enctype attribute for file upload -->
                            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                            <div class="form-group">
                                <label for="title">Article Title</label>
                                <input type="text" name="title" class="form-control" id="title" value="<?php echo htmlspecialchars($article['title']); ?>" placeholder="Article Title">
                            </div>
                            <div class="form-group">
                                <label for="content">Article Content</label>
                                <!-- Use CKEditor for editing content -->
                                <textarea name="content" id="content-<?php echo $article['id']; ?>" class="form-control article-content" placeholder="Article Content"><?php echo htmlspecialchars($article['content']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="author">Article Author</label>
                                <input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($article['author']); ?>" placeholder="Article Author">
                            </div>
                            <div class="form-group">
                                <label for="meta_description">Meta Description</label>
                                <textarea name="meta_description" class="form-control" placeholder="SEO Meta Description"><?php echo isset($article['meta_description']) ? htmlspecialchars($article['meta_description']) : ''; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="keywords">Keywords</label>
                                <input type="text" name="keywords" class="form-control" value="<?php echo isset($article['keywords']) ? htmlspecialchars($article['keywords']) : ''; ?>" placeholder=" SEO Keywords">
                            </div>
                            <!-- Input field for uploading featured image -->
                            <div class="form-group">
                                <label for="featured_image">Article Thumbnail</label>
                                <input type="file" name="featured_image"> <!-- Ensure the name attribute is "featured_image" -->
                            </div>
                            <button type="submit" name="edit_article" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach; ?>
         <!-- Form for adding a new article -->
<form method="post" enctype="multipart/form-data"> <!-- Add enctype attribute for file upload -->
    <input type="text" name="title" placeholder="Article Title">
    <!-- Use CKEditor for adding new article content -->
    <textarea name="content" id="content-new-article" class="form-control article-content" placeholder="Article Content"></textarea>
    <input type="text" name="author" placeholder="Article Author">
    <input type="text" name="meta_description" placeholder=" SEO Meta Description">
    <input type="text" name="keywords" placeholder=" SEO Keywords">
                                <label for="title">Article Thumbnail ,File Types: PNG, JPG, JPEG </label>

    <!-- Input field for uploading featured image -->
     <input type="file" name="featured_image" required> <!-- Ensure the name attribute is "featured_image" and add the required attribute -->

    <button type="submit" name="add_article">Add Article</button>
</form>

   <!-- JavaScript to initialize CKEditor for existing articles -->
<script>
    <?php foreach ($articles as $article): ?>
    ClassicEditor
        .create(document.querySelector('#content-<?php echo $article['id']; ?>'), {
            // Enable image uploads
            image: {
                toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight'],
                styles: ['full', 'alignLeft', 'alignRight']
            },
            // Define the upload URL for images
            ckfinder: {
                uploadUrl: '/upload.php', // Adjust the path if necessary
                options: {}
            }
        })
        .then(editor => {
            console.log('Editor for editing article <?php echo $article['id']; ?> was initialized', editor);
        })
        .catch(error => {
            console.error('Error initializing CKEditor for editing article <?php echo $article['id']; ?>', error);
        });
    <?php endforeach; ?>

    // Initialize CKEditor for adding new articles
    ClassicEditor
        .create(document.querySelector('#content-new-article'), {
            // Enable image uploads
            image: {
                toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight'],
                styles: ['full', 'alignLeft', 'alignRight']
            },
            // Define the upload URL for images
            ckfinder: {
                uploadUrl: '/upload.php', // Adjust the path if necessary
                options: {}
            }
        })
        .then(editor => {
            console.log('Editor for adding new article was initialized', editor);
        })
        .catch(error => {
            console.error('Error initializing CKEditor for adding new article', error);
        });
</script>


 
        </div>



<!-- Support Requests Tab Content -->
<div class="tab-pane fade" id="support-requests" href="#support-requests role="tabpanel" aria-labelledby="support-requests-tab">
    <h2>Support Requests</h2>
    
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <!-- Display support requests -->
                <?php foreach ($supportRequests as $request): ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $request['name']; ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo $request['email']; ?></h6>
                            <p class="card-text"><?php echo $request['message']; ?></p>
                            <p class="card-text">Created at: <?php echo $request['created_at']; ?></p>
                            <!-- Form to delete the support request -->
                            <form method="post">
                                <input type="hidden" name="id" value="<?php echo $request['id']; ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Form to update contact info -->
    <form method="post">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($contactInfo['title'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($contactInfo['email'] ?? ''); ?>">
        </div>
        <button type="submit" class="btn btn-primary" name="update_contact_info">Update Contact Info</button>
    </form>
</div>







<!-- Banners & Adsense Tab -->
<div class="tab-pane fade" href="#banners-adsense" id="banners-adsense">
    <form method="post">
        <div class="form-group">
            <label for="banner1_html">top Banner </label>
            <textarea class="form-control" id="banner1_html" name="banner1_html" rows="5"><?php echo htmlspecialchars($banner1_html ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="banner2_html">buttom Banner</label>
            <textarea class="form-control" id="banner2_html" name="banner2_html" rows="5"><?php echo htmlspecialchars($banner2_html ?? ''); ?></textarea>
        </div>
        
<!-- Updated input for AdSense with the same size as Banner 2 HTML -->
<div class="form-group">
    <label for="adsense_html">AdSense verification code</label>
    <textarea class="form-control" id="adsense_html" name="adsense_html" rows="5"><?php echo htmlspecialchars($adsense_html ?? ''); ?></textarea>
</div>





        <button type="submit" class="btn btn-primary mt-2" name="update_banners_adsense">Update Banners & Adsense</button>
    </form>
</div>


<!-- Profile Tab -->
<div id="profile" href="#profile" class="tab-pane fade">
    <h3>Profile</h3>
    
    <?php if (isset($profileMessage)): ?>
        <div class="alert <?php echo $profileMessageClass; ?>"><?php echo $profileMessage; ?></div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- Display success or error messages here -->
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
        </div>
        <div class="form-group">
            <label for="password">New Password:</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        </div>
        <button type="submit" class="btn btn-primary" name="update_profile">Update Profile</button>
    </form>
     <?php if (isset($profileMessage)): ?>
        <div class="alert <?php echo $profileMessageClass; ?>"><?php echo $profileMessage; ?></div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- Display success or error messages here -->
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" disabled>
        </div>
        <div class="form-group">
            <label for="new_email">New Email:</label>
            <input type="email" class="form-control" id="new_email" name="new_email">
        </div>
        <button type="submit" class="btn btn-primary" name="update_email">Update Email</button>
    </form>
</div>



 <div id="notification-container"></div>











<style>
    /* Add this style to make the image previews small */
    .logo-preview,
    .favicon-preview,
    .open-graph-image-preview {
        width: 50px; /* Adjust the width as per your preference */
        height: auto; /* Maintain aspect ratio */
        display: block; /* Ensure each image is on a new line */
        margin-top: 5px; /* Add some space between the image and label */
    }
</style>

<style>
    .form-group input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 25px; /* Adjust the width for checkboxes */
        height: 20px; /* Adjust the height for checkboxes */
        border: 2px solid #3498db;
        border-radius: 4px;
        outline: none;
        cursor: pointer;
        position: relative;
    }

    .form-group input[type="checkbox"]:checked {
        background-color: #3498db;
        border: 2px solid #3498db;
    }

    .form-group input[type="checkbox"]:checked:after {
        content: '\2713';
        font-size: 12px;
        color: #fff;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .icon-exclamation {
    display: inline-block;
    width: 16px; /* Adjust size as needed */
    height: 16px; /* Adjust size as needed */
    background-color: #008000; /* Example background color */
    color: #fff; /* Example text color */
    text-align: center;
    font-size: 12px; /* Adjust font size as needed */
    line-height: 16px; /* Adjust line height as needed */
    border-radius: 50%; /* Make it round */
}
</style>


<style>


/* CKEditor Styles */
.ck-editor__editable {
    min-height: 200px; /* Adjust as needed */
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 15px;
    font-family: Arial, sans-serif;
}

/* CKEditor Toolbar Styles */
.ck.ck-toolbar {
    border: none;
    border-top: 1px solid #ccc;
    background-color: #f9f9f9;
    border-radius: 4px;
    padding: 5px;
}

.ck.ck-toolbar .ck-button {
    margin: 0 2px;
    padding: 6px;
    border-radius: 3px;
    transition: background-color 0.3s;
}

.ck.ck-toolbar .ck-button:hover {
    background-color: #ddd;
}

/* CKEditor Plugin Styles */
.ck.ck-toolbar .ck-button.ck-disabled {
    opacity: 0.5;
}

/* CKEditor Image Styles */
.ck.ck-toolbar .ck-button.ck-button__imageStyle {

    background-size: 20px 20px; /* Adjust size as needed */
    background-repeat: no-repeat;
    background-position: center;
}

/* CKEditor Styles for Dark Mode */
.dark-mode .ck-editor__editable,
.dark-mode .ck.ck-toolbar {
    background-color: #333;
    color: #fff;
    border-color: #444;
}

.dark-mode .ck.ck-toolbar .ck-button {
    background-color: #555;
}

.dark-mode .ck.ck-toolbar .ck-button:hover {
    background-color: #666;
}

/* Container style */
.tab-pane.fade {
    padding: 20px;
}

/* List item style for both pages and blog articles */
.tab-pane.fade .article {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 5px; /* Reduced padding for smaller size */
    margin-bottom: 5px; /* Reduced margin for smaller spacing */
    cursor: pointer;
    transition: all 0.3s ease;
}
/* List item style for both pages and blog articles */
.tab-pane.fade ul li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 5px; /* Reduced padding for smaller size */
    margin-bottom: 5px; /* Reduced margin for smaller spacing */
    cursor: pointer;
    transition: all 0.3s ease;
}
.tab-pane.fade ul li:hover {
    transform: scale(1.02);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Link style */
.tab-pane.fade a {
    color: #007bff;
    text-decoration: none;
    font-size: 16px;
}

.tab-pane.fade .article:hover {
    transform: scale(1.02);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Link style */
.tab-pane.fade a {
    color: #007bff;
    text-decoration: none;
    font-size: 16px;
}

.tab-pane.fade a:hover {
    color: #0056b3;
}

/* Button style */
.edit-delete-buttons {
    display: flex;
    align-items: center;
}
.featured-image {
    width: 50px; /* Adjust as needed */
    margin-left: 50px; /* Add margin to separate image from title */
}
.edit-article-btn, .edit-page-btn, .delete-button {
    padding: 5px 10px;
    margin-left: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.edit-article-btn:hover, .edit-page-btn:hover {
    background-color: #e0e0e0;
}

.delete-button:hover {
    background-color: #f8d7da; /* Red background for delete button */
}

/* Modal style */
.modal-content {
    border-radius: 10px;
}

.modal-header {
    border-bottom: none;
}

.modal-title {
    margin-bottom: 0;
}

.modal-body {
    padding: 5px; /* Reduced padding for smaller modal body */
}

.modal-footer {
    border-top: none;
}

/* Form style */
form {
    margin-bottom: 20px;
}

form input[type="text"],
form textarea,
form input[type="file"],
form button,
form input[type="checkbox"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

form button {
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
}

form button:hover {
    background-color: #0056b3;
}
/* Style for the delete button */
button[name="delete_social_media"] {
    background-color: #dc3545;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
}

button[name="delete_social_media"]:hover {
    background-color: #c82333;
}

/* Style for the edit button */
.edit-social-media-btn {
    background-color: #17a2b8;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
}

.edit-social-media-btn:hover {
    background-color: #138496;
}

.script-container {
    display: inline-block;
    padding: 5px 10px; /* Adjust padding as needed */
    border: 1px solid blue; /* Add border */
    border-radius: 5px; /* Add border radius */
}

.script-container:hover {
    background-color: lightblue; /* Change background color on hover */
}

.script-name, .script-version {
    color: blue;
    font-size: 1.2em; /* Adjust font size as needed */
}



/* Optional: Add custom styles if necessary */
.list-group-item .btn {
    margin-left: 5px;
}


 
</style>

<!-- Your existing scripts -->

<!-- Bootstrap JS and Popper.js -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<!-- Include CKEditor script -->
<script src="https://cdn.ckeditor.com/ckeditor5/33.0.0/classic/ckeditor.js"></script>

<!-- Create CKEditor instance for editing existing pages -->
<script>
    <?php foreach ($pages as $page): ?>
    ClassicEditor
        .create(document.querySelector('#content-<?php echo $page['id']; ?>'), {
            // Enable image uploads
            image: {
                toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight'],
                styles: ['full', 'alignLeft', 'alignRight']
            },
            // Define the upload URL for images
            ckfinder: {
                uploadUrl: '/upload.php', // Adjust the path if necessary
                options: {}
            }
        })
        .then(editor => {
            console.log('Editor for editing page <?php echo $page['id']; ?> was initialized', editor);
        })
        .catch(error => {
            console.error('Error initializing CKEditor for editing page <?php echo $page['id']; ?>', error);
        });
    <?php endforeach; ?>
</script>

<!-- Create CKEditor instance for adding new pages -->
<script>
    ClassicEditor
        .create(document.querySelector('#content-new'), {
            // Enable image uploads
            image: {
                toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight'],
                styles: ['full', 'alignLeft', 'alignRight']
            },
            // Define the upload URL for images
            ckfinder: {
                uploadUrl: '/upload.php', // Adjust the path if necessary
                options: {}
            }
        })
        .then(editor => {
            console.log('Editor for adding new page was initialized', editor);
        })
        .catch(error => {
            console.error('Error initializing CKEditor for adding new page', error);
        });
</script>

 

</body>
</html>
