<!DOCTYPE html>
<html>
<head>
<title><?php echo $title;?></title>
<style>
* {
	margin:0;
	padding:0;
	color:#656565;
	font-size:12px;
    font-family:tahoma,arial,"Hiragino Sans GB",宋体,sans-serif;
}
ul {
	list-style:none;
	padding:15px;
}
li {
	font-size:14px;
}
#wrap{padding:15px;border:1px solid #aaa;margin:15px;box-shadow: 0 0 4px 0 #a9a9a9;border-radius:3px;}
.label{margin-top:10px;padding:5px;background:#ffffff;/*#eaeaea;*/}
.label span{color:#303030;font-weight:bold;font-family:Microsoft YaHei;}
.title{margin-top:10px;padding:5px;background:#ffffff;}
.title span{color:#303030;font-size:24px;}
.text{padding:15px;background:#fafafa;}
.text .message{color:#454545;font-weight:normal;font-size:14px;}
.text .file{color:#000;font-weight:normal;font-size:14px;}
.text .line{color:#F0363A;font-weight:bold;font-size:14px;}
.bottom{margin-top:10px;color:#000;}
#delay{color:#000;}
</style>
</head>
<body>
<div id="wrap">
    <div class="title"><span><?php echo $title;?></span></div>
    <!--<div class="label"><span>错误信息</span></div>-->
    <div class="text"><span class="message"><?php echo $msg;?></span></div>
    <div class="bottom"><span><b id="delay"><?php echo $delay;?></b>秒钟后自动跳转</span> <a id="go" href="<?php echo $go==''?'javascript:window.history.go(-1);':$go; ?>">点击跳转</a></div>
</div>
<script>
    var delay = <?php echo $delay;?>;
    var interval = setInterval(function() {
        delay--;
        document.getElementById('delay').innerHTML = delay;
        if(delay<=0) {
            clearInterval(interval);
            document.getElementById('go').click();
        }
    }, 1000)
</script>
</body>
</html>