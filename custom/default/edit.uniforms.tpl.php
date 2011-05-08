<table>
    <thead>
      <tr>
        <td>Type</td>
        <td>#</td>
        <td>Out</td>
        <td>In</td>
        <td>Status</td>
        <td colspan="2">Actions</td>
      <tr>
    </thead>
    <tbody>
    <?php
    if (isset($uniSet)):
        foreach ($uniSet as $row):
            $status = $row->status ? '<span style="color: green">Checked In</span>' : '<span style="color: red">Checked Out</span>';
            $in_date = $row->in_date == 0 ? '-' : date('m-d-Y', $row->in_date);
            $out_date = date('m-d-Y', $row->out_date);
    ?>
        <tr class="<?php echo $row->member_id ?>">
          <td><?php echo $row->type ?></td>
          <td><?php echo $row->number ?></td>
          <td><?php echo $out_date ?></td>
          <td><?php echo $in_date ?></td>
          <td><?php echo $status ?></td>
          <td class="editUniform"><a id="<?php echo $row->id ?>">check-in</a></td>
          <td class="deleteUniform"><a id="<?php echo $row->id ?>">delete</a></td>
        </tr>
    <?php
        endforeach;
    else:
    ?>
        <tr><td colspan="7">No Uniforms</td></tr>';
    <?php endif; ?>
</tbody>
</table>