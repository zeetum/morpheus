<?PHP
include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/panels/boiler_header.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/functions/config.php');

// Navigation Bar
?>
<div class='nav_bar'>
    <a class='active' id='home_button'><br></a>
    <a href='new_category.php'>New Category</a>
    <a href='new_resource.php'>New Resource</a>
    <a href='delete_category.php'>Delete Category</a>
    <a href='delete_resource.php'>Delete Resource</a>
    <a href='delete_recurring.php'>Delete Recurring</a>
</div>
<?PHP include_once("admin_sidebar.php"); ?>
<div id='main_panel'></div>
<script src="../functions/main.js" type="text/javascript"></script>
<?PHP include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/panels/boiler_footer.php'); ?>
