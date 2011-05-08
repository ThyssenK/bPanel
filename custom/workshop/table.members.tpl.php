<div id="demo" class="table-wrapper" style="display: none;">
<form id="form">
<div class="toolbar ui-widget-header ui-corner-all" style="text-align: left; margin-bottom: .25em; padding: .25em .25em;">
    <a id="check" class="check">Select All</a>
    <a id="edit" class="edit">Edit</a>
    <a id="sendMail" class="mail">Mail</a>
    <a id="deleteMember" class="delete">Delete</a>
    <a id="tools" class="tools">Tools</a>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="membersTable">
<thead>
  <tr>
    <th></th>
    <th>Updated</th>
    <th>Name</th>
    <th>Email</th>
    <th>1st</th>
    <th>2nd</th>
    <th>Part</th>
    <th>All</th>
    <th>T</th>
    <th>W</th>
    <th>T</th>
    <th>F</th>
    <th>S</th>
  </tr>
</thead>
<tbody>
    <?php
    if (isset($tableData)):
        $days = array('tue','wed','thu','fri','sat');
        while ($row = $tableData->fetch_object('Member')):
            foreach ($days as $day) 
            $color[] = $row->$day == 'Y' ? 'green' : 'red';           
    ?>
    <tr class="<?php echo $row->id ?>">
        <td class="center">
            <input type="checkbox" name="id" value="<?php echo $row->id ?>" class="<?php echo $row->nameFirstLast(); ?>" />
        </td>
        <td><?php echo $row->lastUpdate() ?></td>
        <td><?php echo $row->nameLastFirst() ?></td>
        <td><?php echo $row->email ?></td>
        <td><?php echo $row->section1 ?></td>
        <td><?php echo $row->section2 ?></td>
        <td><?php echo $row->part ?></td>
        <td><?php echo $row->entire ?></td>
        <td style="color: <?php echo $color[0] ?>"><?php echo $row->tue ?></td>
        <td style="color: <?php echo $color[1] ?>"><?php echo $row->wed ?></td>
        <td style="color: <?php echo $color[2] ?>"><?php echo $row->thu ?></td>
        <td style="color: <?php echo $color[3] ?>"><?php echo $row->fri ?></td>
        <td style="color: <?php echo $color[4] ?>"><?php echo $row->sat ?></td>
    </tr>
    <?php
        unset($color);
        endwhile;
    endif;
    ?>
</tbody>
</table>
<div class="toolbar ui-widget-header ui-corner-all" style="text-align: left;  margin-top: .25em; padding: .25em .25em;">
    <a id="check" class="check">Select All</a>
    <a id="edit" class="edit">Edit</a>
    <a id="sendMail" class="mail">Mail</a>
    <a id="deleteMember" class="delete">Delete</a></div>
</form>
</div>