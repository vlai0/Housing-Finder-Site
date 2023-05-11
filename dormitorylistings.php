<?php
    include "top.php";
?>

<main class="dormitorylistings">
    <div class="search-filters">
        <?php
            if($_SERVER["REQUEST_METHOD"] === "GET") {
                $listCampuses = isset($_GET["listCampuses"]) ? $_GET["listCampuses"] : "";
                $listComplexes = isset($_GET["listComplexes"]) ? $_GET["listComplexes"] : "";
                $listRoomSizes = isset($_GET["listRoomSizes"]) ? $_GET["listRoomSizes"] : "";
                $chkBathroom = isset($_GET["chkBathroom"]) ? $_GET["chkBathroom"] : "";

                if(isset($_GET["error"])) {
                    $errorMessage = $_GET["error"];
                }

                if($listCampuses != "" && $listComplexes != "" && $listRoomSizes != "") {
                    $data = array($chkBathroom);
                    $sql = "SELECT pmkListingId, fldListingTitle FROM tblListing ";
                    $sql .= "JOIN tblDormitoryListing ON pmkListingId = fnkListingId ";
                    $sql .= "WHERE pmkListingId > 0 ";
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
                    $sql .= "AND fldHasPrivateBathroom = ? ";
                    $results = $thisDatabaseReader->select($sql, $data);

                    echo $sql;
                    if(!empty($results)) {

                        print "
                        <section class=\"search-listing-wrapper\">
                        <h2>Dormitory Listings</h2>
                        <div class=\"search-listings\">
                            <div class=\"search-listing\">";
                        foreach ($results as $listing){
                            $listingId = $listing["pmkListingId"];
                            $listingTitle = $listing["fldListingTitle"];
                            print '<div class="search-listing">';
                            print '<div class="search-listing-thumbnail">';
                            print '<img src="images/listings/dormitory_listings/'.$listingId.'/'.$listingId.'_1.png">';
                            print '</div>';
                            print '<section class="search-listing-header">';
                                print '<h3>'.$listingTitle.'</h3>';
                                print '<button><a href="listing.php">View</a></button>';
                            print '</section>';
                        print '</div>';
                        }
                        print "</div>
                    </section>
                    ";
                    }else{
                        print '<h2> empty </h2>';
                    }
                }
            }
        ?>

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
                    <option value="No Pref">No Preference</option>
                    <option value="Redstone">Redstone</option>
                    <option value="Athletic">Athletic</option>
                    <option value="Central">Central</option>
                    <option value="Trinity">Trinity</option>
                </select>
            </div>

            <div class="fldComplex">
                <legend>Complex</legend>
                <select name="listComplexes">
                    <option value="Any">Any</option>
                    <option value="CCRH">CCRH</option>
                    <option value="CWPSCR">CWPSCR</option>
                    <option value="HM">HM</option>
                    <option value="LL">LL</option>
                    <option value="MAT">MAT</option>
                    <option value="MSH">MSH</option>
                    <option value="Trinity">Trinity</option>
                    <option value="UHN">UHN</option>
                    <option value="UHS">UHS</option>
                    <option value="WDW">WDW</option>
                </select>
            </div>

            <div class="fldRoomSize">
                <legend>Room Size</legend>
                <select name="listRoomSizes">
                    <option value="Any">Any</option>
                    <option value="Double">Double</option>
                    <option value="Triple">Triple</option>
                    <option value="Quad">Quad</option>
                </select>
            </div>

            <div class="fldBathroom">
                <legend>Private Bathroom?</legend>
                <p>
                    <input type="checkbox" name="chkBathroom" id="chkBathroom" value="1">
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
        <div class="search-listings">
            <div class="search-listing" id="listing-1">
                <div class="search-listing-thumbnail">
                    <img src="images/landing-background.jpg">
                </div>
                <section class="search-listing-header">
                    <h3>Listing Exasdadasdadadadadadadadadadadadadadadadadadadadadadadadadaadadasdadample</h3>
                    <button><a href="#">View</a></button>
                </section>
            </div>

            <div class="search-listing" id="listing-2">
                <div class="search-listing-thumbnail">
                    <img src="images/landing-background.jpg">
                </div>
                <section class="search-listing-header">
                    <h3>Listing Example</h3>
                    <button>View</button>
                </section>
            </div>
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

            function filter(){

            }

            
        </script>
    </body>
</html>