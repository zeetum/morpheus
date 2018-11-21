<?PHP
include_once($_SERVER["DOCUMENT_ROOT"].'/morpheus/functions/config.php');
/*
    prints the week for a date of a resource in a category 
*/
// Returns an array in the form:
// date['Day'] => 'date'
function get_week_dates($date, $format = 'Y-m-d') {
    $names = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday");
    $dates = array();
    $day = date('w', strtotime($date)) - 1;
    $current = date($format, strtotime($date.' -'.$day.' days'));
    $last = date($format, strtotime($current.' +4 days'));
    $i = 0;
    while ($current <= $last) {
        $dates[$names[$i++]] = $current;
        $current = date($format, strtotime($current.' +1 days'));
    }
    return $dates;
}
if (isset($_GET['date']) && isset($_GET['r_id']) && isset($_GET['category'])) {
    $_GET['category'] = str_replace(";","",$_GET['category']);
    $_GET['category'] = str_replace(",","",$_GET['category']);
    $stmt = $conn->prepare("SELECT * FROM ".$_GET['category']." WHERE r_id = :r_id AND date = :date");
    $stmt->execute(array(
        ":r_id" => $_GET['r_id'],
        ":date" => $_GET['date']
    ));
    $timeslots = $stmt->fetch(PDO::FETCH_ASSOC);
    // If there are no timeslots, generate a new week and re-query
    if ($timeslots == NULL) {
        $exec_string = "php ".$_SERVER["DOCUMENT_ROOT"].'/morpheus/functions/new_week.php '.$_GET['date'];
        exec($exec_string);
        $stmt = $conn->prepare("SELECT * FROM ".$_GET['category']." WHERE r_id = :r_id AND date = :date");
        $stmt->execute(array(
            ":r_id" => $_GET['r_id'],
            ":date" => $_GET['date']
        ));
 
        $timeslots = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    $stmt = $conn->prepare("SELECT name FROM resources WHERE r_id = :r_id");
    $stmt->execute(array(
        ":r_id" => $_GET['r_id']
    ));
    $resource = $stmt->fetch(PDO::FETCH_ASSOC);
?>
    <div class="divTable">
<?PHP
            // Previous day and Next day buttons
echo        "<div class='divTableBody'><h1>".$resource['name']."</h1>";
echo        "<div class='buttonWrapper'>";
echo        "<form class=time_button action='user_week.php' method='GET'>";
echo            "<input type='hidden' name='category' value='".$_GET['category']."'>";
echo            "<input type='hidden' name='r_id' value='".$_GET['r_id']."'>";
echo            "<input type='hidden' name='date' value='".date('Y-m-d', strtotime($_GET['date'].' -7 days'))."'>";
echo            "<input type='hidden' name='panel' value='user_week'>";
echo            "<input type='submit' value='Last Week'>";
echo        "</form>";
echo        "<form class=time_button action='user_week.php' method='GET'>";
echo            "<input type='hidden' name='category' value='".$_GET['category']."'>";
echo            "<input type='hidden' name='r_id' value='".$_GET['r_id']."'>";
echo            "<input type='hidden' name='date' value='".date('Y-m-d', strtotime($_GET['date'].' +7 days'))."'>";
echo            "<input type='hidden' name='panel' value='user_week'>";
echo            "<input type='submit' value='Next Week'>";
echo        "</form>";
echo        "</div>";
echo        "<div class=divTableColumnWrapper>";
            // Print the column names
echo        "<div class='divTableColumn'>";
echo 	    	"<div class='divTableCell'></div>";
echo 	    	"<div class='divTableCell'></div>";
	        foreach ($timeslots as $key => $value)
                    if (!($key == 'r_id' || $key == 'date')) 
echo 	    	        "<div class='divTableCell'>".$key."</div>";
echo        "</div>";
                // display each day of this week
                $dates = get_week_dates($_GET['date']);
                foreach ($dates as $day => $date) {
echo            "<div class='divTableColumn'>";
                    $stmt = $conn->prepare("SELECT * FROM ".$_GET['category']." WHERE r_id = :r_id AND date = :date");
                    $stmt->execute(array(
                        ":r_id" => $_GET['r_id'],
                        ":date" => $date
                    ));
                    $timeslots = $stmt->fetch(PDO::FETCH_ASSOC);
    
echo                "<div class='divTableCell day_column_date'>".date('d-m', strtotime($date))."</div>";
echo                "<div class='divTableCell'>".$day."</div>";
		    foreach ($timeslots as $key => $value) if (!($key == 'r_id' || $key == 'date')) {
echo                "<div class='divTableCell'>";
                         if ($value == '') {
echo                     "<form class='book_timeslot' action='../functions/book_timeslot.php' method='POST'>";
echo                         "<input type='hidden' name='r_id' value='".$timeslots['r_id']."'>";
echo                         "<input type='hidden' name='category' value='".$_GET['category']."'>";
echo                         "<input type='hidden' name='column' value='".$key."'>";
echo                         "<input type='hidden' name='username' value='".$_SESSION['username']."'>";
echo                         "<input type='hidden' name='date' value='".$date."'>";
echo                         "<input type='hidden' name='panel' value='user_week'>";
echo                         "<input class='add_user_button' type='submit' value='Book It!'>";
echo                     "</form>";
                         } else if ($value == $_SESSION['username']) {
echo                     "<form action='../functions/delete_timeslot.php' class='delete_timeslot' method='POST'>";
echo                         "<input type='hidden' name='r_id' value='".$_GET['r_id']."'>";
echo                         "<input type='hidden' name='date' value='".$date."'>";
echo                         "<input type='hidden' name='category' value='".$_GET['category']."'>";
echo                         "<input type='hidden' name='column' value='".$key."'>";
echo                         "<input type='hidden' name='panel' value='user_week'>";
echo                         "<input wrap='soft' class='add_user_button' type='submit' value=\"".$value."\">";
echo                     "</form>";
                         } else {
echo                     "<div class=timeslot_taken>";
echo                         str_replace(" ", "<br>",$value);
echo                     "</div>";
		    	}
echo                "</div>";
		    }
echo           "</div>";
               }
echo"     </div>";
}
?>

