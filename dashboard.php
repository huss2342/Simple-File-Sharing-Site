<?php
session_start();
date_default_timezone_set('America/Chicago');


// Check if the user is logged in, otherwise redirect to the login page
// Retrieve the username from the session
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    
    header('Location: login.php');
    exit;
}


//CODE FOR THE ADMINISTRATOR FEATURE

//check of the user is the ADMIN user
if($_SESSION['username'] === "ADMIN"){

          //ADMIN: deleting a user 
            if (isset($_POST['usernamedelete'])) {
                 //check if the user is in the userlist /checks if the user is truly a user
                $legit = false; 
                //stores the future deleted user
                $potential_deletion = $_POST['usernamedelete'];
                // sanitizing the username for security, also make sure it is not equal to admin
                if(( !preg_match('/^[\w_\-]+$/',  $potential_deletion)) || (trim($potential_deletion) === "ADMIN") ){
                    $_SESSION['error_message_admin'] = 'Invalid username. Please clean up your input, The ADMIN user can not be deleted. Please try again.';
                    header('Location: dashboard.php');
                    exit;
                }
                else {
                            // Read the file contents and check if the username exists
                            $potential_deletion = trim($potential_deletion);  // Trim the input
                            $usersFile1 = '/home/ec2-user/users.txt'; //users txt file
                            $usernames1 = file($usersFile1, FILE_IGNORE_NEW_LINES);
                            foreach ($usernames1 as $name1) { 
                                if (!empty($name1) && $name1 === $potential_deletion) {  // Check if name is not empty
                                    $legit = true; 
                                    break;        
                                }
                            }
                            //check for making sure the admin can not be deleted
                            if($potential_deletion === "ADMIN"){
                                $legit = false; 
                                $_SESSION['error_message_admin'] = 'Invalid username, ADMIN cannot be deleted. Please try again.';
                                header('Location: dashboard.php');
                                exit;

                            }
                            else {
                                        if($legit){
                                    //delete the user's files
                                            $username1 = $potential_deletion;
                                            $uploadsDirectory1 = '/home/ec2-user/usersInfo';
                                            //The name of the folder.
                                            $userfolder1 = $uploadsDirectory1 . '/' . $username1 . '/' ;
                                            //Get a list of all of the file names in the folder.
                                            $allfiles1 = glob( $userfolder1 . '/*'); //got this idea from https://intecsols.com/delete-files-and-folders-from-a-folder-using-php-by-intecsols/#:~:text=php%20%2F%2F%20delete%20all%20files,sub%20folders%20and%20also%20files.
                                            //Loop through the file list.
                                            foreach($allfiles1 as $afile1){
                                                //Delete the file in te user's directory
                                                unlink($afile1);
                                            }
                                            //delete the user's log
                                            
                                            $logFile1 = $uploadsDirectory1 . '/' . $username1 . '/.activity.log';
                                            // Clear the log file by truncating it
                                            file_put_contents($logFile1, '');
                                            $oldnames1 ="";
                                            $usersFile1 = '/home/ec2-user/users.txt';
                                               
                                                    //get the old names
                                                foreach ($usernames1 as $name1) {
                                                    if ((strcmp($name1,$username1)) ) {  // Check if name is not empty
                                                        //the old username should be deleted
                                                    
                                                        $oldnames1= $oldnames1.($name1."\n");
                                                    }
                                        
                                                    
                                                }
                                                //read the old names into the txt file of users
                                               //file the names into the users text file
                                                $newfile1 = fopen($usersFile1, "w"); //This syntax here was from geeksforgeeks.org
                                             
                                                fwrite($newfile1, $oldnames1 );//This syntax here was from geeksforgeeks.org
                                                 fclose($newfile1);//This syntax here was from geeksforgeeks.org
                                                
                                                // Log the user deletion activity
                                                logActivity($_SESSION['username'], "deleted the user: $username1 in addition to deleting all of their files and logs");
                                
                                        }
                                    
                                        else{ //see what I should do if the username input is not legit 
                                            $_SESSION['error_message_admin'] = 'Invalid username. Please try again.';
                                            header('Location: dashboard.php');
                                            exit;
                                        }

                            }

                 }
            }

            if (isset($_POST['usernameadd'])) {//ADMIN: handle adding a user 
             // sanitizing the username for security, also make sure it is not equal to admin
             $legit = false;
             $potential_addition = $_POST['usernameadd'];
               if(( !preg_match('/^[\w_\-]+$/',   $potential_addition)) || (trim( $potential_addition) === "ADMIN") ){
                $_SESSION['error_message_admin'] = 'Invalid username. Please clean up your input, The ADMIN user can not be re-added. Please try again.';
                header('Location: dashboard.php');
                exit;
                }
                 else {
                     $usersFile1 = '/home/ec2-user/users.txt';
                     $legit = false; //checks if the user is truly a user
                     // Read the file contents and check if the username exists
                     $potential_addition = trim($potential_addition);  // Trim the input
                     $usersFile1 = '/home/ec2-user/users.txt';
                     $usernames1 = file($usersFile1, FILE_IGNORE_NEW_LINES);
                     foreach ($usernames1 as $name1) {
                         if (!empty($name1) && $name1 === $potential_addition) {  // Check if name is not empty
                             $legit = true; 
                           
                             break;
                         }
                    
                    }

                     if($legit ){
                    
                        $_SESSION['error_message_admin'] = 'Invalid username. The user can not be added again, Please try again.';
                        header('Location: dashboard.php');
                        exit;
                     }
                     else{

                         // Read the file contents and check if the username exists
                         $username1 = trim($username1);  // Trim the input
                         $usersFile1 = '/home/ec2-user/users.txt';
                         $usernames1 = file($usersFile1, FILE_IGNORE_NEW_LINES);
                         $oldnames1 ="";

                        foreach ($usernames1 as $name1) {
                    //add usernames to string
                            $oldnames1=    $oldnames1.($name1."\n");
                            
                            
                         }
               
                      //add the new username after the checks
                    $oldnames1 = $oldnames1.($potential_addition."\n");
                     //adds the new/old names into the txt file of users

                     $newfile1 = fopen($usersFile1, "w");//This syntax here was from geeksforgeeks.org
                       fwrite( $newfile1, $oldnames1 );//This syntax here was from geeksforgeeks.org
                       fclose($newfile1);//This syntax here was from geeksforgeeks.org
                        // Log the user deletion activity
                    logActivity($_SESSION['username'], "added the user: $potential_addition");

                   }


                 }

            }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="serverStyle.css">
</head>

<body>
     <?php

if($_SESSION['username'] === "ADMIN"){ ?>
<?php
    //output the current usernames of those in the file system

    ?>

<div class="container">
   <?php outputnames();?>
</div>
    <!-- Phpself is a way to redirect a site back to itself, it is a good way to run the above username verification code when we gain a username input -->
    <!-- link:https://html.form.guide/php-form/php-form-action-self/  where php_self explanation was from for it's usage-->
    <?php if (isset($_SESSION['error_message_admin'])): ?>
        <div class="container">
                    <div class="error-message">
                        <?php echo $_SESSION['error_message_admin']; ?>
                    </div>
                </div>
                <?php endif; ?>
    <div class="container">
    <form class="login-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
         <label>Input the username to add here:</label>
         <input type="text" name="usernameadd" id="usernameadd" placeholder="Add this user" required autocomplete="off"><br><br>
         <input type="submit" value="Press to add User">
     </form>
    </div>
   <br>
   <div class="container">
     <form class="login-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
         <label>Input the username to delete here:</label>
         <input type="text" name="usernamedelete" id="usernamedelete" placeholder="Delete this User" required autocomplete="off"><br><br>
         <input type="submit" value="Press to delete the User">
     </form>
    </div>
     <?php }
//ADMIN CODE PART 1 ENDS HERE





// Define the base directory for file uploads
$uploadsDirectory = '/home/ec2-user/usersInfo';

// Create the user's directory if it doesn't exist
$userDirectory = $uploadsDirectory . '/' . $username;
if (!is_dir($userDirectory)) {
    mkdir($userDirectory, 0755, true);
}

// Retrieve the file list associated with the user
$userFiles = glob($uploadsDirectory . '/' . $username . '/*');


// Handle file upload, 
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploadFile'])) {
    $file = $_FILES['uploadFile'];

    // Validate the uploaded file   
    if ($file['error'] === UPLOAD_ERR_OK) {
        $filename = basename($file['name']);

        // Make sure the filename is in a valid format
        if (!preg_match('/^[\w_\.\-]+$/', $filename)) {
            $_SESSION['error_message'] = 'Invalid filename.';
            header('Location: dashboard.php');
            exit;
        }

        $uploadPath = $uploadsDirectory . '/' . $username . '/' . $filename;

        // unset the upload error
        unset($_SESSION['error_message']);

        // Move the uploaded file to the destination directory
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // File upload success, redirect back to the dashboard
            header('Location: dashboard.php'); 
            // Log the file upload activity
            $fileInfo = "uploaded: $filename";
            logActivity($username, $fileInfo);

            exit;
        } else {
            $uploadError = 'Failed to upload the file to server.';
            echo $uploadError;
        }
    } else {
        $uploadError = 'Error in uploading the file.';
        echo $uploadError;
    }
}


