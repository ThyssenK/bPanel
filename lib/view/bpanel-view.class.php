<?php
require_once 'bpanel-member.class.php';

/**
 * Class: UserView
 * This class implements a simple template engine
 *
 * @author David Kramer
 * @version 1.0 Winter 2011
 * Revision History:
 * 1.0 created on 01/23/2011
 */
class UserView
{
    /**
     * @var string Group name for custom templates
     */
    var $group;

    /**
     * @var string The path to the templates folder
     */
    var $templatePath;

    /**
     * @var array Temporary storage array for template variables
     */
    var $storage = array();

    /**
     * Class constructor. Instantiates $templatePath and $group.
     *
     * @param $group string The group name
     */
    function __construct($group = '')
    {
        $this->templatePath = '../custom/';
        $this->group = $group;
    }

    /*
     * Set the group after initialization
     *
     * @param $group string The group name
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /*
     * Assigns template variables
     * 
     * @param $varName  string The variable name
     * @param $varValue mixed  The variable contents
     */
    public function assign($varName, $varValue)
    {
        $this->storage[$varName] = $varValue;
    }

    /**
     *
     * @param string $page The template filename
     */
    public function display($page)
    {
        foreach ($this->storage as $var => $value)
            $$var = $value;
        include $this->getPath($page);
    }

    /**
     *
     * @param string $page The template filename
     */
    public function render($page)
    {
        extract($this->storage);
        ob_start();
        require $this->getPath($page);
        return ob_get_clean();
    }

    /**
     * The register page
     *
     * @param $js     string jQuery string
     * @param $errMsg string Error message from an invalid form submission
     * @param $refill array  Previous submission data for form refill
     */
    public function register($js = '', $errMsg = '', $refill = null)
    {
        include $this->getPath('static.register.tpl.php');
    }

    /**
     * Determines if a custom template exists for the $template argument
     * and returns a file path to the custom or default template.
     *
     * @param  string $template The template to configure
     * @return string The path to the template
     */
    private function getPath($template) {
        $custom = $this->templatePath . $this->group .'/'.$template;
        if(file_exists($custom)) {
            return $custom;
        } else {
            return $this->templatePath . 'default/'.$template;
        }
    }
}

?>