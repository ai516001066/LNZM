<?php

namespace Admin\Controller;

use Think\Controller;

class UserController extends Controller {
	
	// public function _initialize(){
	// if(!isset($_SESSION['userAccount'])){
	// $this->error('请先登录 ! ','/Login/login');
	// }
	// }
	public function userSetting() {
		if (! isset ( $_SESSION ['userId'] )) {
			$this->error ( C ( 'LOGIN_FIRST' ) );
		}
		$this->assign('APPLICATION_NAME',C('APPLICATION_NAME'));
		$this->assign('USER_ID',$_SESSION ['userId']);
		$this->assign('USER_LEVEL',$_SESSION ['userLevel']);
		$this->assign('CURRENT_MENU','USER');
		if ($_SESSION ['userId'] != $_GET ['userid']) {
			$this->error ( C ( 'LOGIN_FIRST' ) );
		}
		// 进入个人设置
		
		$user = M ( 'user' );
		$tempUser = $user->where ( 'userid=' . $_SESSION ['userId'] )->limit ( 1 )->select ();
		// dump($tempUser);
		// return;
		$academy = M ( 'academy' );
		$branch = M ( 'branch' );
		$listAllAcademy = $academy->select ();
		$listAllBranch = $branch->select ();
		//将学院和支部转化成信息
		for($j = 0; $j < count ( $listAllAcademy ); $j ++) {
			if ($tempUser [0] ['useracademy'] == $listAllAcademy [$j] ['academyid']) {
				$tempUser [0] ['useracademy'] = $listAllAcademy [$j] ['academyname'];
				break;
			}
			$tempUser [0] ['useracademy'] = '无学院信息';
		}
		for($j = 0; $j < count ( $listAllBranch ); $j ++) {
			if ($tempUser [0] ['userbranch'] == $listAllBranch [$j] ['branchid']) {
				$tempUser [0] ['userbranch'] = $listAllBranch [$j] ['branchname'];
				break;
			}
			$tempUser [0] ['userbranch'] = '无支部信息';
		}
		
		$this->assign ( "tempUser", $tempUser );
		$this->show ();
	}
	public function userSettingSubmit() {
		if (! isset ( $_SESSION ['userId'] )) {
			$this->error ( C ( 'LOGIN_FIRST' ) );
		}
		$this->assign('APPLICATION_NAME',C('APPLICATION_NAME'));
		$this->assign('USER_ID',$_SESSION ['userId']);
		$this->assign('USER_LEVEL',$_SESSION ['userLevel']);
		$this->assign('CURRENT_MENU','USER');
		
		$data ['userId'] = $_GET ['userid'];
		$data ['userNickname'] = $_POST ['userNickname'];
		$exUserPassword = $_POST ['exUserPassword'];
		$data ['userMail'] = $_POST ['userMail'];
		
		$user = M ( 'user' );
		$tempUser = $user->where ( 'userid=' . $_GET ['userid'] )->limit ( 1 )->select ();
		if($_POST ['newUserPassword'] != null){
			//输入了密码，需要修改密码
			if(strcmp($tempUser[0]['userpassword'], $exUserPassword) == 0){
				//密码相同，可以修改
				$data ['userPassword'] = $_POST ['newUserPassword'];
			}else{
				$this->error ( '输入的旧密码不正确' );
				return;
			}

		}
		
// 		dump($tempUser);
// 		dump($data);
// 		return;
		$result = $user->save ( $data );
		if ($result !== false) {
			$this->success ( C ( 'EDIT_SUCCESS' ));
		} else {
			$this->error ( C ( 'EDIT_FAIL' ) );
		}
		
		// $this->show();
	}
}
