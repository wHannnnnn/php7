<?php 
function add (){
  $data=array();
  if(empty($_POST['title'])){
    $GLOBALS['message']='请输入内容';
    return;
  }
  if(empty($_POST['artist'])){
    $GLOBALS['message']='请输入歌手名';
    return;
  }
  if(empty($_FILES['images'])){
    $GLOBALS['message']='请上传图片';
    return;
  }
  $images=$_FILES['images'];   //格式 大小 路径
  for ($i=0; $i < count($images['error']); $i++) { 
    if($images['error'][$i]!==UPLOAD_ERR_OK){
     $GLOBALS['message']='请上传图片';
     return;
    }
    $img_types=array('image/jpg','image/png','image/jpeg');
    if( !in_array($images['type'][$i] , $img_types)){
      $GLOBALS['message']='请输入正确的文件格式';
      return;
    }
    if(1*1024*1024<$images['size'][$i]){
      $GLOBALS['message']='文件大小不正确';
      return;
    }
    $img_tmp='../uploads/images/'.uniqid().$images['name'][$i];
    $imageslj[]=substr($img_tmp,2);
    if(!move_uploaded_file($images['tmp_name'][$i],$img_tmp)){
      $GLOBALS['message']='文件路径错误';
      return;
    }
   
  }


  //音乐  格式 大小 路径
   if(empty($_FILES['source'])){
    $GLOBALS['message']='请正确上传文件';
    return;
   }
   $source=$_FILES['source'];
   if($source['error']!==UPLOAD_ERR_OK){
    $GLOBALS['message']='请上传文件';
    return;
   }
   $source_types=array('audio/mp3','audio/wma');
   if(!in_array($source['type'], $source_types)){
    $GLOBALS['message']='文件格式不正确';
    return;
   }
   if($source['size']>20*1024*1024){
    $GLOBALS['message']='文件大小不得超过20M';
    return;
   }
   $source_tmp='../uploads/audio/'.$source['name'];
   if(!move_uploaded_file($source['tmp_name'],$source_tmp)){
    $GLOBALS['message']='文件上传失败';
    return;
   }
   $sourcelj=substr($source_tmp,2);

  $data['id']=uniqid();
  $data['title']=$_POST['title'];
  $data['artist']=$_POST['artist'];
  $data['images']=$imageslj;
  $data['source']=$sourcelj;
  $json=file_get_contents('music.json');
  $jsonde=json_decode($json,true);
  $jsonde[]=$data;
  file_put_contents('music.json',json_encode($jsonde));
  header('Location: ./list.php');



}

if($_SERVER['REQUEST_METHOD']==='POST'){
  add();
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>添加新音乐</title>
  <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
  <div class="container py-5">
    <h1 class="display-4">添加新音乐</h1>
    <hr>
    <?php if (isset($message)): ?>
    <div class="alert alert-danger" role="alert">
      <?php echo $message; ?>
    </div>
    <?php endif ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="form-group">
        <label for="title">标题</label>
        <input type="text" class="form-control" id="title" name="title">
      </div>
      <div class="form-group">
        <label for="artist">歌手</label>
        <input type="text" class="form-control" id="artist" name="artist">
      </div>
      <div class="form-group">
        <label for="images">海报</label>
        <input type="file" class="form-control" id="images" name="images[]" multiple>
      </div>
      <div class="form-group">
        <label for="source">音乐</label>
        <!-- accept 可以限制文件域能够选择的文件种类，值是 MIME Type -->
        <input type="file" class="form-control" id="source" name="source" accept="audio/*">
      </div>
      <button class="btn btn-primary btn-block">保存</button>
    </form>
  </div>
</body>
</html>