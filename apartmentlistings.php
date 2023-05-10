<?php
    include "top.php";
?>

<main class="apartmentlistings">
    <div class="search-filters">

    
        <?php
        /*
            if($_SERVER["REQUEST_METHOD"] === "GET") {
                $numMin = isset($_GET["numMin"]) ? $_GET["numMin"] : "";
                $numMax = isset($_GET["numMax"]) ? $_GET["numMax"] : "";
                $listBedrooms = isset($_GET["listBedrooms"]) ? $_GET["listBedrooms"] : "";
                $listBathrooms = isset($_GET["listBathrooms"]) ? $_GET["listBathrooms"] : "";
                $listLocations = isset($_GET["listLocations"]) ? $_GET["listLocations"] : "";

                if(isset($_GET["error"])) {
                    $errorMessage = $_GET["error"];
                }

                $sql = "SELECT fldListingId FROM tblListing";
                $sql .= "JOIN tblApartmentListing ON pmkListingId = fnkListingId ";
                $sql .= "WHERE fldRent >= ?";
                $sql .= "AND fldRent <= ?";
                if (
                    $listBedrooms
                )
                $sql .= "AND fldBedrooms = ?";
                $sql .= "AND fldBathrooms = ?";
                $sql .= "AND fldTowns = ?";
                $data = array($numMin, $numMax, $listBedrooms, $listBathrooms, $listLocations);
                $results = $thisDatabaseReader->select($sql, $data);
            }


        */ ?>
        <form action="" method="GET" class="listings-filter">
            <!--
                Apartments filter
                Price range
                # of bedrooms
                # of bathrooms
                Town/city
            -->
            <div class="fldMin">
                <legend>Minimum Price ($)</legend>
                <p>
                    <input type="number" name="numMin" id="numMin" value="0">
                </p>
            </div>

            <p class="separator">
                -
            </p>

            <div class="fldMax">
                <legend>Maximum Price ($)</legend>
                <p>
                    <input type="number" name="numMax" id="numMax" value="10000">
                </p>
            </div>

            <div class="fldNumBedroom">
                <legend>Bedrooms</legend>
                <select name="listBedrooms">
                    <option value="No Pref">No preference</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4+">4+</option>
                </select>
            </div>

            <div class="fldNumBathroom">
                <legend>Bathrooms</legend>
                <select name="listBathrooms">
                    <option value="No Pref">No preference</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4+">4+</option>
                </select>
            </div>

            <div class="fldLocation">
                <legend>Location</legend>
                <select name="listLocations">
                    <option value="Any">Any</option>
                    <option value="Burlington">Burlington</option>
                    <option value="South Burlington">South Burlington</option>
                    <option value="Winooski">Winooski</option>
                    <option value="Colchester">Colchester</option>
                    <option value="Shelburne">Shelburne</option>
                    <option value="Essex">Essex</option>
                    <option value="Williston">Williston</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="fldAmenities">
                <legend>Amenities</legend>
                <p>
                    <input type="checkbox" name="chkAC" id="chkAC" value="1">
                    <label for="chkAC">Air Conditioning</label>
                    <input type="checkbox" name="chkLaundry" id="chkLaundry" value="1">
                    <label for="chkLaundry">In-unit Laundry</label>
                    <input type="checkbox" name="chkParking" id="chkParking" value="1">
                    <label for="chkParking">Parking</label>
                    <input type="checkbox" name="chkDishwasher" id="chkDishwasher" value="1">
                    <label for="chkDishwasher">Dishwasher</label>
                    <input type="checkbox" name="chkInternet" id="chkInternet" value="1">
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
        <h2>Apartment Listings</h2>
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
            
        </script>
    </body>
</html>