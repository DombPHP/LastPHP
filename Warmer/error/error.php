<!DOCTYPE html>
<html>
<head>
<title><?php echo $error['type'];?></title>
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
</style>
</head>
<body>
<div id="wrap">
    <div class="title"><span><?php echo strtoupper($error['type']);?></span></div>
    <div class="label"><span>错误信息</span></div>
    <div class="text"><span class="message"><?php echo $error['message'];?></span></div>
    <div class="label"><span>错误位置</span></div>
    <div class="text" style="font-size:14px;"><span class="file"><?php echo $error['file'];?></span> line <span class="line"><?php echo $error['line'];?></span></div>
    <?php if(!empty($error['trace'])){ ?>
    <div class="label"><span>跟踪信息</span></div>
    <ul>
    <?php foreach($error['trace'] as $val){
		echo '<li>'.$val.'</li>';
	}?>
    </ul>
    <?php } ?>
    <div style="margin-top:10px;color:#000;"><?php echo '<a href="'.LAST_LINK.'" style="text-decoration:none;" target="_blank"><b>LastPHP</a> 0.2.0</b> &nbsp;&nbsp; © 2015 LastPHP.com 版权所有'?></div>
</div>
</body>
</html>
