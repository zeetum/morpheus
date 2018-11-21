<?PHP
include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/panels/boiler_header.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/functions/config.php');

// Create the new category
if (isset($_POST['category']) && isset($_POST['columns'])) {
    // Sanitise the input
    $_POST['category'] = str_replace(' ','_',$_POST['category']);
    $_POST['category'] = str_replace(";","",$_POST['category']);
    $_POST['columns'] = str_replace(" ","_",$_POST['columns']);
    $_POST['columns'] = str_replace(";","",$_POST['columns']);

    // Add the table to the list of categories
    $stmt = $conn->prepare("INSERT INTO categories (table_name) VALUES (:table_name)");
    $stmt->execute(array(
    	":table_name" => $_POST['category']
    ));
    
    // Create the new table
    $query_string = "CREATE TABLE ".$_POST['category']." ( ";
    $query_string .= " r_id INT NOT NULL, ";
    $query_string .= " date VARCHAR(100) NOT NULL, ";
    $columns = explode(",",$_POST['columns']);
    foreach ($columns as $column) {
        // sanitising the input
        str_replace(";","",$column);
    
        $query_string .= "`".$column."` VARCHAR(255), ";
    }
    $query_string .= " PRIMARY KEY(date, r_id) )";
    $stmt = $conn->prepare($query_string);
    $stmt->execute();
}
?>

<!-- Navigation Bar -->
<div class='nav_bar'>
    <a id='home_button' class='active'><br></a>
    <a class='active' href='new_category.php'>New Category</a>
    <a href='new_resource.php'>New Resource</a>
    <a href='delete_category.php'>Delete Category</a>
    <a href='delete_resource.php'>Delete Resource</a>
    <a href='delete_recurring.php'>Delete Recurring</a>
</div>
<?PHP
include_once("admin_sidebar.php");

// Enter new details for form
echo "<div id='main_panel'>";
echo "<form id='new_category_form' onsubmit='combine_columns()' action='new_category.php' method=POST>";
echo     "<input type=text name='category' placeholder='Name of category'></input>";
echo     "<input type=hidden name='columns' id='columns_input'></input>";
echo     "<button id='timeslot_button' onclick=new_column() type='button'>Add Timeslot</button>";
echo     "<input type=submit value='Create'></input>";
echo "</form>";
echo "</div>";
?>

<script>
function new_column (){
  var input = document.createElement('input');
  input.type = 'text';
  input.placeholder = "Timeslot Name";
  input.className = "category_columns";
  document.getElementById("new_category_form").insertBefore(input, document.getElementById('timeslot_button'));
  //document.body.appendChild(input);
};
function combine_columns() {
    columns = document.getElementsByClassName("category_columns");
    var columns_string = "";
    for (var i = 0; i < columns.length; i++) {
        columns_string += columns[i].value + ",";
    }
    columns_string = columns_string.slice(0, -1);
    document.getElementById("columns_input").value = columns_string;
    return true;
}
</script>

<?PHP
include_once($_SERVER["DOCUMENT_ROOT"].'/bookit/panels/boiler_footer.html');
?>

