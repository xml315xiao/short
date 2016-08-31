<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link href="html/style.css" rel="stylesheet" type="text/css" />
    <script src="html/jquery.js"></script>
    <title>用户登录</title>
</head>
<body>
<script>
    $(function(){
        $("input#username").on("change", function(){
            var username = $(this).val();
            if (username.length < 1) {
                $(this).focus();
            } else {
                $.ajax({
                    type: "get",
                    url : "./ajax/checkuser.php",
                    data: {"username" : username},
                    success: function(data){
                        if (data.success == false && data.error != 'password'){
                            alert(data.error);
                            $(this).focus();
                        } else {
                            $("input#password").focus();
                        }
                    },
                    error: function(){

                    },
                    dataType: "json"
                });
            }

        });
        $("input#password").on("change", function(){
            var username = $("input#username").val();
            if (username.length < 1){
                $(this).focus();
            }
            var password = $(this).val();
            if (password.length < 1) {
                $(this).focus();
            }
        });
        $("#login").submit(function(){
            event.preventDefault();
            var username = $("input#username").val();
            if (username.length < 1){
                $(this).focus();
            }
            var password = $("input#password").val();
            if (password.length < 1) {
                $(this).focus();
            } else {
                $.ajax({
                    type: "post",
                    url : "./ajax/checkuser.php",
                    data: {"username" : username, "password" : password },
                    success: function(data){
                        if (data.success == false){
                            alert(data.error);
                        } else {
                            window.location.href = "create.php"
                        }
                    },
                    dataType: "json"
                });
            }
        });
    })
</script>


<div class="wrapper">
    <div class="container">
        <h1>Welcome</h1>

        <form class="form" id="login">
            <input type="text" id="username" placeholder="Username">
            <input type="password" id="password" placeholder="Password">
            <input type="submit" id="login-button" value="Login">
        </form>
    </div>

    <ul class="bg-bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>

</body>
</html>

