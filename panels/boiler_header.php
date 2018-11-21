<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Book It</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/morpheus/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="/morpheus/functions/main.js"></script>
    </head>
    <body>
<?php
session_start();
if(!isset($_SESSION['username'])) {
        include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/panels/boiler_header.php');
        include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/panels/login.php');
        include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/panels/boiler_footer.php');
	exit();
} else {
    echo "<div class='nav_bar'>";
              if ($_SESSION['username'] == 'Administrator')
    echo "        <a class='active' id='home_button' href='/morpheus/panels/admin.php'>Homepage</a>";
	      else
    echo "        <a class='active' id='home_button' href='/morpheus/panels/user.php'>Homepage</a>";
    echo "    <a id='logout_link' href='/morpheus/functions/logout.php'>Logout</a>";
    echo "</div>";
}
?>
