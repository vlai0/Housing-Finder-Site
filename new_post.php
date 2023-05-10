<?php
    include "top.php";
?>

<main class="new-post">
    <h2>Post a New Listing</h2>
    <form class="post-form-selector">
        <p class="form-element">
            <label for="selectListingType">Listing Type</label>
            <select name="selectListingType" onChange="selectListingForm(this);">
                <option value="">Select an Option</option>
                <option value="Dorm Roommate Request">Dorm Roommate Request</option>
                <option value="Apartment Listing">Apartment Listing</option>
            </select>
        </p>
    </form>
    <hr>
    <form class="new-post-dormitory form-option" method="" action="POST">
        <p class="form-element">
            <label for="txtListingTitle">Listing Title</label>
            <input type="text" name="txtListingTitle" placeholder="Enter a title for your new listing..." required>
        </p>
        <p class="form-element">
            <label for="selectComplex">Residential Complex</label>
            <select name="selectComplex" required>
                <option value="">Select a Complex</option>
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
        </p>
        <p class="form-element">
            <label for="selectComplex">Room Size</label>
            <select name="selectRoomSize" required>
                <option value="">Select a Room Size</option>
                <option value="Double">Double</option>
                <option value="Triple">Triple</option>
                <option value="Quad">Quad</option>
            </select>
        </p>
        <p class="form-element">
            <label for="chkPrivateBathroom"><input type="checkbox" name="chkPrivateBathroom"> Has a Private Bathroom?</label>
        </p>
        <p class="form-element">
            <label for="imgListingPhotos">Add Photos (Select All Images You Want to Upload. Must Include One Photo. Max 10)</label>
            <input type="file" name="imgListingPhotos" accept="image/png, image/jpeg" multiple required>
        </p>
        <p class="form-element">
            <label for="txtDescription">Talk About You, Your Roommates (If You Have Any), and the Dorm!</label>
            <textarea cols="4" rows="5" maxlength="10000" name="txtDescription" required></textarea>
        </p>
        <p class="form-element">
            <label for="txtAdditionalInformation">Any Additional Information</label>
            <textarea cols="4" rows="5" maxlength="10000" name="txtDescription"></textarea>
        </p>
        <p class="form-element">
            <button type="submit">Submit New Post</button>
        </p>
    </form>

    <form class="new-post-apartment form-option" method="" action="POST">
        <p class="form-element">
            <label for="txtListingTitle">Listing Title</label>
            <input type="text" name="txtListingTitle" placeholder="Enter a title for your new listing..." required>
        </p>
        <p class="form-element">
            <label>Listing Type</label>
            <label for="radSublease"><input type="radio" id="radSublease" name="radRoomType" required> Sublease</label>
            <label for="radRoommate"><input type="radio" id="radRoommate" name="radRoomType" required> Roommate Request</label>
        </p>
        <p class="form-element">
            <label for="txtAddress">Apartment Address (Optional)</label>
            <input type="text" name="txtAddress" placeholder="Enter the apartment address...">
        </p>
        <p class="form-element">
            <label for="selectTown">City/Town</label>
            <select name="selectTown" required>
                <option value="">Select a City/Town</option>
                <option value="Burlington">Burlington</option>
                <option value="South Burlington">South Burlington</option>
                <option value="Winooski">Winooski</option>
                <option value="Colchester">Colchester</option>
                <option value="Shelburne">Shelburne</option>
                <option value="Essex">Essex</option>
                <option value="Williston">Williston</option>
                <option value="Other">Other</option>
            </select>
        </p>
        <p class="form-element">
            <label for="numRent">Rent ($)</label>
            <input type="number" name="numRent" min="0" required>
        </p>
        <p class="form-element">
            <label for="numRent">Bedrooms</label>
            <input type="number" name="numRent" min="0" required>
        </p>
        <p class="form-element">
            <label for="numRent">Bathrooms</label>
            <input type="number" name="numRent" min="0" required>
        </p>
        <p class="form-element">
            <label>Amenities</label>
            <label for="chkAirConditioning"><input type="checkbox" name="chkAirConditioning"> Air Conditioning</label>
            <label for="chkLaundry"><input type="checkbox" name="chkLaundry"> Laundry</label>
            <label for="chkParking"><input type="checkbox" name="chkParking"> Parking</label>
            <label for="chkDishwasher"><input type="checkbox" name="chkDishwasher"> Dishwasher</label>
            <label for="chkInternet"><input type="checkbox" name="chkInternet"> Internet/Wi-Fi</label>
        </p>
        <p class="form-element">
            <label for="imgListingPhotos">Add Photos (Select All Images You Want to Upload. Must Include One Photo. Max 10)</label>
            <input type="file" name="imgListingPhotos" accept="image/png, image/jpeg" multiple required>
        </p>
        <p class="form-element">
            <label for="txtDescription">Talk About the Apartment, You, and Your Roommates (If You Have Any)!</label>
            <textarea cols="4" rows="5" maxlength="10000" name="txtDescription" required></textarea>
        </p>
        <p class="form-element">
            <label for="txtAdditionalInformation">Any Additional Information</label>
            <textarea cols="4" rows="5" maxlength="10000" name="txtDescription"></textarea>
        </p>
        <p class="form-element">
            <button type="submit">Submit New Post</button>
        </p>
    </form>

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

            // Handle what is displayed on form.
            function selectListingForm(formSelector) {
                var selectedOption = formSelector.options[formSelector.selectedIndex].value;
                let subForms = document.getElementsByClassName("form-option");

                console.log(selectedOption);
                var currentForm = 0;
                if(selectedOption === "Dorm Roommate Request") {
                    console.log("A");
                    for(currentForm; currentForm < subForms.length; currentForm++) {
                        if(subForms[currentForm].classList.contains("new-post-dormitory")) {
                            subForms[currentForm].classList.add("selected-form");
                        } else {
                            subForms[currentForm].classList.remove("selected-form");
                        }
                    }
                } else if(selectedOption === "Apartment Listing") {
                    console.log("B");
                    for(currentForm; currentForm < subForms.length; currentForm++) {
                        if(subForms[currentForm].classList.contains("new-post-apartment")) {
                            subForms[currentForm].classList.add("selected-form");
                        } else {
                            subForms[currentForm].classList.remove("selected-form");
                        }
                    }
                } else {
                    for(currentForm; currentForm < subForms.length; currentForm++) {
                        subForms[currentForm].classList.remove("selected-form");
                    }
                }
            }
        </script>
</main>

<?php
    include "footer.php";
?>