<?php
/**
 * A class for sending and queueing plain-text or HTML mail.
 *
 * @author David Kramer
 */
class bPanelMail extends bPanelModel
{
    /**
     * @var string The group name
     */
    private $group;

    /**
     * @var string The sender address
     */
    private $from;

    /**
     * @var string CSV of recipient IDs
     */
    private $recipientIds;

    /**
     * @var string CSV of recipient addresses
     */
    private $recipients;

    /**
     * @var string The mail subject
     */
    private $subject;

    /**
     * @var string The plain-text mail body
     */
    private $body;

    /**
     * @var string The HTML mail body
     */
    private $htmlBody;

    /**
     * Class Constructor. Parses the url-encoded string $newContent
     * and then initializes properties.
     *
     * @param MySQLi $newDB The database object
     * @param <type> $newGroup The group name
     * @param <type> $newContent URL-encoded string of mail content
     */
    public function __construct(MySQLi $newDB, $newGroup, $newContent)
    {
        parent::__construct($newDB);
        parse_str($newContent, $content);

        $this->group = $newGroup;
        $this->from = $newGroup . '@bpanel.no-ip.org';
        $this->subject = $content['subject'];
        $this->body = $content['body'];
        $this->recipientIds = $content['ids'];
        $this->recipients = $this->getRecipients($content['ids']);
    }

    /**
     * Adds mail to the sent mail database.
     *
     * @return True if mail added successfully, false otherwise
     */
    public function addSentMail()
    {
        $query = sprintf("INSERT
                          INTO   mail
                                 (
                                     timestamp, recipient, ids, subject,
                                     body
                                 )
                                 VALUES
                                 (
                                     %d, '%s', '%s', '%s', '%s'
                                 )"
        ,
            time(),
            $this->recipients,
            $this->recipientIds,
            $this->db->escape_string($this->subject),
            $this->db->escape_string($this->body));

        return $this->db->query($query);
    }

    /**
     * Sends mail via smtp.
     *
     * @return boolean True if the email was accepted for delivery,
     *                 false otherwise
     */
    public function send()
    {
        //xdebug_disable();
        require_once 'Mail.php';
        require_once '../../conf/config.smtp.inc.php';

        $smtp = Mail::factory('smtp', $smtpOptions);

        $headers = $this->prepareHeaderAndBody();
        $mail = $smtp->send($this->recipients, $headers, $this->body);

        if (PEAR::isError($mail)) {
            return false;
        }

        return true;
    }

    /**
     * Adds mail to the database queue for delayed delivery.
     *
     * @return mixed True if the email was accepted for delivery,
     *               error message otherwise
     */
    public function enqueue()
    {
        //xdebug_disable();
        require_once 'Mail.php';
        require_once 'Mail/Queue.php';
        require_once '../../conf/config.mail.inc.php';

        $mail_queue = new Mail_Queue($db_options, $mail_options);

        $headers = $this->prepareHeaderAndBody();
        $mail = $mail_queue->put($this->from, $this->recipients, $headers, $this->body);

        if (PEAR::isError($mail)) {
            return $mail_queue->errorMessage($mail);
        }

        return true;
    }

    /**
     * Sends $amount number of mails from the queue
     *
     * @param integer $amount The number of mails to send
     */
    public function sendFromQueue($amount)
    {
        //xdebug_disable();
        require_once('Mail.php');
        require_once('Mail/Queue.php');
        require_once('../../conf/config.mail.inc.php');

        $mail_queue = new Mail_Queue($db_options, $mail_options);
        $mail_queue->sendMailsInQueue($amount);
    }

    /**
     * Retrieves the names and emails for each id passed in $ids
     *
     * @param  string $ids A csv string of user IDs
     * @return string A formatted email recipient string
     */
    private function getRecipients($ids)
    {
        $recipients = '';
        $result = $this->db->query("SELECT first_name,last_name, email
                                    FROM   members
                                    WHERE  id IN (" . $ids . ")");

        $pattern = "/^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`"
                 . "{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[a-z]"
                 . "{2,4}|museum|travel)$/i";

        while ($row = $result->fetch_object()) {
            if (preg_match($pattern, trim($row->email)))
                $recipients .= $row->first_name . ' ' . $row->last_name . ' <'
                             . $row->email . '>,';
        }

        return rtrim($recipients, ",");
    }

    /**
     * Returns mail headers and formats plain-text bodies or
     * HTML bodies (if the group has a template)
     *
     * @return string The mail headers
     */
    private function prepareHeaderAndBody()
    {
        require_once 'Mail/mime.php';

        $mime = new Mail_mime("\n");
        $headers = array('From' => $this->from,
                         'To' => $this->recipients,
                         'Subject' => $this->subject);

        if ($this->hasHtmlTemplate()) {
            $mime->setHTMLBody($this->htmlBody);
        }

        $mime->setTXTBody($this->body);
        $this->body = $mime->get();

        return $mime->headers($headers);
    }

    /**
     * If this group has a template for html bodies, sets the
     * property $htmlBody and returns true. Otherwise, returns false.
     *
     * @return boolean True if this group has an html template,
     *                 false otherwise
     */
    private function hasHtmlTemplate()
    {
        $template = '../mail/templates/' . $this->group . '.mail.tpl.html';
        $htmlBody = '';
        $hasTemplate = false;

        if (file_exists($template)) {
            ob_start();
            include $template;
            $htmlBody = ob_get_contents();
            ob_end_clean();

            $htmlBody = sprintf($htmlBody, nl2br(htmlspecialchars($this->body)));
            $this->htmlBody = $htmlBody;
            $hasTemplate = true;
        }

        return $hasTemplate;
    }
}
?>