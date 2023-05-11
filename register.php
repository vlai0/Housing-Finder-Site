<?php
    include "top.php";
?>
<main class="register">
    <div class="center-wrapper">
        <section class="form-section">
            <h2>Register</h2>
            <?php
                /* INITIALIZE DEFAULT FORM VALUES */
                $firstName = "";
                $lastName = "";
                $birthDate = "";
                $gender = "";
                $imageFile = "";
                $imageFileType = "";
                $description = "";
                $username = "";
                $email = "";

                $errorMessage = "";

                /* HANDLE FORM POST REQUESTS */
                if($_SERVER["REQUEST_METHOD"] === "POST") {
                    /* ##### RETRIEVE DATA & SANITIZE ##### */
                    // txtFirstName, txtLastName, dateBirthDate, selectGender, imgProfilePicture, txtDescription, txtUsername, txtEmail, txtPassword, txtConfirmPassword
                    $firstName = getPostData("txtFirstName");
                    $lastName = getPostData("txtLastName");
                    $birthDate = isset($_POST["dateBirthDate"]) ? $_POST["dateBirthDate"] : "";
                    $gender = getPostData("selectGender");
                    if(is_uploaded_file($_FILES["imgProfilePicture"]["tmp_name"])) {
                        $imageFile = basename($_FILES["imgProfilePicture"]["name"]);
                        $imageFileType = strtolower(pathinfo($imageFile, PATHINFO_EXTENSION));
                    }
                    $description = getPostData("txtDescription");
                    $username = getPostData("txtUsername");
                    $email = getPostData("txtEmail");
                    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

                    // Do not sanitize passwords. They will be hashed.
                    $password = isset($_POST["txtPassword"]) ? $_POST["txtPassword"] : "";
                    $passwordConfirmation = isset($_POST["txtConfirmPassword"]) ? $_POST["txtConfirmPassword"] : "";

                    /* ##### VALIDATE DATA ##### */
                    $validInput = true;

                    /* VALIDATE firstName AND lastName */
                    if(strlen($firstName) == 0 || $firstName == "") {
                        $validInput = false;
                        $errorMessage = "First name must contain at least 1 character.";
                    }

                    if($validInput && !ctype_alpha($firstName)) {
                        $validInput = false;
                        $errorMessage = "First name can only contain letters.";
                    }

                    if($validInput && strlen($lastName) == 0 || $lastName == "") {
                        $validInput = false;
                        $errorMessage = "Last name must contain at least 1 character.";
                    }

                    if($validInput && !ctype_alpha($lastName)) {
                        $validInput = false;
                        $errorMessage = "Last name can only contain letters.";
                    }

                    /* VALIDATE BIRTH DATE */
                    // Check format.
                    $birthDateObj;
                    if($validInput && $birthDate != date("Y-m-d", strtotime($birthDate))) {
                        $validInput = false;
                        $errorMessage = "Birth date must be in the format YYYY-mm-dd.";
                    } else {
                        // Create birthDateObj if valid.
                        $birthDateObj = new DateTimeImmutable($birthDate);
                    }

                    // Check date is before current date (based on UTC) and after minimum possible date.
                    $currentDate = new DateTimeImmutable("now");
                    $currentYear = $currentDate->format("Y");
                    $firstOfCurrentYearString = $currentYear."-01-01";
                    $firstOfCurrentYear = new DateTimeImmutable($firstOfCurrentYearString); 
                    $oldestAllowedDate = $firstOfCurrentYear->modify('-130 year');
                    if($validInput && $birthDateObj > $currentDate || $birthDateObj < $oldestAllowedDate) {
                        $validInput = false;
                        $errorMessage = "Birth date is not a valid date.";
                    }

                    /* VALIDATE GENDER IDENTITY */
                    $genderOptions = array("Male", "Female", "Non-Binary", "Gender Non-Conforming", "Other");
                    if($validInput && !in_array($gender, $genderOptions)) {
                        $validInput = false;
                        $errorMessage = "Please select a listed gender identity.";
                    }

                    /* VALIDATE PROFILE IMAGE */
                    // Check if profile image was uploaded. If uploaded, validate.
                    if($imageFile != "" && $imageFileType != "") {
                        // Check if file type is jpeg/jpg or png.
                        if($validInput && $imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
                            $validInput = false;
                            $errorMessage = "Profile image must be a jpg, jpeg, or png.";
                        }

                        // Check if file size is too large (cap at 2MB).
                        if($validInput && $_FILES["imgProfilePicture"]["size"] > 2097152) {
                            $validInput = false;
                            $errorMessage = "Profile image is too large. Must be less than 2MB.";
                        }
                    }

                    /* VALIDATE DESCRIPTION */
                    // Check description does not exceed 1,000 characters.
                    if($validInput && strlen($description) > 1000) {
                        $validInput = false;
                        $errorMessage = "Description cannot exceed 1,000 characters.";
                    }

                    /* VALIDATE USERNAME */
                    // Check contains at least 3 characters.
                    if($validInput && strlen($username) < 3) {
                        $validInput = false;
                        $errorMessage = "Username too short. Must be at least 3 characters in length.";
                    }

                    // Check greater than 32 characters.
                    if($validInput && strlen($username) > 32) {
                        $validInput = false;
                        $errorMessage = "Username too long. Please make it at most 32 characters in length.";
                    }

                    // Check if username is already in use.
                    if($validInput) {
                        $sql = "SELECT pmkUsername FROM tblUser ";
                        $sql .= "WHERE pmkUsername = ?";
                        $data = array($username);
                        $results = $thisDatabaseReader->select($sql, $data);

                        if(!empty($results)) {
                            $validInput = false;
                            $errorMessage = "The username ".$username." is already taken.";
                        }    
                    }
                    
                    /* VALIDATE EMAIL */
                    // Check if email is valid format.
                    if($validInput && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $validInput = false;
                        $errorMessage = "Email is not valid.";
                    }

                    // Check if already in use.
                    if($validInput) {
                        $sql = "SELECT fldEmail FROM tblUser ";
                        $sql .= "WHERE fldEmail = ?";
                        $data = array($email);
                        $results = $thisDatabaseReader->select($sql, $data);

                        if(!empty($results)) {
                            $validInput = false;
                            $errorMessage = "This email is already in use.";
                        }    
                    }

                    /* VALIDATE PASSWORD */
                    // Verify password is at least 6 characters in length.
                    if($validInput && strlen($password) < 6) {
                        $validInput = false;
                        $errorMessage = "Password too short. Must be at least 6 characters in length.";
                    }

                    // Verify password matches confirmation password.
                    if($validInput && $password != $passwordConfirmation) {
                        $validInput = false;
                        $errorMessage = "Passwords do not match.";
                    }

                    // Insert into Users table if valid inputs; otherwise, print error.
                    $DEBUG = false;
                    if($validInput && $DEBUG == false) {
                        // Add profile photo to profile images path and rename to standardized name.
                        $standardizedImagePath = "images/profiles/placeholder.png";
                        if($imageFile != "" && $imageFileType != "") {
                            $profileImagePath = "images/profiles/".$imageFile;
                            $standardizedImagePath = "images/profiles/profile_".$username.".png";
                            move_uploaded_file($_FILES["imgProfilePicture"]["tmp_name"], $profileImagePath);
                            rename($profileImagePath, $standardizedImagePath);
                        }

                        // Hash password.
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                
                        $sql = "INSERT INTO tblUser (pmkUsername, fldPasswordHash, fldEmail, fldFirstName, fldLastName, fldBirthDate, fldGender, fldDescription, fldProfileImagePath) ";
                        $sql .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $data = array($username, $passwordHash, $email, $firstName, $lastName, $birthDate, $gender, $description, $standardizedImagePath);
                        $success = $thisDatabaseWriter->insert($sql, $data);

                        // Email the user if registration is successful.
                        if($success) {
                            $to = $email;
                            $subject = "Welcome to UVM Housing Finder!";
                            $message = "
                            <html>
                                <head>
                                    <title>Welcome to UVM Housing Finder!</title>
                                </head>
                                <body style=\"font-family: Arial, sans-serif\">
                                    <h1>Welcome to the UVM Housing Finder!</h1>
                                    <table cellpadding=\"5\">
                                        <tr>
                                            <td>
                                                <p><strong>Hello <span style=\"color: #66AC47\">".$firstName."</span>,</strong></p>
                                                <p>Welcome and thank you for registering an account with <a href=\"https://astem.w3.uvm.edu/cs148/live-final\">UVM Housing Finder</a>.</p>
                                                <p><strong>Your account with the username <i>".$username."</i> has been successfully created</strong>, and you can now log in using your username and password.</p>
                                            </td>
                                        </tr>
                                        <tr style=\"background-color: #EFEFEF;\">
                                            <td>
                                                <p style=\"width: 100%; text-align: center;\"><a href=\"https://astem.w3.uvm.edu/cs148/live-final/login.php\" style=\"border-radius: 4px; background-color: #F47D20; color: white; padding: 1rem 2rem 1rem 2rem;  text-decoration: underline; font-weight: bold; text-transform: uppercase;\">Login</a></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Whether you may be searching for a dorm, an apartment, or the perfect roommate, we hope that UVM Housing Finder serves you well!</p>
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
                            </html>
                            ";
                        
                            $headers = "MIME-Version: 1.0" . "\r\n";
                            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                            
                            mail($to, $subject, $message, $headers);

                            header("Location: login.php");
                        } else {
                            print "<p class=\"form-error\">Unable to complete registration request at this time.</p>";
                        }
                    }
                }
            ?>
            <form class="primary-form" action="" method="POST" enctype="multipart/form-data">
                <section class="form-tab">
                    <h3>Personal Information</h3>
                    <p class="form-element">
                        <label for="txtFirstName">First Name</label>
                        <input type="text" name="txtFirstName" placeholder="Enter First Name" value="<?php if($firstName != "") { print $firstName; } ?>" required>
                    </p>
                    <p class="form-element">
                        <label for="txtLastName">Last Name</label>
                        <input type="text" name="txtLastName" placeholder="Enter Last Name" value="<?php if($lastName != "") { print $lastName; } ?>" required>
                    </p>
                    <p class="form-element">
                        <label for="dateBirthdate">Birth Date</label>
                        <input type="date" name="dateBirthDate" value="<?php if($birthDate != "") { print $birthDate; } ?>" required>
                    </p>                        
                    <p class="form-element">
                        <label for="selectGender">Gender Identity</label>
                        <select name="selectGender" required>
                            <option value="Other" <?php if($gender == "Other") { print " selected "; } ?>>Other</option>
                            <option value="Male" <?php if($gender == "Male") { print " selected "; } ?>>Male</option>
                            <option value="Female" <?php if($gender == "Female") { print " selected "; } ?>>Female</option>
                            <option value="Non-Binary" <?php if($gender == "Non-Binary") { print " selected "; } ?>>Non-Binary</option>
                            <option value="Gender Non-Conforming" <?php if($gender == "Gender Non-Conforming") { print " selected "; } ?>>Gender Non-Conforming</option>
                        </select>
                    </p>
                    <p class="form-element form-buttons">
                        <button class="form-next-button" type="button">Next ></button>
                    </p>
                </section>
                <section class="form-tab">
                    <h3>Tell People About Yourself</h3>
                    <p class="form-element">
                        <label for="imgProfilePicture">Add a Profile Picture</label>
                        <input type="file" name="imgProfilePicture" accept="image/png, image/jpeg">
                    </p>
                    <p class="form-element">
                        <label for="txtDescription">About You</label>
                        <textarea cols="4" rows="5" maxlength="1000" name="txtDescription"><?php if($description != "") { print $description; } ?></textarea>
                    </p>
                    <p class="form-element form-buttons">
                        <button class="form-back-button" type="button">< Back</button>
                        <button class="form-next-button" type="button">Next ></button>
                    </p>
                </section>
                <section class="form-tab">
                    <h3>Login Information</h3>
                    <p class="form-element">
                        <label for="txtUsername">Username</label>
                        <input type="text" name="txtUsername" placeholder="Enter Username" value="<?php if($username != "") { print $username; } ?>" required>
                    </p>
                    <p class="form-element">
                        <label for="txtEmail">Email</label>
                        <input type="text" name="txtEmail" placeholder="Enter Email" value="<?php if($email != "") { print $email; } ?>" required>
                    </p>
                    <p class="form-element">
                        <label for="txtPassword">Password</label>
                        <input type="password" name="txtPassword" placeholder="Enter Password" required>
                    </p>
                    <p class="form-element">
                        <label for="txtConfirmPassword">Confirm Password</label>
                        <input type="password" name="txtConfirmPassword" placeholder="Confirm Password" required>
                    </p>
                    <p class="form-element form-buttons">
                        <button class="form-back-button" type="button">< Back</button>
                        <button type="submit">Register</button>
                    </p>
                </section>
            </form>
            <?php
                /* Print Error Message */
                if($errorMessage != "") {
                    print "<p class=\"form-error\">".$errorMessage."</p>";
                }
            ?> 
            <p class="form-warning">Please fill out all the required fields.</p>
            <div class="form-redirects">
                <p class="form-redirect">
                    Already have an account? <a href="login.php">Log In</a>
                </p>
            </div>
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

            // Handle multi-step form.
            let nextButtons = document.querySelectorAll(".form-next-button");
            let backButtons = document.querySelectorAll(".form-back-button");

            var currentTab = 0;
            toggleFormTab(currentTab);

            function toggleFormTab(currentTab) {
                var tabs = document.getElementsByClassName("form-tab");
                tabs[currentTab].classList.toggle("active");
            }

            let nextClickEvent = () => {
                var tabCount = document.getElementsByClassName("form-tab").length;
                console.log(currentTab + 1);
                console.log(tabCount - 1);
                if(!(currentTab + 1 >= tabCount) && !(currentTab + 1 < 0)) {
                    // Check if valid.
                    if(validateFormTab()) {
                        toggleFormTab(currentTab);
                        currentTab += 1;
                        toggleFormTab(currentTab);
                    }
                }
            }

            let backClickEvent = () => {
                var tabCount = document.getElementsByClassName("form-tab").length;
                console.log(currentTab - 1);
                console.log(tabCount);
                if(!(currentTab - 1 >= tabCount) && !(currentTab - 1 < 0)) {
                    toggleFormTab(currentTab);
                    currentTab -= 1;
                    toggleFormTab(currentTab);
                }
            }

            nextButtons.forEach((nextButton) => {
                nextButton.addEventListener("click", nextClickEvent);
            });

            backButtons.forEach((backButton) => {
                backButton.addEventListener("click", backClickEvent);
            });

            function validateFormTab() {
                var formError = document.querySelector(".form-warning");
                var tabs = document.getElementsByClassName("form-tab");
                var inputs = tabs[currentTab].getElementsByTagName("input");

                // Check that each field is not empty.
                var field;
                var formIsValid = true;
                for(field = 0; field < inputs.length; field++) {
                    console.log("F:" + inputs[field].value);
                    if(inputs[field].hasAttribute("required") && inputs[field].value == "") {
                        inputs[field].classList.add("invalid-entry");
                        formError.classList.add("active");
                        formIsValid = false;
                    } else {
                        inputs[field].classList.remove("invalid-entry");
                    }
                }

                if(formIsValid) {
                    for(field = 0; field < inputs.length; field++) {
                        inputs[field].classList.remove("invalid-entry");
                    }
                    formError.classList.remove("active");
                }

                return formIsValid;
            }
        </script>
    </body>
</html>