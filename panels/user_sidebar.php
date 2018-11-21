<?PHP
// Set current day
if (isset($_GET['date']))
	$day = $_GET['date'];
else
	$day = date("Y-m-d");
// Get an array of resources in catagories from the database in the form:
//     $resources['category'] = array('resource1','resource2','resource3','etc...')
function get_catagories($conn) {
    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $resources = array();
    foreach ($categories as $category) {
    
        // get resources attached to each category
        $stmt = $conn->prepare("SELECT DISTINCT r_id FROM ".$category['table_name']);
        $stmt->execute();
        $r_ids = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
        // create the category array
        $resources[$category['table_name']] = array();
        foreach ($r_ids as $r_id) 	{
            array_push($resources[$category['table_name']],$r_id['r_id']);
        }
    }
    return $resources;
}
?>

<!-- Print $resource array into a selection accordion -->
    <div class="panel-group accordion">
<?PHP
        $resources = get_catagories($conn);
        foreach ($resources as $category => $r_ids) {
echo    "<div class='accordion_category'>";
echo        "<button class='accordion_button' onclick='toggle_class_display(\"".$category."_panel"."\")'>".$category."</button>";

            // Day view of each category
echo        "<div class='".$category."_panel accordion_resource'>";
echo        "<form class='accordion_form' action='user_day.php' method='GET'>";
echo            "<input type='hidden' name='category' value='".$category."'>";
echo            "<input type='hidden' name='date' value='".$day."'>";
echo            "<input class='accordion_button' type='submit' value='View Items'>";
echo        "</form>";
echo        "</div>";

            foreach ($r_ids as $r_id) {
               $stmt = $conn->prepare("SELECT name FROM resources WHERE r_id = :r_id");
               $stmt->execute(array(
                   ":r_id" => $r_id
               ));
               $name = $stmt->fetch(PDO::FETCH_ASSOC)['name'];

               // Week view of each category
echo           "<div class='".$category."_panel accordion_resource'>";
	           // Select buttons for each week for each resource
echo               "<form class='accordion_form' action='user_week.php' method='GET'>";
echo                   "<input type='hidden' name='r_id' value='".$r_id."'>";
echo                   "<input type='hidden' name='category' value='".$category."'>";
echo                   "<input type='hidden' name='date' value='".$day."'>";
echo                   "<input class='accordion_form_button' type='submit' value='".$name."'>";
echo               "</form>";
echo           "</div>";
            }
echo    "</div>";
        }
?>
    </div>
<script src="../functions/main.js" type="text/javascript"></script>


