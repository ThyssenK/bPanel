<div id="demo" class="table-wrapper" style="display: none;"
    <form id="form">
        <div class="toolbar ui-widget-header ui-corner-all" style="text-align: left; margin-bottom: .25em; padding: .25em .25em;">
            <a id="check" class="check">Select All</a>
            <a id="deleteMail" class="delete">Delete</a>
            <a id="tools" class="tools">Tools</a>
        </div>
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="mailTable">
            <thead>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>To</th>
                    <th>Subject</th>
                    <th>Body</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($tableData)):
                    foreach ($tableData as $row):
                        if (strlen($row->body) > 60)
                            $row->body = substr($row->body, 0, 60) . '...';
                        $numRecipients = substr_count($row->recipient, ',') + 1;
                        $timestamp = date('m-d-Y', $row->timestamp);
                        $body = htmlspecialchars($row->body);
                ?>
                        <tr class="<?php echo $row->id ?>">
                            <td class="center"><input type="checkbox" name="id" value="<?php echo $row->id ?>" /></td>
                            <td><?php echo $timestamp ?></td>
                            <td><?php echo $numRecipients ?> Recipients</td>
                            <td><?php echo $row->subject ?></td>
                            <td><?php echo $row->body ?></td>
                        </tr>
                <?php
                        endforeach;
                    endif;
                ?>
            </tbody>
        </table>
        <div class="toolbar ui-widget-header ui-corner-all" style="text-align: left;  margin-top: .25em; padding: .25em .25em;">
            <a id="check" class="check">Select All</a>
            <a id="deleteMail" class="delete">Delete</a></div>
    </form>
</div>
