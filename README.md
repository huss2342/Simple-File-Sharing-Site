# Simple File Sharing Site

## Project Overview

This project is a simple file sharing site that supports uploading, viewing, and deleting files associated with various users. It was developed as part of a group project for a web development course.

## Features

- User authentication system (without passwords)
- File management: upload, view, and delete files
- User-specific file lists
- Secure file handling (no internal file structure revealed in URIs)
- Web security best practices implemented
- W3C Validator compliant

## Creative Portions

1. **File History Logger**
   - Logs file uploads and deletions for each user
   - Displays date, time, and action for each file
   - Option to clear logs

2. **Admin Functionality**
   - Special ADMIN account for user management
   - Add and delete users from the system
   - Admin can perform normal file operations

## Setup and Usage

1. Ensure you have a web server with PHP support.
2. Clone this repository to your web server's directory.
3. Set up the `users.txt` file in a secure location with at least three usernames (one per line).
4. Configure file permissions to allow both your user account and PHP scripts to read/write necessary files.
5. Access the site through the login page.

## Valid Usernames

user1, hussein, grader, ben, joe, nevin, ADMIN

Note: ADMIN is a special account with additional user management capabilities.

## Security Considerations

- Implements FIEO (Filter Input and Escape Output) for file names, usernames, etc.
- Passes W3C Validator with no errors or warnings