// Handle file deletion
if (isset($_GET['delete'])) {
    $filename = $_GET['delete'];
    $fileToDelete = $uploadsDirectory . '/' . $username . '/' . $filename;

    // Validate the file before deleting
    if (file_exists($fileToDelete)) {
        // Delete the file
        unlink($fileToDelete);

        // Log the file deletion activity
        logActivity($username, "deleted: $filename");

        // Redirect back to the dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        $deleteError = 'File not found.';
    }
}

// Handle log clearance
if (isset($_POST['clearLogs'])) {
    $logFile = $userDirectory . '/.activity.log';

    // Clear the log file by truncating it
    file_put_contents($logFile, '');

    // Log the log clearance activity
    logActivity($username, 'cleared logs');

    // Redirect back to the dashboard
    header('Location: dashboard.php');
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Function to log user activity
function logActivity($username, $activity)
{
    $logDirectory = '/home/ec2-user/usersInfo/' . $username;
    $logFile = $logDirectory . '/.activity.log';

    // Create the user's log directory if it doesn't exist
   
    if (!is_dir($logDirectory)) {
        mkdir($logDirectory, 0755, true);
    }

    // Format the log entry
    $timestamp = date('Y-m-d H:i:s');
    if ($activity !== 'cleared logs') {
        $logEntry = "[$timestamp] $username $activity\n";
    } else {
        $logEntry = "[$timestamp] $activity\n";
    }

    // Append the log entry to the log file
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

// Function to read log entries from the activity log file
function readActivityLog($logFile)
{
    if (file_exists($logFile)) {
        // Read the content of the log file
        $logContent = file_get_contents($logFile);

        // Split log entries into an array
        $logEntries = explode("\n", $logContent);

        // Remove empty entries and "cleared logs" entry
        $logEntries = array_filter($logEntries, function ($entry) {
            return !empty($entry) && !strpos($entry, 'cleared logs');
        });

        return $logEntries;
    }

    return [];
}

// Define the path to the user's activity log file
$logFile = $userDirectory . '/.activity.log';

// Read the log entries
$logEntries = readActivityLog($logFile);

?>





    <div class="container dashboard">
        <h1>Welcome,
            <?php echo htmlentities($username); ?>
        </h1>

        <?php if (empty($userFiles)): ?>
            <p>No available files.</p>
        <?php else: ?>
            <h2>Files</h2>
            <div class="file-list">
                <?php foreach ($userFiles as $file): ?>
                    <?php $filename = basename($file); ?>
                    <div class="file-item">
                        <div class="file-name">
                            <a href="view.php?file=<?php echo urlencode($filename); ?>" target="_blank"><?php echo htmlentities($filename); ?></a>
                        </div>
                        <div class="file-buttons">
                            <a href="download.php?file=<?php echo urlencode($filename); ?>">Download</a>
                            <a href="dashboard.php?delete=<?php echo urlencode($filename); ?>" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="upload-a-file">
            <h2>Upload a File</h2>
            <form method="POST" action="dashboard.php" enctype="multipart/form-data">
                <input type="file" name="uploadFile" required>
                <input type="submit" value="Upload">


                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="error-message">
                        <?php echo $_SESSION['error_message']; ?>
                    </div>
                <?php endif; ?>

            </form>
        </div>

        <div class="activity-log">
            <h2>Activity Log</h2>
            <?php if (!empty($logEntries)): ?>
                <ul>
                    <?php foreach ($logEntries as $logEntry): ?>
                        <li>
                            <?php echo htmlentities($logEntry); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No log entries available.</p>
            <?php endif; ?>
        </div>

        <form method="POST" action="dashboard.php">
            <input type="hidden" name="clearLogs" value="true">
            <input type="submit" value="Clear Logs">
        </form>

        <div class="logout-button">
            <a href="dashboard.php?logout=true">Logout</a>
        </div>
        <?php unset($_SESSION['error_message']); ?>
        <?php unset($_SESSION['error_message_admin']); ?>
    </div>
</body>

</html>


<?php
function outputnames(){
    //output the current usernames of those in the file system

$usersFile1 = '/home/ec2-user/users.txt';
$usernames1 = file($usersFile1, FILE_IGNORE_NEW_LINES);
?>
<br>
 <label>Current Program Users:</label>
 <br>
<?php
foreach ($usernames1 as $name1) {
    ?>
 <label ><?php echo " - ".$name1 ?></label>
 <br>
<?php
}


}
?>