<?php
/**
 * 
 * @author landry
 *
 */
class UserManager extends Manager
{
	public function logint($username,$password){
		$db = $this->_db;
		$user = $db->prepare('SELECT user_name FROM users WHERE(user_name = ? AND user_password = ?)');
		$user->execute(array($username,$password));
		$result = $user->fetchAll();
		if (isset($result) AND $result <> NULL){
			$_SESSION['auth'] = $result;
			
			header('Location:index.php?action=adminListPost');
		}
		else{
			$_SESSION['error']='identifiant ou mot de passe incorrect';
			header('Location:index.php?action=login');
		} 


	}
	public function logout(){
		session_destroy();
		header('Location:index.php');
	}
}