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

        <main class="login">
            <div class="login-wrapper">
                <section class="form-section">
                    <h2>Register</h2>
                    <form class="login-form" action="" method="POST">
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
                                <input type="date" name="dateBirthdate" required>
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
                    toggleFormTab(currentTab);
                    currentTab += 1;
                    toggleFormTab(currentTab);
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

        </script>
    </body>
</html>