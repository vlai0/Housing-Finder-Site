<?php
    include "top.php";

    // Get username.
    $user = "";
    if(!isset($_GET["user"])) {
        $user = $_SESSION["username"];
    } else {
        $user = $_GET["user"];
    }
    
    // Get user information
    $sql = "SELECT pmkUsername, fldFirstName, fldDescription, fldProfileImagePath FROM tblUser WHERE pmkUsername = ?";
    $data = array($user);
    $information = $thisDatabaseReader->select($sql, $data);

    // Get listing information
    $sql = "SELECT fldListingTitle, pmkListingId FROM tblListing WHERE pfkUsername = ?";
    $data = array($user);
    $listingInformation = $thisDatabaseReader->select($sql, $data);

    // Followed listings
    $sql = "SELECT tblFollowedListings.pfkUsername, pfkListingId, fldListingTitle FROM tblFollowedListings JOIN tblListing ON pmkListingId = pfkListingId WHERE tblFollowedListings.pfkUsername = ?";
    $data = array($user);
    $followedListing = $thisDatabaseReader->select($sql, $data);

/*
    if(empty($userData)) {
        header("Location: profile.php");
        exit();
    }
    print PHP_EOL;
    */
?>

<main class="dashboard">
<?php
    print "<h3>".$information[0]["fldFirstName"]. "'s Profile</h3>";
?>
    <div class="dashboard-section-wrapper">
        <section class="dashboard-section1">
            <h2>Profile</h2>
            <?php
                print "<p><strong>Username:</strong> ".$information[0]["pmkUsername"]."</p>";
                print "<p><strong>Description:</strong> ".$information[0]["fldDescription"]."</p>";
            ?>
        </section>
        <section class="dashboard-section2">
            <h2>Listings Created</h2>
            <?php
                foreach ($listingInformation as $listing) {
                    print '<li><a href="listing.php?listing_id='.$listing["pmkListingId"].'">'.$listing["fldListingTitle"].' </a></li>';
                }
            ?>
        </section>

        <section class="dashboard-section3">
            <h2>Listings Followed</h2>
            <?php
                foreach ($followedListing as $listing) {
                    print '<li><a href="listing.php?listing_id='.$listing["pfkListingId"].'">'.$listing["fldListingTitle"].'</a></li>';
                }
            ?>
        </section>
    </div>
</main>



<?php
    include "footer.php";
?>