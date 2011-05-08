<?php
/**
 * Class: bPanelMemberAuth
 *
 * Authorization class for member registration 
 * 
 * @author David Kramer
 * @version 1.0
 */
class bPanelMemberAuth extends bPanelAuth {
    
    public function __construct(MySQLi $newDB)
    {
        parent::__construct($newDB);
    }
    
    /**
     * Tests for correct group member login credentials.
     *
     * @return boolean Returns true if group name and password are correct.
     * @throws Exception if group name or password are incorrect.
     */
    public function verifyGroup($group, $password)
    {
        $query = sprintf("SELECT password='%s'
                          FROM   groups
                          WHERE  name='%s'"
        ,
            $this->db->escape_string($password),
            $this->db->escape_string($group));

        $result = $this->db->query($query);

        if ($result->num_rows == 0)
            throw new UserException('Incorrect group name', 8);

        $row = $result->fetch_row();

        if (!$row[0])
            throw new UserException('Incorrect password', 9);
        
        $this->group = $group;

        return true;
    }
    
    /**
     * Sets session variables.
     */
    public function setMemberSession() {
        if (!parent::isValidUser()) {
            $this->session['registered'] = false; //differentiate from an admin
            $this->session['token'] = $this->getSessionToken();
            $this->session['group'] = $this->group;
            $this->setCSRFToken();
        }
    }
    
    /**
     * Authorizes a member's session.
     * 
     * @return boolean True is member is valid, false otherwise
     */
    public function isValidMember()
    {
        $valid = false;

        if (isset($this->session['registered'])
            && isset($this->session['group'])
            && $this->session['registered'] == false //not an admin
            && $this->session['group'] != ''
            && $this->session['token'] == $this->getSessionToken()
        ) {
            $valid = true;
        }

        return $valid;
    }
}
?>
