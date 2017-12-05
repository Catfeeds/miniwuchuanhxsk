<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>
<link href="/miniwuchuanhxsk/Public/ht/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/miniwuchuanhxsk/Public/ht/js/jquery-1.8.3.min.js"></script>
</head>
<body>

<div class="aaa_pts_show_1">【 首页图标管理 】</div>

<div class="aaa_pts_show_2">

    <div class="aaa_pts_3">
      <form action="<?php echo U('saveimg');?>" method="post" onsubmit="return ac_from();" enctype="multipart/form-data">
      <ul class="aaa_pts_5">
        <?php if ($info['link']=='list') { ?>
         <li>
            <div class="d1">产品分类:</div>
            <div>
              <select class="inp_1" name="ptype" id="ptype" onchange="getcatname();">
              <!-- 遍历 -->
      				<?php if(is_array($procat)): $i = 0; $__LIST__ = $procat;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" data-name="<?php echo ($v["name"]); ?>" <?php if($v["id"] == $info['ptype']): ?>selected="selected"<?php endif; ?>><?php echo ($v["name"]); ?></option>
                <?php if(is_array($v['list'])): $i = 0; $__LIST__ = $v['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vs["id"]); ?>" data-name="<?php echo ($vs["name"]); ?>" <?php if($vs["id"] == $info['ptype']): ?>selected="selected"<?php endif; ?>>&nbsp;&nbsp;-- <?php echo ($vs["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
      			  <!-- 遍历 -->
              </select><?php if(count($procat)<=0) { ?><span style="font-size:14px;color:gray;">&nbsp;&nbsp;*&nbsp;请先添加产品分类</span><?php } ?>
            </div>
         </li>
         <?php } ?>
        <!--  <li>
            <div class="d1">显示名称:</div>
            <div>
              <input class="inp_1" name="name" id="name" value="<?php echo ($info["name"]); ?>"/>
            </div>
         </li>
         <li><div style="color:#c00; font-size:14px; padding-left:20px;">图标尺寸 100px*100px透明底图</div></li> -->
         <li data-index="0">
            <div class="d1">图片:</div>
            <div>
              <?php if ($info['photo']) { ?>
                <img src="/miniwuchuanhxsk/Data/<?php echo $info['photo']; ?>" width="500" height="300" /><br /><br />
              <?php } ?>
              <input type="file" name="file" id="photo" />
            </div>
         </li>
         <!-- <li>
            <div class="d1">排  序:</div>
            <div>
              <input class="inp_1" name="sort" id="sort" value="<?php echo ($info["sort"]); ?>"/>
            </div><span style="font-size:14px;color:gray;"></span>
         </li> -->
         <li>
          <input type="submit" name="submit" value="提交" class="aaa_pts_web_3" border="0">
          <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
         </li>
      </ul>
      </form>
         
    </div>
    
</div>
<script>
function getcatname () {
  var names = $("#ptype").find("option:selected").attr("data-name");
  $("#name").val(names);
}
</script>
</body>
</html>