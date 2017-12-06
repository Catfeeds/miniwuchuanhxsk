<?php
namespace Ht\Controller;
use Think\Controller;
class ManageController extends PublicController{
	//***********************************************
    public static $Array;//这个给检查产品的字段用 
    public static $PRO_FENLEI; //这个给产品分类打勾用
	//**************************************************
	//**********************************************
	//说明：产品列表管理 推荐 修改 删除 列表 搜索
	//**********************************************
	public function index(){
		// $shop_id=(int)$_GET['shop_id'];

		//搜索
		$where = '1=1';
		$name = $_GET['name'];
		if($name){
			$where .= ' AND name like "%'.$name.'%"';
			$this->assign('name',$name);
		}
		$tuijian = $_GET['tuijian'];
		if($tuijian != ''){
			$where .= ' AND is_recomed='.intval($tuijian);
			$this->assign('tuijian',$tuijian);
		}
		$where .= " AND (pro_type=2 OR pro_type=3) AND del<1";
		// $shop_id>0 ? $where.=" AND shop_id=$shop_id" : null;

		define('rows',20);
		$count=M('product2')->where($where)->count();
		$rows=ceil($count/rows);
		$page=(int)$_GET['page'];
		$page<0?$page=0:'';
		$limit=$page*rows;
		$page_index=$this->page_index($count,$rows,$page);
		$list = M('product2')->where($where)->order('addtime desc')->limit($limit,rows)->select();
		// foreach ($list as $k => $v) {
		// 	$list[$k]['shangchang'] = M('shangchang')->where('id='.intval($v['shop_id']))->getField('name');
		// 	if (intval($v['pro_type'])==2) {
		// 		$list[$k]['cname'] = '桑拿沐足';
		// 	}elseif (intval($v['pro_type'])==3) {
		// 		$list[$k]['cname'] = '美食';
		// 	}else{
		// 		$list[$k]['cname'] = '其他';
		// 	}
		// }

		//==========================
		// 将GET到的数据再输出
		//==========================
		// $this->assign('shop_id',$shop_id);
		$this->assign('page',$page);
		//=============
		// 将变量输出
		//=============	
		$this->assign('list',$list);
		$this->assign('page_index',$page_index);
		$this->display();
	}
	//**********************************************
	//说明：产品 添加修改
	//注意：cid 分类id  shop_id店铺id
	//**********************************************
	public function add(){	

		$id=(int)$_GET['id'];
		$page=(int)$_GET['page'];
		if($_POST['submit']==true){
		try{	
			//如果不是管理员则查询商家会员的店铺ID
			$id = intval($_POST['pro_id']);
			// if (!intval($_POST['shop_id'])) {
			// 	$this->error('请选择所属商家.');
			// 	exit();
			// }
			
			$array=array(
				'name'=>$_POST['name'] ,
				'pro_type' => 2,
				'intro'=>$_POST['intro'] ,
				'shop_id'=> intval($_POST['shop_id']) ,//所属店铺
				'price'=>(float)$_POST['price'] , 
				'marketprice'=>(float)$_POST['marketprice'] , 
				'stock'=>(int)$_POST['stock'] ,			//库存
				'content'=>$_POST['content'] , 
				'renqi'=>(int)$_POST['renqi'] ,
			);

			// $shop_id = intval($array['shop_id']);
			// $tid = intval(M('shangchang')->where('id='.intval($shop_id))->getField('tid'));
			// if ($tid==3) {
			// 	//KTV
			// 	$array['pro_type'] = 1;
			// }elseif ($tid==11) {
			// 	//桑拿沐足
			// 	$array['pro_type'] = 2;
			// }elseif ($tid==14) {
			// 	//美食
			// 	$array['pro_type'] = 3;
			// }
			  
			//判断产品详情页图片是否有设置宽度，去掉重复的100%
			if(strpos($array['content'],'width="100%"')){
				$array['content']=str_replace(' width="100%"','',$array['content']);
			}
			//为img标签添加一个width
			$array['content']=str_replace('alt=""','alt="" width="100%"',$array['content']);
		  
			//上传产品小图
			if (!empty($_FILES["photo"]["tmp_name"])) {
					//文件上传
					$info = $this->upload_images($_FILES["photo"],array('jpg','png','jpeg'),"product/".date(Ymd));
				    if(!is_array($info)) {// 上传错误提示错误信息
				        $this->error($info);
				        exit();
				    }else{// 上传成功 获取上传文件信息
					    $array['photo'] = 'UploadFiles/'.$info['savepath'].$info['savename'];
					    $xt = M('product2')->where('id='.intval($id))->field('photo')->find();
					    if ($id && $xt['photo']) {
					    	$img_url = "Data/".$xt['photo'];
							if(file_exists($img_url)) {
								@unlink($img_url);
							}
					    }
				    }
			}

			//多张商品轮播图上传
		  	$up_arr = array();
			if (!empty($_FILES["files"]["tmp_name"])) {
					foreach ($_FILES["files"]['name'] as $k => $val) {
						$up_arr[$k]['name'] = $val;
					}

					foreach ($_FILES["files"]['type'] as $k => $val) {
						$up_arr[$k]['type'] = $val;
					}

					foreach ($_FILES["files"]['tmp_name'] as $k => $val) {
						$up_arr[$k]['tmp_name'] = $val;
					}

					foreach ($_FILES["files"]['error'] as $k => $val) {
						$up_arr[$k]['error'] = $val;
					}

					foreach ($_FILES["files"]['size'] as $k => $val) {
						$up_arr[$k]['size'] = $val;
					}
			}

				$adv_str = '';
				if ($up_arr) {
					$res=array();
					foreach ($up_arr as $key => $value) {
						$res = $this->upload_images($value,array('jpg','png','jpeg'),"product/advimg/".date(Ymd));
					    if(is_array($res)) {
					    	// 上传成功 获取上传文件信息保存数据库
					    	$adv_str .= ','.'UploadFiles/'.$res['savepath'].$res['savename'];
					    }
					}
				}
			
			//执行添加
			if(intval($id)>0){
				$adv_img = M('product2')->where('id='.intval($id))->getField('adv_img');
				if ($adv_str!='') {
					if ($adv_img!='') {
						$array['adv_img'] = $adv_img.$adv_str;
					}else{
						$array['adv_img'] = $adv_str;
					}
				}

				//将空数据排除掉，防止将原有数据空置
				foreach ($array as $k => $v) {
					if(empty($v)){
					  	unset($v);
					}
				}

				$sql = M('product2')->where('id='.intval($id))->save($array);
			}else{
				$array['addtime']=time();
				$sql = M('product2')->add($array);
				$id=$sql;
			}

			//规格操作
			if($sql){//name="guige_name[]
				$this->success('操作成功!','index');
				exit();
			}else{
				throw new \Exception('操作失败.');
			}
			  
			}catch(\Exception $e){
				echo "<script>alert('".$e->getMessage()."');location='{:U('index')}?shop_id=".$shop_id."';</script>";
			}
		}

		//=========================
		// 查询产品信息
		//=========================
		$pro_allinfo= $id>0 ? M('product2')->where('id='.$id)->find() : "";
		//商场信息
		// $shangchang= $pro_allinfo ? M('shangchang')->where('id='.intval($pro_allinfo['shop_id']))->find() : "";

		//获取所有商品轮播图
		if ($pro_allinfo['adv_img']) {
			$img_str = explode(',', trim($pro_allinfo['adv_img'],','));
			$this->assign('img_str',$img_str);
		}

		//=========================
		// 查询所有品牌
		//=========================
		$brand_list = M('brand')->where('1=1')->field('id,name')->select();
		$this->assign('brand_list',$brand_list);

		//==========================
		// 将GET到的数据再输出
		//==========================
		$this->assign('id',$id);
		$this->assign('shop_id',$shop_id);
		$this->assign('page',$page);
		//=============
		// 将变量输出
		//=============	
		$this->assign('pro_allinfo',$pro_allinfo);
		// $this->assign('shangchang',$shangchang);
		$this->display();

	}

