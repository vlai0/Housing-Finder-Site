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

            include "connect-DB.php";

            $errorMessage = "";
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                $username = getPostData("txtUsername");
                $password = getPostData("txtPassword");
                $passwordConfirmation = getPostData("txtPasswordConfirm");
                $token = getPostData("txtToken");

                if($username != "" && $password != "" && $passwordConfirmation != "" && $token != "") {
                    $validInput = true;

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

                        print "Succ";

                        // Delete token.
                        $sql = "DELETE FROM tblPasswordReset WHERE pfkUsername = ?";
                        $data = array($username);
                        $thisDatabaseWriter->delete($sql, $data);
    
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

                $errorMessage = "";
                if(isset($_GET["error"])) {
                    echo "Funky".PHP_EOL;
                    $errorMessage = $_GET["error"];
                }

                echo $token;
                if($token != "") {
                    $sql = "SELECT pfkUsername, fldTimestamp FROM tblPasswordReset ";
                    $sql .= "WHERE fldToken = ?";
                    $data = array($token);
                    $results = $thisDatabaseReader->select($sql, $data);

                    if(!empty($results)) {
                        $username = $results[0]["pfkUsername"];
                        $timestamp = new DateTime($results[0]["fldTimestamp"]);
                        $currentTimestamp = new DateTime("now");
    
                        // Check token is not expired (must be used within 5 minutes).
                        if($timestamp->diff($currentTimestamp)->i < 5) {
                            print "
                                <div class=\"login-wrapper\">
                                    <section class=\"form-section\">
                                        
                                        <h2>Reset Password</h2>
                                        <form class=\"login-form\" action=\"\" method=\"POST\">
                                            <p class=\"form-element\">
                                                <label for=\"txtPassword\">New Password</label>
                                                <input type=\"password\" name=\"txtPassword\" placeholder=\"Enter Password\" required>
                                            </p>
                                            <p class=\"form-element\">
                                                <label for=\"txtPasswordConfirm\">Confirm New Password</label>
                                                <input type=\"password\" name=\"txtPasswordConfirm\" placeholder=\"Confirm Password\" required>
                                            </p>
                                            <p class=\"form-element\">
                                                <input type=\"hidden\" name=\"txtUsername\" value=\"".$username."\">
                                                <input type=\"hidden\" name=\"txtToken\" value=\"".$token."\">
                                            </p>
                                            <p class=\"form-element\">
                                                <button type=\"submit\">Submit</button>
                                            </p>
                                        </form>
                                        <?php
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
                            print "<h2>Your password reset token has expired!</h2>";
                        }
                    }      
                } else {
                    print "<h2>Your password reset token has expired!</h2>";
                }
            }
        ?>
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