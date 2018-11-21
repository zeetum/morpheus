<?php
session_start();
if ($_POST['username'] == 'admin'  && $_POST['password'] == 'Holidays2') {
    $_SESSION['username'] = "Administrator";

    header('Location: /morpheus/panels/admin.php');

} else if(isset($_POST['username']) && isset($_POST['password']) && $_POST['password'] != ''){
    $adServer = "ldap://e4008s01sv001.indigo.schools.internal";
    $ldap = ldap_connect($adServer);

    $username = $_POST['username'];
    $password = $_POST['password'];

    $ldaprdn = 'INDIGO' . "\\" . $username;

    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    $bind = @ldap_bind($ldap, $ldaprdn, $password);
    if ($bind) {
        
        $BaseDN = 'OU=School Users,DC=indigo,DC=schools,DC=internal';
        $filter = "(sAMAccountName=".$_POST['username'].")";
        $attributes = array('displayname');
        $result = ldap_search($ldap, $BaseDN, $filter, $attributes);
	$entries = ldap_get_entries($ldap, $result);

	$_SESSION['username'] = $entries[0]['displayname'][0];
    }  
    header('Location: /morpheus/panels/user.php');
} else {
    header('Location: /morpheus/index.php');
}

@ldap_close($ldap);
?>
