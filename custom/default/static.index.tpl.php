<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>bPanel</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.8.custom.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-datatables.css" />
<link rel="stylesheet" type="text/css" href="css/default.css" />
<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.8.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/TableTools.js"></script>
<script type="text/javascript" src="js/ZeroClipboard.js"></script>
<script type="text/javascript" src="js/bpanel-controller.js"></script>
</head>
<body>
<div id="CSRF"><?php echo $csrfToken; ?></div>
<!-- Dialogs -->
<div id="dialog-error" title="Xdebug Error" style="display:none;"></div>
<div id="dialog-confirm" title="Are you sure?" style="display:none;">
    <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
    These items will be permanently deleted and cannot be recovered.
</div>
<div id="dialog-duplicates" title="Error" style="display:none;">
    <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
    One or more selected profiles are already being edited. Close any open tabs to continue.
</div>
<div id="dialog-mail" title="Send Mail" style="display:none;">
    <div class="success ui-state-highlight ui-corner-all" style="width: 9em; margin: 0.8em auto 0 auto; padding: 0.7em 0em 0.9em 0.9em; display: none;">
        <div>
            <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
            <strong>Email Sent</strong>
        </div>
    </div>
    <div class="failure ui-state-error ui-corner-all" style="margin: 0.8em auto 0 auto; padding: 0.7em 0em 0.9em 0.9em; display: none;">
        <div>
            <span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
            <strong>Error: </strong><span id="error"></span>
        </div>
    </div>
    <form action="#" id="mail-form">
        <div style="width: 100%">
            Subject
            <input type="text" name="subject" id="subject" class="text ui-widget-content ui-corner-all" style="margin-top: 1em; width: 100%; height: 2em;" />
            <textarea name="body" id="body" class="text ui-widget-content ui-corner-all" style="margin-top: 1em; width: 100%; height: 20em;"></textarea>
        </div>
    </form>
</div>
<div id="dialog-tools-members" title="Tools" style="display:none;"></div>
<div id="dialog-tools-mail" title="Tools" style="display:none;"></div>
<!-- End Dialogs -->
<form method="post" action="logout.php">
<input type="hidden" name="token" value="<?php echo $csrfToken; ?>" />
<div id="title">
    bPanel
    <span id="ver">v0.11.04</span>
    <span id="external">
        <span id="loggedin"><?php echo $username ?> | Group: <?php echo $group ?></span>
        <a href="register.php" id="register"> Register Page</a>
        <!--<a href="logout.php" id="logout">Logout</a>-->
        <button id="logout" name="submit">Logout</button>
    </span>
    
</div>
</form>
<div id="main">
    <ul>
        <li><a href="#members">Manage Members</a></li>
        <li><a href="#mail">Sent Mail</a></li>
    </ul>
    <div id="members"></div>
    <div id="mail"></div>
</div>
</body>
</html>