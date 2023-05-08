<?php
    function getPostData($field) {
        if(!isset($_POST[$field])) {
            $data = "";
        } else {
            $data = trim($_POST[$field]);
            $data = htmlspecialchars($data);
        }
        return $data;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>UVM Housing Finder | Home</title>
            <meta name="author" content="Anthony Stem">
            <meta name="author" content="Vincent Lai">
            <meta name="description" content="">
            <link rel="stylesheet" href="css/styles.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body>
        <!-- Navigation Bar -->
        <nav class="navigation-bar">
            <div class="hamburger-button">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="navigation-close-button">
                <span>&times;</span>
            </div>
            <div class="navigation-logo">
                <!-- Subject to Change -->
                <h1>UVM Housing Finder</h1>
            </div>
            <ul class="navigation-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="#">Dorm Roommate Requests</a></li>
                <li><a href="#">Find an Apartment</a> <i class="fa-solid fa-caret-down"></i></li>
                <ul class="navigation-dropdown">
                    <li><a href="#">Subleases</a></li>
                    <li><a href="#">Roommate Requests</a></li>
                </ul>
                <li><a href="login.html" class="bullet">Log In</a></li>
            </ul>
        </nav>

        <main class="login">
            <div class="login-wrapper">
                <section class="form-section">
                    <?php

                        include "connect-DB.php";

                        /* Form */
                        $email = "";
                        $errorMessage = "";

                        if($_SERVER["REQUEST_METHOD"] === "POST") {
                            // Getting email + sanitation
                            $email = getPostData("txtEmail");

                            /* VALIDATE EMAIL */
                            $validInput = true;

                            // Check if email is valid format.
                            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $validInput = false;
                                $errorMessage = "Email is not valid.";
                            }

                            // Check if already in database.
                            if($validInput) {
                                $sql = "SELECT pmkUsername, fldFirstName FROM tblUser ";
                                $sql .= "WHERE fldEmail = ?";
                                $data = array($email);
                                $results = $thisDatabaseReader->select($sql, $data); 

                                // Don't alert user if email doesn't exist in database
                                if(empty($results)) {
                                    $validInput = false;
                                }    

                                // Generate password reset token for security.
                                $token = bin2hex(random_bytes(32)); 

                                // Delete any password reset tokens.
                                $sql = "DELETE FROM tblPasswordReset ";
                                $sql .= "WHERE pfkUsername = ?";
                                $data = array($results[0]["pmkUsername"]);
                                $suc = $thisDatabaseWriter->delete($sql, $data);

                                // Insert the password reset token into database.
                                $sql = "INSERT INTO tblPasswordReset (pfkUsername, fldToken) ";
                                $sql .= "VALUES(?, ?)";
                                $data = array($results[0]["pmkUsername"], $token);
                                $suc = $thisDatabaseWriter->insert($sql, $data);

                                //create the email to be sent to reset password
                                $to = $email;
                                $subject = 'UVM Housing Password Reset';
                                $message  = "
                                <html>
                                    <head>
                                        <title>UVM Housing Finder Password Reset Request</title>
                                    </head>
                                    <body style=\"font-family: Arial, sans-serif\">
                                        <h1>UVM Housing Finder Password Reset Request</h1>
                                        <table cellpadding=\"5\">
                                            <tr>
                                                <td>
                                                    <p><strong>Hello <span style=\"color: #66AC47\">".$results[0]["fldFirstName"]."</span>,</strong></p>
                                                    <p>If you did not request this, you can safely ignore this email.</p>
                                                    <p>You're receiving this email because a request to reset your password was made.</p>
                                                    <p>To change password, please follow the link below.</p>
                                                </td>
                                            </tr>
                                            <tr style=\"background-color: #EFEFEF;\">
                                                <td>
                                                    <p style=\"width: 100%; text-align: center;\"><a href=\"https://astem.w3.uvm.edu/cs148/live-final/resetpassword.php?token=".$token."\" style=\"border-radius: 4px; background-color: #F47D20; color: white; padding: 1rem 2rem 1rem 2rem;  text-decoration: underline; font-weight: bold; text-transform: uppercase;\">Reset</a></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p>Best Regards,</p>
                                                    <p>UVM Housing Finder</p>
                                                </td>
                                            </tr>
                                        </table>        
                                        <hr>
                                        <table>
                                            <tr>
                                                <td>
                                                    <p>If you have any questions or need assistance, reach out to the support team at <a href=\"mailto:anthony.stem@uvm.edu\">anthony.stem@uvm.edu</a>.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </body>
                                </html>";

                                $headers = "MIME-Version: 1.0" . "\r\n";
                                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                                
                                mail($to, $subject, $message, $headers);
                            }
                        }
                        
                    ?>

                    <h2>Reset Password</h2>
                    <form class="login-form" action="" method="POST">
                        <p class="form-element">
                            Enter an email and we'll send you a link to reset your password.
                        </p>
                        <p class="form-element">
                            <label for="txtEmail">Email</label>
                            <input type="text" name="txtEmail" placeholder="Enter Email" required>
                        </p>
                        <p class="form-element">
                            <button type="submit">Submit</button>
                        </p>
                    </form>
                </section>
            </div>
        </main>

        <footer>
            <section class="footer-section">
                <h2>UVM Housing Finder</h2>
            </section>
            <section class="footer-section">
                <h2>Links</h2>
                <p><a href="../index.php">Site Map</a></p>
                <p><a href="astem.w3.uvm.edu/cs148/final">Anthony Stem</a></p>
                <p><a href="vlai.w3.uvm.edu/cs148/final">Vincent Lai</a></p>
            </section>
        </footer>

        <!-- Main/Unique Section -->
        <script>
            // Handle Navigation Hamburger Button.
            const hamburgerButton = document.querySelector(".hamburger-button");
            const navigationCloseButton = document.querySelector(".navigation-close-button");
            const navigationLinks = document.querySelector(".navigation-links");

            hamburgerButton.addEventListener('click', () => {
                navigationLinks.classList.toggle("show");
                navigationCloseButton.classList.toggle("show");
            });

            navigationCloseButton.addEventListener('click', () => {
                navigationLinks.classList.toggle("show");
                navigationCloseButton.classList.toggle("show");
            });
        </script>
    </body>
</html>