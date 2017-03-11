$(document).ready(function() {
        $('.btn-reg').click(function() {
                var user = $('#inputEmail').val();
                var pass = $('#inputPassword').val();
                $.post('/reg', {
                        user: user,
                        pass: pass
                }, function (data) {
                        if (data.status == 'OK') {
                                window.location.reload();
                        } else {
                                console.log(data);
                        }
                }, 'json');
        });

        $('#deleteCred').click(function() {
                var id = $(this).data('id');
                $.get('/cred/' + id + '/remove', function(data) {
                        if (data.status == 'OK') {
                                window.location.reload();
                        } else {
                                console.log(data);
                        }
                }, 'json');
        });

        $('.showPass').click(function() {
                var id = $(this).data('id');
                $.get('/pwdfor/' + id, function(data) {
                        $('#showCredModal').find('#s_pass').val(data.pwd);
                        $('#showCredModal').find('#s_user').val(data.user);
                        $('#showCredModal').find('#s_site').val(data.site);
                        $('#showCredModal').find('#deleteCred').data('id', id);
                        $('#showCredModal').modal();                       
                }, 'json'); 
        });

        $('#addCred').click(function() {
                $('#addCredModal').modal();
        });

        $('#saveCred').click(function() {
                $.post(
                        '/cred/add',
                        {
                                'credu': $('#user').val(),
                                'creds': $('#site').val(),
                                'credp': $('#pass').val()
                        },
                        function(data) {
                                if (data.status == 'OK') {
                                        $('#addCredModal').find('input').val('');
                                        $('#addCredModal').modal('hide');
                                        window.location.reload();
                                } else {
                                        console.log(data);
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
                        'newpwd2': $('#newpwd2').val()
                    },
                    function(data) {
                        if (data.status == 'OK') {
                                $('input').val('');
                                $('<div class="alert alert-success"><strong>Password changed!</strong> Your password has been changed successfully.</div>').insertBefore('form');
                        } else {
                                $('.form-group').not('.has-error').addClass('has-error');
                        }
                    },
                    'json'
                );
        });
});
