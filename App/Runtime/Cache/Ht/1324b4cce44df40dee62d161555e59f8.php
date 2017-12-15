<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>
<link href="/miniwuchuanhxsk/Public/ht/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/miniwuchuanhxsk/Public/ht/js/jquery1.8.js"></script>
<script type="text/javascript" src="/miniwuchuanhxsk/Public/ht/js/action.js"></script>
<script type="text/javascript" src="/miniwuchuanhxsk/Public/ht/js/jCalendar.js"></script>
<script type="text/javascript" src="/miniwuchuanhxsk/Public/ht/js/jquery.XYTipsWindow.min.2.8.js"></script>
<script type="text/javascript" src="/miniwuchuanhxsk/Public/ht/js/mydate.js"></script>
<link href="/miniwuchuanhxsk/Public/ht/css/order.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div class="aaa_pts_show_1">【 全部标签 】</div>


<div class="aaa_pts_show_2">
    <div>
       <!-- <div class="aaa_pts_4"><a href="<?php echo U('order_count');?>?shop_id=<?php echo ($shop_id); ?>">销售统计</a></div> -->
       <div class="aaa_pts_4"><a href="<?php echo U('index');?>">全部标签</a></div>
       <div class="aaa_pts_4"><a href="<?php echo U('add');?>">添加标签</a></div>
    </div>
    
    <div class="aaa_pts_3">
      <form name='form' action="<?php echo U('index');?>" method='post'>
      <div class="pro_4 bord_1">
         <div class="pro_5">
               标签：
              <input type="text" name="tag_name"/>
         </div>
         
         
         <div class="pro_6"><input type="button" class="aaa_pts_web_3" value="搜 索" style="margin:0;" onclick="product_option();"></div>
      </div>
      </form>
      <table class="pro_3">
         <tr class="tr_1">  
           <td style="width:140px;">标签ID</td>
           <td>标签名</td>
           <td style="width:180px;">操作</td>
         </tr>
         <?php if(is_array($tag_list)): $i = 0; $__LIST__ = $tag_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tag): $mod = ($i % 2 );++$i;?><tr>
		      <td><?php echo ($tag['id']); ?></td>
          <td><?php echo ($tag['name']); ?></td>
		   <td>
		      <a href="<?php echo U('add');?>?id=<?php echo ($tag['id']); ?>">修改</a> | 
			  <a onclick="del_id_url(<?php echo ($tag['id']); ?>)">删除</a>
		   </td>
	     </tr><?php endforeach; endif; else: echo "" ;endif; ?>
         <tr>
            <td colspan="10" class="td_2">
                <?php echo ($page); ?> 
             </td>
         </tr>
      </table>
    </div>
    
</div>

<script>
//搜索按钮点击事件
function product_option(){
  $('form').submit(); 
}

//订单删除方法
function del_id_url(id){
   if(confirm("确认删除吗？"))
   {
	  location='<?php echo U("del");?>?id='+id;
   }
}
</script>
</body>
</html>