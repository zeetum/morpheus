<?PHP
/* Updates timeslots.sql with the primary keys:
    - $_POST['date']
    - $_POST['r_id']
   which will post the:
    - $_POST['username']
   into the:
    - $_POST['t_id']
*/

// WARNING: This script stomps on the t_id!
include_once('config.php');

// Get an array of all dates between lower and upper bound
function get_dates($lower_bound, $jump, $upper_bound) {
    $current = $lower_bound;
    $dates = array();

    while ($current < $upper_bound) {
        array_push($dates, $current);
        $current = date('Y-m-d', strtotime($current." +".$jump));
    }
    return $dates;
}

// Stomping on data if we're deleting recurring data
if (isset($_POST['column_name']) && isset($_POST['start_date'])) {
	$_POST['column'] = $_POST['column_name'];
	$_POST['date'] = $_POST['start_date'];
}

if (isset($_POST['r_id']) && isset($_POST['date']) && isset($_POST['category']) && isset($_POST['column'])) {
    // sanitising the input
    str_replace(";","",$_POST['column']);
    str_replace(",","",$_POST['column']);
    str_replace(";","",$_POST['category']);
    str_replace(",","",$_POST['category']);

    $string = "UPDATE ".$_POST['category']." SET `".$_POST['column']."` = NULL 
               WHERE date = :date AND r_id = :r_id";
    $stmt = $conn->prepare($string);
    $stmt->execute(array(
        ":date" => $_POST['date'],
        ":r_id" => $_POST['r_id']
    ));
    
    // Delete recurring details
    if (isset($_POST['recurring']) && $_POST['recurring'] == 'on') {

        // Get the maximum date for the table
        $stmt = $conn->prepare("SELECT DISTINCT date FROM ".$_POST['category']." ORDER BY date DESC");
        $stmt->execute(); $last_date = $stmt->fetch()['date'];
        $dates = get_dates($_POST['date'], $_POST['jump'], $last_date);
 
        foreach($dates as $date) {
            $query = "UPDATE ".$_POST['category']." SET `".$_POST['column']."` = NULL WHERE date = '".$date."' AND r_id = ".$_POST['r_id'];
            $stmt = $conn->prepare($query);
            $stmt->execute();
        }

	// Delete from recurring booking
        $stmt = $conn->prepare("DELETE FROM recurring_booking WHERE category = :category AND r_id = :r_id AND column_name = :column_name AND username = :username AND start_date = :start_date AND jump = :jump");
	$stmt->execute(array(
	    ":category"    => $_POST['category'],
	    ":r_id"        => $_POST['r_id'],
	    ":column_name" => $_POST['column'],
	    ":username"    => $_POST['username'],
	    ":start_date"  => $_POST['date'],
	    ":jump"        => $_POST['jump'],
	));

    }
}
include_once("panel_navigation.php");
?>
