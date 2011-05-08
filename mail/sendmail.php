<?php
/**
 * lunarpages config
 *
 * ini_set('include_path', '.:/usr/local/lib/php:/home/epsclan3/pear');
 *
 * require_once('/home/epsclan3/pear/Mail.php');
 * require_once('/home/epsclan3/pear/Mail/mime.php');
 * require_once('/home/epsclan3/pear/Mail/Queue.php');
 * require_once('config.mail.inc.php');
 */
//xdebug_disable();
require_once('Mail.php');
require_once('Mail/mime.php');	
require_once('Mail/Queue.php');
require_once('../../conf/config.mail.inc.php');

$mail_queue = new Mail_Queue($db_options, $mail_options);
$max_amount_mails = 10;
$mail_queue = new Mail_Queue($db_options, $mail_options);
$mail_queue->sendMailsInQueue($max_amount_mails);