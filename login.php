<?php
    include "top.php";

    if(session_id() != "" && isset($_SESSION) && isset($_SESSION["username"])) {
        header("Location: profile.php");
    } 
?>
<main class="login">
    <div class="center-wrapper">
        <section class="form-section">
            <h2>Log In</h2>
            <form class="primary-form" action="#" method="POST">
                <p class="form-element">
                    <label for="txtUsername">Username</label>
                    <input type="text" name="txtUsername" placeholder="Enter Your Username" required>
                </p>
                <p class="form-element">
                    <label for="txtPassword">Password <a href="forgotpassword.php">(Forgot?)</a></label>
                    <input type="password" name="txtPassword" placeholder="Enter Your Password" required>
                </p>
                <p class="form-element">
                    <button type="submit">Login</button>
                </p>
            </form>
            <?php
                if($_SERVER["REQUEST_METHOD"] === "POST") {

                    $username = getPostData("txtUsername");
                    $password = getPostData("txtPassword");

                    $query = "SELECT fldPasswordHash FROM tblUser ";
                    $query .= "WHERE pmkUsername = ?";
                    $data = array($username);

                    $results = $thisDatabaseReader->select($query, $data);

                    // Check if username exists.
                    if(!empty($results)) {
                        // Verify password is correct.
                        if(password_verify($password, $results[0]["fldPasswordHash"])) {
                            // Success: Redirect user to dashboard.php and store username in session variable.
                            $_SESSION["username"] = $username;
                            header("Location: profile.php");
                            exit();
                        } else {
                            print "<p class=\"form-error\">Incorrect username or password.</p>";
                        }
                    } else {
                        print "<p class=\"form-error\">Incorrect username or password.</p>";
                    }
                }
            ?>
            <div class="form-redirects">
                <p class="form-redirect">
                    Don't have an account? <a href="register.php">Register</a>
                </p>
            </div>
        </section>
    </div>
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