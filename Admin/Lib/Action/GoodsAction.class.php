<?php
	class GoodsAction extends CommonAction{
		
		public function goodsInfo(){
			
			$Goods=M('Prizeset');
			
			
			$queryResult = $Goods->select();
			$chance = $Goods->sum('chance');

			import('ORG.Util.Page');// 导入分页类
			$count      = count($queryResult);// 查询满足要求的总记录数
			$Page       = new Page($count,C('GOODSPOINT_PAGE_COUNT'));// 实例化分页类 传入总记录数和每页显示的记录数
			$show       = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			
			
			$list = $Goods->limit($Page->firstRow.','.$Page->listRows)->select();
			
            $this->assign('chance',$chance);
			$this->assign('data',$list);// 赋值数据集
			$this->assign('page',$show);// 赋值分页输出
			$this->display(); // 输出模板
		}
		
		public function searchGoods(){
			
			$where=" where 1=1";
			
			if(!empty($_GET['prizename'])){
				$where.= " and prizename like '%".$_GET['prizename']."%'";
			
			}
			if(!empty($_GET['prizecontent'])){
				$where.= " and prizecontent like '%".$_GET['prizecontent']."%'";
			}
				
			$queryStr ="select * from tp_prizeset".$where;
			
			$Model = new Model(); // 实例化一个model对象 没有对应任何数据表
			
			$queryResult = $Model->query($queryStr);
			
			if($queryResult!=null){
				$parameter = 'prizename='.urlencode($_GET['prizename']).'&prizecontent='.urlencode($_GET['prizecontent']);
					
				import('ORG.Util.Page');// 导入分页类
				$count      = count($queryResult);// 查询满足要求的总记录数
				$Page       = new Page($count,C('GOODSPOINT_PAGE_COUNT'),$parameter);// 实例化分页类 传入总记录数和每页显示的记录数
				$show = $Page->show();// 分页显示输出
				// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			
				$queryStr1 ="select * from tp_prizeset ".$where;
				$queryStr1.=" limit ".$Page->firstRow.",".$Page->listRows;
			
				$list = $Model->query($queryStr1);
                
				$this->assign('data',$list);// 赋值数据集
				$this->assign('page',$show);// 赋值分页输出
			
			}
			
			$this->display('goodsInfo'); // 输出模板
		}
		
		public function addGoods(){
			$GoodsType = M('Goodstype');
			$goodsTypeList= $GoodsType->select();
			$this->assign('goodsTypeList',$goodsTypeList);
			$this->display();
		}
		
		public function doAdd(){
			
			$Goods=M('Prizeset');
			
			$Goods->create();
			
			$lastId=$Goods->add();
			if($lastId){
				$this->success('添加商品成功','goodsInfo');
			}else{
				$this->error('添加商品失败');
			}
		}
		
		public function doDel(){
			$Goods = M('Prizeset');

			$id = $_GET['id'];

			$count = $Goods->delete($id);
			if($count>0){
				$this->success('删除商品成功');
			}else {
				$this->error('删除商品失败');
			}
		}
		
		/*
		 *	显示修改页面
		* */
		public function modifyGoods(){
			$id=$_GET['id'];

			$m=M('Prizeset');
			$arr=$m->find($id);
		
			$this->assign('data',$arr);
			$this->display();
		}
		
		public function doUpdate(){
			$m=M('Prizeset');
			$data['id']=$_POST['id'];
			$data['prizename']=$_POST['prizename'];
			$data['prizecontent']=$_POST['prizecontent'];
			$data['prizenum']=$_POST['prizenum'];
			$data['chance']=$_POST['chance'];
			
			$count=$m->save($data);
			if($count>0){
				$this->success('修改商品成功','goodsInfo');
			}else{
				$this->error('修改商品失败');
			}
		}
		
		
	}
?>
