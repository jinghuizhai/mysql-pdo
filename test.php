<?php
	require 'zjhsql.php';

	$db = Zjhsql::getInstance('localhost','mytable','root','123456');
	//查询操作
	$result = $db->queryOne('select * from `user` where name=? and pass=?',array($name,$pass));
	//一次查询多个
	$result = $db->query('select * from `user` where user_id > ?',$user_id);
	//插入操作
	$result = $db->exec('insert into `user` set name=?,pass=?',array($name,$pass));
	//更新,这次换个方式
	$result = $db->exec('update `user` set name=:name where user_id=:user_id',array(
		'name' => $name,
		'user_id' => $user_id
	));
	//删除
	$result = $db->exec('delete from `user` where user_id=1');
	//事务
	$db->begin();
	try{
		$db->exec('insert into `user` set name=?',$name);
		throw new Exception("Error sql execute error");
		$db->commit();
	}catch(Exception $e){
		$db->rollback();
	}
	//上面的代码是不可能提交的，因为总是有异常，处理异常的时候就事务回滚。
	//分页
	$current = 2;//当前页
	$url = "?p=";//分页链接
	$limit = 20;//每页信息条数
	$result = $db->query('select * from `user` where name like "%"?"%" ',$name,array($current,'?p=',$limit));
	$pagination = $db->getPage();
	echo $pagination;
	//分页的功能可以在zjhsql.php修改，因为每个系统的路由分发是不同的。
?>
