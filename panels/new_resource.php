<?PHP
include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/panels/boiler_header.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/functions/config.php');
// Insert details into the resource table
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['category'])) {
    $stmt = $conn->prepare("INSERT INTO resources (name,description) VALUES(:name, :description)");
    $stmt->execute(array(
        ":name" => $_POST['name'],
        ":description" => $_POST['description']
    ));
    $r_id = $conn->lastInsertId();
    // Add the timeslots for the new category into it's table
    $_POST['category'] = str_replace(";","",$_POST['category']);
    $_POST['category'] = str_replace(",","",$_POST['category']);
    $stmt = $conn->prepare("SELECT DISTINCT date FROM ".$_POST['category']);
    $stmt->execute();
    $dates = $stmt->fetchAll();
    // if it's the first item, enter todays date
    if (count($dates) == 0) {
        $stmt = $conn->prepare("INSERT INTO ".$_POST['category']." (r_id,date) VALUES(:r_id, :date)");
        $stmt->execute(array(
            ":r_id" => $r_id,
	    ":date" =>  date('Y-m-d')
        ));
    }
    // otherwise add all existing timeslots for the resource
    foreach ($dates as $date) {
        $stmt = $conn->prepare("INSERT INTO ".$_POST['category']." (r_id,date) VALUES(:r_id, :date)");
        $stmt->execute(array(
            ":r_id" => $r_id,
            ":date" => $date['date']
        ));
    }
    $exec_string = "php ".$_SERVER["DOCUMENT_ROOT"].'/morpheus/functions/new_week.php '.date('Y-m-d');
    exec($exec_string);
}
?>

<!-- Navigation Bar -->
<div class='nav_bar'>
    <a id='home_button' class='active'><br></a>
    <a href='new_category.php'>New Category</a>
    <a class='active' href='new_resource.php'>New Resource</a>
    <a href='delete_category.php'>Delete Category</a>
    <a href='delete_resource.php'>Delete Resource</a>
    <a href='delete_recurring.php'>Delete Recurring</a>
</div>
<?PHP

// Display delete options
include_once("admin_sidebar.php");
echo "<div id='main_panel'>";
echo "<form action='new_resource.php' method=POST>";
echo     "<input type=text name='name' placeholder='Name of resource'></input>";
echo     "<input type=text name='description' placeholder='Description of resource'></input>";
echo     "<select name='category'>";
             $catagories = get_catagories($conn);
             foreach ($catagories as $category => $resources) {
                 echo "<option value='".$category."'>".$category."</option>";
             }
echo     "</select>";
echo     "<input type=submit value='Create'></input>";
echo "</form>";
echo "</div>";

include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/panels/boiler_footer.html');
?>

