<?php 
if(empty($_GET['id'])){
	exit('xx');
}
$id=$_GET['id'];
$json=file_get_contents('music.json');
$arrj=json_decode($json,true);

foreach ($arrj as $value) {
   if($value['id']==$id){
   	$index=array_search($value, $arrj);
   	array_splice($arrj,$index,1);
   	$newarrj=json_encode($arrj);
   	file_put_contents('music.json',$newarrj);
   	break;
   }
}
header('Location: list.php');


 ?>
