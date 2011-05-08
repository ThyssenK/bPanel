<?php
class Member
{
    public $dateFormat = 'n/j/y';

    function nameLastFirst() {
        return $this->last_name . ', ' . $this->first_name;
    }

    function nameFirstLast() {
        return $this->first_name . ' ' . $this->last_name;
    }

    function lastUpdate() {
        return date($this->dateFormat, $this->timestamp);
    }

    function getUniformStatus() {
       $status['jacket'] = $this->jacket_status ? 'green' : 'red';
       $status['pants'] = $this->pants_status ? 'green' : 'red';
       $status['shako'] = $this->shako_status ? 'green' : 'red';
       return $status;
    }
}
