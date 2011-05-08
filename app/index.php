<?php
require_once '../../conf/config.inc.php';
require_once '../lib/bpanel-auth.class.php';
require_once '../lib/view/bpanel-view.class.php';

$view = new UserView();
$auth = new bPanelAuth($db);
$errMsg = '';

try {
    if (isset($_POST['submit'])) {
        $auth->isValidCsrfToken($_POST['token']);
        $auth->setCredentials($_POST['username'], $_POST['password']);
        $auth->verifyCredentials();
        $auth->setSession($_POST['remember']);
        header('Location: index.php');
    }
} catch (Exception $e) {
    $errMsg = $e->__toString();
}    

if ($auth->isValidUser()) {
    $view->setGroup($auth->getGroup());
    $view->assign('group', $auth->getGroup());
    $view->assign('username', $auth->getUsername());
    $view->assign('csrfToken', $auth->getCSRFToken());
    $view->display('static.index.tpl.php');
} else {
    $auth->setCSRFToken();
    $view->assign('csrfToken', $auth->getCSRFToken());
    $view->assign('errMsg', $errMsg);
    $view->display('static.login.tpl.php');
}
?>