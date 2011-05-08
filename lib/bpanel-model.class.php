<?php
require_once 'bpanel-exception.class.php';

/**
 * Class: UserModel
 * This class is a user managemant
 * system.
 *
 * @author David Kramer
 * @version 1.0 Winter 2010 <br />
 * Revision History: <br />
 * 1.0 created on 03/26/2010
 */
class bPanelModel
{

    /**
     * The database object
     * @var object
     */
    protected $db;

    /**
     * Creates a UserAdmin object with the database connection
     * passed in $newDB.
     *
     * @param MySQLi $newDB The database object
     */
    function __construct(MySQLi $newDB)
    {
        $this->db = $newDB;
    }

    /**
     * Returns an array of user objects.
     *
     * @return object Returns an array of user objects.
     */
    public function getUsers()
    {
        $i = 0;
        $query = "SELECT   id      , first_name, last_name,
                           username, phone     , level
                  FROM     users
                  ORDER BY last_name";

        $result = $this->db->query($query);

        while ($row = $result->fetch_object()) {
            $users[$i] = $row;
            $i++;
        }

        return $users;
    }

    /**
     * Returns an array of member objects.
     *
     * @param  string $ids An optional csv string of member ids to query the
     *                     database with. If blank, selects all members.
     * @return object Returns an array of member objects.
     */
    public function getMembers($ids = "")
    {
        $members = array();
        $where = ($ids == "") ? " " : "WHERE id IN (" . $ids . ")";

        $query = "SELECT   *
                  FROM     members
                  " . $where . "
                  ORDER BY FIELD(id," . $ids . ")";

        $result = $this->db->query($query);

        while ($row = $result->fetch_object()) {
            $members[] = $row;
        }

        return $members;
    }

    /**
     * Returns an array of uniform objects that belong to the
     * member with ID passed in $id
     *
     * @param  int    $id The member ID
     * @return object Returns an array of uniform objects.
     */
    public function getUniforms($id)
    {
        $uniforms = array();
        $query = "SELECT   *
                  FROM     uniforms
                  WHERE    member_id=" . $id . "
                  ORDER BY type, out_date DESC";

        $result = $this->db->query($query);

        while ($row = $result->fetch_object()) {
            $uniforms[] = $row;
        }

        return $uniforms;
    }

    /**
     * Returns an array of member objects and their currently
     * checked-out uniform
     *
     * @return array Returns an array of member/uniform objects
     */
    public function getMembersAndUniforms()
    {
        if ($this->hasUniforms()) {
            $query =
               "SELECT     members.*,
                           SUBSTRING_INDEX(GROUP_CONCAT(IF(type='jacket',
                           uniforms.number, NULL) ORDER BY out_date DESC), ',',
                           1) AS jacket_id,
                           SUBSTRING_INDEX(GROUP_CONCAT(IF(type='pants',
                           uniforms.number, NULL) ORDER BY out_date DESC), ',',
                           1) AS pants_id,
                           SUBSTRING_INDEX(GROUP_CONCAT(IF(type='shako',
                           uniforms.number, NULL) ORDER BY out_date DESC), ',',
                           1) AS shako_id,
                           SUBSTRING_INDEX(GROUP_CONCAT(IF(type='jacket',
                           uniforms.status, NULL) ORDER BY out_date DESC), ',',
                           1) AS jacket_status,
                           SUBSTRING_INDEX(GROUP_CONCAT(IF(type='pants',
                           uniforms.status, NULL) ORDER BY out_date DESC), ',',
                           1) AS pants_status,
                           SUBSTRING_INDEX(GROUP_CONCAT(IF(type='shako',
                           uniforms.status, NULL) ORDER BY out_date DESC), ',',
                           1) AS shako_status
                FROM       members
                LEFT JOIN  uniforms
                ON         members.id = uniforms.member_id
                GROUP BY   members.id";

        } else {
            $query = "SELECT   *
                      FROM     members
                      ORDER BY id";
        }

        return $this->db->query($query);
    }

    /**
     * Adds a member to the database. Uses the keys of $member as
     * SQL column names.
     *
     * @param  array   $member   An array of the member's info.
     * @param  array   $optional An array of optional form fields.
     * @return boolean Returns true upon success, false otherwise.
     * @throws Exception if any fields are empty.
     */
    public function  addMember($member)
    {
        $columns = '';
        $values = '';

        foreach ($member as $key => $value) {
            $columns .= $key . ',';
            $values .= "'" . $value . "',";
        }

        $columns = substr($columns, 0, -1);
        $values = substr($values, 0, -1);

        $query = "INSERT
                  INTO   members
                         (
                             " . $columns . "
                         )
                         VALUES
                         (
                             " . $values . "
                         )";

        return $this->db->query($query);
    }

