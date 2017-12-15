<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>
<link href="/miniwuchuanhxsk/Public/ht/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/miniwuchuanhxsk/Public/ht/js/jquery.js"></script>
<script type="text/javascript" src="/miniwuchuanhxsk/Public/ht/js/action.js"></script>
<script type="text/javascript" src="/miniwuchuanhxsk/Public/plugins/xheditor/xheditor-1.2.1.min.js"></script>
<script type="text/javascript" src="/miniwuchuanhxsk/Public/plugins/xheditor/xheditor_lang/zh-cn.js"></script>
<style>
.dx2{color:#090; font-size:16px;  border-bottom:1px solid #CCC; width:100% !important; padding-bottom:8px;}
.img-err {
    position: relative;
    top: 2px;
    left: 82%;
    color: white;
    font-size: 20px;
    border-radius: 16px;
    background: #c00;
    height: 21px;
    width: 21px;
    text-align: center;
    line-height: 20px;
    cursor:pointer;
}
.btn{
            height: 25px;
            width: 60px;
            line-height: 24px;
            padding: 0 8px;
            background: #24a49f;
            border: 1px #26bbdb solid;
            border-radius: 3px;
            color: #fff;
            display: inline-block;
            text-decoration: none;
            font-size: 13px;
            outline: none;
            -webkit-box-shadow: #666 0px 0px 6px;
            -moz-box-shadow: #666 0px 0px 6px;
        }
        .btn:hover{
          border: 1px #0080FF solid;
          background:#D2E9FF;
          color: red;
          -webkit-box-shadow: rgba(81, 203, 238, 1) 0px 0px 6px;
          -moz-box-shadow: rgba(81, 203, 238, 1) 0px 0px 6px;
        }
        .cls{
            background: #24a49f;
        }
</style>

</head>
<body>

<div class="aaa_pts_show_1">【 套餐管理 】</div>

<div class="aaa_pts_show_2">
    <div>
       <div class="aaa_pts_4"><a href="<?php echo U('index');?>">全部套餐</a></div>
       <div class="aaa_pts_4"><a href="<?php echo U('add');?>">添加套餐</a></div>
    </div>
    <div class="aaa_pts_3">
		<form action="<?php echo U('add');?>" method="post" onsubmit="return ac_from();" enctype="multipart/form-data">
		<ul class="aaa_pts_5">
			<li>
				<div class="d1">套餐名称:</div>
				<div>
					<input class="inp_1" name="name" id="name" value="<?php echo ($pro_allinfo["name"]); ?>"/>
				</div>
			</li>
      <li>
        <div class="d1">套餐简介:</div>
        <div>
          <input class="inp_1" name="intro" style="width:350px" id="intro" value="<?php echo ($pro_allinfo["intro"]); ?>"/>
        </div>
      </li>

      <!-- <li>
        <div class="d1">所属商家:</div>
        <div>
          <input class="inp_1" id="partner" value="<?php echo ($shangchang["name"]); ?>" disabled="disabled"/>
          <input type="hidden" name="shop_id" id="shop_id" value="<?php echo ($pro_allinfo["shop_id"]); ?>"/>
          <input type="button" value="选择商家" class="aaa_pts_web_3" style="margin-left:15px;" onclick="win_open('<?php echo U('get_shop');?>',1280,800)">
        </div>
       </li> -->

         <!-- <li class="product">
          <div class="d1">产品品牌:</div>
          <div>
            <select class="inp_1" name="brand_id" id="brand_id" style="width:150px;margin-right:5px;">
                <option value="">选择品牌</option>
                <?php if(is_array($brand_list)): $i = 0; $__LIST__ = $brand_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if($v["id"] == $pro_allinfo['brand_id']): ?>selected="selected"<?php endif; ?>>-- <?php echo ($v["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
              </select>没有可不选
          </div>
        </li> -->
		 
         <li class="product"><div class="d1 dx2">价格管理</div></li>
         <li class="product">
            <div class="d1">门市价:</div>
            <div>
              <input class="inp_1 inp_6" name="marketprice" id="marketprice" value="<?php echo ($pro_allinfo["marketprice"]); ?>"/>
            </div>
         </li>
         <li class="product">
            <div class="d1">现  价:</div>
            <div>
              <input class="inp_1 inp_6" name="price" id="price" value="<?php echo ($pro_allinfo["price"]); ?>"/>
            </div>
         </li>
        <li class="product"><div class="d1 dx2">图片信息</div></li>
		    <li>
          <div style="color:#c00; font-size:14px; padding-left:20px;">上传产品列表缩略图大小:  230*230的图片 &nbsp;&nbsp;&nbsp;只能添加一张图片！！</div>
        </li>
        <li>
          <div class="d1">缩略图:</div>
           <div>
            <?php if ($pro_allinfo['photo']) { ?>
                  <img src="/miniwuchuanhxsk/Data/<?php echo $pro_allinfo['photo']; ?>" width="80" height="80" style="margin-bottom: 3px;" />
                  <br />
              <?php } ?>
              <input type="file" name="photo" id="photo" />
            </div>
         </li>
         <li>
            <div style="color:#c00; font-size:14px; padding-left:20px;">上传产品详情轮播图: 480*200的图片，可添加多张&nbsp;&nbsp;&nbsp;<!-- 可多张 --></div>
         </li>
        <?php if (is_array($img_str)) { ?>
        <li>
          <div class="d1">已上传：</div>
          <?php foreach ($img_str as $v) { ?>
          <div>
            <div class="img-err" title="删除" onclick="del_img('<?php echo $v; ?>',this);">×</div>
            <img src="<?php echo '/miniwuchuanhxsk/Data/'.$v; ?>" width="100" height="100">
          </div>
          <?php } ?>
        </li>
        <?php } ?>
        <li id="imgs_add">
          <div class="d1">轮播图:</div>
          <div>
            <input type="file" name="files[]" style="width:160px;" />
          </div>
        </li>
        <li>
          <div class="d1">&nbsp;</div>
          <div>
             &nbsp;<span class="btn cls" style="background:#D0D0D0; width:40px; color:black;" onclick="upadd();">添加+</span>
          </div>
        </li>
        <li class="product"><div class="d1 dx2">其他信息</div></li>
         <li>
            <div class="d1">备注介绍:</div>
            <div>
              <textarea class="inp_1 inp_2" name="content" id="content"/><?php echo ($pro_allinfo["content"]); ?></textarea>
            </div>
         </li>
        <li>
            <div class="d1">库  存:</div>
            <div>
              <input class="inp_1" style="width:100px;" name="stock" id="stock" value="<?php echo (int)$pro_allinfo['stock']; ?>"/>
            </div>
         </li>
        <!-- <li>
          <div class="d1">人  气:</div>
          <div>
            <input class="inp_1" style="width:100px;" name="renqi" id="renqi" value="<?php echo (int)$pro_allinfo['renqi']; ?>"/>
          </div>
        </li> -->

      <li><input type="submit" name="submit" value="提交" class="aaa_pts_web_3" border="0" id="aaa_pts_web_s">
          <input type="hidden" name="pro_id" id='pro_id' value="<?php echo ($pro_allinfo["id"]); ?>">
      </li>
      </ul>
      </form>
         
    </div>
    
</div>
<script type="text/javascript" src="/miniwuchuanhxsk/Public/ht/js/product.js"></script>
<script>
function upadd(obj){
  //alert('aaa');
  $('#imgs_add').append('<div>&nbsp;&nbsp;<input type="file" style="width:160px;" name="files[]" /><a onclick="$(this).parent().remove();" class="btn cls" style="background:#D0D0D0; width:40px; color:black;"">&nbsp;&nbsp;&nbsp;删除</a></div>');
  return false;
}

function getcid(){
  var cateid = $('#cateid').val();
  $.post('<?php echo U("getcid");?>',{cateid:cateid},function(data){
      if(data.catelist!=''){
        var htmls = '<option value="">二级分类</option>';
        var cate = data.catelist;
        for (var i = 0; i<cate.length; i++) {
          htmls += '<option value="'+cate[i].id+'">-- '+cate[i].name+'</option>';
        }
        $('#cid').html(htmls);
        $('#catedesc').html('&nbsp;&nbsp; * 必选项');
      }else{
        $('#cid').html('<option value="">二级分类</option>');
        $('#catedesc').html('&nbsp;&nbsp; * 该分类下还没有二级分类，请先添加');
      }
    },"json");
}

//图片删除
function del_img(img,obj){
  var pro_id = $('#pro_id').val();
  if (confirm('是否确认删除？')) {
    $.post('<?php echo U("img_del");?>',{img_url:img,pro_id:pro_id},function(data){
      if(data.status==1){
        $(obj).parent().remove();
        return false;
      }else{
        alert(data.err);
        return false;
      }
    },"json");
  };
}

function ac_from(){

  var name=document.getElementById('name').value;
  if(name.length<1){
	  alert('产品名称不能为空');
	  return false;
	} 
  
  // var cid=parseInt(document.getElementById("cid").value);
  // if(!cid){
  //   alert("请选择分类.");
  //   return false;
  // }

  var pid=parseInt(document.getElementById("shop_id").value);
	if(isNaN(pid) || pid<1){
		alert("请选择所属商家");
		return false;
	}
  
}

//初始化编辑器
$('#content').xheditor({
  skin:'nostyle' ,
  upImgUrl:'<?php echo U("Upload/xheditor");?>'
});
</script>
</body>
</html>