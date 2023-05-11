<?php
    include "top.php";
?>

<main class="listing-page">
    <div class="listing">
    <?php
        $information = "";
        $imagePaths = "";
        $listingType = "";
        if(isset($_GET["listing_id"])) {
            // Get Listing Id.
            $listingID = $_GET["listing_id"];
            
            // Get type of listing.
            $sql = "SELECT fldType FROM tblListing WHERE pmkListingId = ?";
            $data = array($listingID);
            $results = $thisDatabaseReader->select($sql, $data);
            $listingType = $results[0]["fldType"];

            // Get generic listing data.
            // Get image paths.
            $sql = "SELECT * FROM tblListingImages ";
            $sql .= "WHERE pfkListingId = ?";
            $data = array($listingID);
            $imagePaths = $thisDatabaseReader->select($sql, $data);

            // Get username of poster.
            $sql = "SELECT pfkUsername FROM tblListing WHERE pmkListingId = ? ";
            $data = array($listingID);
            $username = $thisDatabaseReader->select($sql, $data)[0]["pfkUsername"];

            // Get email of poster.
            $sql = "SELECT fldEmail FROM tblUser WHERE pmkUsername = ?";
            $data = array($username);
            $userEmail = $thisDatabaseReader->select($sql, $data)[0]["fldEmail"];

            // Get Listing Data Based on Type.
            if($listingType == "Dormitory") {
                // Get dorm listing data.
                $sql = "SELECT * FROM tblListing JOIN tblDormitoryListing ON pmkListingId = fnkListingId ";
                $sql .= "WHERE pmkListingId = ?";
                $data = array($listingID);
                $information = $thisDatabaseReader->select($sql, $data);

            } else if($listingType == "Apartment") {
                // Get apartment listing data.
                $sql = "SELECT * FROM tblListing JOIN tblApartmentListing ON pmkListingId = fnkListingId ";
                $sql .= "WHERE pmkListingId = ?";
                $data = array($listingID);
                $information = $thisDatabaseReader->select($sql, $data);
            } else {
                header("Location: index.php");
                exit();
            }
        } else {
            header("Location: index.php");
            exit();
        }
    ?>
    
        <div class="listing-slideshow">
            <?php
                $curr = 0;
                for($curr = 0; $curr < count($imagePaths); $curr++) {
                    print "<!-- Slides -->".PHP_EOL;
                    print "<div class=\"slide\">".PHP_EOL;
                    print "<img src=\"".$imagePaths[$curr]["fldImagePath"]."\">".PHP_EOL;
                    print "</div>";
                }
            ?>

            <!-- Next/Previous Buttons -->
            <button class="slideshow-previous" onClick="changeSlide(-1);">&#10094;</button>
            <button class="slideshow-next" onClick="changeSlide(1);">&#10095;</button>

            <!-- Slideshow Dots -->
            <div class="slideshow-dots">
                <?php
                    for($curr = 0; $curr < count($imagePaths); $curr++) {
                        print "<span class=\"slideshow-dot\" onclick=\"setSlide(".$imagePaths[$curr]["fldOrderNumber"].")\"></span>".PHP_EOL;
                    }
                ?>
            </div>
        </div>
        <section class="listing-header">
            <h2><?php print $information[0]["fldListingTitle"] ?></h2>
            <p><strong>Posted By:</strong> <?php print "<a href=\"profile.php?user=".$username."\">".$username."</a>" ?></p>
            <p><i class="fa fa-envelope"></i> <a href="mailto:<?php print $userEmail; ?>"><?php print $userEmail; ?></a></p>
            <?php
                /* MANAGE LISTING FOLLOWING/UNFOLLOWING */
                if($_SERVER["REQUEST_METHOD"] === "POST") {
                    $listingToToggle = getPostData("hidToggleFollow");
                    // Check if user already follows.
                    $sql = "SELECT * FROM tblFollowedListings WHERE pfkUsername = ? AND pfkListingId = ?";
                    $data = array($_SESSION["username"], $listingID);
                    $results = $thisDatabaseReader->select($sql, $data);

                    if(empty($results)) {
                        // Insert follow.
                        $sql = "INSERT INTO tblFollowedListings (pfkUsername, pfkListingId) ";
                        $sql .= "VALUES (?, ?)";
                        $data = array($_SESSION["username"], $listingToToggle);
                        $thisDatabaseWriter->insert($sql, $data);
                    } else {
                        // Delete follow.
                        // Insert follow.
                        $sql = "DELETE FROM tblFollowedListings ";
                        $sql .= "WHERE pfkUsername = ? AND pfkListingId = ?";
                        $data = array($_SESSION["username"], $listingToToggle);
                        $thisDatabaseWriter->delete($sql, $data);
                    }
                }
                // Check if user is logged in.
                if(session_id() != "" && isset($_SESSION) && isset($_SESSION["username"])) {
                    print "<form action=\"#\" method=\"POST\">".PHP_EOL;

                    // Check if user already follows.
                    $sql = "SELECT * FROM tblFollowedListings WHERE pfkUsername = ? AND pfkListingId = ?";
                    $data = array($_SESSION["username"], $listingID);
                    $results = $thisDatabaseReader->select($sql, $data);
                    print "<p>".PHP_EOL;
                    print "<input type=\"hidden\" name=\"hidToggleFollow\" value=\"".$listingID."\">".PHP_EOL;
                    print "</p>".PHP_EOL;
                    print "<p>".PHP_EOL;
                    if(empty($results)) {
                        print "<button class=\"follow-button\" type=\"submit\"><i class=\"fa fa-heart\"></i> Save Listing</button>".PHP_EOL;
                    } else {
                        print "<button class=\"unfollow-button\" type=\"submit\"><i class=\"fa-solid fa-heart-crack\"></i> Unfollow Listing</button>".PHP_EOL;
                    }
                    print "</p>".PHP_EOL;
                    print "</form>".PHP_EOL;
                }
            ?>      
        </section>
        <section class="listing-details">
            <h3>Information</h3>
            
            <?php
                if($listingType == "Dormitory") {
                    print "<p><strong>Residential Complex:</strong> ".$information[0]["fldComplex"]."</p>".PHP_EOL;
                    print "<p><strong>Campus:</strong> ".$information[0]["fldCampus"]."</p>".PHP_EOL;
                    print "<p><strong>Room Type:</strong> ".$information[0]["fldRoomType"]."</p>".PHP_EOL;
                    print "<p><strong>Private Bathroom:</strong> ".PHP_EOL;
                    if($information[0]["fldHasPrivateBathroom"] == 1) { print "Yes."; } else { print "No."; }
                    print "</p>".PHP_EOL;
                } else if($listingType == "Apartment") {
                    print "<p><strong>City/Town:</strong> ".$information[0]["fldTown"]."</p>".PHP_EOL;
                    print "<p><strong>Rent:</strong> $".$information[0]["fldRent"]."/month.</p>".PHP_EOL;
                    print "<p><strong>Bedrooms:</strong> ".$information[0]["fldBedrooms"]."</p>".PHP_EOL;
                    print "<p><strong>Bathrooms:</strong> ".$information[0]["fldBathrooms"]."</p>".PHP_EOL;
                    print "</section>".PHP_EOL;
                    print "<section class=\"listing-amenities\">".PHP_EOL;
                    print "<h3>Amenities</h3>".PHP_EOL;
                    print "<p><strong>Air Conditioning:</strong> ".PHP_EOL;
                    if($information[0]["fldHasAirConditioning"] == 1) { print "Yes."; } else { print "No."; }
                    print "</p>".PHP_EOL;
                    print "<p><strong>Laundry:</strong> ".PHP_EOL;
                    if($information[0]["fldHasLaundry"] == 1) { print "Yes."; } else { print "No."; }
                    print "</p>".PHP_EOL;
                    print "<p><strong>Parking:</strong> ".PHP_EOL;
                    if($information[0]["fldHasParking"] == 1) { print "Yes."; } else { print "No."; }
                    print "</p>".PHP_EOL;
                    print "<p><strong>Dishwasher:</strong> ".PHP_EOL;
                    if($information[0]["fldHasDishwasher"] == 1) { print "Yes."; } else { print "No."; }
                    print "</p>".PHP_EOL;
                    print "<p><strong>Internet/Wi-Fi:</strong> ".PHP_EOL;
                    if($information[0]["fldHasInternet"] == 1) { print "Yes."; } else { print "No."; }
                    print "</p>".PHP_EOL;
                    print "</section>";
                }
            ?>

        </section>
        <section class="listing-description">
            <h3>Description</h3>
            <p><?php print $information[0]["fldListingDescription"]; ?></p>
        </section>
        <section class="listing-additional-information">
            <h3>Additional Information</h3>
            <p><?php if($information[0]["fldListingAdditionalInformation"] != "") { print $information[0]["fldListingAdditionalInformation"]; } else { print "None."; } ?></p>
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

            // Slideshow
            let slideIndex = 1;
            showSlide(slideIndex);

            function changeSlide(indexChange) {
                slideIndex += indexChange;
                showSlide(slideIndex);
            }

            function setSlide(slide) {
                slideIndex = slide;
                showSlide(slideIndex);
            }

            function showSlide(slide) {
                let slides = document.getElementsByClassName("slide");
                let dots = document.getElementsByClassName("slideshow-dot");
                
                // Return to first slide if user toggles next past last slide.
                if(slide > slides.length) {
                    slideIndex = 1;
                }

                // Set to last slide if user toggles previous past the first slide.
                if(slide < 1) {
                    slideIndex = slides.length;
                }

                let index;
                for(index = 0; index < slides.length; index++) {
                    slides[index].style.display = "none";
                }
                
                for(index = 0; index < slides.length; index++) {
                    dots[index].className = dots[index].className.replace(" active-slide", "");
                }

                slides[slideIndex - 1].style.display = "flex";
                dots[slideIndex - 1].className += " active-slide";
            }
        </script>
    </body>
</html>