    /**
     * Updates member's info with the data passed in $formData
     *
     * @param  string  $formData A string of member info
     * @return int     Returns the ID of the edited member.
     */
    public function editMember($formData)
    {
        parse_str($formData, $member);
        $id = $member['id'];
        $member['timestamp'] = time();     
        unset($member['submit'], $member['id']);
        $values = '';

        foreach ($member as $key => $value) {
            $values .= $key . "='" . $value . "',";
        }

        $values = substr($values, 0, -1);
        $query = "UPDATE members
                  SET    " . $values . "
                  WHERE  id='" . $id . "'";

        $this->db->query($query);

        return $id;
    }

    /**
     * Handles uniform check-ins and deletes.
     *
     * @param  string  $id The uniform id
     * @return boolean Returns true upon successful edit, false otherwise.
     */
    public function editUniform($id)
    {
        $query = sprintf("UPDATE uniforms
                          SET    in_date='%s', status=1
                          WHERE  id     =%d"
        ,
            time(),
            $id);

        return $this->db->query($query);
    }

    /**
     * Handles uniform check-outs
     *
     * @param  string  $formData A url-encoded string of member info.
     * @return int     Returns the ID of the edited member.
     */
    public function addUniform($formData)
    {
        parse_str($formData, $uniform);

        $query = sprintf("INSERT
                          INTO   uniforms
                                 (
                                     member_id, type, number, admin, out_date
                                 )
			         VALUES
                                 (
                                     '%s', '%s', '%s', '%s', '%s'
                                 )"
        ,
            $uniform['id'],
            $uniform['type'],
            $uniform['number'],
            $uniform['admin'],
            time());

        $this->db->query($query);

        return $uniform['id'];
    }

    /**
     * Deletes all users whose IDs are passed in $ids
     *
     * @param  string  $users A csv string of user IDs
     * @return boolean Returns true upon successful delete, false otherwise.
     */
    public function deleteUser($ids)
    {
        return $this->db->query("DELETE
                                 FROM   users
                                 WHERE  id IN (" . $ids . ")");
    }

    /**
     * Deletes all members whose IDs are passed in $ids
     *
     * @param  string  $ids A csv string of member IDs
     * @return boolean Returns true upon successful delete, false otherwise.
     */
    public function deleteMember($ids)
    {
        return $this->db->query("DELETE
                                 FROM   members
                                 WHERE  id IN (" . $ids . ")");
    }

    /**
     * Deletes the uniform with the ID passed in $id
     *
     * @param  string  $id The user ID
     * @return boolean Returns true upon successful delete, false otherwise.
     */
    public function deleteUniform($id)
    {
        return $this->db->query("DELETE
                                 FROM   uniforms
                                 WHERE  id=" . $id);
    }

    /**
     * Deletes the mail with the ID passed in $id
     *
     * @param  string  $id The mail ID
     * @return boolean Returns true upon successful delete, false otherwise.
     */
    public function deleteMail($ids)
    {
        return $this->db->query("DELETE
                                 FROM   mail
                                 WHERE  id IN (" . $ids . ")");
    }

    /**
     * Returns an array of mail objects.
     *
     * @param  string  $ids  An optional csv string of mailids to query the
     *                       database with. If blank, selects all mails.
     * @param  boolean $json If true, JSON encodes the returned array
     * @return object  Returns an array of mail objects.
     */
    public function getMail($ids = null, $json = true)
    {
        $mail = array();
        $where = " ";

        if($ids != null) {
            $where = "WHERE id IN (" . $ids . ")";
        }

        $query = "SELECT   *
                  FROM     mail
                  " . $where . "
                  ORDER BY id ASC";

        $result = $this->db->query($query);

        while ($row = $result->fetch_object()) {
            $mail[] = $row;
        }

        //if ($json)
        //    return json_encode($mail);
        //else
            return $mail;
    }

    /**
     * Checks for empty values in an array of form data and
     * also sanitizes the input.
     *
     * @param  array $array    The array of form data to validate
     * @param  array $optional An array of fields to skip during validation
     * @throws UserException if there are empty values
     */
    function validate(&$array, $optional) {
        $i = 0;
        foreach ($array as $key => &$value) {
            if (trim($value) == '' && !in_array($key, $optional)) {
                $empty[$i] = $key;
                $i++;
            }

            $key = $this->db->escape_string($key);
            $value = $this->db->escape_string(htmlspecialchars($value));
        }

        if (isset($empty)) {
            $message = 'Required field';
            $message .= (sizeof($empty) == 1) ? ' is empty' : 's are empty';
            throw new UserException($message, -1, $empty, $array);
        }
    }

    /**
     * Determines whether a group has uniforms.
     *
     * @return boolean Returns true if group has uniforms, false otherwise
     */
    function hasUniforms() {
        $result = $this->db->query("SHOW TABLES LIKE 'uniforms'");
        return $result->num_rows;
    }
}