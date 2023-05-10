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
            <select name="selectComplex">
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
            <select name="selectRoomSize">
                <option value="">Select a Room Size</option>
                <option value="Single">Single</option>
                <option value="Double">Double</option>
                <option value="Triple">Triple</option>
                <option value="Quad">Quad</option>
            </select>
        </p>
        <p class="form-element">
            <label for="chkPrivateBathroom">Has a Private Bathroom?</label>
            <input type="checkbox" name="chkPrivateBathroom">
    </form>

    <form class="new-post-apartment form-option" method="" action="POST">
        <p> FORM B</p>
         <p class="form-element">
            <label for="txtListingTitle">Listing Title</label>
            <input type="text" name="txtListingTitle" placeholder="Enter a title for your new listing..." required>
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