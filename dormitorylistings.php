<?php
    include "top.php";
?>

<main class="dormitorylistings">
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
                <select name="lstRoomSizes">
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
            
        </script>
    </body>
</html>