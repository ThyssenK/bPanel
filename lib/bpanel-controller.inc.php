<?php
//TODO universal echo with json option
require_once '../../conf/config.inc.php';
require_once '../lib/view/bpanel-view.class.php';
require_once '../lib/bpanel-model.class.php';
require_once '../lib/bpanel-mail.class.php';
require_once '../lib/bpanel-auth.class.php';

$auth = new bPanelAuth($db);
$payload = null;
$json = false;

try {
    if ($auth->isValidUser()) {
        $auth->isValidCsrfToken($_POST['token']);
        $user = $auth->getUsername();
        $group = $auth->getGroup();

        $db->select_db($prefix . $group);
        $model = new bPanelModel($db);
        $view = new UserView($group);

        $action = $_POST['action'];
        $args = $_POST['args'];

        switch ($action) {
            case 'members':
                $view->assign('tableData', $model->getMembersAndUniforms());
                $payload = $view->render('table.members.tpl.php');
                break;
            case 'mail':
                $view->assign('tableData', $model->getMail(null, false));
                $payload = $view->render('table.mail.tpl.php');
                break;
            case 'editMember':
                $editData = $model->getMembers($args);
                if ($model->hasUniforms()) {
                    foreach($editData as $row) {
                        $uniData[] = $model->getUniforms($row->id);
                    }

                    $view->assign('uniData', $uniData);
                }
                $view->assign('editData', $editData);
                $payload = $view->render('edit.members.tpl.php');
                break;
            case 'memberUniform':
                $view->assign('uniSet', $model->getUniforms($args));
                $payload = $view->render('edit.uniforms.tpl.php');
                break;
            case 'updateProfile':
                $payload = $model->editMember($args);
                break;
            case 'deleteMember':
                $payload = $model->deleteMember($args);
                break;
            case 'addUniform':
                $payload = $model->addUniform($args);
                break;
            case 'editUniform':
                $payload = $model->editUniform($args);
                break;
            case 'deleteUniform':
                $payload = $model->deleteUniform($args);
                break;
            case 'sendMail':
                $mail = new bPanelMail($db, $group, $args);
                $mail->addSentMail();
                $payload = $mail->send();
                break;
            case 'getMail':
                $payload = $model->getMail($args);
                break;
            case 'deleteMail':
                $payload = $model->deleteMail($args);
                break;
            default:
                throw new UserException('Invalid action', 10);
                break;
        }
		
        if ($payload != null) {
            echo json_encode(array(true, $payload));
        }
    }
} catch (Exception $e) {
	$payload = 'Code ' . $e->getCode() . ': ' . $e->__toString();
	echo json_encode(array(false, $payload));
}
?>