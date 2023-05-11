<?php
    include "top.php";
?>

<main class="dormitorylistings">
    <?php
        $results = "";
        $data = array();
        if($_SERVER["REQUEST_METHOD"] === "GET") {
            $listCampuses = isset($_GET["listCampuses"]) ? $_GET["listCampuses"] : "";
            $listComplexes = isset($_GET["listComplexes"]) ? $_GET["listComplexes"] : "";
            $listRoomSizes = isset($_GET["listRoomSizes"]) ? $_GET["listRoomSizes"] : "";
            $chkBathroom = isset($_GET["chkBathroom"]) ? $_GET["chkBathroom"] : 0;
            if($listCampuses != "" && $listComplexes != "" && $listRoomSizes != "") {
                $sql = "SELECT pmkListingId, fldListingTitle FROM tblListing ";
                $sql .= "JOIN tblDormitoryListing ON pmkListingId = fnkListingId ";
                $sql .= "WHERE pmkListingId > 0 ";
                if($chkBathroom == 1) {
                    $sql .= "AND fldHasPrivateBathroom = ? ";
                    array_push($data, $chkBathroom);
                }
                if ($listCampuses != "No Pref"){
                    $sql .= "AND fldCampus = ? ";
                    array_push($data, $listCampuses);
                }
                if ($listComplexes != "Any"){
                    $sql .= "AND fldComplex = ? ";
                    array_push($data, $listComplexes);
                }
                if ($listRoomSizes != "Any"){
                    $sql .= "AND fldRoomType = ? ";
                    array_push($data, $listRoomSizes);
                }
                $sql .= " ORDER BY fldCreationTimeStamp DESC";

                $results = $thisDatabaseReader->select($sql, $data);
            } else {
                $sql = "SELECT pmkListingId, fldListingTitle FROM tblListing ";
                $sql .= "JOIN tblDormitoryListing ON pmkListingId = fnkListingId ";
                $sql .= "ORDER BY fldCreationTimeStamp DESC ";
                $sql .= "LIMIT 50";
                $results = $thisDatabaseReader->select($sql);
            }
        } else {
            $sql = "SELECT pmkListingId, fldListingTitle FROM tblListing ";
            $sql .= "JOIN tblDormitoryListing ON pmkListingId = fnkListingId ";
            $sql .= "ORDER BY fldCreationTimeStamp DESC ";
            $sql .= "LIMIT 50";
            $results = $thisDatabaseReader->select($sql);
        }
    ?>

    <div class="search-filters">
        <form action="" method="GET" class="listings-filter">
            <!--
                Dorm room filter
                Dropdown: Campuses.
                Dropdown: Housing Complex (UHeights, Central, etc.).
                Dropdown: Room Size (Single, Double, Triple, etc.)
                Checkbox: Private Bathroom?
            -->

            <div class="fldCampus">
                <legend>Campus</legend>
                <select name="listCampuses">
                    <option value="No Pref" <?php if($listCampuses == "" || $listCampuses == "No Pref") { print "selected"; }?>>No Preference</option>
                    <option value="Redstone" <?php if($listCampuses == "Redstone") { print "selected"; }?>>Redstone</option>
                    <option value="Athletic" <?php if($listCampuses == "Athletic") { print "selected"; }?>>Athletic</option>
                    <option value="Central" <?php if($listCampuses == "Central") { print "selected"; }?>>Central</option>
                    <option value="Trinity" <?php if($listCampuses == "Trinity") { print "selected"; }?>>Trinity</option>
                </select>
            </div>

            <div class="fldComplex">
                <legend>Complex</legend>
                <select name="listComplexes">
                    <option value="Any" <?php if($listComplexes == "" || $listComplexes == "Any") { print "selected"; }?>>Any</option>
                    <option value="CCRH" <?php if($listComplexes == "CCRH") { print "selected"; }?>>CCRH</option>
                    <option value="CWPSCR" <?php if($listComplexes == "CWPSCR") { print "selected"; }?>>CWPSCR</option>
                    <option value="HM" <?php if($listComplexes == "HM") { print "selected"; }?>>HM</option>
                    <option value="LL" <?php if($listComplexes == "LL") { print "selected"; }?>>LL</option>
                    <option value="MAT" <?php if($listComplexes == "MAT") { print "selected"; }?>>MAT</option>
                    <option value="MSH" <?php if($listComplexes == "MSH") { print "selected"; }?>>MSH</option>
                    <option value="Trinity" <?php if($listComplexes == "Trinity") { print "selected"; }?>>Trinity</option>
                    <option value="UHN" <?php if($listComplexes == "UHN") { print "selected"; }?>>UHN</option>
                    <option value="UHS" <?php if($listComplexes == "UHS") { print "selected"; }?>>UHS</option>
                    <option value="WDW" <?php if($listComplexes == "WDW") { print "selected"; }?>>WDW</option>
                </select>
            </div>

            <div class="fldRoomSize">
                <legend>Room Size</legend>
                <select name="listRoomSizes">
                    <option value="Any" <?php if($listRoomSizes == "" || $listRoomSizes == "Any") { print "selected"; }?>>Any</option>
                    <option value="Double" <?php if($listRoomSizes == "Double") { print "selected"; } ?>>Double</option>
                    <option value="Triple" <?php if($listRoomSizes == "Triple") { print "selected"; } ?>>Triple</option>
                    <option value="Quad" <?php if($listRoomSizes == "Quad") { print "selected"; } ?>>Quad</option>
                </select>
            </div>

            <div class="fldBathroom">
                <legend>Private Bathroom?</legend>
                <p>
                    <input type="checkbox" name="chkBathroom" id="chkBathroom" value="1" <?php if($chkBathroom == 1) { print "checked"; } ?>>
                </p>
            </div>

            <div class="filterSubmit">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>

    <!-- Listings -->
    <section class="search-listing-wrapper">
        <h2>Dormitory Listings</h2>
        <?php
            if(session_id() != "" && isset($_SESSION) && isset($_SESSION["username"])) {
                print "<p><a href=\"new_post.php\" class=\"new-listing-button\">&#43; Make a New Listing</a></p>";
            } else {
                print "<p><a href=\"login.php\" class=\"new-listing-button\">Log In to Make a Listing!</a></p>";
            }
        ?>
        
        <div class="search-listings">
            <?php
                $searchListingNumber = 1;
                if(!empty($results)) {
                    foreach ($results as $listing) {
                        $listingId = $listing["pmkListingId"];
                        $listingTitle = $listing["fldListingTitle"];
                        print "<div class=\"search-listing\" id=\"listing-".$searchListingNumber."\">".PHP_EOL;
                            print '<div class="search-listing-thumbnail">'.PHP_EOL;
                                print '<img src="images/listings/dormitory_listings/'.$listingId.'/'.$listingId.'_1.png">'.PHP_EOL;
                            print '</div>'.PHP_EOL;
                            print '<section class="search-listing-header">'.PHP_EOL;
                                print '<h3>'.$listingTitle.'</h3>'.PHP_EOL;
                                print '<button><a href="listing.php?listing_id='.$listingId.'">View</a></button>'.PHP_EOL;
                            print '</section>'.PHP_EOL;
                        print '</div>'.PHP_EOL;
                    }
                } else {
                    print '<p>No listings found.</p>';
                }
            ?>
        </div>
    </section>
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


            // Handle listing dropdown button.
            function toggleListingDropdown(listingID) {
                console.log(listingID);
                let listing = document.getElementById(listingID);
                let dropdown = listing.querySelector(".search-listing-dropdown");
                
                dropdown.classList.toggle("show");
            }
            
        </script>
    </body>
</html>