	/*
	* 商品单张图片删除
	*/
	public function img_del(){
		$img_url = trim($_REQUEST['img_url']);
		$pro_id = intval($_REQUEST['pro_id']);
		$check_info = M('product2')->where('id='.intval($pro_id).' AND del=0')->find();
		if (!$check_info) {
			echo json_encode(array('status'=>0,'err'=>'产品不存在或已下架删除！'));
			exit();
		}

		$arr = explode(',', trim($check_info['adv_img'],','));
		if (in_array($img_url, $arr)) {
			foreach ($arr as $k => $v) {
				if ($img_url===$v) {
					unset($arr[$k]);
				}
			}
			$data = array();
			$data['adv_img'] = implode(',', $arr);
			$res = M('product2')->where('id='.intval($pro_id))->save($data);
			if (!$res) {
				echo json_encode(array('status'=>0,'err'=>'操作失败！'.__LINE__));
				exit();
			}
			//删除服务器上传文件
			$url = "Data/".$img_url;
			if (file_exists($url)) {
				@unlink($url);
			}

			echo json_encode(array('status'=>1));
			exit();
		}else{
			echo json_encode(array('status'=>0,'err'=>'操作失败！'.__LINE__));
			exit();
		}
	}

	//***************************
	//说明：产品 设置推荐
	//***************************
	public function set_tj(){
		$pro_id = intval($_REQUEST['pro_id']);
		$tj_update=M('product2')->field('shop_id,is_recomed')->where('id='.intval($pro_id).' AND del=0')->find();
		if (!$tj_update) {
			$this->error('产品不存在或已下架删除！');
			exit();
		}

		// $shopinfo = M('shangchang')->where('id='.intval($tj_update['shop_id']))->find();
		//查status,不符合条件不给通过
		// if(intval($shopinfo['status']) != 1) { 
		//     $this->error('商家未通过审核，产品不能设置推荐.');
		//     exit;
		// }

		//查推荐type
		//dump($tj_update);
		$data = array();
		$data['is_recomed'] = $tj_update['is_recomed']==1 ? 0 : 1;
		$up = M('product2')->where('id='.intval($pro_id))->save($data);
		if ($up) {
			$this->redirect('index',array('page'=>intval($_REQUEST['page'])));
			exit();
		}else{
		    $this->error('操作失败！');
			exit();
		}
	}


