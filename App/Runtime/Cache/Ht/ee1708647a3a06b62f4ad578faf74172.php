<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>
<link href="/miniwuchuanhxsk/Public/ht/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/miniwuchuanhxsk/Public/ht/js/jquery.js"></script>
<script type="text/javascript" src="/miniwuchuanhxsk/Public/ht/js/action.js"></script>
<script type="text/javascript" src="/miniwuchuanhxsk/Public/plugins/xheditor/xheditor-1.2.1.min.js"></script>
<script type="text/javascript" src="/miniwuchuanhxsk/Public/plugins/xheditor/xheditor_lang/zh-cn.js"></script>
</head>
<body>

<div class="aaa_pts_show_1">【 栏目管理 】</div>

<div class="aaa_pts_show_2">
    
    <div>
       <div class="aaa_pts_4"><a href="<?php echo U('More/pweb_gl');?>">返回</a></div>
    </div>
    <div class="aaa_pts_3">
      <form action="<?php echo U('More/pweb');?>" method="post" onsubmit="return ac_from();">
      <ul class="aaa_pts_5">
         <li>
            <div class="d1">标题名称:</div>
            <div>
              <input class="inp_1" name="uname" id="uname" value="<?php echo ($datas['uname']); ?>"/>
            </div>
         </li>
         <li>
            <div class="d1">内容介绍:</div>
            <div>
              <textarea class="inp_1 inp_2" name="concent" id="concent"/><?php echo ($datas['concent']); ?></textarea>
            </div>
         </li>
         <li>
            <div class="d1">排序:</div>
            <div>
              <input class="inp_1" name="sort" id="sort" value="<?php echo ($datas['sort']); ?>"/>
            </div>
         </li>
         <li>
            <div class="d1">发表时间:</div>
            <div>
              <input class="inp_1" name="addtime" id="addtime" value="<?php echo date("Y-m-d H:i:s",time()); ?>" disabled=""/>
            </div>
         </li>
         
         <li><input type="submit" name="submit" value="提交" class="aaa_pts_web_3" border="0">
         <input type="hidden" name="id" value="<?php echo ($datas['id']); ?>"></li>
      </ul>
      </form>
         
    </div>
    
</div>
<script>
//初始化编辑器
$('#concent').xheditor({
	skin:'nostyle', 
	upImgUrl:'<?php echo U("Upload/xheditor");?>'
});
</script>
</body>
</html>