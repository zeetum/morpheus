<?PHP
session_start();

$_SESSION['username'] = NULL;
header("Location: /morpheus");

?>
