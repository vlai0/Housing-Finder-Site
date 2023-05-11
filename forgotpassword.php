<?php
    include "top.php";
?>

<main class="forgotpassword">
    <div class="center-wrapper">
        <section class="form-section">
            <?php
                /* Form */
                $email = "";
                $errorMessage = "";
                $confirmation = "";

                if($_SERVER["REQUEST_METHOD"] === "POST") {
                    $confirmation = "A reset password email was sent and will appear in your email if the email address is in our system.";

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

                        if($validInput) {
                            // Generate password reset token for security.
                            $token = bin2hex(random_bytes(32)); 

                            // Delete any password reset tokens.
                            $sql = "DELETE FROM tblPasswordReset ";
                            $sql .= "WHERE pfkUsername = ?";
                            $data = array($results[0]["pmkUsername"]);
                            $success = $thisDatabaseWriter->delete($sql, $data);

                            // Insert the password reset token into database.
                            $sql = "INSERT INTO tblPasswordReset (pfkUsername, fldToken) ";
                            $sql .= "VALUES(?, ?)";
                            $data = array($results[0]["pmkUsername"], $token);
                            $success = $thisDatabaseWriter->insert($sql, $data);

                            //create the email to be sent to reset password
                            $to = $email;
                            $subject = 'UVM Housing Finder Password Reset';
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
                                        <p>You're receiving this email because a request to reset your password was made.</p>
                                        <p>If you did not request to reset your password, you can safely ignore this email.</p>
                                        <p>To change your password, please follow the link below.</p>
                                    </td>
                                </tr>
                                <tr style=\"background-color: #EFEFEF;\">
                                    <td>
                                        <p style=\"width: 100%; text-align: center;\"><a href=\"https://astem.w3.uvm.edu/cs148/live-final/resetpassword.php?token=".$token."\" style=\"border-radius: 4px; background-color: #F47D20; color: white; padding: 1rem 2rem 1rem 2rem;  text-decoration: underline; font-weight: bold; text-transform: uppercase;\">Reset</a></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><i>Password reset tokens expire 5 minutes after a password reset request is made.</i></p>
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
                }
                
            ?>

            <h2>Reset Password</h2>
            <form class="primary-form" action="" method="POST">
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
                <?php
                    if($confirmation != "") {
                        print "<p style=\"color: green; text-align: center; font-family: Arial, sans-serif;\">".$confirmation."</p>";
                    }
                ?>
            </form>
        </section>
    </div>
</main>

<?php
    include "footer.php";
?>

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