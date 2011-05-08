<?php
require_once('bpanel-exception.class.php');

/**
 * Class: UserBase
 * This class is an attempt to create a robust, secure, 
 * effecient, object-oriented, yet simple PHP/MySQL 
 * session management system.
 *
 * @author David Kramer
 * @version 1.0 Winter 2010 <br />
 * Revision History: <br />
 * 1.0 created on 03/04/2010
 */
class bPanelAuth
{
    /**
     * The database object
     * @var object
     */
    protected $db;

    /**
     * Indicates session validity
     * @var boolean
     */
    protected $isValid = false;

    /**
     * The username passed in $_POST
     * @var string
     */
    private $postUsername;

    /*
     * The password passed in $_POST
     * @var string
     */
    private $postPassword;

    /**
     * The validated username
     * @var string
     */
    private $username;

    /**
     * The user's group id
     * @var string
     */
    protected $group;

    /**
     * Holds a reference to $_SESSION
     * @var array
     */
    protected $session;

    /**
     * Holds a reference to $_SERVER
     * @var array
     */
    protected $server;

    /**
     * Holds a reference to $_COOKIE
     * @var array
     */
    protected $cookie;

    /**
     * Creates a new Session object with the database connection
     * passed in $newDB.
     *
     * @param MySQLi $newDB The database object
     */
    public function __construct(MySQLi $newDB)
    {
        $this->db = &$newDB;

        session_set_save_handler(array(&$this, 'open'),
                                 array(&$this, 'close'),
                                 array(&$this, 'read'),
                                 array(&$this, 'write'),
                                 array(&$this, 'destroy'),
                                 array(&$this, 'gc'));
        $this->setId();
        session_start();

        $this->session = &$_SESSION;
        $this->server = &$_SERVER;
        $this->cookie = &$_COOKIE;
    }

    /**
     * Validates client input and initializes private instance
     * variables $username and $password. If $newPass2 is not null
     * it will be compared to $newPass1 for registration purposes.
     *
     * @param  string The username.
     * @param  string The password.
     * @param  string The repeated password.
     * @throws Exception if username/password are invalid or password mismatch.
     */
    public function setCredentials($newUser, $newPass1, $newPass2 = null)
    {
        // Valid email
        $pattern = "/^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`"
                 . "{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[a-z]"
                 . "{2,4}|museum|travel)$/i";

        if (!preg_match($pattern, trim($newUser)))
            throw new UserException('Invalid username', 0);

        if (!preg_match('/.{4,50}/', $newPass1))
            throw new UserException('Invalid password', 1);

        if ($newPass2 !== null && $newPass1 != $newPass2)
            throw new UserException('Passwords do not match', 2);

        $this->postUsername = $newUser;
        $this->postPassword = $newPass1;
    }

    /**
     * Tests for correct login credentials.
     *
     * @return boolean Returns true if username and password are correct.
     * @throws Exception if username or password are incorrect.
     */
    public function verifyCredentials()
    {
        $query = sprintf("SELECT SHA1(CONCAT(salt, '%s'))=password, 
                                 users.group_id
                          FROM   users
                          WHERE  username='%s'"
        ,
            $this->db->escape_string($this->postPassword),
            $this->db->escape_string($this->postUsername));

        $result = $this->db->query($query);

        if ($result->num_rows == 0)
            throw new UserException('Incorrect username', 3);

        $row = $result->fetch_row();

        if (!$row[0])
            throw new UserException('Incorrect password', 4);

        $this->username = $this->postUsername;
        $this->group = $row[1];

        return true;
    }

    /**
     * Inserts a new user into the database.
     *
     * @throws Exception if username is taken
     */
    public function addUser()
    {
        $salt = substr(md5(uniqid(rand(), true)), 0, 9);
        $passwordHash = sha1($salt . $this->postPassword);

        $query = sprintf("INSERT 
                          INTO   users
                                 (
                                     username, password, salt
                                 )
                                 VALUES
                                 (
                                     '%s', '%s', '%s'
                                 )"
        ,
            $this->db->escape_string($this->postUsername),
            $this->db->escape_string($passwordHash),
            $this->db->escape_string($salt));

        if (!$this->db->query($query))
            throw new UserException('Username taken', 5);
    }

