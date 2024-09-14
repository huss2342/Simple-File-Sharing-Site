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
            // Get the file extension
            $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            // Handling csv files separately 
            if ($fileExtension === 'csv') {
                // Display CSV files as an HTML table
                $csvContent = file_get_contents($filePath);
                $lines = explode("\n", $csvContent);

                // Set the appropriate content-type header
                header('Content-Type: text/html');

                // Generate an HTML table from the CSV content
                echo '<table>';
                foreach ($lines as $line) {
                    $fields = str_getcsv($line);
                    echo '<tr>';
                    foreach ($fields as $field) {
                        echo '<td>' . htmlspecialchars($field) . '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
                exit;
            }

        

            // Handling all other more basic file extensions
            viewanyfile($filePath, $fileExtension);

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
//work on implementing this function
function viewanyfile($filePath, $fileExtension)
{
    // Now we need to get the MIME type (e.g., image/jpeg).  PHP provides a neat little interface to do this called finfo.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($filePath);

    // Get the base name of the file
    $fileName = basename($filePath);

    // Finally, set the Content-Type header to the MIME type of the file, and display the file.
    header("Content-Type: " . $mime);
    header('content-disposition: inline; filename="' . $fileName . '";');
    readfile($filePath);
}


?>