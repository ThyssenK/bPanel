<?php
/* Set radio defaults */
$member['entire'] = '';
$member['tue'] = '';
$member['wed'] = '';
$member['thu'] = '';
$member['fri'] = '';
$member['sat'] = '';

/* Array of optional form fields */
function getOptional(&$member) {
    $optional = array('notes','tue','wed','thu','fri','sat');

    // Availability: Must have selected at least one day
    if ($member['entire'] == 'N') {
        $member['avail'] = $member['tue'] . $member['wed'] . $member['thu']
                         . $member['fri'] . $member['sat'];
    }

    if($member['skill'] == '') {
        $optional[] = 'skill_type';
    }

    return $optional;
}

/* Success message */
$jsSuccess = "$('#regform table').hide();"
           . "$('#thanks').fadeIn('fast');"
           . "$('.regtitle div').html('Registration Completed!');";

/* Prepare POST data for insert */
function prepare(&$member) {
    $days = array('tue','wed','thu','fri','sat');
    
    if ($member['entire'] == 'N') {
        foreach ($days as $day) {
            if (empty($member[$day]))
                $member[$day] = 'N';
        }
    } else {
        foreach ($days as $day) 
           $member[$day] = 'Y';
    }

    $member['timestamp'] = time();
    unset($member['submit'], $member['avail']);
}
