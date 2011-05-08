<?php
require_once '../../conf/config.inc.php';
require_once '../lib/bpanel-auth.class.php';

// Define scope of new admin
$group = 'workshop';

$s = new bPanelAuth($db);

if (isset($_POST['submit'])) {
    try {
        $s->setCredentials($_POST['username'], $_POST['password'], $_POST['password2']);
        $s->addUser();
        $s->addUserDetails($_POST);
        $s->verifyCredentials(); // Login the user
        $s->setSession($_POST['remember']);
        header('Location: index.php');
    } catch (Exception $e) {
        $errMsg = $e->__toString();
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Band Manager</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/jquery-ui-1.8.8.custom.css" type="text/css" />
<link rel="stylesheet" href="css/default.css" type="text/css" />
<script type="text/javascript" src="../js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.8.custom.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("button").button();
    });
</script>
</head>	
<body>
    <div class="regtitle ui-widget-header ui-corner-tr ui-corner-tl">Add New Admin</div>
    <form id="regform" method="post" action="create_admin.php">
        <table>
            <tr>
                <td></td>
                <td><?php echo $errMsg; ?></td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td><input class="full text ui-widget-content ui-corner-all" type="text" id="username" name="username" maxlength="50" /></td>
            </tr>
            <tr>
                <td class="label">Password</td>
                <td><input class="full text ui-widget-content ui-corner-all" type="password" id="password" name="password" maxlength="50" /></td>
            </tr>
            <tr>
                <td class="label">Repeat Password</td>
                <td><input class="full text ui-widget-content ui-corner-all" type="password" id="password" name="password2" maxlength="50" /></td>
            </tr>
            <tr>
                <td class="label">First Name</td>
                <td><input class="full text ui-widget-content ui-corner-all" type="text" id="fname" name="first_name" maxlength="50" /></td>
            </tr>
            <tr>
                <td class="label">Last Name</td>
                <td><input class="full text ui-widget-content ui-corner-all" type="text" id="lname" name="last_name" maxlength="50" /></td>
            </tr>
            <tr>
                <td><input type="hidden" name="group_id" value="<?php echo $group; ?>" /></td>
                <td><button type="submit" name="submit" value="submit" id="submit">Create Account</button></td>
            </tr>
        </table>
    </form>
</body>
</html>