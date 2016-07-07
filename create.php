<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>卖场网-短链接生成</title>
    <link rel="stylesheet" href="./style.css">
    <script src="jquery.js"></script>

</head>
    <input type="text" id="url" name="url" value="" placeholder="http://">
    <input type="submit" id="submit" value="shorter" name="submit"><br/>
    <span id="error"></span>

    <script>
        $(function(){
            $("#submit").click(function () {
                $("#error").hide();
                var long_url = $("#url").val();
                if (long_url.length > 0) {
                    $.ajax({
                        type: 'POST',
                        url: "./shorter.php/",
                        data: {"url": long_url},
                        success: function (data) {
                            if (false == data.success) {
                                $("#error").html(data.error + ": " + long_url).css("color", "#b94a48").show();
                                $("#url").val("").focus();
                            } else {
                                $("#url").val(data.short_url).css("color", "#468847");
                            }
                        },
                        error: function () {
                            $("#error").val("输入的网址无效或者网络异常").css("color", "#b94a48").show();
                            $("url").val("").focus();
                        },
                        dataType: "json"
                    })
                } else {
                    $("#error").html("链接地址不能为空").css("color", "#b94a48").show();
                    $("url").focus();
                }
            });
        });
    </script>
</body>
</html>