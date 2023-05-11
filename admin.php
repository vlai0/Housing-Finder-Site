<?php
    include "top.php";
    $formType = "";
    // Get form type on post.
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $formType = getPostData("hidAdminForm");
    }

    $successMessage = "";
    $errorMessage = "";
?>
<main class="admin">
    <h2>Admin Page</h2>
    <section>
        <h3>Manage Listings &amp; Users: Create, Update, and Delete</h3>
            <?php
                // Default values.
                // Create new listing following relationship.
                $targetUserAddSave = "";
                $listingPoster = "";
                $listingTitleToSave = "";
                // Update user password.
                $usernameToUpdatePassword = "";
                $newPassword = "";
                // Delete listing.
                $usernameOfListingPoster = "";
                $listingToDelete = "";
                // Delete user.
                $userToDelete = "";

                if($formType == "Modify") {
                    // Get values.
                    $targetUserAddSave = getPostData("txtUsernameUpdate");
                    $listingPoster = getPostData("txtUsernameOfListing");
                    $listingTitleToSave = getPostData("txtListingToSave");

                    $usernameToUpdatePassword = getPostData("txtUsernamePasswordUpdate");
                    $newPassword = getPostData("txtNewPassword");

                    $usernameOfListingPoster = getPostData("txtUsernameOfPoster");     
                    $listingToDelete = getPostData("txtListingToDelete");     

                    $valid = true;

                    // Check if all values are specified for new saved listing.
                    if($valid && $targetUserAddSave != "" && $listingPoster != "" && $listingTitleToSave != "") {
                        // Check if listing exists.
                        $sql = "SELECT * FROM tblListing WHERE pfkUsername = ? AND fldListingTitle = ?";
                        $data = array($listingPoster, $listingTitleToSave);
                        $results = $thisDatabaseReader->select($sql, $data);
                        $listingID = "";
                        if($valid && empty($results)) {
                            $valid = false;
                            $errorMessage = "Specified listing '".$listingTitleToSave."' does not exist.";
                        } else {
                            $listingID = $results[0]["pmkListingId"];
                        }

                        // Check if user already follows.
                        $sql = "SELECT * FROM tblFollowedListings WHERE pfkUsername = ? AND pfkListingId = ?";
                        $data = array($targetUserAddSave, $listingID);
                        $results = $thisDatabaseReader->select($sql, $data);
                        if($valid && empty($results)) {
                            // Create new following.
                            $sql = "INSERT INTO tblFollowedListings (pfkUsername, pfkListingId) VALUES (?, ?)";
                            $data = array($targetUserAddSave, $listingID);
                            $success = $thisDatabaseWriter->insert($sql, $data);
                            if($success) {
                                $successMessage .= "Successfully made user follow listing.\n";
                            } else {
                                $successMessage = "";
                                $errorMessage = "Encountered issue adding following.";
                            }
                        } else if($valid) {
                            $valid = false;
                            $errorMessage = "User already follows this listing!";
                        }
                    }

                    // Check if all values are specified for update password.
                    if($valid && $usernameToUpdatePassword != "" && $newPassword != "") {
                        if($valid && strlen($newPassword < 6)) {
                            $valid = false;
                            $errorMessage = "New password too short. Must be greater than or equal to 6 characters.";
                        }

                        if($valid) {
                            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                            $sql = "UPDATE tblUser SET fldPasswordHash = ? WHERE pmkUsername = ?";
                            $data = array($hash, $usernameToUpdatePassword);
                            $success = $thisDatabaseWriter->update($sql, $data);
                            if($success) {
                                $successMessage .= "Successfully updated user's password.\n";
                            } else {
                                $valid = false;
                                $successMessage = "";
                                $errorMessage = "Encountered issue submitting password change.";
                            }
                        }   
                    }

                    // Check if all values are specified for delete listing.
                    if($valid && $usernameOfListingPoster != "" && $listingToDelete != "") {
                        // Check if listing exists.
                        $sql = "SELECT * FROM tblListing WHERE pfkUsername = ? AND fldListingTitle = ?";
                        $data = array($usernameOfListingPoster, $listingToDelete);
                        $results = $thisDatabaseReader->select($sql, $data);
                        $listingID = "";
                        $type = "";
                        if($valid && empty($results)) {
                            $valid = false;
                            $errorMessage = "Failed to delete listing. Specified listing '".$listingToDelete."' does not exist.";
                        } else {
                            $listingID = $results[0]["pmkListingId"];
                            $type = $results[0]["fldType"];
                        }

                        $sql = "";
                        if($type == "Apartment") {
                            $sql = "DELETE tblApartmentListing FROM tblApartmentListing JOIN tblListing ON pmkListingId = fnkListingId WHERE pmkListingId = ? AND fldListingTitle = ?";
                        } else if($type == "Dormitory") {
                            $sql = "DELETE tblDormitoryListing FROM tblDormitoryListing JOIN tblListing ON pmkListingId = fnkListingId WHERE pmkListingId = ? AND fldListingTitle = ?";
                        }
                
                        if($valid && $sql != "") {
                            $data = array($listingID, $listingToDelete);
                            $success = $thisDatabaseWriter->delete($sql, $data);

                            if($success) {
                                // Delete main listing.
                                $sql = "DELETE FROM tblListing WHERE pmkListingId = ? AND fldListingTitle = ?";
                                $success = $thisDatabaseWriter->delete($sql, $data);
                            }

                            if($success) {
                                // Delete follows.
                                $sql = "DELETE FROM tblFollowedListings WHERE pfkListingId = ?";
                                $data = array($listingID);
                                $success = $thisDatabaseWriter->delete($sql, $data);
                            }

                            if($success) {
                                $successMessage .= "Successfully made changes to all entries.";
                            } else {
                                $successMessage = "";
                                $errorMessage = "Encountered issue deleting listing. Please try again and check your input.";
                            }
                        }
                    } 
                }
            ?>
        <form class="admin-form" action="#" method="POST">
            <p><strong>Add a New Saved Listing to User</strong></p>
            <p class="form-element">
                <label for="txtUsernameUpdate">Username of User to Save Listing</label>
                <input type="text" name="txtUsernameUpdate" placeholder="" value="<?php if($targetUserAddSave != "") { print $targetUserAddSave; } ?>">
            </p>
            <p class="form-element">
                <label for="txtUsernameOfListing">Username of Listing Poster</label>
                <input type="text" name="txtUsernameOfListing" placeholder="" value="<?php if($listingPoster != "") { print $listingPoster; } ?>">
            <p>
            <p class="form-element">
                <label for="txtListingToSave">Listing Title</label>
                <input type="text" name="txtListingToSave" placeholder="" value="<?php if($listingTitleToSave != "") { print $listingTitleToSave; } ?>">
            </p>
            <p><strong>Update a User's Password</strong></p>
            <p class="form-element">
                <label for="txtUsernameUpdate">Username</label>
                <input type="text" name="txtUsernamePasswordUpdate" placeholder="" value="<?php if($usernameToUpdatePassword != "") { print $usernameToUpdatePassword; } ?>">
            </p>
            <p class="form-element">
                <label for="txtNewPassword">Update User's Password</label>
                <input type="password" name="txtNewPassword" placeholder="" value="<?php if($newPassword != "") { print $newPassword; } ?>">
            </p>
            <p><strong>Delete a Listing</strong></p>
            <p class="form-element">
                <label for="txtUsernameToDelete">Username of Listing Poster</label>
                <input type="text" name="txtUsernameOfPoster" placeholder="" value="<?php if($usernameOfListingPoster != "") { print $usernameOfListingPoster; } ?>">
            <p>
            <p class="form-element">
                <label for="txtListingToDelete">Listing Title</label>
                <input type="text" name="txtListingToDelete" placeholder="" value="<?php if($listingToDelete != "") { print $listingToDelete; } ?>">
            </p>
            <p>
                <input type="hidden" name="hidAdminForm" value="Modify">
            </p>
            <button type="submit">Execute Changes</button>
        </form>
        <?php if($successMessage != "") { print "<p class=\"form-success\">".$successMessage."</p>".PHP_EOL; } ?>
        <?php if($errorMessage != "") { print "<p class=\"form-error\">".$errorMessage."</p>".PHP_EOL; } ?>
    </section>
    <section>
        <h3>Site Usage Report</h3>
        <p>Get insight into useful information about site engagement and usage such as the following:
        <ul>
            <li>How many users are registered.</li>
            <li>Site listing post activity</li>
            <li>Number of posts across the system</li>
            <li>Average follows by post.</li>
            <li>And more...</li>
        </ul>
        <form class="admin-form" action="#" method="POST">
            <p>
                <input type="hidden" name="hidAdminForm" value="Report">
                <button type="submit">Run Report</button>
            </p>
        </form>
    </section>
    <?php
        // Report.
        if($formType == "Report") {
            print "<section>".PHP_EOL;
            print "<h3>Site Usage &amp; Engagement Report Results</h3>".PHP_EOL;
            print "<p><strong>Number of Registered Users:</strong> ".($thisDatabaseReader->select("SELECT count(*) as 'countRegisteredUsers' FROM tblUser")[0]["countRegisteredUsers"]).".</p>".PHP_EOL;
            print "<p><strong>Number of Dormitory Listings:</strong> ".($thisDatabaseReader->select("SELECT count(*) as 'countDormListings' FROM tblListing JOIN tblDormitoryListing ON pmkListingId = fnkListingId")[0]["countDormListings"]).".</p>".PHP_EOL;
            print "<p><strong>Number of Apartment Listings:</strong> ".($thisDatabaseReader->select("SELECT count(*) as 'countAptListings' FROM tblListing JOIN tblApartmentListing ON pmkListingId = fnkListingId")[0]["countAptListings"]).".</p>".PHP_EOL;
            print "<p><strong>Total Number of Listings:</strong> ".($thisDatabaseReader->select("SELECT count(*) as 'countListings' FROM tblListing")[0]["countListings"]).".".PHP_EOL;
            print "<p><strong>Average Number of Photos By Listing:</strong> ".($thisDatabaseReader->select("SELECT avg(numPhotos) as \"average\" FROM (SELECT pmkListingId, count(*) as \"numPhotos\" FROM tblListing JOIN tblListingImages WHERE pmkListingId = pfkListingId GROUP BY pmkListingId) AS Counts"))[0]["average"].".</p>".PHP_EOL;
            print "<p><strong>Average Number of Followed Listings By User:</strong> ".($thisDatabaseReader->select("SELECT avg(followingCount) as \"average\" FROM (SELECT pfkUsername, count(*) as \"followingCount\" FROM tblFollowedListings GROUP BY pfkUsername) as Counts"))[0]["average"].".</p>".PHP_EOL;
            $sql = "SELECT fldListingTitle, fldCreationTimestamp FROM tblListing ORDER BY fldCreationTimestamp DESC LIMIT 1";
            $results = $thisDatabaseReader->select("SELECT fldListingTitle, fldCreationTimestamp FROM tblListing ORDER BY fldCreationTimestamp DESC LIMIT 1");
            print "<p><strong>Most Recent Listing Post:</strong><br> <strong>Title</strong>: ".$results[0]["fldListingTitle"].", <strong>Datetime:</strong> ".$results[0]["fldCreationTimestamp"].".</p>".PHP_EOL;
            print "</section>".PHP_EOL;
        }
    ?>
</main>


<?php
    include "footer.php";
?>