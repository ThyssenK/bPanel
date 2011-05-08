$(document).ready(function() {

(function($) {

    var AJAX_URL  = 'ajax.php';
    var page      = 'members';
    var oTable    = {};
    var idArr     = {};
    var tog       = false;
    var CSRFToken;
    var $tabs;
    var tabId     = 0;
    var numTabs   = 0;
    var options   = {
        select : function(e, ui) {
            page = ui.panel.id;
            $('.close-alert').hide();
        },
        add    : function(e, ui) {
            $tabs.tabs('select', '#' + ui.panel.id);
            $('#' + page).html('Loading...');
        }
    };
    var TABLES   = {
        members : '#membersTable',
        mail    : '#mailTable'
    };

    var bQuery = function() {
        $tabs = $('#main').tabs(options);
        $tabs.tabs('option', 'tabTemplate', "<li><a href='#{href}'><span>#{label}"
            + "</span></a><span class='ui-icon ui-icon-close'>#{href}</span></li>");
        $('#main span.ui-icon-close').live('click', closeTab);
        $('#logout').button({
            icons: {
                primary : 'ui-icon-power'
            }
        });
        $('#register').button({
            icons: {
                primary : 'ui-icon-plusthick'
            }
        });
        $('.toolbar a').live('click.bQuery', toolbar);
        $('tbody td:not(.center)').live('click', itemClick);
        $('#editform form').live('submit', updateProfile);
        $('#uniform form').live('submit', checkoutUniform);
        $('.unihistory a').live('click' , editUniform);
        CSRFToken = $('#CSRF').html();
        getTables(TABLES);
    }

    function closeTab() {
        var tabIndex = $(this.parentNode).index(), tabName = this.innerHTML;
        delete idArr[tabName];
        $tabs.tabs('remove', tabIndex);
        if(numTabs == 1)
            $tabs.tabs('select', '#members');
        numTabs--;
    }

    function getTables(getTable) {
        $.each(getTable, function(table) {
            ajaxJson(table, null, function(tableHTML) {
                $('#'+table).html(tableHTML);
                initDataTable(table);
            });
        });
    }

    function toolbar() {
        var action = this.id, newIdArr = {}, oneId, filled;

        $('#' + page + ' input:checked').each(function() {
            oneId = this.value;
            newIdArr[oneId] = this.className;
        });

        filled = (size(newIdArr) > 0);

        if(action == 'check')
            toggleCheck();
        if(action == 'tools')
            toolDialog();
        if(action == 'sendMail' && filled)
            mailDialog(toCSV(newIdArr), false);
        if(action == 'edit' && filled)
            getProfile(newIdArr);
        if((action == 'deleteMember' || action == 'deleteMail') && filled)
            confirmDialog(action, newIdArr);
    }

    function itemClick() {
        var id = this.parentNode.className.split(" ")[0];
        var newIdArr = {};
        if(page == 'members') {
            newIdArr[id] = $('#membersTable .' + id + ' input').attr('class');
            getProfile(newIdArr);
        }
        if(page == 'mail')
            mailDialog(id, true);
    }

    //TODO revert to select all if user navigates away
    function toggleCheck() {
        $('#' + page + ' input[type=checkbox]').attr('checked', !tog);
        tog = !tog;
        if(tog)
            $('.check span').html('Select None');
        else
            $('.check span').html('Select All');
    }

    function getProfile(newIdArr) {
        var newTab, duplicates;
        duplicates = duplicateProfiles(newIdArr, false);

        if(size(newIdArr) > 0) {
            numTabs++;
            tabId++;
            newTab = '#tab-'+tabId;
            idArr[newTab] = newIdArr;
            $tabs.tabs('add', newTab, 'Member Profile');

            //TODO pass 2nd arg with original order to use in sql field function
            ajaxJson('editMember', toCSV(newIdArr), function(profileHTML) {
                $(newTab).html(profileHTML);
                initEditButtons(newIdArr);
                notify(null, duplicates);
            });
        }
        else
            duplicateDialog();
    }

    function duplicateProfiles(newIdArr, move) {
        var duplicates = {}, tab, id, index;
        for(tab in idArr) {
            if(idArr.hasOwnProperty(tab)) {
                for(id in idArr[tab]) {
                    if(id in newIdArr) {
                        duplicates[id] = idArr[tab][id];
                        if(move) {
                            $(tab + ' .' + id).remove();
                            delete idArr[tab][id];
                        } else {
                            delete newIdArr[id];
                        }
                    }
                }
                if($(tab).children().size() == 0) {
                    delete idArr[tab];
                    index = $(tab).index();
                    index--;
                    $tabs.tabs('remove', index);
                    numTabs--;
                }
            }
        }
        return duplicates;
    }

    function updateProfile() {
        var formData = $(this).serialize();
        ajax('updateProfile', formData, function(id) {
            notify('Changes Saved!', id)
        });
        getTables({
            members: null
        });
        return false;
    }

    function checkoutUniform() {
        var formData = $(this).serialize();
        ajax('addUniform', formData, function(id) {
            getMemberUniform(id);
        });
        return false;
    }

    function editUniform() {
        var id = $(this).attr('id'),
        action = $(this).parent().attr('class'),
        member_id = $(this).parent().parent().attr('class');

        ajax(action, id, function() {
            getMemberUniform(member_id);
        });
    }

    function getMemberUniform(id) {
        var idClass;
        ajaxJson('memberUniform', id, function(data) {
            idClass = '.' + id;
            $(idClass + ' .unihistory').html(data);
            $('#number'+id).val('');
            $(idClass + ' .unihistory .editUniform a').button({
                icons: {
                    primary : 'ui-icon-check'
                }
            });
            $(idClass + ' .unihistory .deleteUniform a').button({
                icons: {
                    primary : 'ui-icon-trash'
                }
            });
            getTables({
                members: null
            });
        });
    }

    function initDataTable(page) {
        var table = TABLES[page];
        oTable[page] = $(table).dataTable({
            'bJQueryUI'       : true,
            'sPaginationType' : 'full_numbers',
            'iDisplayLength'  : -1,
            'aLengthMenu'     : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            'aoColumnDefs'    : [{
                'bSortable' : false,
                'aTargets' : [0]
                }],
            'aaSorting'       : [[ 1, 'desc' ]],
            'sDom'            : 'T<"H"lfr>t<"F"ip>'
        });

        $(table+' tbody td').live('mouseover mouseout', function(e) {
            if (e.type == 'mouseover') {
                $(this).siblings().andSelf().addClass('highlighted');
            } else {
                $('td.highlighted').removeClass('highlighted');
            }
        });

        initTableButtons();
        $('#dialog-tools-'+page).html('');
        $('#'+ page + ' .TableTools').appendTo('#dialog-tools-'+page);

        $('.table-wrapper').show();
    }

    function initTableButtons() {
        $('.edit').button({
            icons: {
                primary : 'ui-icon-pencil'
            }
        });
        $('.delete').button({
            icons: {
                primary : 'ui-icon-trash'
            }
        });
        $('.check').button({
            icons: {
                primary : 'ui-icon-check'
            }
        });
        $('.mail').button({
            icons: {
                primary : 'ui-icon-mail-closed'
            }
        });
        $('.tools').button({
            icons: {
                primary : 'ui-icon-wrench'
            },
            text : false
        }).css('float','right');
    }

    function initEditButtons(newIdArr) {
        for(var id in newIdArr) {
            if(newIdArr.hasOwnProperty(id)) {
                id = '.' + id;
                $(id+' button').button();
                $(id+' .radio').buttonset();
                $(id+' .unihistory .editUniform a').button({
                    icons: {
                        primary : 'ui-icon-check'
                    }
                });
                $(id+' .unihistory .deleteUniform a').button({
                    icons: {
                        primary : 'ui-icon-trash'
                    }
                });
            }
        }
    }

    function confirmDialog(action, newIdArr) {
        $('#dialog-confirm').dialog({
            resizable : false,
            draggable : false,
            modal     : true,
            width     : 260,
            height    : 150,
            buttons: {
                'Delete': function() {
                    ajax(action, toCSV(newIdArr));
                    for(var id in newIdArr) {
                        if(newIdArr.hasOwnProperty(id)) {
                            oTable[page].fnDeleteRow($(TABLES[page] + ' .' + id).get()[0]);
                        }
                    }
                    $(this).dialog('close');
                },
                Cancel: function() {
                    $(this).dialog('close');
                }
            }
        });
    }

    function duplicateDialog() {
        $('#dialog-duplicates').dialog({
            resizable : false,
            draggable : false,
            modal     : true,
            width     : 275,
            height    : 150,
            buttons: {
                Ok: function() {
                    $(this).dialog('close');
                }
            }
        });
    }

    function mailDialog(ids, resend) {
        if(resend) {
            ajaxJson('getMail', ids, function(data) {
                $('#subject').val(data[0].subject);
                $('#body').val(data[0].body);
                ids = data[0].ids;
            }, 'json');
        }

        $('#dialog-mail').dialog({
            resizable : false,
            draggable : true,
            modal     : true,
            width     : 600,
            height    : 'auto',
            buttons: {
                'Send': function() {
                    var formData = $('#mail-form').serialize();
                    formData += '&ids=' + ids;

                    ajax('sendMail', formData, function(result) {
                        if(result) {
                            getTables({
                                mail: null
                            });
                            $('#subject').val('');
                            $('#body').val('');
                            $('.success').fadeIn('slow').delay(2000).fadeOut('slow');
                        } else {
                            $('.failure').fadeIn('slow');
                            $('#error').html(result);
                        }
                    });
                },
                Cancel: function() {
                    $(this).dialog('close');
                }
            },
            close: function() {
                $('#subject').val('');
                $('#body').val('');
                $('.success').hide();
                $('.failure').hide();
            }
        });
    }

    function toolDialog() {
        $('#dialog-tools-'+page).dialog('close');
        $('#dialog-tools-'+page).dialog({
            resizable : false,
            draggable : true,
            modal     : false,
            width     : 235,
            height    : 125
        });
    }

    function notify(msg, id) {
        if(msg != null) {
            $('.'+id+' .alert-msg span').html(msg);
            $('.'+id+' .alert-msg').fadeIn(200).delay(2000).fadeOut(200);
            $('.'+id+' .alert-msg').addClass('close-alert');
        } else if(size(id) != 0) {
            msg = 'These members are already being edited: ';
            for(var key in id) {
                if(id.hasOwnProperty(key)) {
                    msg += id[key] + ', ';
                }
            }
            msg = msg.slice(0, -2);
            $('#tab-' + tabId + ' .alert-msg:first span').html(msg);
            $('#tab-' + tabId + ' .alert-msg:first').fadeIn(200);
        }
    }

    function toCSV(obj) {
        var csv = '', prop;
        for(prop in obj) {
            if(obj.hasOwnProperty(prop))
                csv += prop + ',';
        }
        return csv.slice(0, -1);
    }

    function size(obj) {
        var size = 0, key;
        for(key in obj) {
            if(obj.hasOwnProperty(key))
                size++;
        }
        return size;
    }

    function ajax(action, args, callback, type) {
        if (type === undefined)
            type = 'html';

        $.post(AJAX_URL, {
            action : action,
            args   : args,
            json   : false,
            token  : CSRFToken
        }, function(data) {
            if(data.indexOf('xdebug-error') < 0) {
                if (callback !== undefined)
                    callback(data);
            } else {
                errorDialog(data);
            }
        }, type);
    }

    function ajaxJson(action, args, callback) {
        $.post(AJAX_URL, {
            action : action,
            args   : args,
            token  : CSRFToken
        }, function(payload) {
            if(payload[0]) {
                if (callback !== undefined) {
                    callback(payload[1]);
                }
            } else {
                errorDialog(payload[1]);
            }
        }, 'json');
    }
	
    function errorDialog(error) {
        $('#dialog-error').html(error)
        $('#dialog-error').dialog({
            resizable : true,
            draggable : true,
            modal     : false,
            width     : 500,
            height    : 300,
            buttons: {
                Ok: function() {
                    $(this).dialog('close');
                }
            }
        });
    }

    return bQuery();

})(jQuery);

});