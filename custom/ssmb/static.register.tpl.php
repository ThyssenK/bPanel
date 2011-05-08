<?php require_once '../custom/default/header.register.inc.html' ?>
<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($js)) echo $js ?>
        $("button").button();
    });
</script>
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
                    <td><?php echo $errMsg; ?></td>
                </tr>
                <tr>
                    <td id="first_name" class="label">First name</td>
                    <td><input type="text" class="full text ui-widget-content ui-corner-all" name="first_name" value="<?php if (isset($refill['first_name'])) echo $refill['first_name'] ?>" maxlength="50" /></td>
                </tr>
                <tr>
                    <td id="last_name" class="label">Last name</td>
                    <td><input type="text" class="full text ui-widget-content ui-corner-all" name="last_name" value="<?php if (isset($refill['last_name'])) echo $refill['last_name'] ?>" maxlength="50" /></td>
                </tr>
                <tr>
                    <td id="irl_id" class="label">Student ID/License/SSN</td>
                    <td><input type="text" class="full text ui-widget-content ui-corner-all" name="irl_id" value="<?php if (isset($refill['irl_id'])) echo $refill['irl_id'] ?>" maxlength="50" /></td>
                </tr>
                <tr>
                    <td id="address1" class="label">Street Address/Apt #</td>
                    <td><input type="text" class="full text ui-widget-content ui-corner-all" name="address1" value="<?php if (isset($refill['address1'])) echo $refill['address1'] ?>" maxlength="50" /></td>
                </tr>
                <tr>
                    <td class="label">City, State, Zip</td>
                    <td>
                        <input type="text" class="address text ui-widget-content ui-corner-all" id="city" name="city" value="<?php if (isset($refill['city'])) echo $refill['city'] ?>" maxlength="50" />
                        <select style="width: 4em;" class="address text ui-widget-content ui-corner-all" id="state" name="state">
                            <?php
                            if (!empty($refill['state']))
                                echo '<option selected="selected">' . $refill['state'] . '</option>';
                            ?>
                            <option value="CA">CA</option>
                            <option value="AK">AK</option>
                            <option value="AL">AL</option>
                            <option value="AR">AR</option>
                            <option value="AZ">AZ</option>
                            <option value="CO">CO</option>
                            <option value="CT">CT</option>
                            <option value="DC">DC</option>
                            <option value="DE">DE</option>
                            <option value="FL">FL</option>
                            <option value="GA">GA</option>
                            <option value="HI">HI</option>
                            <option value="IA">IA</option>
                            <option value="ID">ID</option>
                            <option value="IL">IL</option>
                            <option value="IN">IN</option>
                            <option value="KS">KS</option>
                            <option value="KY">KY</option>
                            <option value="LA">LA</option>
                            <option value="MA">MA</option>
                            <option value="MD">MD</option>
                            <option value="ME">ME</option>
                            <option value="MI">MI</option>
                            <option value="MN">MN</option>
                            <option value="MO">MO</option>
                            <option value="MS">MS</option>
                            <option value="MT">MT</option>
                            <option value="NC">NC</option>
                            <option value="ND">ND</option>
                            <option value="NE">NE</option>
                            <option value="NH">NH</option>
                            <option value="NJ">NJ</option>
                            <option value="NM">NM</option>
                            <option value="NV">NV</option>
                            <option value="NY">NY</option>
                            <option value="OH">OH</option>
                            <option value="OK">OK</option>
                            <option value="OR">OR</option>
                            <option value="PA">PA</option>
                            <option value="RI">RI</option>
                            <option value="SC">SC</option>
                            <option value="SD">SD</option>
                            <option value="TN">TN</option>
                            <option value="TX">TX</option>
                            <option value="UT">UT</option>
                            <option value="VA">VA</option>
                            <option value="VT">VT</option>
                            <option value="WA">WA</option>
                            <option value="WI">WI</option>
                            <option value="WV">WV</option>
                            <option value="WY">WY</option>
                        </select>
                        <input type="text" class="address text ui-widget-content ui-corner-all" id="zip" name="zip" value="<?php if (isset($refill['zip'])) echo $refill['zip'] ?>" maxlength="5" />
                    </td>
                </tr>
                <tr>
                    <td class="label">Telephone</td>
                    <td>
                        (<input type="text" class="phone text ui-widget-content ui-corner-all" id="phone1" name="phone1" value="<?php if (isset($refill['phone1'])) echo $refill['phone1']; ?>" maxlength="3" />)
                        <input type="text" class="phone text ui-widget-content ui-corner-all" id="phone2" name="phone2" value="<?php if (isset($refill['phone2'])) echo $refill['phone2']; ?>" maxlength="3" /> -
                        <input type="text" class="phone text ui-widget-content ui-corner-all" id="phone3" name="phone3" value="<?php if (isset($refill['phone3'])) echo $refill['phone3']; ?>" maxlength="4" />
                    </td>
                </tr>
                <tr>
                    <td id="email" class="label">Email</td>
                    <td><input type="text" class="full text ui-widget-content ui-corner-all" name="email" value="<?php if (isset($refill['email'])) echo $refill['email']; ?>" maxlength="50" /></td>
                </tr>
                <tr>
                    <td id="section" class="label">Section</td>
                    <td>
                        <select class="full text ui-widget-content ui-corner-all" name="section" >
                            <?php
                            if (!empty($refill['section']))
                                echo '<option selected="selected">' . $refill['section'] . '</option>';
                            ?>
                            <option value="">Select your section</option>
                            <option>Battery: Snare</option>
                            <option>Battery: Tenor</option>
                            <option>Battery: Bass</option>
                            <option>Battery: Cymbals</option>
                            <option>Pit</option>
                            <option>Colorguard</option>
                            <option>Trumpet</option>
                            <option>Mellophone</option>
                            <option>Trombone</option>
                            <option>Baritone</option>
                            <option>Tuba</option>
                            <option>Piccolo</option>
                            <option>Clarinet</option>
                            <option>Saxophone</option>
                            <option>Drum Major</option>
                            <option>None</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td id="grade" class="label">Grade</td>
                    <td>
                        <select class="full text ui-widget-content ui-corner-all" name="grade" >
                            <?php
                            if (!empty($refill['grade']))
                                echo '<option selected="selected">' . $refill['grade'] . '</option>';
                            ?>
                            <option value="">Select your grade</option>
                            <option>CSUS Freshman</option>
                            <option>CSUS Sophomore</option>
                            <option>CSUS Junior</option>
                            <option>CSUS Senior</option>
                            <option>CSUS Grad Student</option>
                            <option>Community College</option>
                            <option>High School</option>
                            <option>None</option>
                        </select>
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