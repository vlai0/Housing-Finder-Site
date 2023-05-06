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
                <li><a href="#">Home</a></li>
                <li><a href="#">Dorm Roommate Requests</a></li>
                <li><a href="#">Find an Apartment</a> <i class="fa-solid fa-caret-down"></i></li>
                <ul class="navigation-dropdown">
                    <li><a href="#">Subleases</a></li>
                    <li><a href="#">Roommate Requests</a></li>
                </ul>
                <li><a href="#" class="bullet">Log In</a></li>
            </ul>
        </nav>
        <main class="register">
            <div class="login-wrapper">
                <section class="form-section">
                    <h2>Register</h2>
                    <form class="login-form" action="" method="POST" enctype="multipart/form-data">
                        <section class="form-tab" id="form-tab-1">
                            <h3>Personal Information</h3>
                            <p class="form-element">
                                <label for="txtFirstName">First Name</label>
                                <input type="text" name="txtFirstName" placeholder="Enter First Name" required>
                            </p>
                            <p class="form-element">
                                <label for="txtLastName">Last Name</label>
                                <input type="text" name="txtLastName" placeholder="Enter Last Name" required>
                            </p>
                            <p class="form-element">
                                <label for="dateBirthdate">Birth Date</label>
                                <input type="date" name="dateBirthDate" required>
                            </p>                        
                            <p class="form-element">
                                <label for="selectGender">Gender Identity</label>
                                <select name="selectGender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Non-Binary">Non-Binary</option>
                                    <option value="Gender Non-Conforming">Gender Non-Conforming</option>
                                    <option value="Other">Other</option>
                                </select>
                            </p>
                            <p class="form-element form-buttons">
                                <button class="form-next-button" type="button">Next ></button>
                            </p>
                        </section>
                        <section class="form-tab" id="form-tab-2">
                            <h3>Tell People About Yourself</h3>
                            <p class="form-element">
                                <label for="imgProfilePicture">Add a Profile Picture</label>
                                <input type="file" name="imgProfilePicture" accept="image/png, image/jpeg">
                            </p>
                            <p class="form-element">
                                <label for="txtDescription">About You</label>
                                <textarea cols="4" rows="5" maxlength="500" name="txtDescription"></textarea>
                            </p>
                            <p class="form-element form-buttons">
                                <button class="form-back-button" type="button">< Back</button>
                                <button class="form-next-button" type="button">Next ></button>
                            </p>
                        </section>
                        <section class="form-tab" id="form-tab-3">
                            <h3>Login Information</h3>
                            <p class="form-element">
                                <label for="txtUsername">Username</label>
                                <input type="text" name="txtUsername" placeholder="Enter Username" required>
                            </p>
                            <p class="form-element">
                                <label for="txtEmail">Email</label>
                                <input type="text" name="txtEmail" placeholder="Enter Email" required>
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
                        if($_SERVER["REQUEST_METHOD"] === "POST") {
                            /* ##### RETRIEVE DATA & SANITIZE ##### */
                            // txtFirstName, txtLastName, dateBirthDate, selectGender, imgProfilePicture, txtDescription, txtUsername, txtEmail, txtPassword, txtConfirmPassword
                            $firstName = getPostData("txtFirstName");
                            $lastName = getPostData("txtLastName");
                            $birthDate = isset($_POST["dateBirthDate"]) ? $_POST["dateBirthDate"] : "";
                            $gender = getPostData("selectGender");
                            $imageFile = basename($_FILES["imgProfilePicture"]["name"]);
                            $imageFileType = strtolower(pathinfo($imageFile, PATHINFO_EXTENSION));
                            $username = getPostData("txtUsername");
                            $email = getPostData("txtEmail");
                            // Do not sanitize passwords. They will be hashed.
                            $password = isset($_POST["txtPassword"]) ? $_POST["txtPassword"] : "";
                            $passwordConfirmation = isset($_POST["txtConfirmPassword"]) ? $_POST["txtConfirmPassword"] : "";

                            /* ##### VALIDATE DATA ##### */
                            $validInput = true;
                            $errorMessage = "";

                            print_r($_POST);
                            /* VALIDATE firstName AND lastName */
                            if(strlen($firstName) <= 0 || $firstName == "") {
                                $validInput = false;
                                $errorMessage = "First name must contain at least 1 character.";
                            }

                            if($validInput && strlen($lastName) <= 0 || $lastName == "") {
                                $validInput = false;
                                $errorMessage = "Last name must contain at least 1 character.";
                            }

                            /* VALIDATE BIRTH DATE */
                            // Check format.
                            if($validInput && $birthDate != date("Y-m-d", strtotime($birthDate))) {
                                $validInput = false;
                                $errorMessage = "Birth date must be in the format YYYY-mm-dd.";
                            }

                            // Check date is before current date.
                            if($validInput && date('Y-m-d', strtotime($birthDate)) > date("Y-m-d")) {
                                $validInput = false;
                                $errorMessage = "Birth date is not a valid date.";
                            }

                            /* VALIDATE GENDER IDENTITY */
                            if($validInput && $gender != "Male" && $gender != "Female" && $gender != "Non-Binary" && $gender != "Gender Non-Conforming" && $gender != "Other") {
                                $validInput = false;
                                $errorMessage = "Please select a listed gender identity.";
                            }

                            /* VALIDATE PROFILE IMAGE */
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

                            /* VALIDATE USERNAME */
                            // Check at least 3 characters.
                            if($validInput && strlen($username) < 3) {
                                $validInput = false;
                                $errorMessage = "Username too short. Must be at least 3 characters in length.";
                            }

                            // Check greater than 32 characters.
                            if($validInput && strlen($username) > 32) {
                                $validInput = false;
                                $errorMessage = "Username too long. Please make it at most 32 characters in length.";
                            }

                            // Check if already in use.

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

                            /* Print Error Message */
                            echo $validInput;
                            print "<p class=\"form-error\">".$errorMessage."</p>";
                        }
                    ?> 
                    <p class="form-warning">Please fill out all the required fields.</p>
                    <div class="form-redirects">
                        <p class="form-redirect">
                            <a href="login.html">Already have an account?</a>
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