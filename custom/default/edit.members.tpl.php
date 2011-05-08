<?php
$i = 0;
foreach ($editData as $row):
$timestamp = date('M d, Y m:ia', $row->timestamp);
$this->assign('uniSet', $uniData[$i]);
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
        <td class="label">Phone</td>
        <td>
          <input type="text" id="phone" name="phone" value="<?php echo $row->phone ?>" maxlength="50" class="text ui-widget-content ui-corner-all" />
        </td>
      </tr>
      <tr>
        <td class="label">Last name</td>
        <td>
          <input type="text" id="last_name" name="last_name" value="<?php echo $row->last_name ?>" maxlength="50" class="text ui-widget-content ui-corner-all" />
        </td>
        <td class="label">Email</td>
        <td>
          <input type="text" id="email" name="email" value="<?php echo $row->email ?>" maxlength="50" class="text ui-widget-content ui-corner-all" />
        </td>
      </tr>
      <tr>
        <td class="label">ID/License/SSN</td>
        <td>
          <input type="text" id="irl_id" name="irl_id" value="<?php echo $row->irl_id ?>" maxlength="50" class="text ui-widget-content ui-corner-all" />
        </td>
        <td class="label">Section</td>
        <td>
          <select id="section" name="section" class="text ui-widget-content ui-corner-all" >
          <option selected="selected"><?php echo $row->section ?></option>
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
        <td class="label">Street Address</td>
        <td>
          <input type="text" id="address1" name="address1" value="<?php echo $row->address1 ?>" maxlength="50" class="text ui-widget-content ui-corner-all" />
        </td>
        <td class="label">Grade</td>
        <td>
        <select id="grade" name="grade" class="text ui-widget-content ui-corner-all" >
          <option selected="selected"><?php echo $row->grade ?></option>
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
        <td class="label">City, State, Zip</td>
        <td>
          <input type="text" id="address2" name="address2" value="<?php echo $row->address2 ?>" maxlength="50" class="text ui-widget-content ui-corner-all" />
        </td>
        <td class="label">Class</td>
        <td>
          <select id="class" name="class" class="text ui-widget-content ui-corner-all" >
            <option selected="selected"><?php echo $row->class ?></option>
            <option>Marching Band</option>
            <option>Winterline</option>
          </select>
        </td>
      </tr>
    </table>
    <div id="editbutton"><button type="submit" name="submit" id="submit">Save Changes</button></div>
    </form>
</div>

<!-- Uniform Table -->
<div class="unihistory"><?php $this->display('edit.uniforms.tpl.php'); ?></div>

<!-- Uniform Checkout -->
<div id="uniform" class="ui-corner-bl ui-corner-br">
    <form>
        <input type="hidden" name="id" id="memid" value="<?php echo $row->id ?>" />
        <input type="hidden" name="admin" value="KJ" />
        <div class="radio">
            <input type="radio" value="jacket" id="<?php echo $row->id  ?>jacket" name="type" />
            <label for="<?php echo $row->id ?>jacket">Jacket</label>
            <input type="radio" value="pants" id="<?php echo $row->id  ?>pants" name="type" />
            <label for="<?php echo $row->id  ?>pants">Pants</label>
            <input type="radio" value="shako" id="<?php echo $row->id  ?>shako" name="type" />
            <label for="<?php echo $row->id  ?>shako">Shako</label>
        </div>
         #
        <input type="text" id="number<?php echo $row->id ?>" name="number" maxlength="3" class="text ui-widget-content ui-corner-all" />
        <button type="submit" name="checkout" id="checkout">Checkout</button>
    </form>
</div>

</div>
<?php
$i++;
endforeach;
?>
