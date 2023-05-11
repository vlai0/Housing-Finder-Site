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
        <?php include "header.php"; ?>
    </div>
    <ul class="navigation-links">
        <?php
            $displayLoggedInLinks = false; 
            if(session_id() != "" && isset($_SESSION) && isset($_SESSION["username"])) {
                $displayLoggedInLinks = true; 
                $isAdmin = false;
                if(in_array($_SESSION["username"], ADMINS)) {
                    $isAdmin = true;
                }
            } 

            if($displayLoggedInLinks) {
                print "<li><a class=\"";
                    if(PATH_PARTS['filename'] == "profile") { 
                        print 'active-page';
                    }
                print "\" href=\"profile.php\">My Profile</a></li>".PHP_EOL;

                print "<li><a class=\"";
                    if(PATH_PARTS['filename'] == "dormitorylistings") { 
                        print 'active-page';
                    }
                print "\" href=\"dormitorylistings.php\">Dorm Roommate Requests</a></li>".PHP_EOL;

                print "<li><a class=\"";
                    if(PATH_PARTS['filename'] == "apartmentlistings") { 
                        print 'active-page';
                    }
                print "\" href=\"apartmentlistings.php\">Find an Apartment</a></li>".PHP_EOL;

                print "<li><a class=\"bullet ";
                    if(PATH_PARTS['filename'] == "logout") { 
                        print 'active-page';
                    }
                print "\" href=\"logout.php\">Log Out</a></li>".PHP_EOL;

                if($isAdmin) {
                    print "<li><a style=\"color: white; background-color: #D0342C;\" class=\"bullet ";
                    if(PATH_PARTS['filename'] == "admin") { 
                        print 'active-page';
                    }
                    print "\" href=\"admin.php\">Admin</a></li>".PHP_EOL;
                }
            } else {
                print "<li><a class=\"";
                    if(PATH_PARTS['filename'] == "index") { 
                        print 'active-page';
                    }
                print "\" href=\"index.php\">Home</a></li>".PHP_EOL;

                print "<li><a class=\"";
                    if(PATH_PARTS['filename'] == "dormitorylistings") { 
                        print 'active-page';
                    }
                print "\" href=\"dormitorylistings.php\">Dorm Roommate Requests</a></li>".PHP_EOL;

                print "<li><a class=\"";
                    if(PATH_PARTS['filename'] == "apartmentlistings") { 
                        print 'active-page';
                    }
                print "\" href=\"apartmentlistings.php\">Find an Apartment</a></li>".PHP_EOL;

                print "<li><a class=\"bullet ";
                    if(PATH_PARTS['filename'] == "login") { 
                        print 'active-page';
                    }
                print "\" href=\"login.php\">Log In</a></li>".PHP_EOL;
            }

        ?>
    </ul>
</nav>