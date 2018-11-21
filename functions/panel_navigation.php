<?PHP // TODO: header on $_POST['page'] == 'user_week'
if ($_POST['panel'] == 'user_day')
    header("Location: /morpheus/panels/user_day.php?category=".$_POST['category']."&date=".$_POST['date']);
else if ($_POST['panel'] == 'user_week')
    header("Location: /morpheus/panels/user_week.php?r_id=".$_POST['r_id']."&category=".$_POST['category']."&date=".$_POST['date']);
else if ($_POST['panel'] == 'admin_day')
    header("Location: /morpheus/panels/admin_day.php?category=".$_POST['category']."&date=".$_POST['date']);
else if ($_POST['panel'] == 'admin_week')
    header("Location: /morpheus/panels/admin_week.php?r_id=".$_POST['r_id']."&category=".$_POST['category']."&date=".$_POST['date']);
else
    header("Location: /morpheus/index.php");
?>
