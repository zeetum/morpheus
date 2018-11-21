<?PHP
/*
    Creates a new timeslots week for each resource, where $_POST['date'] = the new week primary key
*/
$user = 'morpheus';
$pass = 'Holidays2';
$conn = new PDO('mysql:host=localhost;dbname=morpheus', $user, $pass);

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

// remove stale recurring bookings
$stmt = $conn->prepare("DELETE FROM recurring_booking
                        WHERE end_date < CURDATE()");
$stmt->execute();

// get recurring bookings
$stmt = $conn->prepare("SELECT * FROM recurring_booking");
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($bookings as $booking) {

    $dates = get_dates($booking['start_date'], $booking['jump'], $booking['end_date']);
    foreach($dates as $date) {
        $query = "UPDATE ".$booking['category']." SET `".$booking['column_name']."` = \"".$booking['username']."\" WHERE date = '".$date."' AND r_id = ".$booking['r_id'];
        $stmt = $conn->prepare($query);
	echo $query;
        $stmt->execute();
    }
}
?>
