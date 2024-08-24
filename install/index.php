<?php

$phpVersion = phpversion();
$phpVersionRequired = '7.2';
$phpVersionCheck = version_compare($phpVersion, $phpVersionRequired, '>=');

$envFileExists = file_exists('../.env');

$htaccessFileExists = file_exists('../.htaccess');


// If PHP version is not met, suggest updating it
if (!$phpVersionCheck) {
    echo 'Your current PHP version is ' . $phpVersion . '. Please update to PHP ' . $phpVersionRequired . '.' . PHP_EOL;
}


// Function to validate form inputs
function validateInput($input) {
    return htmlspecialchars(trim($input));
}

// Function to perform database import
function importDatabase($host, $dbname, $user, $pass) {
    try {
        // Connect to MySQL server
        $conn = new mysqli($host, $user, $pass);

        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Create database if not exists
        if (!$conn->query("CREATE DATABASE IF NOT EXISTS $dbname")) {
            throw new Exception("Error creating database: " . $conn->error);
        }

        // Select database
        if (!$conn->select_db($dbname)) {
            throw new Exception("Error selecting database: " . $conn->error);
        }

        // Import SQL file
        $sqlFile = 'database.sql'; 
        $sql = file_get_contents($sqlFile);
        if ($conn->multi_query($sql) === TRUE) {
            // Close connection
            $conn->close();
            return true; // Return true if import is successful
        } else {
            throw new Exception("Error importing database: " . $conn->error);
        }
    } catch (Exception $e) {
        return $e->getMessage(); // Return error message if an exception occurs
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form inputs
    $host = validateInput($_POST['DB_HOST']);
    $dbname = validateInput($_POST['DB_NAME']);
    $user = validateInput($_POST['DB_USER']);
    $pass = validateInput($_POST['DB_PASS']);
    $websiteUrl = validateInput($_POST['website_url']);

    // Import database
    $databaseImported = importDatabase($host, $dbname, $user, $pass);

    if ($databaseImported === true) {
        // Read .env file
        $envFile = '../.env';
        $envContent = file_get_contents($envFile);

        // Update database values in .env file
        $envContent = preg_replace('/^DB_HOST=.*/m', 'DB_HOST=' . $host, $envContent);
        $envContent = preg_replace('/^DB_NAME=.*/m', 'DB_NAME=' . $dbname, $envContent);
        $envContent = preg_replace('/^DB_USER=.*/m', 'DB_USER=' . $user, $envContent);
        $envContent = preg_replace('/^DB_PASS=.*/m', 'DB_PASS=' . $pass, $envContent);

        // Write updated content back to .env file
        file_put_contents($envFile, $envContent);

    
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Installation Wizard</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        h2 {
            margin-top: 0;
            color: #333;
        }
        .installation-step {
            margin-bottom: 30px;
        }
        .installation-step label {
            font-weight: bold;
        }
        .installation-step input[type="text"],
        .installation-step input[type="password"],
        .installation-step input[type="url"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .installation-step input[type="submit"] {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .installation-step input[type="submit"]:hover {
            background-color: #45a049;
        }
        .status {
            font-weight: bold;
        }
        .checkmark {
            color: green;
            margin-right: 5px;
        }
        .cross {
            color: red;
            margin-right: 5px;
        }
        .error {
            color: red;
            font-weight: bold;
        }
         .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-size: 24px;
            margin-top: 30px;
        }
        body, html {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    width: 100%;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: radial-gradient(ellipse at center, rgba(255,254,234,1) 0%, rgba(255,254,234,1) 35%, #B7E8EB 100%);
}
.container {
    max-width: 600px;
    margin: 50px auto;
    text-align: center; /* Center align the content */
}

.logo {
    margin-bottom: 50px; /* Add space between logo and heading */
}



h2 {
    margin-top: 20px; /* Add space between logo and the main heading */
}
     @media only screen and (max-width: 480px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
            
            h2 {
                font-size: 24px;
            }
            
            .installation-step input[type="text"],
            .installation-step input[type="password"],
            .installation-step input[type="url"],
            .installation-step input[type="submit"] {
                padding: 8px;
            }
        }

 
    </style>
 
</head>
<body>
    <div class="container">
        <h2>Welcome to Installation Wizard</h2>
    
    
         <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="https://qrgen.riftzilla.com/admin/uploads/favicon.png">

 <div class="logo">
        <img src="https://qrgen.riftzilla.com/admin/uploads/logo.png" alt="QRGen" class="logo">
        
        <!-- PHP Version Check -->
        <h2>Step 1: Server Requirements</h2>
        <div class="installation-step">
            <div class="status"><?php echo 'PHP Version: ' . ($phpVersionCheck ? '<span class="checkmark">&#10004;</span>' : '<span class="cross">&#10008;</span>'); ?> (<?php echo $phpVersion; ?>)</div>
            <?php if (!$phpVersionCheck): ?>
                <div>Please update to PHP <?php echo $phpVersionRequired; ?>.</div>
            <?php endif; ?>
        </div>

        <!-- .env File Check -->
        <div class="installation-step">
            <div class="status">.env File: <?php echo $envFileExists ? '<span class="checkmark">&#10004;</span>' : '<span class="cross">&#10008;</span>'; ?></div>
        </div>

        <!-- .htaccess File Check -->
        <div class="installation-step">
            <div class="status">.htaccess File: <?php echo $htaccessFileExists ? '<span class="checkmark">&#10004;</span>' : '<span class="cross">&#10008;</span>'; ?></div>
        </div>

        <!-- Database Configuration Form -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <h2>Step 2: Database Configuration</h2>
    <div class="installation-step">
        <label for="DB_HOST">Database Host:</label><br>
        <input type="text" id="DB_HOST" name="DB_HOST" placeholder="localhost" required><br>
    </div>
    <div class="installation-step">
        <label for="DB_NAME">Database Name:</label><br>
        <input type="text" id="DB_NAME" name="DB_NAME" placeholder="database_name" required><br>
    </div>
    <div class="installation-step">
        <label for="DB_USER">Database User:</label><br>
        <input type="text" id="DB_USER" name="DB_USER" placeholder="db_username" required><br>
    </div>
    <div class="installation-step">
        <label for="DB_PASS">Database Password:</label><br>
        <input type="password" id="DB_PASS" name="DB_PASS" placeholder="database_password" required><br>
    </div>
    <div class="installation-step">
        <label for="website_url">Website URL:</label><br>
        <input type="url" id="website_url" name="website_url" placeholder="https://" required><br>
          <small>Please include "https://"</small>
    </div>
            <div class="installation-step">
                <input type="submit" value="install">
            </div>
        </form>
<!-- Installation Success Message -->
<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !$databaseError && $databaseImported === true): ?>
    <div class="installation-step success-message">
        <h2>Installation Completed</h2>
        <p>Installation completed successfully.</p>
        <div class="login-info">
            <p><strong>Username:</strong> admin</p>
            <p><strong>Password:</strong> admin</p>
        </div>
        <p>Your website URL: <?php echo $websiteUrl; ?></p>
        <p><a href="<?php echo $websiteUrl; ?>">Visit Frontend</a> | <a href="<?php echo $websiteUrl; ?>/admin">Go to Dashboard</a></p>
        <p style="color: red;">PLEASE DELETE THE FOLDER /INSTALL FROM YOUR SERVER</p>
    </div>
<?php endif; ?>


        <!-- Installation Error Message -->
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $databaseImported !== true): ?>
            <div class="installation-step error">
                <h2>Installation Failed</h2>
                <p><?php echo $databaseImported; ?></p>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
