<?php
    define("ADMINS", array("astem", "vlai", "idavis1", "rerickso"));
    define('PATH_PARTS', pathinfo(htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8")));
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>UVM Housing Finder | Home</title>
            <meta name="author" content="Anthony Stem">
            <meta name="author" content="Vincent Lai">
            <meta name="description" content="">
            <!-- custom.css is mobile design; we did mobile design first -->
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" type="text/css" media="(max-width: 1400px)" href="css/desktop.css">
            <link rel="stylesheet" type="text/css" media="(max-width: 992px)" href="css/tablet.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
<?php
    print "<body>".PHP_EOL;

    /* ##### INCLUDES ###### */
    include "connect-DB.php";
    print PHP_EOL;
    // Header is included in nav.php.
    include 'nav.php';
    print PHP_EOL;

    function getPostData($field) {
        if(!isset($_POST[$field])) {
            $data = "";
        } else {
            $data = trim($_POST[$field]);
            $data = htmlspecialchars($data);
        }
        return $data;
    }
?>
