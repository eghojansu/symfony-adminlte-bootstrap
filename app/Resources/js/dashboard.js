$(function(){
    $('[data-transform=select2]').select2();
    $('[data-provide=datepicker]').datepicker({
        autoclose: true
    });
    $('[data-provide=inputmask]').inputmask();
    $('[data-provide=clearbutton]').addClear();
    $('.treeview-menu .active').each(function() {
        $(this).parents('.treeview').addClass('active');
    });
    $('.table').on('click', '[data-action=delete]', function(event) {
        event.preventDefault();
        var $this = $(this);
        var deleteUrl = $this.prop('href');
        var $dialog = bootbox.dialog({
            closeButton: false,
            message: `<form method="post">
            <p>Are you sure?</p>
            <div class="text-right">
            <button type="reset" class="btn btn-flat btn-default"><i class="fa fa-ban"></i> Cancel</button>
            <button type="submit" class="btn btn-flat btn-danger"><i class="fa fa-exclamation-triangle"></i> Confirm</button>
            </div>
            </form>`
        });
        $dialog.find('button:submit').on('click', function(event) {
            event.preventDefault();
            var $body = $dialog.find('.bootbox-body');
            var showMessage = function(message, alert) {
                $body.html('<div class="alert alert-'+alert+'" style="margin-bottom: 0">'+message+'</div>');
                setTimeout(function() {
                    $dialog.modal('hide');
                }, 2000);
            };
            $.ajax({
                url: deleteUrl,
                type:'DELETE',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        $dialog.modal('hide');
                        $.notify(data.message, {
                            className: 'success',
                            position: 'top center'
                        });
                        setTimeout(function() {
                            window.location.href = window.location.href.replace(window.location.search, '');
                        }, 1000);
                    }
                    else {
                        showMessage(data.message, 'danger');
                    }
                },
                error: function(a, b, c) {
                    showMessage(b+': '+c, 'danger');
                },
                beforeSend: function() {
                    $body.html('<i class="fa fa-spinner fa-spin fa-fw"></i> please wait...');
                }
            });
        });
        $dialog.find('button:reset').on('click', function() {
            $dialog.modal('hide');
        });
    });
    $('[data-handle=submit]').on('submit', function(event) {
        event.preventDefault();
        var $this = $(this);
        var $spoofer = $this.find('[name=_method]');
        var submitUrl = $this.prop('href');
        var $dialog;
        var showMessage = function(message, alert) {
            $dialog.find('.bootbox-body').html('<div class="alert alert-'+alert+'" style="margin-bottom: 0">'+message+'</div>');
            setTimeout(function() {
                $dialog.modal('hide');
            }, 2000);
        };

        $.ajax({
            url: submitUrl,
            data: $this.serialize(),
            type: $spoofer.length?$spoofer.val():$this.prop('method'),
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    $dialog.modal('hide');
                    $.notify(data.message, {
                        className: 'success',
                        position: 'top center'
                    });
                }
                else {
                    showMessage(data.message, 'danger');
                }
            },
            error: function(a, b, c) {
                showMessage(b+': '+c, 'danger');
            },
            beforeSend: function() {
                $dialog = bootbox.dialog({
                    closeButton: false,
                    message: `<div class="text-center">
                        <i class="fa fa-spinner fa-spin fa-fw"></i> saving changes, please wait..
                    </div>`
                });
            }
        });
    });
});
