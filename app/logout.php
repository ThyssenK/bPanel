<?php
require_once '../../conf/config.inc.php';
require_once '../lib/bpanel-auth.class.php';

$auth = new bPanelAuth($db);
$auth->isValidUser(false, 'index.php');

try {
    if (isset($_POST['submit']) && $auth->isValidCsrfToken($_POST['token']))
        $auth->destroySession();
} catch(Exception $e) {
    $errMsg = $e->__toString();
    die($errMsg);
}

header('Location: index.php');