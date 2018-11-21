<?PHP
include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/panels/boiler_header.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/functions/config.php');

// Navigation Bar
?>
<div class='nav_bar'>
    <a class='active' id='home_button'><br></a>
</div>
<?PHP include_once("user_sidebar.php"); ?>
<div id='main_panel'></div>
<?PHP include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/panels/boiler_footer.php'); ?>

