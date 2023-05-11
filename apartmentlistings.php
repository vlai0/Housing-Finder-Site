<?php
    include "top.php";
?>

<main class="apartmentlistings">
    <?php
        $results = "";
        $data = array();
        if($_SERVER["REQUEST_METHOD"] === "GET") {
            $numMin = isset($_GET["numMin"]) ? $_GET["numMin"] : "";
            $numMax = isset($_GET["numMax"]) ? $_GET["numMax"] : "";
            $listBedrooms = isset($_GET["listBedrooms"]) ? $_GET["listBedrooms"] : "";
            $listBathrooms = isset($_GET["listBathrooms"]) ? $_GET["listBathrooms"] : "";
            $listLocations = isset($_GET["listLocations"]) ? $_GET["listLocations"] : "";
            $chkAC = isset($_GET["chkAC"]) ? $_GET["chkAC"] : 0;
            $chkLaundry = isset($_GET["chkLaundry"]) ? $_GET["chkLaundry"] : 0;
            $chkParking = isset($_GET["chkParking"]) ? $_GET["chkParking"] : 0;
            $chkDishwasher = isset($_GET["chkDishwasher"]) ? $_GET["chkDishwasher"] : 0;
            $chkInternet = isset($_GET["chkInternet"]) ? $_GET["chkInternet"] : 0;

            if($numMin != "" && $numMax != "" && $listBedrooms != "" && $listBathrooms != "" && $listLocations != "") {
                $sql = "SELECT pmkListingId, fldListingTitle FROM tblListing ";
                $sql .= "JOIN tblApartmentListing ON pmkListingId = fnkListingId ";
                $sql .= "WHERE fldRent >= ? ";
                array_push($data, $numMin);
                $sql .= "AND fldRent <= ? ";
                array_push($data, $numMax);
                if($chkAC == 1) {
                    $sql .= "AND fldHasAirConditioning = ? ";
                    array_push($data, $chkAC);
                }
                if($chkLaundry == 1) {
                    $sql .= "AND fldHasLaundry = ? ";
                    array_push($data, $chkLaundry);
                }
                if($chkParking == 1) {
                    $sql .= "AND fldHasParking = ? ";
                    array_push($data, $chkParking);
                }
                if($chkDishwasher == 1) {
                    $sql .= "AND fldHasDishwasher = ? ";
                    array_push($data, $chkDishwasher);
                }
                if($chkInternet == 1) {
                    $sql .= "AND fldHasInternet = ? ";
                    array_push($data, $chkInternet);
                }
                if ($listBedrooms != "1" && $listBedrooms != "2" && $listBedrooms != "3" && $listBedrooms != "No Pref"){
                    $sql .= "AND fldBedrooms >= 4 ";
                }
                else if ($listBedrooms != "No Pref" && $listBedrooms != "4+") {
                    $sql .= "AND fldBedrooms = ? ";
                    array_push($data, $listBedrooms);
                }
                if ($listBathrooms != "1" && $listBathrooms != "2" && $listBathrooms != "3" && $listBathrooms != "No Pref"){
                    $sql .= "AND fldBedrooms >= 4 ";
                }
                else if ($listBathrooms != "No Pref"){
                    $sql .= "AND fldBathrooms = ? ";
                    array_push($data, $listBathrooms);
                }
                if ($listLocations != "Any"){
                    $sql .= "AND fldTown = ? ";
                    array_push($data, $listLocations);
                }

                $sql .= " ORDER BY fldCreationTimeStamp DESC";

                $results = $thisDatabaseReader->select($sql, $data);
            } else {
                $sql = "SELECT pmkListingId, fldListingTitle FROM tblListing ";
                $sql .= "JOIN tblApartmentListing ON pmkListingId = fnkListingId ";
                $sql .= "ORDER BY fldCreationTimeStamp DESC ";
                $sql .= "LIMIT 50";
                $results = $thisDatabaseReader->select($sql);
            }
        } else {
            $sql = "SELECT pmkListingId, fldListingTitle FROM tblListing ";
            $sql .= "JOIN tblApartmentListing ON pmkListingId = fnkListingId ";
            $sql .= "ORDER BY fldCreationTimeStamp DESC ";
            $sql .= "LIMIT 50";
            $results = $thisDatabaseReader->select($sql);
        }
        ?>
    
    <div class="search-filters">
        <form action="" method="GET" class="listings-filter">
            <!--
                Apartments filter
                Price range
                # of bedrooms
                # of bathrooms
                Town/city
            -->
            <div class="fldMin">
                <legend>Minimum Rent ($)</legend>
                <p>
                    <input type="number" name="numMin" id="numMin" min="0" max="999999" step="0.01" value="<?php if($numMin >= "0" && $numMin < "1000000") { print $numMin; } else { print "0"; } ?>">
                </p>
            </div>

            <p class="separator">
                -
            </p>

            <div class="fldMax">
                <legend>Maximum Rent ($)</legend>
                <p>
                    <input type="number" name="numMax" id="numMax" min="0" max="999999" step="0.01" value="<?php if($numMax < "1000000" && $numMax >= "0") { print $numMax; } else { print "999999"; } ?>">
                </p>
            </div>

            <div class="fldNumBedroom">
                <legend>Bedrooms</legend>
                <select name="listBedrooms">
                    <option value="No Pref" <?php if($listBedrooms == "" || $listBedrooms == "No Pref") { print "selected"; }?>>No preference</option>
                    <option value="1" <?php if($listBedrooms == "1") { print "selected"; }?>>1</option>
                    <option value="2" <?php if($listBedrooms == "2") { print "selected"; }?>>2</option>
                    <option value="3" <?php if($listBedrooms == "3") { print "selected"; }?>>3</option>
                    <option value="4+" <?php if($listBedrooms == "4+") { print "selected"; }?>>4+</option>
                </select>
            </div>

            <div class="fldNumBathroom">
                <legend>Bathrooms</legend>
                <select name="listBathrooms">
                    <option value="No Pref" <?php if($listBathrooms == "" || $listBathrooms == "No Pref") { print "selected"; }?>>No preference</option>
                    <option value="1" <?php if($listBathrooms == "1") { print "selected"; }?>>1</option>
                    <option value="2" <?php if($listBathrooms == "2") { print "selected"; }?>>2</option>
                    <option value="3" <?php if($listBathrooms == "3") { print "selected"; }?>>3</option>
                    <option value="4+" <?php if($listBathrooms == "4+") { print "selected"; }?>>4+</option>
                </select>
            </div>

            <div class="fldLocation">
                <legend>Location</legend>
                <select name="listLocations">
                    <option value="Any" <?php if($listLocations == "" || $listLocations == "Any") { print "selected"; }?>>Any</option>
                    <option value="Burlington" <?php if($listLocations == "Burlington") { print "selected"; }?>>Burlington</option>
                    <option value="South Burlington" <?php if($listLocations == "South Burlington") { print "selected"; }?>>South Burlington</option>
                    <option value="Winooski" <?php if($listLocations == "Winooski") { print "selected"; }?>>Winooski</option>
                    <option value="Colchester" <?php if($listLocations == "Colchester") { print "selected"; }?>>Colchester</option>
                    <option value="Shelburne" <?php if($listLocations == "Shelburne") { print "selected"; }?>>Shelburne</option>
                    <option value="Essex" <?php if($listLocations == "Essex") { print "selected"; }?>>Essex</option>
                    <option value="Williston" <?php if($listLocations == "Williston") { print "selected"; }?>>Williston</option>
                    <option value="Other" <?php if($listLocations == "Other") { print "selected"; }?>>Other</option>
                </select>
            </div>

            <div class="fldAmenities">
                <legend>Amenities</legend>
                <p>
                    <input type="checkbox" name="chkAC" id="chkAC" value="1" <?php if($chkAC == 1) { print "checked"; } ?>>
                    <label for="chkAC">Air Conditioning</label>
                    <input type="checkbox" name="chkLaundry" id="chkLaundry" value="1" <?php if($chkLaundry == 1) { print "checked"; } ?>>
                    <label for="chkLaundry">In-unit Laundry</label>
                    <input type="checkbox" name="chkParking" id="chkParking" value="1" <?php if($chkParking == 1) { print "checked"; } ?>>
                    <label for="chkParking">Parking</label>
                    <input type="checkbox" name="chkDishwasher" id="chkDishwasher" value="1" <?php if($chkDishwasher == 1) { print "checked"; } ?>>
                    <label for="chkDishwasher">Dishwasher</label>
                    <input type="checkbox" name="chkInternet" id="chkInternet" value="1" <?php if($chkInternet == 1) { print "checked"; } ?>>
                    <label for="chkInternet">Internet</label>
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
                                print '<img src="images/listings/apartment_listings/'.$listingId.'/'.$listingId.'_1.png">'.PHP_EOL;
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