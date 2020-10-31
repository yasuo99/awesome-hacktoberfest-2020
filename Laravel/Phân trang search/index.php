
<script>
    //Script phân trang sau khi search và search bằng ajax dựa trên event keyup keydown
    $(document).ready(function () {
        //function search với event click vào thẻ a trong div có class là pagination, phần này được hàm paginate của laravel tự generate ra code html
        $(document).on('click', '#pagination a', function (event) {
            event.preventDefault();
            // $('li').removeClass('focus');
            // $(this).css('color: black');
            var myurl = $(this).attr('href');
            var page = $(this).attr('href').split('page=')[1];
            getData(page);
        });
        //function search với event keyup, khi input có id là search thay đổi value thì sẽ tự động gọi ajax và get data từ server
        $(document).on('keyup', '#search', function () {
            var q = $(this).val();
            $.ajax({
                url: "{{route('get.getAccounts')}}",
                method: 'GET',
                data: { q: q },
                dataType: 'json',
                success: function (data) {
                    $('tbody').html('');
                    $('tbody').html(data.accounts);
                    $('#pagination').html(data.pagination)
                }
            })

        })
        //function search với event keydown, khi input có id là search thay đổi value thì sẽ tự động gọi ajax và get data từ server
        $(document).on('keydown', '#search', function () {
            var q = $(this).val();
            $.ajax({
                url: "{{route('get.getAccounts')}}",
                method: 'GET',
                data: { q: q },
                dataType: 'json',
                success: function (data) {
                    $('tbody').html('');
                    $('tbody').html(data.accounts);
                    $('#pagination').html(data.pagination)
                }
            })
        })
        $('#editForm input').keyup(function(){
            $('#eidtUsernameWarning > strong').empty();
            $('#editEmailWarning > strong').empty();
            $('#editPhoneWarning > strong').empty();

            var username = $('#newUsername').val();
            var email = $('#newEmail').val();
            var phone = $('#newPhone').val();
            //kiểm tra input có chứa kí tự đặc biệt không
            if(/^[a-zA-Z0-9- ]*$/.test(username) == false) {
                $('#editUsernameWarning').removeAttr('hidden');
                $('#editUsernameWarning > strong').html("Can't use special characters");
                $('#editButton').attr('disabled',true);
            }   
            else if(/^[a-zA-Z0-9@. ]*$/.test(email) == false) {
                $('#editEmailWarning').removeAttr('hidden');
                $('#editEmailWarning > strong').html("Can't use special characters");
                $('#editButton').attr('disabled',true);
            }   
            else if(/^[0-9- ]*$/.test(phone) == false) {
                $('#editPhoneWarning').removeAttr('hidden');
                $('#editPhoneWarning > strong').html("Can't use special characters");
                $('#editButton').attr('disabled',true);
            }  
            else{
            $.ajax({
                url: '{{route('post.validate')}}',
                type: 'GET',
                data: {username: username, email: email, phone: phone},
                dataType: 'json',
                success: function (data){    
                    console.log(data);
                    if(data.usernames.username)
                    {
                        $('#editUsernameWarning').removeAttr('hidden');
                        $('#editUsernameWarning > strong').empty();
                        Array.from(data.usernames.username);
                        data.usernames.username.forEach(function(item){
                            $('#editUsernameWarning > strong').append(item + ' <br/>');
                        })
                        $('#editButton').attr('disabled',true);              
                    }
                    else{
                        $('#editUsernameWarning').attr('hidden',true);
                        $('#editButton').attr('disabled',false); 
                    }
                    if(data.emails.email)
                    {
                        $('#editEmailWarning').removeAttr('hidden');
                        $('#editEmailWarning > strong').empty();
                        Array.from(data.emails.email);
                        data.emails.email.forEach(function(item){
                            $('#editEmailWarning > strong').append(item + ' <br/>');
                        }) 
                        $('#editButton').attr('disabled',true);             
                    }
                    else{
                        $('#editEmailWarning').attr('hidden',true);
                        $('#editButton').attr('disabled',false); 
                    }
                    if(data.phones.phone)
                    {
                        $('#editPhoneWarning > strong').empty();
                        $('#editPhoneWarning').removeAttr('hidden');
                        Array.from(data.phones.phone);
                        data.phones.phone.forEach(function(item){
                            $('#editPhoneWarning > strong').append(item + ' <br/>');
                        }) 
                        $('#editButton').attr('disabled',true);             
                    }
                    else{
                        $('#editPhoneWarning').attr('hidden',true);
                        $('#editButton').attr('disabled',false); 
                    }
                }
            })}
        });
        $('#createForm input').keyup(function(){
            $('#usernameWarning > strong').empty();
            $('#emailWarning > strong').empty();
            $('#phoneWarning > strong').empty();

            var username = $('#username').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            if(/^[a-zA-Z0-9- ]*$/.test(username) == false) {
                $('#usernameWarning').removeAttr('hidden');
                $('#usernameWarning > strong').html("Can't use special characters");
                $('#createButton').attr('disabled',true); 
            }   
            else if(/^[a-zA-Z0-9@. ]*$/.test(email) == false) {
                $('#emailWarning').removeAttr('hidden');
                $('#emailWarning > strong').html("Can't use special characters");
                $('#createButton').attr('disabled',true); 
            }   
            else if(/^[0-9- ]*$/.test(phone) == false) {
                $('#phoneWarning').removeAttr('hidden');
                $('#phoneWarning > strong').html("Can't use special characters");
                $('#createButton').attr('disabled',true); 
            }  
            else{
            $.ajax({
                url: '{{route('post.validate')}}',
                type: 'GET',
                data: {username: username, email: email, phone: phone},
                dataType: 'json',
                success: function (data){    
                    console.log(data);
                    if(data.usernames.username)
                    {
                        $('#usernameWarning').removeAttr('hidden');
                        $('#usernameWarning > strong').empty();
                        Array.from(data.usernames.username);
                        data.usernames.username.forEach(function(item){
                            $('#usernameWarning > strong').append(item + ' <br/>');
                        });
                        $('#createButton').attr('disabled',true);          
                    }
                    else{
                        $('#usernameWarning').attr('hidden',true);
                        $('#createButton').attr('disabled',false); 
                    }
                    if(data.emails.email)
                    {
                        $('#emailWarning').removeAttr('hidden');
                        $('#emailWarning > strong').empty();
                        Array.from(data.emails.email);
                        data.emails.email.forEach(function(item){
                            $('#emailWarning > strong').append(item + ' <br/>');
                        })
                        $('#createButton').attr('disabled',true);             
                    }
                    else{
                        $('#emailWarning').attr('hidden',true);
                        $('#createButton').attr('disabled',false); 
                    }
                    if(data.phones.phone)
                    {
                        $('#phoneWarning > strong').empty();
                        $('#phoneWarning').removeAttr('hidden');
                        Array.from(data.phones.phone);
                        data.phones.phone.forEach(function(item){
                            $('#phoneWarning > strong').append(item + ' <br/>');
                        })        
                        $('#createButton').attr('disabled',true);       
                    }
                    else{
                        $('#phoneWarning').attr('hidden',true);
                        $('#createButton').attr('disabled',false); 
                    }
                }
            })}
        });
        // $(document).on('keyup','#username', function(){
        //     $('#usernameWarning > strong').empty();
        //     var username = $(this).val();
        //     if(/^[a-zA-Z0-9- ]*$/.test(username) == false) {
        //         $('#usernameWarning').removeAttr('hidden');
        //         $('#usernameWarning > strong').html("Can't use special characters");
        //     }   
        //     else{
        //     $.ajax({
        //         url: '{{route('post.validate')}}',
        //         type: 'GET',
        //         data: {username: username},
        //         dataType: 'json',
        //         success: function (data){    
        //             if(data.usernames.username)
        //             {
        //                 $('#usernameWarning').removeAttr('hidden');
        //                 Array.from(data.usernames.username);
        //                 data.usernames.username.forEach(function(item){
        //                     $('#usernameWarning > strong').append(item + ' <br/>');
        //                 })              
        //             }
        //             else{
        //                 $('#usernameWarning').attr('hidden',true);
        //             }
        //         }
        //     })}
        // });
        // $(document).on('keyup','#email', function(){
        //     $('#emailWarning > strong').empty();
        //     var email = $(this).val();
        //     if(/^[a-zA-Z0-9@. ]*$/.test(email) == false) {
        //         $('#emailWarning').removeAttr('hidden');
        //         $('#emailWarning > strong').html("Can't use special characters");
        //     }   
        //     else{
        //     $.ajax({
        //         url: '{{route('post.validate')}}',
        //         type: 'GET',
        //         data: {email: email},
        //         dataType: 'json',
        //         success: function (data){    
        //             if(data.emails.email)
        //             {
        //                 $('#emailWarning').removeAttr('hidden');
        //                 Array.from(data.emails.email);
        //                 data.emails.email.forEach(function(item){
        //                     $('#emailWarning > strong').append(item + ' <br/>');
        //                 })              
        //             }
        //             else{
        //                 $('#emailWarning').attr('hidden',true);
        //             }
        //         }
        //     })}
        // });
        // $(document).on('keyup','#phone', function(){
        //     $('#phoneWarning > strong').empty();
        //     var phone = $(this).val();
        //     if(/^[0-9- ]*$/.test(phone) == false) {
        //         $('#phoneWarning').removeAttr('hidden');
        //         $('#phoneWarning > strong').html("Can't use special characters");
        //     }   
        //     else{
        //     $.ajax({
        //         url: '{{route('post.validate')}}',
        //         type: 'GET',
        //         data: {phone: phone},
        //         dataType: 'json',
        //         success: function (data){    
        //             if(data.phones.phone)
        //             {
        //                 $('#phoneWarning').removeAttr('hidden');
        //                 Array.from(data.phones.phone);
        //                 data.phones.phone.forEach(function(item){
        //                     $('#phoneWarning > strong').append(item + ' <br/>');
        //                 })              
        //             }
        //             else{
        //                 $('#phoneWarning').attr('hidden',true);
        //             }
        //         }
        //     })}
        // })
        $(document).on('click', '.deleteUser', function () {
            var userID = $(this).attr('data-userid');
            $('#app_id').val(userID);
        });
        $(document).on('click', '.updateUser', function () {
            var userId = $(this).attr('data-id');
            getOneData(userId);
            $('#user_id').val(userId);
            console.log($('#user_id').val());
        });
    });
    function getOneData(id) {
        $.ajax(
            {
                url: 'detail/' + id,
                type: "get",
                dataType: "json",
                success: function (data) {
                    document.getElementById('oldUsername').value = data.username;
                    document.getElementById("oldFullname").value = data.fullname;
                    document.getElementById("oldEmail").value = data.email;
                    document.getElementById("oldPhone").value = data.phone;
                    document.getElementById("oldAddress").value = data.address;
                }
            }
        )
    }
    function getData(page) {
        var q = document.getElementById('search').value;
        $.ajax(
            {
                url: '{{route('get.getAccounts')}}',
                type: "get",
                data: { q: q, page: page },
                datatype: "html"
            }).done(function (data) {
                
                data = JSON.parse(data); //convert data nhận từ server thành kiểu object
                $("tbody").empty().html(data.accounts); //render lại html trong thẻ tbody
                $('#pagination').html(data.pagination) //render lại html trong thẻ có id là pagination
                location.hash = page;
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
    }
</script>