    /**
     * Inserts additional info passed in $_POST requests
     * into the database. It is assumed that keys of the $_POST array
     * correspond with database column names.
     *
     * @param  array   $post  The $_POST array
     * @param  string  $where The key of the array element that contains the
     *                        username of the user being updating.
     * @return boolean Returns true if details were added successfully,
     *                 false otherwise.
     * @throws Exception if the post array has empty elements.
     */
    public function addUserDetails($post)
    {
        $empty = array('', ' ');
        if (array_intersect($empty, $post))
            throw new UserException('All fields must be filled', 6);

        $values = '';
        $post = array_reverse($post, true);

        while (next($post) && key($post) != 'password2') {
            $values .= key($post) . "='"
                     . $this->db->escape_string(current($post)) . "',";
        }

        $values = substr($values, 0, -1);
        $query = "UPDATE users 
                  SET    " . $values . "
                  WHERE  username='" . $post['username'] . "'";

        return $this->db->query($query);
    }

    /**
     * Deletes all users whose usernames are passed in $users
     *
     * @param string $users A csv string of usernames
     */
    public function deleteUser($users)
    {
        return $this->db->query("DELETE
                                 FROM  users
                                 WHERE username IN (" . $users . ")");
    }

    /**
     * Checks for a user's privilege level.
     *
     * @param  boolean $redirect
     * @param  string  $location The URI to redirect to`
     * @return boolean Returns true upon successful authentication,
     *                 otherwise returns false. If $redirect is true, will
     *                 return a redirect to $location if if the user is
     *                 valid. If $redirect is false, will return a redirect
     *                 to $location if the user is invalid.
     */
    public function isValidUser($redirect = null, $location = '')
    {
        $valid = false;

        // Check for a valid cookie
        if (!isset($this->session['registered'])
            && isset($this->cookie['cookie_id'])
        ) {
            $cookieHash = substr($this->cookie['cookie_id'], -40);
            $cookieUsername = substr($this->cookie['cookie_id'], 0, -40);

            $query = sprintf("SELECT SHA1(CONCAT(username, token))='%s', 
                                     users.group_id,
                                     users.username
                              FROM   users
                              WHERE  username='%s'"
            ,
                $this->db->escape_string($cookieHash),
                $this->db->escape_string($cookieUsername));

            $result = $this->db->query($query);

            if ($result->num_rows) {
                $row = $result->fetch_row();
                $valid = $row[0];
            }

            if ($valid) {
                $this->username = $row[2];
                $this->group = $row[1];
                $this->setSession();
            }
        }

        // Check for a valid session
        if (isset($this->session['registered'])
            && isset($this->session['username'])
            && $this->session['registered'] == true
            && $this->session['username'] != ''
            && $this->session['token'] == $this->getSessionToken()
        )
            $valid = true;

        // Optional redirect
        if ($location != ''
            && (($redirect == true && $valid == true)
            || ($redirect == false && $valid == false))
        )
            $valid = header('Location: ' . $location);

        return $valid;
    }

    /**
     * Creates and validates signed session IDs. Sets $isValid to true
     * upon successful ID validation, otherwise creates a signed ID for new
     * sessions.
     */
    protected function setId()
    {
        $key = '!a1WPT^&75z~!rq546%${,;mn8gf928u2,2i2-MCkS539J};"[j;HFe/(*-qx+';

        if (isset($_COOKIE['PHPSESSID'])) {
            $id = &$_COOKIE['PHPSESSID'];
            $unsignedId = substr($id, 0, 40);
            $hashedKey = substr($id, -40);

            if (sha1($unsignedId . $key) == $hashedKey) {
                $this->isValid = true;
            } else {
                error_log('Invalid Session ID', 0);
                //TODO destroy the invalid session
            }
        } else {
            $id = sha1(uniqid(mt_rand(), true));
            $id = $id . sha1($id . $key);
            session_id($id);
            $this->isValid = true;
        }
    }

    /**
     * Sets session variables for users after logging in.
     *
     * @param  boolean $remember Determines if cookie will be set
     */
    public function setSession($remember = false)
    {
        $this->session['registered'] = true;
        $this->session['token'] = $this->getSessionToken();
        $this->session['username'] = $this->username;
        $this->session['group'] = $this->group;
        $this->setCSRFToken();


        if ($remember) {
            $cookieToken = sha1(uniqid(mt_rand(), true));
            $cookie = $this->username . sha1($this->username . $cookieToken);

            $query = sprintf("UPDATE users
                              SET    token='%s'
                              WHERE  username='%s'"
            ,
                $this->db->escape_string($cookieToken),
                $this->db->escape_string($this->username));

            $this->db->query($query);

            setcookie('cookie_id', $cookie, time() + 14 * 86400, '');
        }
    }

    /**
     * Destroys the session and the client's cookie
     */
    public function destroySession()
    {
        if (isset($this->cookie['cookie_id'])) {
            $query = sprintf("UPDATE users 
                              SET    token=''
                              WHERE  username='%s'"
            ,
                $this->db->escape_string($this->session['username']));

            $this->db->query($query);

            setcookie('cookie_id', '', time() - 365 * 86400, '');
        }

        session_destroy();
    }

    /*
     * Sets the CSRF prevention token
     */
    public function setCSRFToken()
    {
        $this->session['CSRFToken'] = sha1(microtime());
    }

    /**
     * Validates the CSRF prevention token
     *
     * @param string $token The token to validate
     * @return boolean True for valid token, false otherwise
     * @throws UserException If the token is not set or the session is invalid
     */
    public function isValidCsrfToken($token)
    {
        if ($this->isValid
            && isset($this->session['CSRFToken'])
            && $this->session['CSRFToken'] == $token
        )
            return true;
        else
            throw new UserException('Authorization Failed', 7);
    }

    /**
     * Returns the CSRF prevention token
     *
     * @return string The token
     */
    public function getCSRFToken()
    {
        return $this->session['CSRFToken'];
    }

    /**
     * Returns a unique token for session hijacking prevention
     *
     * @return string The token
     */
    protected function getSessionToken()
    {
        $key = '#fd$%^QMnbfd\"E#33(FG#$%dfgl^ffk9%efRFY56RthfGHJ^&*#$%DFgJKL8I';
        
        $token = $key . $this->server['HTTP_USER_AGENT']
               . $this->server['REMOTE_ADDR'];

        return sha1($token);
    }

    /**
     * Get the username
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->session['username'];
    }

    /**
     * Get the user's group
     *
     * @return string The group
     */
    public function getGroup()
    {
        return $this->session['group'];
    }

    /**
     * Opens the session.
     *
     * @return boolean
     */
    public function open()
    {
        return true;
    }

    /**
     * Closes the session.
     *
     * @return boolean
     */
    public function close()
    {
        return $this->db->close();
    }

    /**
     * Reads the session from the database.
     *
     * @param  int    $id The session id
     * @return string Returns session data upon successful query.
     *                Otherwise, an empty string.
     */
    public function read($id)
    {
        $result = '';

        if($this->isValid) {
            $id = substr($id, 0, 40);
            $query = sprintf("SELECT session_data
                              FROM   sessions
                              WHERE  session_id = '%s'"
            ,
                $this->db->escape_string($id));

            $result = $this->db->query($query);

            if ($result->num_rows) {
                $record = $result->fetch_assoc();
                $result =  $record['session_data'];
            }
        }

        return $result;
    }

    /**
     * Writes the session to the database.
     *
     * @param  int    $id   The session id.
     * @param  string $data The session data.
     * @return boolean
     */
    public function write($id, $data)
    {
        $result = false;

        if($this->isValid) {
            $id = substr($id, 0, 40);
            $access = time();
            $query = sprintf("REPLACE
                              INTO    sessions
                                      VALUES
                                      (
                                          '%s', '%s', '%s'
                                      )"
            ,
                $this->db->escape_string($id),
                $this->db->escape_string($data),
                $this->db->escape_string($access));

            $result = $this->db->query($query);
        }

        return $result;
    }

    /**
     * Destroys the session.
     *
     * @param  int $id The session id.
     * @return boolean
     */
    public function destroy($id)
    {
        $id = substr($id, 0, 40);
        $query = sprintf("DELETE 
                          FROM  sessions
                          WHERE session_id = '%s'"
        ,
            $this->db->escape_string($id));

        return $this->db->query($query);
    }

    /**
     * Garbage Collector
     *
     * @param  int $max The max lifetime (in seconds) for sessions
     * @return boolean
     * @see    session.gc_divisor     100
     * @see    session.gc_maxlifetime 1440
     * @see    session.gc_probability 1
     * @usage  execution rate 1/100
     *         (session.gc_probability/session.gc_divisor)
     */
    public function gc($max)
    {
        $old = time() - $max;

        $query = sprintf("DELETE 
                          FROM  sessions
                          WHERE session_expiration < '%s'"
        ,
            $this->db->escape_string($old));

        return $this->db->query($query);
    }
}
