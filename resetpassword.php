<?php
    include "top.php";
?>

<main class="resetpassword">
<?php
    $errorMessage = "";
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $password = getPostData("txtPassword");
        $passwordConfirmation = getPostData("txtPasswordConfirm");
        $token = getPostData("txtToken");

        $sql = "SELECT pfkUsername FROM tblPasswordReset ";
        $sql .= "WHERE fldToken = ?";
        $data = array($token);
        $results = $thisDatabaseReader->select($sql, $data);
           
        if(!empty($results)) {
            $validInput = true;
            $username = $results[0]["pfkUsername"];

            // Verify password is at least 6 characters in length.
            if(strlen($password) < 6) {
                $validInput = false;
                $errorMessage = "Password too short. Must be at least 6 characters in length.";
            }

            // Verify password matches confirmation password.
            if($validInput && $password != $passwordConfirmation) {
                $validInput = false;
                $errorMessage = "Passwords do not match.";
            }

            if($validInput) {
                
                // Insert new password to database.
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $sql = "UPDATE tblUser SET fldPasswordHash = ? WHERE pmkUsername = ?";
                $data = array($passwordHash, $username);
                $thisDatabaseWriter->update($sql, $data);

                // Delete token.
                $sql = "DELETE FROM tblPasswordReset WHERE pfkUsername = ?";
                $data = array($username);
                $thisDatabaseWriter->delete($sql, $data);

                print "<section class=\"center-wrapper\">";
                print "<h2 style=\"font-family: Arial, sans-serif; color: white;\">Password Successfully Updated!</h2>";
                print "</section>";

            } else {
                header("Location: resetpassword.php?token=".$token."&error=".$errorMessage);
            }
        } else {
            header("Location: index.php");
        }
    }

    // Get the token from url.
    if($_SERVER["REQUEST_METHOD"] === "GET") {
        $token = isset($_GET["token"]) ? $_GET["token"] : "";

        if(isset($_GET["error"])) {
            $errorMessage = $_GET["error"];
        }

        if($token != "") {
            $sql = "SELECT fldTimestamp FROM tblPasswordReset ";
            $sql .= "WHERE fldToken = ?";
            $data = array($token);
            $results = $thisDatabaseReader->select($sql, $data);

            if(!empty($results)) {
                $timestamp = new DateTime($results[0]["fldTimestamp"]);
                $currentTimestamp = new DateTime("now");

                // Check token is not expired (must be used within 5 minutes).
                if($timestamp->diff($currentTimestamp)->i < 5) {
                    print "
                        <div class=\"center-wrapper\">
                            <section class=\"form-section\">
                                
                                <h2>Reset Password</h2>
                                <form class=\"primary-form\" action=\"\" method=\"POST\">
                                    <p class=\"form-element\">
                                        <label for=\"txtPassword\">New Password</label>
                                        <input type=\"password\" name=\"txtPassword\" placeholder=\"Enter Password\" required>
                                    </p>
                                    <p class=\"form-element\">
                                        <label for=\"txtPasswordConfirm\">Confirm New Password</label>
                                        <input type=\"password\" name=\"txtPasswordConfirm\" placeholder=\"Confirm Password\" required>
                                    </p>
                                    <p class=\"form-element\">
                                        <input type=\"hidden\" name=\"txtToken\" value=\"".$token."\">
                                    </p>
                                    <p class=\"form-element\">
                                        <button type=\"submit\">Submit</button>
                                    </p>
                                </form>
                        ";

                    /* Print Error Message */
                    if($errorMessage != "") {
                        print "<p class=\"form-error\">".$errorMessage."</p>";
                    }
                    
                    print "
                            </section>
                        </div>
                    ";
                } else {
                    print "<section class=\"center-wrapper\">";
                    print "<h2 style=\"font-family: Arial, sans-serif; color: white;\">Your password reset token has expired or does not exist!</h2>";
                    print "</section>";
                }
            } else {
                print "<section class=\"center-wrapper\">";
                print "<h2 style=\"font-family: Arial, sans-serif; color: white;\">Your password reset token has expired or does not exist!</h2>";
                print "</section>";
            }
        } else {
            print "<section class=\"center-wrapper\">";
            print "<h2 style=\"font-family: Arial, sans-serif; color: white;\">Your password reset token has expired or does not exist!</h2>";
            print "</section>";
        }
    }
?>
</main>

<?php
    include "footer.php";
?>

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