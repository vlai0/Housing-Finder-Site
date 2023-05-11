<?php
    include "top.php";

    // Check if user is logged in.
    if(session_id() == "" || !isset($_SESSION) || !isset($_SESSION["username"])) {
        header("Location: login.php");
        exit();
    }
?>

<main class="new-post">
    <h2>Post a New Listing</h2>
    <?php
        /* Initialize Default Form Values */
        // Dormitory form values.
        $dormitoryListingTitle = "";
        $complex = "";
        $campus = "";
        $roomType = "";
        $hasPrivateBathroom = 0;
        $dormitoryDescription = "";
        $dormitoryAdditionalInformation = "";

        // Apartment form values.
        $apartmentListingTitle = "";
        $apartmentListingType = "";
        $address = "";
        $location = "";
        $rent = NULL;
        $bedrooms = NULL;
        $bathrooms = NULL;
        $hasAirConditioning = 0;
        $hasLaundry = 0;
        $hasParking = 0;
        $hasDishwasher = 0;
        $hasInternet = 0;
        $apartmentDescription = "";
        $apartmentAdditionalInformation = "";

        $errorMessage = "";
        $postType = "";

        $photos = array();

        if(isset($_SESSION["username"]) && $_SERVER["REQUEST_METHOD"] === "POST") {

            $postType = getPostData("hidPostOption");
            $validInput = true;

            /* Sanitize & Validate Dormitory Post Form */
            if($postType == "Dorm Roommate Request") {
                // Get & Sanitize Form Values
                $dormitoryListingTitle = getPostData("txtListingTitle");
                $complex = getPostData("selectComplex");
                echo $complex;
                // Get campus.
                if($complex == "CCRH") {
                    $campus = "Central";
                } else if($complex == "CWPSCR" || $complex == "MSH" || $complex == "WDW") {
                    $campus = "Redstone";
                } else if($complex == "HM" || $complex == "LL" || $complex == "MAT" || $complex == "UHN" || $complex == "UHS") {
                    $campus = "Athletic";
                } else if($complex == "Trinity") {
                    $campus = "Trinity";
                }
                echo "C: ".$campus;
                $roomType = getPostData("selectRoomType");
                $hasPrivateBathroom = getPostData("chkPrivateBathroom") == "on" ? 1 : 0;
                $dormitoryDescription = getPostData("txtDescription");
                $dormitoryAdditionalInformation = getPostData("txtAdditionalInformation");

                /* VALIDATE LISTING TITLE */
                if(strlen($dormitoryListingTitle) > 64) {
                    $validInput = false;
                    $errorMessage = "Listing title too long. Can only be up to 64 characters long.";
                }

                /* VALIDATE COMPLEX */
                $complexes = array("CCRH", "CWPSCR", "HM", "LL", "MAT", "MSH", "Trinity", "UHN", "UHS", "WDW");
                if(!in_array($complex, $complexes)) {
                    $validInput = false;
                    $errorMessage = "Please select a complex.";
                }

                /* VALIDATE ROOM TYPE */
                $roomTypes = array("Double", "Triple", "Quad");
                if(!in_array($roomType, $roomTypes)) {
                    $validInput = false;
                    $errorMessage = "Please select a room type.";
                }

                /* DO NOT NEED TO VALIDATE PRIVATE BATHROOM CHECKBOX */

                /* GET AND VALIDATE PHOTOS */
                if($validInput) {
                    if(isset($_FILES["imgListingPhotos"])) {
                        // Create array of photos and check that there is at least one.
                        $tmp = array();
                        $count = count(array_filter($_FILES['imgListingPhotos']['name']));
                        if($count != 0) {
                            foreach($_FILES['imgListingPhotos']['tmp_name'] as $key => $tmpName) {
                                $photoName = basename($_FILES['imgListingPhotos']['name'][$key]);
                                $photoFileType = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
                                $photoSize = $_FILES['imgListingPhotos']['size'][$key];
                                array_push($photos, array("name" => $photoName, "tmp_name" => $tmpName, "type" => $photoFileType, "size" => $photoSize));
                            }
                        } else {
                            $validInput = false;
                            $errorMessage = "You must upload at least one photo.";
                        }
                    } else {
                        $validInput = false;
                        $errorMessage = "You must upload at least one photo.";
                    }
                }

                // Check if all files are either .png, .jpeg, or .jpg.
                if($validInput) {
                    foreach($photos as $photo) {
                        if($photo["type"] != "jpg" && $photo["type"] != "jpeg" && $photo["type"] != "png") {
                            $validInput = false;
                            $errorMessage = "Images can only be jpg, jpeg, or png.";
                        }
                    }
                }

                // Check if all files are at most 2MB and and not 0.
                if($validInput) {
                    foreach($photos as $photo) {
                        if($photo["size"] == "0") {
                            $validInput = false;
                            $errorMessage = "We were unable to upload '".$photo["name"]."'. Please select a new photo or try again.";
                            break;
                        }

                        if($photo["size"] > 2097152 ) {
                            $validInput = false;
                            $errorMessage = "You are trying to upload an image that is too large. Each image must be less than 2MB.";
                            break;
                        }
                    }
                }

                /* VALIDATE LISTING DESCRIPTION */
                // Check that description doesn't exceed 10000 characters.
                if($validInput && strlen($dormitoryDescription) > 10000) {
                    $validInput = false;
                    $errorMessage = "Description cannot exceed 10,000 characters.";
                }

                /* VALIDATE ADDITIONAL INFORMATION */
                // Check that additional information doesn't exceed 10000 characters.
                if($validInput && strlen($dormitoryAdditionalInformation) > 10000) {
                    $validInput = false;
                    $errorMessage = "Additional information cannot exceed 10,000 characters.";
                }

                /* INSERT NEW DORMITORY LISTING INTO DATABASE */
                $DEBUG = false;
                echo "Posting";
                if($validInput && $DEBUG == false) {
                    // Insert into tblListing.
                    echo $dormitoryListingTitle;
                    echo $dormitoryDescription;
                    $sql = "INSERT INTO tblListing (pfkUsername, fldListingTitle, fldListingDescription, fldListingAdditionalInformation, fldType) VALUES (?, ?, ?, ?, ?)";
                    $data = array($_SESSION["username"], $dormitoryListingTitle, $dormitoryDescription, $dormitoryAdditionalInformation, "Dormitory");
                    $success = $thisDatabaseWriter->insert($sql, $data);

                    // Get new listing id.
                    $sql = "SELECT pmkListingId FROM tblListing ";
                    $sql .= "WHERE pfkUsername = ? ORDER BY fldCreationTimestamp DESC LIMIT 1";
                    $data = array($_SESSION["username"]);
                    $results = $thisDatabaseReader->select($sql, $data);
                    $listingID = $results[0]["pmkListingId"];

                    // Insert into tblListingApartment
                    $sql = "INSERT INTO tblDormitoryListing (fldCampus, fldComplex, fldRoomType, fldHasPrivateBathroom, fnkListingId) ";
                    $sql .= "VALUES (?, ?, ?, ?, ?)";
                    $data = array($campus, $complex, $roomType, $hasPrivateBathroom, $listingID);
                    $success = $thisDatabaseWriter->insert($sql, $data);

                    // Add photos.
                    // Add profile photo to profile images path and rename to standardized name.
                    $standardizedImagePath = NULL;
                    $orderNumber = 1;
                    $newDirPath = "images/listings/dormitory_listings/".$listingID;
                    mkdir($newDirPath, 0777, true);

                    foreach($photos as $photo) {
                        // Add photo to its directory.
                        $name = $photo["name"];
                        $profileImagePath = "images/listings/dormitory_listings/".$listingID."/".$name;
                        $standardizedImagePath = "images/listings/dormitory_listings/".$listingID."/".$listingID."_".$orderNumber.".png";
                        move_uploaded_file($photo["tmp_name"], $profileImagePath);
                        rename($profileImagePath, $standardizedImagePath);

                        // Insert photo path into database.
                        $sql = "INSERT INTO tblListingImages (pfkListingId, fldOrderNumber, fldImagePath) ";
                        $sql .= "VALUES (?, ?, ?)";
                        $data = array($listingID, $orderNumber, $standardizedImagePath);
                        $thisDatabaseWriter->insert($sql, $data);
                        
                        $orderNumber++;
                    }
                }
                // Validate Apartment Post Form
            } else if ($postType == "Apartment Listing") {
                // Get data for apartment post.
                $apartmentListingTitle = getPostData("txtListingTitle");
                $apartmentListingType = getPostData("radApartmentListingType");
                $location = getPostData("selectTown");
                $rent = (float)getPostData("numRent");
                $bedrooms = (int)getPostData("numBedrooms");
                $bathrooms = (int)getPostData("numBathrooms");
                $hasAirConditioning = getPostData("chkAirConditioning") == "on" ? 1 : 0;
                $hasLaundry = getPostData("chkLaundry") == "on" ? 1 : 0;
                $hasParking = getPostData("chkParking") == "on" ? 1 : 0;
                $hasDishwasher = getPostData("chkDishwasher") == "on" ? 1 : 0;
                $hasInternet = getPostData("chkInternet") == "on" ? 1 : 0;
                $apartmentDescription = getPostData("txtDescription");
                $apartmentAdditionalInformation = getPostData("txtAdditionalInformation");

                /* VALIDATE LISTING TITLE */
                if(strlen($apartmentListingTitle) > 64) {
                    $validInput = false;
                    $errorMessage = "Listing title too long. Can only be up to 64 characters long.";
                }

                /* VALIDATE LISTING TYPE */
                if($validInput && $apartmentListingType != "Apartment Roommate Request" && $apartmentListingType != "Sublease") {
                    $validInput = false;
                    $errorMessage = "Please select a listing type.";
                }

                /* VALIDATE LOCATION */
                $locations = array("Burlington", "South Burlington", "Winooski", "Colchester", "Shelburne", "Essex", "Williston", "Other");
                if($validInput && !in_array($location, $locations)) {
                    $validInput = false;
                    $errorMessage = "Please select a listed city/town option.";
                }

                /* VALIDATE RENT */
                if($validInput && $rent >= 1000000 || $rent < 0) {
                    $validInput = false;
                    $errorMessage = "Rent values are limited to the range $0 to $1,000,000/month.";
                }

                /* VALIDATE BEDROOMS */
                if($validInput && $bedrooms >= 100 || $bedrooms < 0) {
                    $validInput = false;
                    $errorMessage = "Bedroom values are limited to the range 0 to 100.";
                }

                /* VALIDATE BATHROOMS */
                if($validInput && $bathrooms >= 100 || $bathrooms < 0) {
                    $validInput = false;
                    $errorMessage = "Bathroom values are limited to the range 0 to 100.";
                }

                /* DO NOT NEED TO VALIDATE CHECKBOXES. THEY ARE EITHER SELECTED OR UNSELECTED. USER DOES NOT NEED TO SELECT ANY IF THEY DONT WANT TO. */

                /* GET AND VALIDATE PHOTOS */
                if($validInput) {
                    if(isset($_FILES["imgListingPhotos"])) {
                        // Create array of photos and check that there is at least one.
                        $tmp = array();
                        $count = count(array_filter($_FILES['imgListingPhotos']['name']));
                        if($count != 0) {
                            foreach($_FILES['imgListingPhotos']['tmp_name'] as $key => $tmpName) {
                                $photoName = basename($_FILES['imgListingPhotos']['name'][$key]);
                                $photoFileType = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
                                $photoSize = $_FILES['imgListingPhotos']['size'][$key];
                                echo $tmpName;
                                echo $photoName;
                                echo $photoFileType;
                                echo $photoSize;
                                array_push($photos, array("name" => $photoName, "tmp_name" => $tmpName, "type" => $photoFileType, "size" => $photoSize));
                            }
                        } else {
                            $validInput = false;
                            $errorMessage = "You must upload at least one photo.";
                        }
                    } else {
                        $validInput = false;
                        $errorMessage = "You must upload at least one photo.";
                    }
                }

                // Check if all files are either .png, .jpeg, or .jpg.
                if($validInput) {
                    foreach($photos as $photo) {
                        if($photo["type"] != "jpg" && $photo["type"] != "jpeg" && $photo["type"] != "png") {
                            $validInput = false;
                            $errorMessage = "Images can only be jpg, jpeg, or png.";
                            break;
                        }
                    }
                }

                // Check if all files are at most 2MB and and not 0.
                if($validInput) {
                    foreach($photos as $photo) {
                        if($photo["size"] == "0") {
                            $validInput = false;
                            $errorMessage = "We were unable to upload '".$photo["name"]."'. Please select a new photo or try again.";
                            break;
                        }

                        if($photo["size"] > 2097152 ) {
                            $validInput = false;
                            $errorMessage = "You are trying to upload an image that is too large. Each image must be less than 2MB.";
                            break;
                        }
                    }
                }

                /* VALIDATE LISTING DESCRIPTION */
                // Check that description doesn't exceed 10000 characters.
                if($validInput && strlen($apartmentDescription) > 10000) {
                    $validInput = false;
                    $errorMessage = "Description cannot exceed 10,000 characters.";
                }

                /* VALIDATE ADDITIONAL INFORMATION */
                // Check that additional information doesn't exceed 10000 characters.
                if($validInput && strlen($apartmentAdditionalInformation) > 10000) {
                    $validInput = false;
                    $errorMessage = "Additional information cannot exceed 10,000 characters.";
                }

                /* INSERT NEW APARTMENT LISTING INTO DATABASE */
                $DEBUG = false;
                echo "Posting";
                if($validInput && $DEBUG == false) {
                    // Insert into tblListing.
                    $sql = "INSERT INTO tblListing (pfkUsername, fldListingTitle, fldListingDescription, fldListingAdditionalInformation, fldType) VALUES (?, ?, ?, ?, ?)";
                    $data = array($_SESSION["username"], $apartmentListingTitle, $apartmentDescription, $apartmentAdditionalInformation, "Apartment");
                    $success = $thisDatabaseWriter->insert($sql, $data);

                    // Get new listing id.
                    $sql = "SELECT pmkListingId FROM tblListing ";
                    $sql .= "WHERE pfkUsername = ? ORDER BY fldCreationTimestamp DESC LIMIT 1";
                    $data = array($_SESSION["username"]);
                    $results = $thisDatabaseReader->select($sql, $data);
                    $listingID = $results[0]["pmkListingId"];

                    // Insert into tblListingApartment
                    $sql = "INSERT INTO tblApartmentListing (fldTown, fldRent, fldBedrooms, fldBathrooms, fnkListingId, fldHasAirConditioning, fldHasLaundry, fldHasParking, fldHasDishwasher, fldHasInternet, fldApartmentListingType) ";
                    $sql .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $data = array($location, $rent, $bedrooms, $bathrooms, $listingID, $hasAirConditioning, $hasLaundry, $hasParking, $hasDishwasher, $hasInternet, $apartmentListingType);
                    $success = $thisDatabaseWriter->insert($sql, $data);

                    // Add photos.
                    // Add profile photo to profile images path and rename to standardized name.
                    $standardizedImagePath = NULL;
                    $orderNumber = 1;
                    $newDirPath = "images/listings/apartment_listings/".$listingID;
                    mkdir($newDirPath, 0777, true);

                    foreach($photos as $photo) {
                        // Add photo to its directory.
                        $name = $photo["name"];
                        $profileImagePath = "images/listings/apartment_listings/".$listingID."/".$name;
                        $standardizedImagePath = "images/listings/apartment_listings/".$listingID."/".$listingID."_".$orderNumber.".png";
                        move_uploaded_file($photo["tmp_name"], $profileImagePath);
                        rename($profileImagePath, $standardizedImagePath);

                        // Insert photo path into database.
                        $sql = "INSERT INTO tblListingImages (pfkListingId, fldOrderNumber, fldImagePath) ";
                        $sql .= "VALUES (?, ?, ?)";
                        $data = array($listingID, $orderNumber, $standardizedImagePath);
                        $thisDatabaseWriter->insert($sql, $data);
                        
                        $orderNumber++;
                    }
                }
            }   
        }
    ?>
    <form class="post-form-selector">
        <p class="form-element">
            <label for="selectListingType">Listing Type</label>
            <select name="selectListingType" id="selectListingType" onChange="selectListingForm(this.id);">
                <option value="">Select an Option</option>
                <option value="Dorm Roommate Request" <?php if($postType == "Dorm Roommate Request") { print "selected"; } ?>>Dorm Roommate Request</option>
                <option value="Apartment Listing" <?php if($postType == "Apartment Listing") { print "selected"; } ?>>Apartment Listing</option>
            </select>
        </p>
    </form>
    <hr>
    <!-- Dormitory Listing Form -->
    <form class="new-post-dormitory form-option" action="" method="POST" enctype="multipart/form-data">
        <p class="form-element">
            <label for="txtListingTitle">Listing Title</label>
            <input type="text" name="txtListingTitle" placeholder="Enter a title for your new listing..." value="<?php if($dormitoryListingTitle != "") { print $dormitoryListingTitle; } ?>" required>
        </p>
        <p class="form-element">
            <label for="selectComplex">Residential Complex</label>
            <select name="selectComplex" required>
                <option value="">Select a Complex</option>
                <option value="CCRH" <?php if($complex == "CCRH") { print "selected"; }?>>CCRH</option>
                <option value="CWPSCR" <?php if($complex == "CWPSCR") { print "selected"; }?>>CWPSCR</option>
                <option value="HM" <?php if($complex == "HM") { print "selected"; }?>>HM</option>
                <option value="LL" <?php if($complex == "LL") { print "selected"; }?>>LL</option>
                <option value="MAT" <?php if($complex == "MAT") { print "selected"; }?>>MAT</option>
                <option value="MSH" <?php if($complex == "MSH") { print "selected"; }?>>MSH</option>
                <option value="Trinity" <?php if($complex == "Trinity") { print "selected"; }?>>Trinity</option>
                <option value="UHN" <?php if($complex == "UHN") { print "selected"; }?>>UHN</option>
                <option value="UHS" <?php if($complex == "UHS") { print "selected"; }?>>UHS</option>
                <option value="WDW" <?php if($complex == "WDW") { print "selected"; }?>>WDW</option>
            </select>
        </p>
        <p class="form-element">
            <label for="selectRoomType">Room Type</label>
            <select name="selectRoomType" required>
                <option value="">Select a Room Type</option>
                <option value="Double" <?php if($roomType == "Double") { print "selected"; } ?>>Double</option>
                <option value="Triple" <?php if($roomType == "Triple") { print "selected"; } ?>>Triple</option>
                <option value="Quad" <?php if($roomType == "Quad") { print "selected"; } ?>>Quad</option>
            </select>
        </p>
        <p class="form-element">
            <label for="chkPrivateBathroom">Has a Private Bathroom? <input type="checkbox" name="chkPrivateBathroom" <?php if($hasPrivateBathroom == 1) { print "checked"; } ?>></label>
        </p>
        <p class="form-element">
            <label for="imgListingPhotos">Add Photos (Select All Images You Want to Upload. Must Include One Photo. Max 10)</label>
            <input type="file" name="imgListingPhotos[]" accept="image/png, image/jpeg" multiple required>
        </p>
        <p class="form-element">
            <label for="txtDescription">Talk About You, Your Roommates (If You Have Any), and the Dorm!</label>
            <textarea cols="4" rows="5" maxlength="10000" name="txtDescription" required><?php if($dormitoryDescription != "") { print $dormitoryDescription; } ?></textarea>
        </p>
        <p class="form-element">
            <label for="txtAdditionalInformation">Any Additional Information</label>
            <textarea cols="4" rows="5" maxlength="10000" name="txtAdditionalInformation"><?php if($dormitoryAdditionalInformation != "") { print $dormitoryAdditionalInformation; } ?></textarea>
        </p>
        <p class="form-element">
            <input type="hidden" name="hidPostOption" value="Dorm Roommate Request" required>
        </p>
        <p class="form-element">
            <button type="submit">Submit New Post</button>
        </p>
    </form>

    <form class="new-post-apartment form-option" action="" method="POST" enctype="multipart/form-data">
        <p class="form-element">
            <label for="txtListingTitle">Listing Title</label>
            <input type="text" name="txtListingTitle" placeholder="Enter a title for your new listing..." value="<?php if($apartmentListingTitle != "") { print $apartmentListingTitle; } ?>" required>
        </p>
        <p class="form-element">
            <label>Apartment Listing Type</label>
            <label for="radSublease"><input type="radio" id="radSublease" name="radApartmentListingType" value="Sublease" required <?php if($apartmentListingType == "Sublease") { print "checked"; } ?>> Sublease</label>
            <label for="radRoommate"><input type="radio" id="radRoommate" name="radApartmentListingType" value="Apartment Roommate Request" required <?php if($apartmentListingType == "Apartment Roommate Request") { print "checked"; } ?>> Roommate Request</label>
        </p>
        <p class="form-element">
            <label for="selectTown">City/Town</label>
            <select name="selectTown" required>
                <option value="">Select a City/Town</option>
                <option value="Burlington" <?php if($location == "Burlington") { print "selected"; } ?>>Burlington</option>
                <option value="South Burlington"<?php if($location == "South Burlington") { print "selected"; } ?>>South Burlington</option>
                <option value="Winooski" <?php if($location == "Winooski") { print "selected"; } ?>>Winooski</option>
                <option value="Colchester" <?php if($location == "Colchester") { print "selected"; } ?>>Colchester</option>
                <option value="Shelburne" <?php if($location == "Shelburne") { print "selected"; } ?>>Shelburne</option>
                <option value="Essex" <?php if($location == "Essex") { print "selected"; } ?>>Essex</option>
                <option value="Williston" <?php if($location == "Williston") { print "selected"; } ?>>Williston</option>
                <option value="Other" <?php if($location == "Other") { print "selected"; } ?>>Other</option>
            </select>
        </p>
        <p class="form-element">
            <label for="numRent">Rent ($)</label>
            <input type="number" name="numRent" min="0" step="0.01" value="<?php if($rent != NULL) { print $rent; } ?>" required>
        </p>
        <p class="form-element">
            <label for="numRent">Bedrooms</label>
            <input type="number" name="numBedrooms" min="0" step="1" value="<?php if($bedrooms != NULL) { print $bedrooms; } ?>" required>
        </p>
        <p class="form-element">
            <label for="numRent">Bathrooms</label>
            <input type="number" name="numBathrooms" min="0" step="1" value="<?php if($bathrooms != NULL) { print $bathrooms; } ?>" required>
        </p>
        <p class="form-element">
            <label>Amenities</label>
            <label for="chkAirConditioning"><input type="checkbox" name="chkAirConditioning" <?php if($hasAirConditioning == 1) { print "checked"; } ?>> Air Conditioning</label>
            <label for="chkLaundry"><input type="checkbox" name="chkLaundry" <?php if($hasLaundry == 1) { print "checked"; } ?>> Laundry</label>
            <label for="chkParking"><input type="checkbox" name="chkParking" <?php if($hasParking == 1) { print "checked"; } ?>> Parking</label>
            <label for="chkDishwasher"><input type="checkbox" name="chkDishwasher" <?php if($hasDishwasher == 1) { print "checked"; } ?>> Dishwasher</label>
            <label for="chkInternet"><input type="checkbox" name="chkInternet" <?php if($hasInternet == 1) { print "checked"; } ?>> Internet/Wi-Fi</label>
        </p>
        <p class="form-element">
            <label for="imgListingPhotos">Add Photos (Select All Images You Want to Upload. Must Include One Photo. Max 10)</label>
            <input type="file" name="imgListingPhotos[]" accept="image/png, image/jpeg" multiple required>
        </p>
        <p class="form-element">
            <label for="txtDescription">Talk About the Apartment, You, and Your Roommates (If You Have Any)!</label>
            <textarea cols="4" rows="5" maxlength="10000" name="txtDescription" required><?php if($apartmentDescription != "") { print $apartmentDescription; } ?></textarea>
        </p>
        <p class="form-element">
            <label for="txtAdditionalInformation">Any Additional Information</label>
            <textarea cols="4" rows="5" maxlength="10000" name="txtAdditionalInformation"><?php if($apartmentAdditionalInformation != "") { print $apartmentAdditionalInformation; } ?></textarea>
        </p>
        <p class="form-element">
            <input type="hidden" name="hidPostOption" value="Apartment Listing" required>
        </p>
        <p class="form-element">
            <button type="submit">Submit New Post</button>
        </p>
    </form>

    <!-- Form Errors -->
    <?php
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            if($errorMessage == "") {
                print "<p class=\"form-success\">You have successfully created a new listing!</p>";
            } else {
                print "<p class=\"form-error\">".$errorMessage."</p>";
            }
        }
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

            <?php
                if($_SERVER["REQUEST_METHOD"] === "POST") {
                    // Go back to selected form on POST if errors.
                    if($postType != "" && $errorMessage != "") {
                        print "selectListingForm(\"selectListingType\");";
                    }
                }
            ?>

            // Handle what is displayed on form.
            function selectListingForm(formSelector) {
                var formSelector = document.getElementById(formSelector);
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