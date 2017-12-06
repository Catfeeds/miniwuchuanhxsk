<?php
namespace Ht\Controller;
use Think\Controller;
class HotelController extends PublicController{
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
		$where .= " AND del<1";
		// $shop_id>0 ? $where.=" AND shop_id=$shop_id" : null;

		define('rows',20);
		$count=M('hotel')->where($where)->count();
		$rows=ceil($count/rows);
		$page=(int)$_GET['page'];
		$page<0?$page=0:'';
		$limit=$page*rows;
		$page_index=$this->page_index($count,$rows,$page);
		$list = M('hotel')->where($where)->order('addtime desc')->limit($limit,rows)->select();
		// foreach ($list as $k => $v) {
		// 	$list[$k]['shangchang'] = M('shangchang')->where('id='.intval($v['shop_id']))->getField('name');
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
			$array=array(
				'name'=>$_POST['name'] ,
				'intro'=>$_POST['intro'] ,
				'shop_id'=> intval($_POST['shop_id']) ,//所属店铺
				'brand_id'=> intval($_POST['brand_id']) ,//产品品牌ID
				'price'=>(float)$_POST['price'] , 
				'stock'=>(int)$_POST['stock'] ,			//库存
				'content'=>$_POST['content'] , 
				'wifi'=>$_POST['wifi'] ,
				'bathroom'=>$_POST['bathroom'] ,
				'windows'=>$_POST['windows'] ,
				'people'=>$_POST['people'] ,
				'floor'=>$_POST['floor'] ,
				'area'=>$_POST['area'] ,
				'bedtype'=>$_POST['bedtype'] ,
				'breakfast'=>$_POST['breakfast'] ,
			);
			  
			//判断产品详情页图片是否有设置宽度，去掉重复的100%
			if(strpos($array['content'],'width="100%"')){
				$array['content']=str_replace(' width="100%"','',$array['content']);
			}
			//为img标签添加一个width
			$array['content']=str_replace('alt=""','alt="" width="100%"',$array['content']);
		  
			//上传产品小图
			if (!empty($_FILES["photo"]["tmp_name"])) {
					//文件上传
					$info = $this->upload_images($_FILES["photo"],array('jpg','png','jpeg'),"hotel/".date(Ymd));
				    if(!is_array($info)) {// 上传错误提示错误信息
				        $this->error($info);
				        exit();
				    }else{// 上传成功 获取上传文件信息
					    $array['photo'] = 'UploadFiles/'.$info['savepath'].$info['savename'];
					    $xt = M('hotel')->where('id='.intval($id))->field('photo')->find();
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
			if ($up_arr) {
					$res=array();
					$adv_str = '';
					foreach ($up_arr as $key => $value) {
						$res = $this->upload_images($value,array('jpg','png','jpeg'),"hotel/advimg/".date(Ymd));
					    if(is_array($res)) {
					    	// 上传成功 获取上传文件信息保存数据库
					    	$adv_str .= ','.'UploadFiles/'.$res['savepath'].$res['savename'];
					    }
					}
					$array['adv_img'] = $adv_str;
			}
			
			//执行添加
			if(intval($id)>0){
				$imgs = M('hotel')->where('id='.intval($id))->getField('adv_img');
				if ($imgs && $array['adv_img']) {
					$array['adv_img'] = $imgs.$array['adv_img'];
				}

				//将空数据排除掉，防止将原有数据空置
				foreach ($array as $k => $v) {
					if(empty($v)){
					  	unset($v);
					}
				}

				$sql = M('hotel')->where('id='.intval($id))->save($array);
			}else{
				$array['addtime']=time();
				$sql = M('hotel')->add($array);
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
		$pro_allinfo= $id>0 ? M('hotel')->where('id='.$id)->find() : "";
		//商场信息
		$shangchang= $pro_allinfo ? M('shangchang')->where('id='.intval($pro_allinfo['shop_id']))->find() : "";

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
		$this->assign('shangchang',$shangchang);
		$this->display();

	}

	/*
	* 商品单张图片删除
	*/
	public function img_del(){
		$img_url = trim($_REQUEST['img_url']);
		$pro_id = intval($_REQUEST['pro_id']);
		$check_info = M('hotel')->where('id='.intval($pro_id).' AND del=0')->find();
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
			$res = M('hotel')->where('id='.intval($pro_id))->save($data);
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
		$tj_update=M('hotel')->field('shop_id,is_recomed')->where('id='.intval($pro_id).' AND del=0')->find();
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
		$up = M('hotel')->where('id='.intval($pro_id))->save($data);
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
		$info = M('hotel')->where('id='.intval($id))->find();
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
		$up = M('hotel')->where('id='.intval($id))->save($data);
		if ($up) {
			$this->redirect('index',array('page'=>intval($_REQUEST['page'])));
			exit();
		}else{
			$this->error('操作失败.');
			exit();
		}
	}

}