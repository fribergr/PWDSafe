function showError(reason) {
    PNotify.alert({
        type: 'error',
        text: reason
    });
}
$(document).ready(function() {
    $('.btn-signin').click(function() {
        if ($('input[name="email"]').val().length > 0 && $('input[name="password"]').val().length > 0) {
            $('#working').removeClass('d-none');
        }
    });
        $('.btn-reg').click(function() {
                $('#working').removeClass('d-none');
                var user = $('#inputEmail').val();
                var pass = $('#inputPassword').val();
                $.post('/reg', {
                    user: user,
                    pass: pass,
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                }, function (data) {
                        setTimeout(function() {
                            $('#working').addClass('d-none');
                            if (data.status === 'OK') {
                                $('#regsuccess').removeClass('d-none');
                            } else {
                                $('.form-group').not('.has-error').addClass('has-error');
                                showError(data.reason);
                            }
                        }, 500);
                }, 'json')
                .fail(function(data) {
                    $('#working').addClass('d-none');
                    Object.keys(data.responseJSON.errors).forEach(function (key) {
                        showError(data.responseJSON.errors[key]);
                    });
                });
        });

        $('#deleteCred, .credDelete').click(function() {
                var id = $(this).data('id');
                $.get('/cred/' + id + '/remove', function(data) {
                        if (data.status === 'OK') {
                                window.location.reload();
                        } else {
                                showError(data.reason);
                        }
                }, 'json');
        });

        $('#updateCred').click(function() {
            var id = $(this).data('id');
            var s_site = $('#s_site').val();
            var s_user = $('#s_user').val();
            var s_pass = $('#s_pass').val();
            var s_notes = $('#s_notes').val();
            var s_group = $('#s_group').val();
            $.post('/cred/' + id, {
                'creds': s_site,
                'credu': s_user,
                'credp': s_pass,
                'credn': s_notes,
                'currentgroupid': s_group,
                '_token': $('meta[name="csrf-token"]').attr('content'),
            }, function(data) {
                if (data.status === 'OK') {
                    $('#addCredModal').find('input').val('');
                    $('#addCredModal').modal('hide');
                    window.location.reload();
                } else {
                    showError(data.reason);
                }
            }, 'json');
        });

        $('.showPass').click(function() {
                var id = $(this).data('id');
                $.get('/pwdfor/' + id, function(data) {
                        $('#showCredModal').find('#s_pass').val(data.pwd);
                        $('#showCredModal').find('#s_user').val(data.user);
                        $('#showCredModal').find('#s_site').val(data.site);
                        $('#showCredModal').find('#s_notes').val(data.notes);
                        $('#showCredModal').find('#s_group').val(data.groupid);
                        $('#showCredModal').find('#deleteCred').data('id', id);
                        $('#showCredModal').find('#updateCred').data('id', id);
                        $('#showCredModal').modal();
                }, 'json');
        });

        $('#addCred').click(function() {
                $('#addCredModal').modal();
        });

        $('#importCred').click(function() {
            $('#importCredModal').modal();
        });
        $('#importCredSave').click(function() {
            $('#creduploadform').submit();
        });

    $('#saveCred').click(function () {
        $.post(
            '/cred/add',
            {
                'credu': $('#user').val(),
                'creds': $('#site').val(),
                'credp': $('#pass').val(),
                'credn': $('#notes').val(),
                'currentgroupid': $('#groupid').val(),
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            function (data) {
                if (data.status === 'OK') {
                    $('#addCredModal').find('input').val('');
                    $('#addCredModal').modal('hide');
                    window.location.reload();
                } else {
                    showError(data.reason);
                }
            },
            'json'
        );
    });

        $('#changePwd').click(function() {
                $.post(
                    '/changepwd',
                    {
                        'oldpwd': $('#oldpwd').val(),
                        'newpwd1': $('#newpwd1').val(),
                        'newpwd2': $('#newpwd2').val(),
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    function(data) {
                        if (data.status === 'OK') {
                                $('input').val('');
                                $('<div class="alert alert-success"><strong>Password changed!</strong> Your password has been changed successfully.</div>').insertBefore('form#pwdchangeform');
                        } else {
                                $('.form-group').not('.has-error').addClass('has-error');
                                showError(data.reason);
                        }
                    },
                    'json'
                );
        });

        $('#createGroupForm').submit(function(event) {
            event.preventDefault();
            $('#createGroup').click();
        });

        $('#createGroup').click(function() {
            $.post(
                '/groups/create',
                {
                    'groupname': $('#groupname').val(),
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                function(data) {
                    if (data.status === 'OK') {
                        $('input').val('');
                        $('<div class="alert alert-success"><strong>Group created!</strong> Your group has been created successfully.</div>').insertBefore('form#createGroupForm');
                        window.setTimeout(function(){
                            window.location.href = "/groups/" + data.groupid;
                        }, 1500);
                    } else {
                        $('.form-group').not('.has-error').addClass('has-error');
                    }
                },
                'json'
            );
        });

        $('#deletegroup').click(function() {
            $.post(
                '/groups/' + $(this).data('id') + '/delete', {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                function(data) {
                    if (data.status === "OK") {
                        window.location.replace("/");
                    }
                },
                'json'
            );
        });

        $('#shareGroup').click(function() {
                $('#shareGroupModal').modal();
        });

        $('#shareGroupSave').click(function() {
            $.post(
                '/groups/' + $("#currentgroupid").val() + '/share',
                {
                    'email': $('#email').val(),
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                function(data) {
                        if (data.status === "OK") {
                                window.location.reload();
                        } else {
                            showError(data.reason);
                        }
                },
                'json'
            );
        });

        $('#changeGroupName').click(function() {
            $('#changeGroupNameModal').modal();
        });
        $('#changeGroupNameSave').click(function() {
            $.post(
                '/groups/' + $('#groupid').val() + '/changename',
                {
                    'groupname': $('#grpname').val(),
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                function(data) {
                    if (data.status === "OK") {
                        window.location.reload();
                    } else {
                        showError(data.reason);
                    }
                },
                'json'
            );
        });

        $('.removeUser').click(function() {
            $.post(
                '/groups/' + $(this).data('groupid') + '/unshare/' + $(this).data('id'), {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                function(data) {
                    if (data.status === "OK") {
                        window.location.reload();
                    } else {
                        showError(data.reason);
                    }
                },
                'json'
            );
        });

        new ClipboardJS('.copypwd', {
            text: function(trigger) {
                var pwd = "";
                $.ajax({
                    url: '/pwdfor/' + $(trigger).data('id'),
                    type: 'get',
                    dataType: 'json',
                    async: false,
                    success: function(data) {
                        pwd = data.pwd;
                    }
                });
                return pwd;
            }
        });

        $('.popconfirm').popConfirm({
            placement: "left",
        });
});
