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
        <?php
            session_start();

            include 'connect-DB.php';
            
            if($_SERVER["REQUEST_METHOD"] === "POST") {

                $username = $_POST["txtUsername"];
                $password = $_POST["txtPassword"];
    
                $query = "SELECT fldPasswordHash FROM tblUser ";
                $query .= "WHERE pmkUsername = ?";
                $data = array($username);

                $results = $thisDatabaseReader->select($query, $data);
                print_r($results);

                // Check if username exists.
                if(!empty($results)) {
                    // Verify password is correct.
                    if(password_verify($password, $results[0]["fldPasswordHash"])) {
                        // Success: Redirect user to dashboard.php and store username in session variable.
                        $_SESSION["username"] = $username;
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        print "<p class=\"form-error\">Incorrect username or password.</p>";
                    }
                } else {
                    print "<p class=\"form-error\">Incorrect username or password.</p>";
                }
            }
    
        ?>

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
                <li><a href="#">Home</a></li>
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
                    <h2>Log In</h2>
                    <form class="login-form" action="" method="POST">
                        <p class="form-element">
                            <label for="txtUsername">Username</label>
                            <input type="text" name="txtUsername" placeholder="Enter Your Username" required>
                        </p>
                        <p class="form-element">
                            <label for="txtPassword">Password</label>
                            <input type="password" name="txtPassword" placeholder="Enter Your Password" required>
                        </p>
                        <p class="form-element">
                            <button type="submit">Login</button>
                        </p>
                    </form>
                    <div class="form-redirects">
                        <p class="form-redirect">
                            <a href="register.html">Don't have an account?</a>
                        </p>
                        <p class="form-redirect">
                            <a href="#">Forgot your password?</a>
                        </p>
                    </div>
                </section>
            </div>
        </main>

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