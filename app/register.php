<?php
require_once '../../conf/config.inc.php';
require_once '../lib/bpanel-auth.class.php';
require_once '../lib/bpanel-member-auth.class.php';
require_once '../lib/bpanel-model.class.php';
require_once '../lib/view/bpanel-view.class.php';

$auth = new bPanelMemberAuth($db);
$view = new UserView();
$isValidMember = false;
$errMsg = '';

if (isset($_POST['login'])) {
    try {
        $auth->isValidCsrfToken($_POST['token']);
        $auth->verifyGroup($_POST['group'], $_POST['password']);
        $auth->setMemberSession();
        header('Location: register.php');
    } catch (UserException $e) {
        $errMsg = $e->__toString();
    }
}

if ($auth->isValidUser() || $auth->isValidMember()) {
    
    $group = $auth->getGroup();
    $view->setGroup($group);
    require_once '../custom/' . $group . '/register.inc.php';
    
    if (isset($_POST['submit']) && $_POST['token'] == $auth->getCSRFToken()) {
        try {
            $db->select_db($prefix . $group);
            $model = new bPanelModel($db);

            $member = array_merge((array)$member, $_POST);
            $model->validate($member, getOptional($member));

            prepare($member);
            unset($member['token']);
            $model->addMember($member);

            header('Location: register-success.php');
        } catch (UserException $e) {
            $errMsg = $e->__toString();
            $empty = $e->getEmptyFields();
            $refill = $e->getPostData();
            $jsRequired= '';

            foreach ($empty as $index => $field) {
                $jsRequired .= "$('#" . $field . "')"
                             . ".css('color', '#CC0000');";
            }
            
            $view->assign('js', $jsRequired);
            $view->assign('csrfToken', $auth->getCSRFToken());
            $view->assign('errMsg', $errMsg);
            $view->assign('refill', $refill);
            $view->display('static.register.tpl.php');
        }
    } else {
        $view->assign('errMsg', '');
        $view->assign('csrfToken', $auth->getCSRFToken());
        $view->display('static.register.tpl.php');
    }
} else {
    $auth->setCSRFToken();
    $view->assign('errMsg', $errMsg);
    $view->assign('csrfToken', $auth->getCSRFToken());
    $view->display('reglogin.tpl.php');
}