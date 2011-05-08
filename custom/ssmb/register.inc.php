<?php
$member = array();

/* Array of optional form fields */
function getOptional() {
    $optional = array('irl_id');
    return $optional;
}

/* Success message */
$jsSuccess = "$('#regform table').hide();"
           . "$('#thanks').fadeIn('fast');"
           . "$('.regtitle div').html('Registration Completed!');";

//TODO update address to work with new jQuery empty field notifier
/* Prepare POST data for insert */
function prepare(&$member) {
    $member['timestamp'] = time();

    $member['phone'] = '(' . $member['phone1'] . ') ' . $member['phone2']
                     . '-' . $member['phone3'];

    $member['address2'] = $member['city'] . ', ' . $member['state'] . ', '
                        . $member['zip'];

    unset($member['phone1'], $member['phone2'], $member['phone3'],
        $member['city'], $member['state'], $member['zip'], $member['submit']);
}
