<?php

/**
 * Class: UserException
 * Handles exceptions for the
 * UserBase and UserAdmin classes.
 *
 * @author David Kramer
 * @version 1.0 Spring 2010 <br />
 * Revision History: <br />
 * 1.0 created on 04/04/2010
 */
class UserException extends Exception
{
    /**
     * Array containing the positions of empty form fields.
     * @var array
     */
    private $empty;

    /**
     * Array containing clean, but invalid POST data.
     * @var array
     */
    private $postData;

    /**
     * Creates a new UserException object.
     *
     * @param  string $message  The exception message
     * @param  int    $code     The exception code
     * @param  array  $newEmpty Invalid form positions
     */
    public function __construct($message = null, $code = -1, $newEmpty = null,
        $newPostData = null
    ) {
        switch ($code) {
            case 0:
                $message = 'Invalid email address';
                break;
            case 1:
                $message = 'Password must be at least 4 charcters';
                break;
            case 3:
                $message = 'Incorrect email address';
                break;
            case 5:
                $message = 'Another user has that email address';
                break;
            case 8:
            case 9:
                $message = 'Invalid group name or password';
                break;
        }

        $this->empty = $newEmpty;
        $this->postData = $newPostData;

        parent::__construct($message, $code);
    }

    /**
     * Returns the $empty array.
     * @return Returns the $empty array.
     */
    function getEmptyFields()
    {
        return $this->empty;
    }

    /**
     * Returns the $postData.
     * @return array The $postData.
     */
    public function getPostData()
    {
        return $this->postData;
    }

    /**
     * Returns a string representation of this UserException object.
     *
     * @return string Returns a string representation of this
     *                UserException object.
     */
    function __toString()
    {
        return $this->message;
    }

}