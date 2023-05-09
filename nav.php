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
        <li><a class="<?php 
            if(PATH_PARTS['filename'] == "index") { 
                print 'active-page';
            }
            ?>" 
            href="index.php">Home</a>
        </li>
        <li><a class="<?php 
            if(PATH_PARTS['filename'] == "dormitorylistings") { 
                print 'active-page';
            }
            ?>" 
            href="dormitorylistings.php">Dorm Roommate Requests</a>
        </li>
        <li><a class="<?php 
            if(PATH_PARTS['filename'] == "apartmentlistings") { 
                print 'active-page';
            }
            ?>"
         href="apartmentlistings.php">Find an Apartment</a>
        </li>
        <li><a class="<?php 
            if(PATH_PARTS['filename'] == "login") { 
                print 'active-page';
            }
            ?>" 
            href="login.php" class="bullet">Log In</a>
        </li>
    </ul>
</nav>