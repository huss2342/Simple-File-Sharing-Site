<?php
session_start();

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Retrieve the username from the session
$username = $_SESSION['username'];

// Define the base directory for file uploads
$uploadsDirectory = '/home/ec2-user/usersInfo';

// Get the requested file from the query parameter
if (isset($_GET['file'])) {
    $requestedFile = $_GET['file'];
    
    // Validate the requested file
    if (preg_match('/^[\w_\.\-]+$/', $requestedFile)) {
        $filePath = $uploadsDirectory . '/' . $username . '/' . $requestedFile;

        // Check if the file exists
        if (file_exists($filePath)) {
            // Set the appropriate headers for file download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $requestedFile . '"');
            header('Content-Length: ' . filesize($filePath));
            
            // Read the file and output its contents
            readfile($filePath);
            exit;
        } else {
            // File not found
            die('File not found.');
        }
    } else {
        // Invalid file name
        die('Invalid file name.');
    }
} else {
    // File parameter not provided
    die('File parameter is missing.');
}
?>
