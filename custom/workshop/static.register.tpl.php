<?php require_once '../custom/default/header.register.inc.html' ?>
<script type="text/javascript">
$(document).ready(function() {
    <?php if (isset($js)) echo $js ?>
    $('button').button();
    $('#schedule input').button();
    $('#entire-radio, #skill-radio').buttonset();
    $('#N').bind('click', function() {
        $('#schedule').fadeIn(800);
    });
    $('#Y').bind('click', function() {
        $('#schedule').fadeOut(800);
    });
    $('#skill-select').change(function() {
        var skill = $('#skill-select').val();
        var pre = '';

        if (skill != '') {
            if (skill == 'Professional')
                pre = 'a ';
            else if (skill == 'Community Band')
                pre = 'in a ';
            else
                pre = 'in ';

            $('#insert-skill').html(pre + skill);
            $('#skill-type-row').fadeIn(800);
        } else {
            $('#skill-type-row').fadeOut(800);
        }
    });
  
    var isReserve = $('#N').attr('checked');
    if (isReserve)
        $('#schedule').show();

    var skill = $('#skill-select').val();
    if (skill) {
        var skill = $('#skill-select').val();
        var pre = '';

        if (skill == 'Professional')
            pre = 'a ';
        else if (skill == 'Community Band')
            pre = 'in a ';
        else
            pre = 'in ';

        $('#insert-skill').html(pre + skill);
        $('#skill-type-row').show();
    }
        

    /*
    $('#musician').bind('click', function() {
        $('.instrument').fadeIn(800);
    });
    $('#conductor').bind('click', function() {
        $('.instrument').fadeOut(800);
    });

    var isMusician = $('#musician').attr('checked');
    if (isMusician)
        $('.instrument').show();
     */
});
</script>
<style type="text/css">
#schedule, #skill-type-row {
    display: none;
}

#schedule .s-wrapper {
    width: 25em;
    padding: 0.2em;
    text-align: center;
}

textarea {
    width: 20em;
}
</style>
</head>
<body>
    <div class="regtitle ui-widget-header ui-corner-tr ui-corner-tl">
        <div>Register</div>
    </div>
    <div id="regform" class="ui-corner-bl ui-corner-br">
        <form method="post" action="register.php">
            <input type="hidden" name="token" value="<?php echo $csrfToken; ?>" />
            <table>
                <tr>
                    <td></td>
                    <td><span style="color: #CC0000; font-weight: bold;"><?php echo $errMsg; ?></span></td>
                </tr>
                <tr>
                    <td id="first_name" class="label">First Name</td>
                    <td><input type="text" class="full text ui-widget-content ui-corner-all" name="first_name" value="<?php if (isset($refill['first_name'])) echo $refill['first_name'] ?>" maxlength="50" /></td>
                </tr>
                <tr>
                    <td id="last_name" class="label">Last Name</td>
                    <td><input type="text" class="full text ui-widget-content ui-corner-all" name="last_name" value="<?php if (isset($refill['last_name'])) echo $refill['last_name'] ?>" maxlength="50" /></td>
                </tr>
                <tr>
                    <td id="email" class="label">Email</td>
                    <td><input type="text" class="full text ui-widget-content ui-corner-all" name="email" value="<?php if (isset($refill['email'])) echo $refill['email'] ?>" maxlength="50" /></td>
                </tr>
                <tr class="instrument">
                    <td id="section1" class="label">Primary Instrument</td>
                    <td>
                        <select class="full text ui-widget-content ui-corner-all" name="section1" >
                            <?php
                            if (!empty($refill['section1']))
                                echo '<option selected="selected">' . $refill['section1'] . '</option>';
                            ?>
                            <option value="">Select Primary</option>
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
                </tr>

                <tr class="instrument">
                    <td id="section2" class="label">Secondary Instrument</td>
                    <td>
                        <select class="full text ui-widget-content ui-corner-all" name="section2" >
                            <?php
                            if (!empty($refill['section2']))
                                echo '<option selected="selected">' . $refill['section2'] . '</option>';
                            ?>
                            <option value="None">None</option>
                            <option>Piccolo</option>
                            <option>English Horn</option>
                            <option>Soprano Saxophone</option>
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
                </tr>
                <tr>
                    <td id="part" class="label">Part Assignment</td>
                    <td>
                        <select name="part" class="full text ui-widget-content ui-corner-all">
                            <?php
                            if (!empty($refill['part']))
                                echo '<option selected="selected">' . $refill['part'] . '</option>';
                            ?>
                            <option value="">Select Part Assignment</option>
                            <option value="1">1st</option>
                            <option value="2">2nd</option>
                            <option value="3">3rd</option>
                            <option value="-">Not Applicable</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td id="skill" class="label">Playing Experience</td>
                    <td>
                        <select class="full text ui-widget-content ui-corner-all" name="skill" id="skill-select">
                            <?php
                            if (!empty($refill['skill']))
                                echo '<option selected="selected">' . $refill['skill'] . '</option>';
                            ?>
                            <option value="">Select Playing Experience</option>
                            <option>High School</option>
                            <option>College</option>
                            <option>Community Band</option>
                            <option>Professional</option>
                        </select>
                    </td>
                </tr>
                <tr id="skill-type-row">
                    <td id="skill_type" class="label">
                        <p>Is this past experience or are you</p>
                        <p>currently <span id="insert-skill"></span>?</p>
                    </td>
                    <td>
                        <?php
                        if (!empty($refill['skill_type']))
                            $$refill['skill_type'] = 'checked="checked"';
                        ?>
                        <div id="skill-radio">
                            <input type="radio" id="past" name="skill_type" value="Past" <?php if (isset($Past)) echo $Past ?> /><label for="past">Past</label>
                            <input type="radio" id="current" name="skill_type" value="Current" <?php if (isset($Current)) echo $Current ?> /><label for="current">Current</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td id="entire" class="label">
                        Available for entire workshop?
                    </td>
                    <td>
                        <?php
                        if (!empty($refill['entire']))
                            $$refill['entire'] = 'checked="checked"';
                        ?>
                        <div id="entire-radio">
                            <input type="radio" id="Y" name="entire" value="Y" <?php if (isset($Y)) echo $Y ?> /><label for="Y">Yes</label>
                            <input type="radio" id="N" name="entire" value="N" <?php if (isset($N)) echo $N ?> /><label for="N">No</label>
                        </div>
                    </td>
                </tr>
                <tr id="schedule">
                    <td id="avail" class="label">
                        <p>You will be placed on the reserve</p>
                        <p>list. What is your availability?</p>
                    </td>
                    <td>
                        <div class="s-wrapper ui-widget-content ui-corner-all">
                            <div>
                                <input type="checkbox" id="tue" name="tue" value="Y" /><label for="tue">Tue</label>
                                <input type="checkbox" id="wed" name="wed" value="Y" /><label for="wed">Wed</label>
                                <input type="checkbox" id="thu" name="thu" value="Y" /><label for="thu">Thu</label>
                                <input type="checkbox" id="fri" name="fri" value="Y" /><label for="fri">Fri</label>
                                <input type="checkbox" id="sat" name="sat" value="Y" /><label for="sat">Sat</label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td id="notes" class="label">
                        Notes
                    </td>
                    <td>
                        <textarea name="notes" cols="35" rows="5" class="ui-widget-content ui-corner-all"><?php if (isset($refill['notes'])) echo $refill['notes']; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><button type="submit" name="submit" value="submit" id="submit">Submit</button></td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>