<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>bPanel</title>
<link rel="stylesheet" type="text/css" href="css/default.css"/>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.8.custom.css" />
<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.8.custom.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#submit").button();
    });
</script>
</head>
<body>
    <div id="login" class="ui-widget-content ui-corner-all">
        <div class="ui-widget-header ui-corner-all"><div>Registration Login</div></div>
        <form method="post" action="register.php">
            <input type="hidden" name="token" value="<?php echo $csrfToken; ?>" />
            <table>
                <tr>
                    <td></td>
                    <td><?php echo $errMsg; ?></td>
                </tr>
                <tr>
                    <td class="label">Group</td>
                    <td><input type="text" id="username" class="ui-widget-content ui-corner-all" name="group" maxlength="50" /></td>
                </tr>
                <tr>
                    <td class="label">Password</td>
                    <td><input type="password" id="password" class="ui-widget-content ui-corner-all" name="password" maxlength="50" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" id="submit" name="login" value="Submit" /></td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>