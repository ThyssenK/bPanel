<?php
foreach ($editData as $row):
    $timestamp = date('M d, Y m:ia', $row->timestamp);
    $days = array('tue','wed','thu','fri','sat');
    foreach ($days as $day) {
        $color[] = $row->$day == 'Y' ? 'green' : 'red';
    }
?>
<div class="profile <?php echo $row->id ?>">

<!-- Update Notification -->
<div class="ui-widget alert-wrapper">
    <div class="alert-msg ui-corner-all">
        <span></span>
    </div>
</div>

<div class="edit-title ui-widget-header ui-corner-tl ui-corner-tr">
    <?php echo $row->last_name ?>, <?php echo $row->first_name ?> - <?php echo $timestamp ?>
</div>

<!-- Member Info -->
<div id="editform">
    <form>
        <input type="hidden" name="id" value="<?php echo $row->id ?>" />
        <table>
            <tr>
                <td class="label">First name</td>
                <td>
                    <input type="text" id="first_name" name="first_name" value="<?php echo $row->first_name ?>" maxlength="50" class="text ui-widget-content ui-corner-all" />
                </td>
                <td class="label">Email</td>
                <td>
                    <input type="text" id="email" name="email" value="<?php echo $row->email ?>" maxlength="50" class="text ui-widget-content ui-corner-all" />
                </td>
            </tr>
            <tr>
                <td class="label">Last name</td>
                <td>
                    <input type="text" id="last_name" name="last_name" value="<?php echo $row->last_name ?>" maxlength="50" class="text ui-widget-content ui-corner-all" />
                </td>
                <td class="label">Playing Experience</td>
                <td>
                    <select id="skill" name="skill" class="full text ui-widget-content ui-corner-all">
                        <option selected="selected"><?php echo $row->skill ?></option>
                        <option>High School</option>
                        <option>College</option>
                        <option>Community Band</option>
                        <option>Professional</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label">Primary</td>
                <td>
                    <select id="section1" name="section1" class="text ui-widget-content ui-corner-all">
                        <option selected="selected"><?php echo $row->section1 ?></option>
                        <option>Trumpet</option>
                        <option>French Horn</option>
                        <option>Trombone</option>
                        <option>Baritone</option>
                        <option>Tuba</option>
                        <option>Flute</option>
                        <option>Oboe</option>
                        <option>Clarinet</option>
                        <option>Bass Clarinet</option>
                        <option>Bassoon</option>
                        <option>Alto Saxophone</option>
                        <option>Tenor Saxophone</option>
                        <option>Bari Saxophone</option>
                        <option>Percussion</option>
                        <option>Piano</option>
                        <option>String Bass</option>
                    </select>
                </td>
                <td class="label">Past/Current</td>
                <td>
                    <select id="skill_type" name="skill_type" class="full text ui-widget-content ui-corner-all">
                        <option selected="selected"><?php echo $row->skill_type ?></option>
                        <option>Past</option>
                        <option>Current</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label">Secondary</td>
                <td>
                    <select id="section2" name="section2" class="text ui-widget-content ui-corner-all">
                        <option selected="selected"><?php echo $row->section2 ?></option>
                        <option>Piccolo</option>
                        <option>English Horn</option>
                        <option>Soprano Saxophone</option>
                        <option>None</option>
                    </select>
                </td>
                <td id="notes" class="label" rowspan="2">
                    Notes
                </td>
                <td rowspan="2">
                    <textarea name="notes" cols="25" rows="4" class="ui-widget-content ui-corner-all" style="width: 20em;"><?php echo $row->notes ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="label">Part Assignment</td>
                <td>
                    <select id="part" name="part" class="full text ui-widget-content ui-corner-all">
                        <option selected="selected"><?php echo $row->part ?></option>
                        <option value="1">1st</option>
                        <option value="2">2nd</option>
                        <option value="3">3rd</option>
                        <option value="-">Not Applicable</option>
                    </select>
                </td>
                <td></td>
            </tr>
        </table>
        <div id="editbutton"><button type="submit" name="submit" id="submit">Save Changes</button></div>
    </form>
</div>

<!-- Availability Table -->
<div class="unihistory ui-corner-bl ui-corner-br">
    <table style="width: 75%; margin: 1em auto;">
        <thead>
            <tr>
                <td>Tue</td>
                <td>Wed</td>
                <td>Thu</td>
                <td>Fri</td>
                <td>Sat</td>
            <tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: <?php echo $color[0] ?>"><?php echo $row->tue ?></td>
                <td style="color: <?php echo $color[1] ?>"><?php echo $row->wed ?></td>
                <td style="color: <?php echo $color[2] ?>"><?php echo $row->thu ?></td>
                <td style="color: <?php echo $color[3] ?>"><?php echo $row->fri ?></td>
                <td style="color: <?php echo $color[4] ?>"><?php echo $row->sat ?></td>
            </tr>
        </tbody>
    </table>
</div>

</div>
<?php
endforeach;
?>
