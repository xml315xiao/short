<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>卖场网-短链接生成</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="jquery.js"></script>
    <style>
        span.error {
            color: #b94a48;
        }
        span.warning {
            color: #c09853;
        }
        span.success {
            color: #468847;
            font-size: 24.5px;
        }
        span.info {
            color: #3a87ad;
            margin-left: 5px;
            font-size: 12px;
            height: 12px;
            line-height: 12px;
        }
        label {
            font-size: 17.5px;
        }
    </style>
</head>
<body>
    <div class="form-horizontal form-actions">
        <div class="control-group">
            <label for="url" class="control-label">长链接：</label>
            <div class="controls">
                <input type="text" id="url" class="input-xxlarge" placeholder="http://"/>
                <span class="help-inline error"></span>
            </div>
        </div>
        <div class="control-group">
            <label for="customer" class="control-label">自定义：</label>
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on">http://mc.cc/</span>
                    <input type="text" class="input-xxlarge" id="customer" style="width:440px;" placeholder="字母或数字">
                </div>
                <span class="help-inline warning"></span>
            </div>
        </div>
        <div class="controls" >
            <span id="result" class="help-inline hide"></span>
            <span class="info hide">当前URL已成功生成过短链接,不可再自定义</span>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">生成短链接</button>
            <button type="button" class="btn cancel">取消</button>
        </div>
    </div>

    <script>

        $(function(){
            $("input#url").on("change, focusout", function(){
                var long_url = $("#url").val().trim();
                if (long_url.length < 1) {
                    $("span.error").text("链接地址不能为空");
                    $("#url").focus();
                } else if(!validateURL(long_url)) {
                    $("span.error").text("请输入有效的链接地址");
                    $("#url").focus();
                } else {
                    $("span.error").text("");
                }
            });

            $("input#customer").on("change", function(){
                var customer = $("#customer").val().trim();
                if (customer.length > 0) {
                    if (!validateShort(customer)) {
                        $("span.warning").text("输入的短链接地址不合法");
                        $("#customer").focus();
                    } else if( !validateEnable(customer)) {
                        $("span.warning").text("输入的短链接地址已被使用，请重新输入");
                        $("#customer").focus();
                    } else {
                        $("span.warning").text("");
                    }
                }
            });

            $("button[type=submit]").click(function(){
                $("span.error").text("");
                $("span.warning").text("");
                $("span.info").text("");
                var long_url = $("#url").val().trim();
                var customer = $("#customer").val().trim();
                if (long_url.length < 1) {
                    $("span.error").text("链接地址不能为空");
                    $("#url").focus();
                } else if( !validateURL(long_url)) {
                    $("span.error").text("请输入有效的链接地址");
                    $("#url").focus();
                } else if(customer.length > 0) {
                    if (!validateShort(customer)) {
                        $("span.warning").text("输入的短链接地址不合法");
                        $("#customer").focus();
                    } else if( !validateEnable(customer)) {
                        $("span.warning").text("输入的短链接地址已被使用，请重新输入");
                        $("#customer").focus();
                    } else {
                        createShortUrl(long_url, customer);
                    }
                } else {
                    createShortUrl(long_url, '');
                }
            });

            $("button[type=button].cancel").click(function(){
                $("input").val("");
                $("div.controls > span:not(.info)").text("");
                $("div.controls > span.info").addClass("hide");
            });

            function validateURL(url){
                return /^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(url);
            }

            function validateShort(short){
                return /^\w*$/.test(short);
            }

            function validateEnable(short){
                var filter = ['create', 'jump', 'check'];
                if (short.length < 1)
                    return true;
                if ($.inArray(short.toLowerCase(), filter) != -1)
                    return false;
                var isEnable = true;
                $.ajax({
                    type: "GET",
                    url: "./check.php",
                    async: false,
                    data: {"short": short},
                    success: function(data){
                        isEnable = data.enable;
                    },
                    error: function(){
                        isEnable = false;
                    },
                    dataType: "json"
                });

                return isEnable;
            }

            function createShortUrl(long_url, customer_url){
                $.ajax({
                    type: "GET",
                    url: "./shorter.php/",
                    data: {"url": long_url, 'customer':customer_url},
                    success: function (data) {
                        if (false == data.success) {
                            $("#result").text(data.error).addClass("error").removeClass("hide");
                        } else {
                            $("#result").text(data.short_url).addClass("success").removeClass("hide");
                            $("#result").next("span").removeClass("error").addClass("info").text("当前URL已成功生成过短链接,不可再自定义");
                            if (customer_url.length > 0 && data.short_url.replace("http://mc.cc/", "" != customer_url)) {
                                $("span.info").removeClass("hide");
                            } else {
                                $("span.info").addClass("hide");
                            }
                        }
                    },
                    error: function () {
                        $("span.info").text("网络异常").removeClass("info").addClass("error").removeClass("hide");
                    },
                    dataType: "json"
                })
            }

        });

    </script>
</body>
</html>