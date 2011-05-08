<?php
require_once '../../conf/config.inc.php';
require_once '../lib/bpanel-auth.class.php';

$auth = new bPanelAuth($db);
$csrfToken = $auth->getCSRFToken();
?>
<html>
<head>
<title>bPanel Tests</title>
</head>
<body>
    <form method="POST" action="ajax.php">
        <input type="text" name="token" value="<?php echo $csrfToken; ?>" />
        <input type="text" name="action" />
        <input type="text" name="args" />
        <input type="submit" name="submit" />
    </form>
</body>
</html>