	//***************************
	//说明：产品 删除
	//***************************
	public function del()
	{
		$id = intval($_REQUEST['did']);
		$info = M('product2')->where('id='.intval($id))->find();
		if (!$info) {
			$this->error('产品信息错误.'.__LINE__);
			exit();
		}

		if (intval($info['del'])==1) {
			$this->success('操作成功！.'.__LINE__);
			exit();
		}

		$data=array();
		$data['del'] = $info['del'] == '1' ?  0 : 1;
		$up = M('product2')->where('id='.intval($id))->save($data);
		if ($up) {
			$this->redirect('index',array('page'=>intval($_REQUEST['page'])));
			exit();
		}else{
			$this->error('操作失败.');
			exit();
		}
	}

	//***************************
	//说明：获取商家
	//***************************
	public function get_shop(){
		//===================
		// GET获得的数据集合
		//===================
		$id=(int)$_GET['id'];
		$type=$_GET['type'];
		$tuijian=$_GET['tuijian']!=NULL ? (int)$_GET['tuijian'] : '';
		$name=$this->htmlentities_u8($_GET['name']);
		$sheng=$_GET['sheng']!=NULL ? (int)$_GET['sheng'] : '';
		$city=$_GET['city']!=NULL ? (int)$_GET['city'] : '';
		$quyu=$_GET['quyu']!=NULL ? (int)$_GET['quyu'] : '';
		
		//===================================================
		// 查询省市区数据,先将省查出来,后面用ajax+js将市区补上
		//===================================================
		$output_sheng=$this->city_option($sheng,0,1);
		$output_city=$this->city_option($city,$sheng);
		$output_quyu=$this->city_option($quyu,$city);

		//===============================
		// 数据查询和搜索
		//===============================
		$where="status=1 AND tid>3";
		$name!='' ? $where.=" AND name like '%$name%'" : null;
		$tuijian!=='' ? $where.=" AND type=$tuijian" : null;
		//地区搜索
		if($quyu>0){
			$where.=" AND quyu=$quyu";
		}elseif($city>0){
			$where.=" AND city=$city";
		}elseif($sheng>0){
			$where.=" AND sheng=$sheng";
		}

		define('rows',20);
		$count=M('shangchang')->where($where)->count();
		$rows=ceil($count/rows);
		$page=(int)$_GET['page'];
		$page<0?$page=0:'';
		$limit=$page*rows;
		$page_index=$this->page_index($count,$rows,$page);
		$shangchang=M('shangchang')->where($where)->order('addtime desc')->limit($limit,rows)->select();
		//组装数据
		foreach ($shangchang as $k => $v) {
			$shangchang[$k]['cname'] = M('category')->where('id='.intval($v['tid']))->getField('name');
			$shangchang[$k]['zn-sheng'] = M('ChinaCity')->where('id='.intval($v['sheng']))->getField('name');
		}
		
		//==========================
		// 将GET到的数据再输出
		//==========================
		$this->assign('tuijian',$tuijian);
		$this->assign('name',$name);
		$this->assign('type',$type);
		$this->assign('sheng',$sheng);
		$this->assign('city',$city);
		$this->assign('quyu',$quyu);
		//=============
		// 将变量输出
		//=============	
		$this->assign('output_sheng',$output_sheng);
		$this->assign('output_city',$output_city);
		$this->assign('output_quyu',$output_quyu);
		$this->assign('page_index',$page_index);
		$this->assign('shangchang',$shangchang);
		$this->display();
	}

}