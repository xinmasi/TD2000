<?
$SESSION_WRITE_CLOSE = 0;
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/itask/itask.php");
include_once("inc/utility_cache.php");
include_once("avatar.function.php");


/***********���ݲ����ü�ͼƬ*******************/

$id   = substr($id,0,strlen($id)-1);
$name = substr($name,0,strlen($name)-1);

$FILE_PATH = attach_real_path($id, $name);
$pathinfo  = pathinfo($FILE_PATH);

$geneAvatar!="only"?$model = "photo":$model = "avatar";


//ȡ��ʱɾ����ʱ�ļ�
if($action =="delete")
{
    $result = @unlink($FILE_PATH);
    exit;
}


//��Ƭ�ϴ�
if($geneAvatar!="only")
{
    //���ݳߴ�
    if($originW=="200" && $originH=="200")
    {
        $filetype = ".".$pathinfo['extension'];
        
        if($geneAvatar =="true")
        {
            if(file_exists($FILE_PATH)){
                $img_type = exif_imagetype($FILE_PATH);
            }
            switch ($img_type){
                case IMAGETYPE_JPEG :
                    $im = imagecreatefromjpeg($FILE_PATH);
                    break;
                case IMAGETYPE_PNG :
                    $im = imagecreatefrompng($FILE_PATH);
                    break;
                case IMAGETYPE_GIF :
                    $im = imagecreatefromgif($FILE_PATH);
                    break;
                default:
                    echo "����ͼ�����!";
                    exit();
            }
            resizeImage($im,"64","64",$_SESSION["LOGIN_UID"],$filetype,"avatar");
        }
        //ת���ļ�
        $dst_file =  MYOA_ATTACH_PATH.$model. '/'.$_SESSION["LOGIN_UID"].'.' . $pathinfo['extension'];
        rename($FILE_PATH,$dst_file);
    }
    else
    {
        //��������
        if($originW!=$compressW)
        {
            $scale = $originW/round($compressW);
        }else
        {
            $scale = 1;
        }
        $x_new = round($x*$scale);
        $y_new = round($y*$scale);
        $w_new = round($w*$scale);
        $h_new = round($h*$scale);
        
        $file_new = thumb($FILE_PATH,$w_new,$h_new,0,'',$x_new,$y_new,$model);
        
        $file_new_array = explode(".",$file_new);
        
        $filetype = ".".$file_new_array[1];
        if(file_exists($FILE_PATH)){
			$img_type = exif_imagetype($FILE_PATH);
		}
        switch ($img_type){
            case IMAGETYPE_JPEG :
                $im = imagecreatefromjpeg($file_new);
                break;
            case IMAGETYPE_PNG :
                $im = imagecreatefrompng($file_new);
                break;
            case IMAGETYPE_GIF :
                $im = imagecreatefromgif($file_new);
                break;
            default:
                echo "����ͼ�����!";
                exit();
        }
        resizeImage($im,"200","200",$_SESSION["LOGIN_UID"],$filetype,"photo");
        
        if($geneAvatar =="true")
        {
            resizeImage($im,"64","64",$_SESSION["LOGIN_UID"],$filetype,"avatar");
        }
        //ɾ���ü�����ʱͼƬ
        $result = @unlink($file_new); 
    }
    
}
else
{
    //Сͷ��
    if($isGif =="true")
    {
        //ת���ļ�
        $filetype = ".".$pathinfo['extension'];
        $dst_file =  MYOA_ATTACH_PATH.$model. '/'.$_SESSION["LOGIN_UID"].'.' . $pathinfo['extension'];
        rename($FILE_PATH,$dst_file);    
    }
    else
    {
        if($originW=="200" && $originH=="200")
        {
            if(file_exists($FILE_PATH)){
				$img_type = exif_imagetype($FILE_PATH);
			}
            switch ($img_type){
                case IMAGETYPE_JPEG :
                    $im = imagecreatefromjpeg($FILE_PATH);
                    break;
                case IMAGETYPE_PNG :
                    $im = imagecreatefrompng($FILE_PATH);
                    break;
                case IMAGETYPE_GIF :
                    $im = imagecreatefromgif($FILE_PATH);
                    break;
                default:
                    echo "����ͼ�����!";
                    exit();
            }
            
            $filetype = ".".$pathinfo['extension'];    
            resizeImage($im,"64","64",$_SESSION["LOGIN_UID"],$filetype,"avatar");
        }else
        {
            //��������
            if($originW!=$compressW)
            {
                $scale = $originW/round($compressW);
            }else
            {
                $scale = 1;
            }
            $x_new = round($x*$scale);
            $y_new = round($y*$scale);
            $w_new = round($w*$scale);
            $h_new = round($h*$scale);
            
            $file_new = thumb($FILE_PATH,$w_new,$h_new,0,'',$x_new,$y_new,$model);
            
            $file_new_array = explode(".",$file_new);
            
            $filetype = ".".$file_new_array[1];
            if(file_exists($FILE_PATH)){
				$img_type = exif_imagetype($FILE_PATH);
			}
            switch ($img_type){
                case IMAGETYPE_JPEG :
                    $im = imagecreatefromjpeg($file_new);
                    break;
                case IMAGETYPE_PNG :
                    $im = imagecreatefrompng($file_new);
                    break;
                case IMAGETYPE_GIF :
                    $im = imagecreatefromgif($file_new);
                    break;
                default:
                    echo "����ͼ�����!";
                    exit();
            }
            resizeImage($im,"64","64",$_SESSION["LOGIN_UID"],$filetype,"avatar");
            //ɾ���ü�����ʱͼƬ
            $result = @unlink($file_new);    
        }    
    }
}

//ɾ����ʱ�ļ�
$result = @unlink($FILE_PATH);

$attachment = $_SESSION["LOGIN_UID"].$filetype;

$where = "";
if($geneAvatar!="only")
{
    $where  .= "PHOTO='$attachment'";
    if($geneAvatar =="true")
    {
        $where  .= ",AVATAR='$attachment'";
        $_SESSION['LOGIN_AVATAR'] = $attachment;
    }
}else
{
    $where  .= "AVATAR='$attachment'";
    $_SESSION['LOGIN_AVATAR'] = $attachment;
}
 
$query="update USER set $where where UID='".$_SESSION["LOGIN_UID"]."' ";
exequery(TD::conn(),$query);
cache_users();

updateUserCache($_SESSION["LOGIN_UID"]);
echo "ok";

?>
