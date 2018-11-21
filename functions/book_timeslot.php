<?PHP
/* Updates timeslots.sql with the primary keys:
    - $_POST['date']
    - $_POST['r_id']
   which will post the:
    - $_POST['username']
   into the:
    - $_POST['t_id']
*/
include('config.php');

if (isset($_POST['category']) && isset($_POST['column']) && isset($_POST['r_id']) && isset($_POST['username']) && isset($_POST['date'])) {
    
    // sanitising the input
    $_POST['column'] = str_replace(";","",$_POST['column']);
    $_POST['column'] = str_replace(",","",$_POST['column']);
    $_POST['category'] = str_replace(";","",$_POST['category']);
    $_POST['category'] = str_replace(",","",$_POST['category']);

    $string = "UPDATE ".$_POST['category']." SET `".$_POST['column']."` = :username
               WHERE date = :date AND r_id = :r_id";
    $stmt = $conn->prepare($string);
    $stmt->execute(array(
        ":username" => $_POST['username'],
        ":date" => $_POST['date'],
        ":r_id" => $_POST['r_id']
    ));

    // Enter recurring details
    if (isset($_POST['recurring']) &&  $_POST['recurring']== 'on' && $_POST['start_date'] != $_POST['end_date']) {
        $stmt = $conn->prepare("INSERT INTO recurring_booking (category, r_id, column_name, username, start_date, end_date, jump)
	                        VALUES (:category, :r_id, :column_name, :username, :start_date, :end_date, :jump)");
        $stmt->execute(array(
                ":category" => $_POST['category'],
                ":r_id" => $_POST['r_id'],
                ":column_name" => $_POST['column'],
                ":username" => $_POST['username'],
                ":start_date" => $_POST['start_date'],
                ":end_date" => $_POST['end_date'],
                ":jump" => $_POST['jump']
        ));
        include_once("generate_recurring.php");
    }
        
}

include_once("panel_navigation.php");

