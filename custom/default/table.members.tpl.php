<div id="demo" class="table-wrapper" style="display: none;"
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
    <th>Grade</th>
    <th>Section</th>
    <th>J</th>
    <th>P</th>
    <th>S</th>
  </tr>
</thead>
<tbody>
    <?php
    if (isset($tableData)):
        while ($row = $tableData->fetch_object('Member')):
            $status = $row->getUniformStatus();
    ?>
    <tr class="<?php echo $row->id ?>">
        <td class="center">
            <input type="checkbox" name="id" value="<?php echo $row->id ?>" class="<?php echo $row->nameFirstLast(); ?>" />
        </td>
        <td><?php echo $row->lastUpdate() ?></td>
        <td><?php echo $row->nameLastFirst() ?></td>
        <td><?php echo $row->email ?></td>
        <td><?php echo $row->grade ?></td>
        <td><?php echo $row->section ?></td>
        <td style="color: <?php echo $status['jacket'] ?>"><?php echo $row->jacket_id ?></td>
        <td style="color: <?php echo $status['pants'] ?>"><?php echo $row->pants_id ?></td>
        <td style="color: <?php echo $status['shako'] ?>"><?php echo $row->shako_id ?></td>
    </tr>
    <?php
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