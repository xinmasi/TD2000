<?
    include_once("inc/auth.inc.php");
    $HTML_PAGE_TITLE = _("图片预览");
    include_once("inc/header.inc.php");
    include_once 'inc/utility_file.php';

    //默认的图片宽度
    $MEDIUM_IMAGE_SIZE = array(
        'w' => 1024,
        'h' => 768
    );

    $URL_FILE_NAME = strip_tags($URL_FILE_NAME);

    $IMG_TYPE_STR="gif,jpg,png,swf,swc,tiff,bmp,iff,jp2,jpx,jb2,jpc,xbm,wbmp,tif"; //允许文件类型

    if($ASC_DESC=="") //4升序  3降序
        $ASC_DESC=4;

    if($VIEW_TYPE=="") //默认按照文件名排列
        $VIEW_TYPE="NAME";

    if(!in_array($ASC_DESC, array(3,4)) || !in_array($VIEW_TYPE, array('NAME','TYPE','TIME','SIZE'))){
        Message(_("错误"), _("传递参数错误"));
        exit;
    }

    if($SUB_DIR!="")
    {
        $FOLDER = $SUB_DIR=urldecode($SUB_DIR);
        $SUBDIR=substr($SUB_DIR,0,strrpos($SUB_DIR,"/"));
    }

    //读取新建图片目录路径及名称
    if($CUR_DIR=="")
    {
        $query = "SELECT PIC_NAME,PIC_PATH,ROW_PIC_NUM,ROW_PIC from PICTURE where (find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_DEPT_ID) OR TO_DEPT_ID='ALL_DEPT' or  find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',TO_PRIV_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_USER_ID)) and PIC_ID='$PIC_ID'";
        $cursor= exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
           $PIC_NAME=$ROW["PIC_NAME"];
           $PIC_PATH=$ROW["PIC_PATH"];
           $TD_COUNT = $ROW["ROW_PIC_NUM"]==0?7:$ROW["ROW_PIC_NUM"];//每行图片数量
           $ROW_PIC = $ROW["ROW_PIC"]==0?5:$ROW["ROW_PIC"];
           $ONE_PAGE_PICS = $TD_COUNT * $ROW_PIC;// 每页文件或文件夹数量
        }
        else
           exit;

        if(strstr($SUB_DIR,"..") || strstr($SUB_DIR,"./"))
           exit;

        //当前目录路径
        if(substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
           $CUR_DIR = $PIC_PATH.$SUB_DIR;
        else
           $CUR_DIR = $PIC_PATH."/".$SUB_DIR;

        if(stristr($CUR_DIR,".."))
        {
            Message(_("错误"),_("参数含有非法字符。"));
            exit;
        }
    }
    else
    {
        $query = "SELECT PIC_NAME,PIC_PATH,PIC_ID,ROW_PIC_NUM,ROW_PIC,TO_PRIV_ID from PICTURE where (find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_DEPT_ID) OR TO_DEPT_ID='ALL_DEPT' or  find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',TO_PRIV_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_USER_ID)) and PIC_ID='$PIC_ID'";
        $cursor= exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
        {
            $PIC_PATH = $ROW["PIC_PATH"];
            if(stristr($CUR_DIR,$PIC_PATH))
            {
                $PIC_ID = $ROW["PIC_ID"];
                $PIC_NAME = $ROW["PIC_NAME"];
                $TD_COUNT = $ROW["ROW_PIC_NUM"]==0?7:$ROW["ROW_PIC_NUM"];//每行图片数量
                $ROW_PIC = $ROW["ROW_PIC"]==0?5:$ROW["ROW_PIC"];
                $ONE_PAGE_PICS = $TD_COUNT * $ROW_PIC;// 每页文件或文件夹数量
                $TEMP = $CUR_DIR;
                $TEMP = str_ireplace($PIC_PATH,"",$TEMP);
                $TEMP = substr($TEMP,1);
                if(strlen($CUR_DIR)!=strlen($PIC_PATH))
                   $SUB_DIR = $TEMP;
                $SUBDIR=substr($SUB_DIR,0,strrpos($SUB_DIR,"/"));
                break;
            }
        }
    }

    //------------------
    $SORT_COUNT = 0;  //当前目录文件夹数量
    $FILE_COUNT = 0;  //当前文件夹数量
    $dh = @opendir(iconv2os($CUR_DIR));
    if($dh === FALSE)
    {
        Message(_("错误"), _("目录路径错误"));
        exit;
    }
    
    while(false !== ($FILE_NAME = @readdir($dh)))
    {
        $FILE_NAME = iconv2oa($FILE_NAME);

        if($FILE_NAME=='.' || $FILE_NAME=='..')
            continue;

        //遍历文件
        $tmp_file_url = iconv2os($CUR_DIR."/".$FILE_NAME);
        if(is_file($tmp_file_url))
        {
            $TEP_TYPE = strtolower(substr(strrchr($FILE_NAME,"."),1));

            if($TEP_TYPE=="db" || !find_id($IMG_TYPE_STR,$TEP_TYPE))
              continue;
            $FILE_ATTR_ARRAY[$FILE_COUNT]["TYPE"]=substr(strrchr($FILE_NAME,"."),1);
            $FILE_ATTR_ARRAY[$FILE_COUNT]["NAME"]=$FILE_NAME;
			if(file_exists($tmp_file_url))
			{
				$FILE_ATTR_ARRAY[$FILE_COUNT]["TIME"]=date("Y-m-d H:i:s", filemtime($tmp_file_url));
				$FILE_ATTR_ARRAY[$FILE_COUNT]["SIZE"]=sprintf("%u", filesize($tmp_file_url));
			}

            $FILE_COUNT++;  //文件计数
            $TEMP_FILE_DIR=$CUR_DIR."/tdoa_cache/".$FILE_NAME; //当前文件缩略图路径

            $NOW_FILE_DIR1=$CUR_DIR."/".$FILE_NAME;
            $NOW_FILE_DIR2=$CUR_DIR."/tdoa_cache/".$FILE_NAME;

            $TEMP_FILE_DIR = iconv2os($TEMP_FILE_DIR);
            if(!file_exists($TEMP_FILE_DIR)) //是否有缩略图
            {
                $FILE_TYPE=substr(strrchr($FILE_NAME, "."), 1);
                $FILE_TYPE=strtolower($FILE_TYPE);

                if(find_id($IMG_TYPE_STR,$FILE_TYPE))
                {
                    $DEFAULT_DIR=$CUR_DIR."/"."tdoa_cache";
                    $DEFAULT_DIR=iconv2os($DEFAULT_DIR);
                    if(!file_exists($DEFAULT_DIR))
                      mkdir($DEFAULT_DIR);

                    $NOW_FILE_DIR=$CUR_DIR."/".$FILE_NAME;
                    $NOW_FILE_DIR=str_replace("//","/",$NOW_FILE_DIR);

                    if ($FILE_TYPE!="bmp")
                      crop($NOW_FILE_DIR,150,150,1,iconv2oa($TEMP_FILE_DIR));
                    else
                      td_copy($NOW_FILE_DIR1, $NOW_FILE_DIR2);
                }
            }

            //增加中等图片medium lp 2013/10/17 15:31:49
            $TEMP_MEDIUM_PATH = $CUR_DIR."/tdoa_cache/medium_".$FILE_NAME; //当前文件中等图片
            if(!file_exists($TEMP_MEDIUM_PATH))
            {
                $FILE_TYPE=substr(strrchr($FILE_NAME, "."), 1);
                $FILE_TYPE=strtolower($FILE_TYPE);

                if(find_id($IMG_TYPE_STR,$FILE_TYPE))
                {
                    $DEFAULT_DIR=iconv2os($CUR_DIR);
                    if(!file_exists($DEFAULT_DIR))
                        mkdir($DEFAULT_DIR);

                    $NOW_FILE_DIR=$CUR_DIR."/".$FILE_NAME;
                    $NOW_FILE_DIR=str_replace("//","/",$NOW_FILE_DIR);

                    if ($FILE_TYPE!="bmp")
                        CreateThumb($NOW_FILE_DIR,$MEDIUM_IMAGE_SIZE['w'],$MEDIUM_IMAGE_SIZE['h'],iconv2oa($TEMP_MEDIUM_PATH));
                    else
                        td_copy($NOW_FILE_DIR, $TEMP_MEDIUM_PATH);
                }    
            }
        }
        else
        {
            if($FILE_NAME=='tdoa_cache')  //跳过缩略图目录
            continue;

            //遍历目录
            $SORT_ATTR_ARRAY[$SORT_COUNT]["NAME"]=$FILE_NAME;
			if(file_exists($tmp_file_url))
			{
				$SORT_ATTR_ARRAY[$SORT_COUNT]["TIME"]=date("Y-m-d H:i:s", filemtime($tmp_file_url));
			}

            //目录计数
            $SORT_COUNT++;
        }
    } //当前目录遍历结束

    //文件排序处理
    if($FILE_COUNT!=0)
    {
        $SORT_ASC = 4;
        $SORT_DESC = 3;
        foreach($FILE_ATTR_ARRAY as $RES)
          $SORTAUX[]= strtolower($RES[$VIEW_TYPE])."<br>";
        if($ASC_DESC==4)
          array_multisort($SORTAUX,$SORT_ASC,SORT_NUMERIC,$SORTAUX,$SORT_ASC,SORT_STRING,$FILE_ATTR_ARRAY);
        else
          array_multisort($SORTAUX,$SORT_DESC,SORT_NUMERIC,$SORTAUX,$SORT_DESC,SORT_STRING,$FILE_ATTR_ARRAY);
    }

    //文件夹排序处理
    if($SORT_COUNT!=0)
    {
        if($VIEW_TYPE=="TYPE" || $VIEW_TYPE=="SIZE")
        {
          foreach($SORT_ATTR_ARRAY as $RES1)
            $SORTAUX1[]= strtolower($RES1["NAME"]);
        }
        if($VIEW_TYPE=="TIME" || $VIEW_TYPE=="NAME")
        {
          foreach($SORT_ATTR_ARRAY as $RES1)
            $SORTAUX1[]= strtolower($RES1[$VIEW_TYPE]);
        }

        $SORT_ASC=4;
        $SORT_DESC=3;

        if($ASC_DESC==4)
          array_multisort($SORTAUX1,$SORT_ASC,SORT_NUMERIC,$SORTAUX1,$SORT_ASC,SORT_STRING,$SORT_ATTR_ARRAY);
        else
          array_multisort($SORTAUX1,$SORT_DESC,SORT_NUMERIC,$SORTAUX1,$SORT_DESC,SORT_STRING,$SORT_ATTR_ARRAY);
    }

    //数组合并
    $ALL_COUNT = $SORT_COUNT + $FILE_COUNT;
    $Z=0;
    for($H=0;$H < $FILE_COUNT; $H++)
    {
        $Z++;
        $SORT_FILE_ARRAY[$Z]["TYPE"] = $FILE_ATTR_ARRAY[$H]["TYPE"];
        $SORT_FILE_ARRAY[$Z]["NAME"] = $FILE_ATTR_ARRAY[$H]["NAME"];
        $SORT_FILE_ARRAY[$Z]["TIME"] = $FILE_ATTR_ARRAY[$H]["TIME"];
        $SORT_FILE_ARRAY[$Z]["SIZE"] = $FILE_ATTR_ARRAY[$H]["SIZE"];
    }

    //获取当前路径
    if($SUB_DIR=="")
    {
        $LOCATION = $PIC_NAME;
        $FOLDER =   $PIC_NAME;
    }
    else
    {
        $LOCATION=$PIC_NAME."/".$SUB_DIR;  
    }
    $LOCATION=str_replace("/","\\",$LOCATION);

    $arr_data = array();

    $cur_image_index = 0;

    //显示的文件数量
    for($I = 0;$I <= $FILE_COUNT;$I++)
    {
       $WRAP_COUNT++;

       //处理文件名过长
       if(strlen(csubstr($SORT_FILE_ARRAY[$I]["NAME"],0,strrpos($SORT_FILE_ARRAY[$I]["NAME"], "."))) >= 8)
          $CSUB_SF_NAME = csubstr(csubstr($SORT_FILE_ARRAY[$I]["NAME"],0,strrpos($SORT_FILE_ARRAY[$I]["NAME"], ".")),0,8)."...";
       else
          $CSUB_SF_NAME = $SORT_FILE_ARRAY[$I]["NAME"];

        $FILE_TYPE=substr(strrchr($SORT_FILE_ARRAY[$I]["NAME"], "."), 1);

        $FILE_TYPE=strtolower($FILE_TYPE);
        if($SORT_FILE_ARRAY[$I]["NAME"]!="")
        {
            //允许的类型文件
            if(find_id($IMG_TYPE_STR,$FILE_TYPE))
            {
                $TEMP_FILE_DIR=$CUR_DIR."/tdoa_cache/".$SORT_FILE_ARRAY[$I]["NAME"]; //当前文件缩略图路径
                if(!file_exists(iconv2os($TEMP_FILE_DIR)))
                {
                    $FILE_TYPE=substr(strrchr($SORT_FILE_ARRAY[$I]["NAME"], "."), 1);
                    $FILE_TYPE=strtolower($FILE_TYPE);

                    if(find_id($IMG_TYPE_STR,$FILE_TYPE))
                    {
                        $DEFAULT_DIR=$CUR_DIR."/"."tdoa_cache";
                        if(!file_exists(iconv2os($DEFAULT_DIR)))
                        mkdir(iconv2os($DEFAULT_DIR));

                        $NOW_FILE_DIR=$CUR_DIR."/".$SORT_FILE_ARRAY[$I]["NAME"];
                        $NOW_FILE_DIR=str_replace("//","/",$NOW_FILE_DIR);

                        crop($NOW_FILE_DIR,150,150,1,iconv2oa($TEMP_FILE_DIR));
                    }
                }

                if(file_exists(iconv2os($TEMP_FILE_DIR)))
                    $FILE_PATH=$CUR_DIR."/tdoa_cache/".$SORT_FILE_ARRAY[$I]["NAME"];
                else
                    $FILE_PATH=$CUR_DIR."/".$SORT_FILE_ARRAY[$I]["NAME"];
                $IMG_ATTR=@getimagesize(iconv2os($FILE_PATH));
                if($IMG_ATTR[0]>150)
                    $IMG_ATTR[0]=150;
                if($IMG_ATTR[1]>150)
                    $IMG_ATTR[1]=150;

                $arr_data[$I] = array(
                    'name' => $SORT_FILE_ARRAY[$I]['NAME'],
                    'size' => get_file_size_for_ga($CUR_DIR, $SORT_FILE_ARRAY[$I]["NAME"]),
                    'dim' => implode('X', get_file_dim_for_ga($CUR_DIR, $SORT_FILE_ARRAY[$I]["NAME"])),
                    'path' => $CUR_DIR."\\".$SORT_FILE_ARRAY[$I]["NAME"],
                    'medium_path' => $CUR_DIR."/tdoa_cache/medium_".$SORT_FILE_ARRAY[$I]["NAME"],
                    'exif' => get_file_exif_for_ga($CUR_DIR, $SORT_FILE_ARRAY[$I]["NAME"]),
                    'down' => 'down.php?PIC_ID='.$PIC_ID.'&SUB_DIR='.$SUB_DIR.'&FILE_NAME='.urlencode($SORT_FILE_ARRAY[$I]["NAME"])
                );

                if($SORT_FILE_ARRAY[$I]['NAME'] == $URL_FILE_NAME)
                    $cur_image_index = ($I - 1);
            }
        }
    }

    function get_file_dim_for_ga($CUR_DIR, $NAME)
    {
        $FILE_PATH = $CUR_DIR."/".$NAME;
        $IMG_ATTR = @getimagesize(iconv2os($FILE_PATH));
        return array($IMG_ATTR[0], $IMG_ATTR[1]);
    }

    function get_file_size_for_ga($CUR_DIR, $NAME)
    {
		$FILE_SIZE = 0;
		if(file_exists(iconv2os($CUR_DIR."/".$NAME)))
	    {
			$FILE_SIZE = filesize(iconv2os($CUR_DIR."/".$NAME));
			if(floor($FILE_SIZE/1024/1024)>0)
				$FILE_SIZE=round($FILE_SIZE/1024/1024,1)."M";
			else if(floor($FILE_SIZE/1024)>0)
				$FILE_SIZE=round($FILE_SIZE/1024,1)."K";
			else
				$FILE_SIZE=round($FILE_SIZE)."B";
		}
        return $FILE_SIZE;
    }

    function get_file_url_for_ga($PIC_ID, $SUB_DIR, $FILE_NAME){
        return 'header.php?PIC_ID='.$PIC_ID.'&SUB_DIR='.urlencode($SUB_DIR).'&FILE_NAME='.$FILE_NAME;
    }

    function get_file_thumb_url_for_ga($PIC_ID, $SUB_DIR, $FILE_NAME){
        return get_file_url_for_ga($PIC_ID, $SUB_DIR, $FILE_NAME)."&Is_Thumb=1";    
    }

    function get_file_medium_url_for_ga($PIC_ID, $SUB_DIR, $FILE_NAME){
        return get_file_url_for_ga($PIC_ID, $SUB_DIR, "medium_".$FILE_NAME)."&Is_Thumb=1";    
    }

    function get_file_down_url_for_ga($PIC_ID, $SUB_DIR, $FILE_NAME){
        return 'down.php?PIC_ID='.$PIC_ID.'&SUB_DIR='.urlencode($SUB_DIR).'&FILE_NAME='.urlencode($FILE_NAME);
    }

    function get_file_exif_for_ga($CUR_DIR, $FILE_NAME){
        //读取EXIF信息
        $exif_str = '';
        if(strtolower(substr($FILE_NAME,-3))!="jpg" && strtolower(substr($FILE_NAME,-4))!="jpeg" && strtolower(substr($FILE_NAME,-3))!="tif" && strtolower(substr($FILE_NAME,-4))!="tiff") 
        {
            $exif_str = "<p>"._("没有图片EXIF信息")."</p>";
        }
        else
        {
            $imgtype = array("", "GIF", "JPG", "PNG", "SWF", "PSD", "BMP", "TIFF(intel byte order)", "TIFF(motorola byte order)", "JPC", "JP2", "JPX", "JB2", "SWC", "IFF", "WBMP", "XBM");

            $EXIF = @exif_read_data (iconv2os($CUR_DIR."/".$FILE_NAME),"IFD0");
            if ($EXIF===false) 
            {
                $exif_str = "<p>"._("没有图片EXIF信息")."</p>";
            }
            else
            {
                $EXIF = @exif_read_data (iconv2os($CUR_DIR."/".$FILE_NAME),0,true);
                $TEMP = $EXIF[EXIF][DateTimeDigitized];
                $TEMP = explode(" ",$TEMP);
                $TEMP[0] = str_ireplace(":","-",$TEMP[0]);
                
                $GQZ = $EXIF[EXIF][MaxApertureValue]==""?"":"F";
                $PGSJ = $EXIF[EXIF][ExposureTime]==""?"":"s";
                $ISO = $EXIF[EXIF][ISOSpeedRatings]==""?"":"ISO-";
                $JJ = $EXIF[EXIF][FocalLength]==""?"":"mm";
                if($TEMP[0]!="" || $TEMP[1]!="")
                    $exif_str .= "<p>"._("拍摄日期：").$TEMP[0]." ".$TEMP[1]."</p>";

                if($EXIF[IFD0][Make]!="")
                    $exif_str .= "<p>"._("相机制造商：").$EXIF[IFD0][Make]."</p>";

                if($EXIF[IFD0][Model]!="")
                    $exif_str .= "<p>"._("相机型号：").$EXIF[IFD0][Model]."</p>";

                if($EXIF[EXIF][ApertureValue]!="")
                    $exif_str .= "<p>"._("光圈：").$EXIF[EXIF][ApertureValue]."</p>";

                if($GQZ!="" || $EXIF[EXIF][MaxApertureValue]!="")
                    $exif_str .= "<p>"._("光圈值：").$GQZ.$EXIF[EXIF][MaxApertureValue]."</p>";

                if($EXIF[EXIF][ExposureTime]!="")
                    $exif_str .= "<p>"._("曝光时间：").$EXIF[EXIF][ExposureTime]."</p>";

                if($ISO!="" || $EXIF[EXIF][ISOSpeedRatings]!="")
                    $exif_str .= "<p>"._("ISO速度：").$ISO.$EXIF[EXIF][ISOSpeedRatings]."</p>";

                if($EXIF[EXIF][FocalLength]!="")
                    $exif_str .= "<p>"._("焦距：").$EXIF[EXIF][FocalLength]."</p>";

                if($EXIF[EXIF][ExposureMode]!="")
                    $exif_str .= "<p>"._("闪光模式：").($EXIF[EXIF][ExposureMode]==1?_("手动"):_("自动"))."</p>";
            }
        }

        if($exif_str == "")
            $exif_str = "<p>"._("没有图片EXIF信息")."</p>";
        
        return $exif_str;
    }

    //加载点击的图片
    $default_img_thumb_url = get_file_thumb_url_for_ga($PIC_ID, $SUB_DIR, urldecode($URL_FILE_NAME));
    $default_img_medium_url = get_file_medium_url_for_ga($PIC_ID, $SUB_DIR, urldecode($URL_FILE_NAME));
    $default_img_down_url = get_file_down_url_for_ga($PIC_ID, $SUB_DIR, $URL_FILE_NAME);
    $default_img_size = get_file_size_for_ga($CUR_DIR, urldecode($URL_FILE_NAME));
    $default_img_dim = implode('X', get_file_dim_for_ga($CUR_DIR, urldecode($URL_FILE_NAME)));
    $default_img_exif = get_file_exif_for_ga($CUR_DIR, urldecode($URL_FILE_NAME));
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/lhpMegaImgViewer/css/reset.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/css/flick/jquery-ui-1.10.3.custom.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/lhpMegaImgViewer/css/lhp_miv.css" />
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/js/jquery-ui-1.10.3.custom.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.easing.1.3.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jscrollpane/jquery.mousewheel.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/lhpMegaImgViewer/js/jquery.lhpMegaImgViewer.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/sliderkit/js/jquery.sliderkit.1.9.2.js<?=$GZIP_POSTFIX?>"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/sliderkit/css/sliderkit-core.css" />
<style>
object{display:none;}
html, body{height: 100%;font-family: arial;}

/* 全屏模式 */
.fullscreen #ga-left{margin-right: 0;}
.fullscreen #ga-right, .fullscreen .sliderkit-nav{display: none;}
.fullscreen .images-view-tools .images-view-tool.ivt-screen{background-position: 0 -16px;}

#ga-left{background-color:#000;height: 100%;position:relative;margin-right:200px;}
#ga-right{position:absolute;width:200px;top:0;right:0;background-color: #f3f3f3;height: 100%;}
#image-show-wrapper{position:absolute;top:0;left:0;bottom:120px;right: 0;*+position:inherit; /*ie7 hack*/}
#image-show-wrapper #image-show-dom{overflow:hidden; background:#000;width:100%;height:100%;}
#image-thumb-wrapper{height:120px;width:100%;background-color: #000;position: absolute;bottom: 0;left:0;}
p, a.btn-primary{font-size: 12px;margin: 10px 10px 10px 15px;word-wrap:break-word;word-break: break-all;}
#ga-right .ga-pic-title{height: 40px;line-height: 40px;font-size: 14px;margin: 10px;border-bottom: 1px solid #cdcdcd;font-weight: bold;color: #666666;}
#ga-right .ga-pic-desc{font-weight:bold;color: #666666;margin-left: 10px;}
a.btn-primary{width:120px;height:30px;line-height:30px;display: block;color: #ffffff;text-decoration: none;}
a.btn-primary:hover{background-color: #0044cc;}

table{width:200px;}
table th{height: 24px;line-height:24px;width:30px;text-align: right;}
table td{word-wrap:break-word;overflow:hidden;}

a.image-arrow-wrapper{height: 120px;width: 60px;position: absolute;top: 50%;z-index: 100;margin-top: -60px;opacity:0.5;filter:alpha(opacity=50);display: block;cursor: pointer;}
a.image-arrow-wrapper:hover{opacity:1;filter:alpha(opacity=100);}
.image-arrow-wrapper div{height: 60px;width: 34px;background: transparent url(<?=MYOA_STATIC_SERVER?>/static/modules/picture/images/arrows.png) no-repeat 0 0;margin-top: 30px;margin-left: 15px;}
a.image-arrow-wrapper.left-arrow{left:0;}
a.image-arrow-wrapper.right-arrow{right: 0;}
.image-arrow-wrapper.right-arrow div{margin-right: 15px;background-position: -34px 0;}
a.image-arrow-wrapper.disabled{opacity:0.1;filter:alpha(opacity=10);}

.image-thumb-content{width:595px;height:56px;margin:34px auto 0;}
.images-view-tools{height:30px;line-height:30px;text-align: right;position: absolute;bottom: 0;right: 10px;}
.images-view-tools .images-view-tool{width: 16px;height: 16px;display: inline-block;background: transparent url(<?=MYOA_STATIC_SERVER?>/static/modules/picture/images/tools.png) 0 0 no-repeat;}
.images-view-tools .images-view-tool.ivt-screen{background-position: 0 0;}
.images-view-tools a{opacity: 0.5;filter:alpha(opacity=50);}
.images-view-tools a:hover{opacity: 0.8;filter:alpha(opacity=80);}

/* Navbar */
.image-thumb-content .sliderkit-nav{width:595px;height:56px;}
.image-thumb-content .sliderkit-nav-clip ul li{float:left;margin:0 5px 0 0;font-size: 0;background:#000000;}
.image-thumb-content .sliderkit-nav-clip ul li a{display:block;margin:0 1px;border:1px solid #333;padding:2px;overflow:hidden;margin:0;opacity:0.5;filter:alpha(opacity=50);}
.image-thumb-content .sliderkit-nav-clip ul li a img{height:50px;width:50px;}
.image-thumb-content .sliderkit-nav-clip ul li a:hover{opacity:1;}
.image-thumb-content .sliderkit-nav-clip ul li.sliderkit-selected a{opacity:1;filter:alpha(opacity=100);}

/* Navbar buttons */
.image-thumb-content .sliderkit-nav .sliderkit-nav-btn{position:absolute;top:0;padding:3px;}
.image-thumb-content .sliderkit-nav .sliderkit-nav-btn span{display:none;}
.image-thumb-content .sliderkit-nav .sliderkit-nav-btn a{cursor:pointer; width:50px; height:50px; opacity:0.5;filter:alpha(opacity=50);display:block;background: transparent url(<?=MYOA_STATIC_SERVER?>/static/modules/picture/images/arrows_small.png) no-repeat 0 0px;border:none;opacity:0.5;filter:alpha(opacity=50);}
.image-thumb-content .sliderkit-nav .sliderkit-go-prev{left:0;}
.image-thumb-content .sliderkit-nav .sliderkit-go-next{right:0;}
.image-thumb-content .sliderkit-nav .sliderkit-go-prev a{background-position:0px 5px}
.image-thumb-content .sliderkit-nav .sliderkit-go-next a{background-position:-40px 5px;}
.image-thumb-content .sliderkit-nav .sliderkit-go-prev a:hover,
.image-thumb-content .sliderkit-nav .sliderkit-go-prev a:focus,
.image-thumb-content .sliderkit-nav .sliderkit-go-next a:hover,
.image-thumb-content .sliderkit-nav .sliderkit-go-next a:focus{opacity:0.8;filter:alpha(opacity=80);}
/* Buttons > Disable */
.image-thumb-content .sliderkit-nav .sliderkit-btn-disable a,
.image-thumb-content .sliderkit-nav .sliderkit-btn-disable a:hover,
.image-thumb-content .sliderkit-nav .sliderkit-btn-disable a:focus{opacity:0.1;filter:alpha(opacity=10);cursor:default;}
.ga-loading{background:#fff url(<?=MYOA_STATIC_SERVER?>/static/images/ajax-loader.gif) no-repeat center center;}
.btn-primary{background-color: #006dcc;height:30px;line-height: 30px;text-align: center;padding:0 10px;}
.btn-primary:hover{color: #ffffff;background-color: #0044cc;}
</style>
<body>
<div id="ga-left">
    <div id="image-show-wrapper">
        <a class="image-arrow-wrapper left-arrow" href="javascript:;" node-type="imageArrow" node-data="left"><div></div></a>
        <a class="image-arrow-wrapper right-arrow" href="javascript:;" node-type="imageArrow" node-data="right"><div></div></a>
        <div id="image-show-dom"></div>
    </div>
    <div id="image-thumb-wrapper">
        <div class="sliderkit image-thumb-content" node-type="imageThumbNav">
            <div class="sliderkit-nav">
                <div class="sliderkit-nav-clip" node-type="imageThumbList">
                    <ul>
                        <? 
                            foreach($arr_data as $k => $v)
                            {
                        ?>
                                <li>
                                    <a href="javascript:;" title="<?=$v['name']?>">
                                        <img src="<?=MYOA_STATIC_SERVER?>/static/modules/picture/images/space.gif" data-thumb-src="<?=get_file_thumb_url_for_ga($PIC_ID, $SUB_DIR, urlencode($v['name']))?>" data-medium-src="<?=get_file_medium_url_for_ga($PIC_ID, $SUB_DIR, urlencode($v['name']))?>" />
                                    </a>
                                </li>
                        <? 
                            }
                        ?>
                    </ul>
                </div>

                <div class="sliderkit-btn sliderkit-nav-btn sliderkit-go-btn sliderkit-go-prev" node-type="goPrev"><a href="javascript:;" title="<?=_("向左滑动")?>"><span><?=_("上一张")?></span></a></div>
                <div class="sliderkit-btn sliderkit-nav-btn sliderkit-go-btn sliderkit-go-next" node-type="goNext"><a href="javascript:;" title="<?=_("向右滑动")?>"><span><?=_("下一张")?></span></a></div>

            </div>
            <div class="sliderkit-panels">
                <div class="sliderkit-panel"></div>
            </div>
        </div>
        <div class="images-view-tools">
            <a href="javascript:;" node-type="ivt" node-data="fullscreen" class="images-view-tool ivt-screen" title="<?=_("全屏")?>"></a>
        </div>
    </div>
</div>
<div id="ga-right">
    <p class="ga-pic-title"><?=_("图片信息")?></p>
    <p class="ga-pic-desc"><?=_("图片名称")?></p>
    <p node-type="name"><?=urldecode($URL_FILE_NAME)?></p>
    <p class="ga-pic-desc"><?=_("文件大小")?></p>
    <p node-type="size"><?=$default_img_size?></p>
    <p class="ga-pic-desc"><?=_("原始尺寸")?></p>
    <p node-type="dim"><?=$default_img_dim?></p>
    <p class="ga-pic-desc"><?=_("文件路径")?></p>
    <p node-type="path"><?=$LOCATION?></p>
    <p class="ga-pic-desc"><?=_("EXIF信息")?></p>
    <div node-type="exif"><?=urldecode($default_img_exif)?></div>
    <div class="ga-pic-down">
        <a node-type="down" class="btn btn-primary" href="<?=$default_img_down_url?>"><?=_("下载原图")?></a>    
    </div>
</div>

<script type="text/javascript">
var currId = parseInt('<?=$cur_image_index?>', 10);
var gels = <?=json_encode(td_iconv($arr_data, MYOA_CHARSET, 'utf-8'))?>;
var allItems = parseInt('<?=count($arr_data)?>', 10);

function showImageInfo(i)
{
    var s = $("#ga-right");
    i++;
    if(gels && gels[i])
    {
        s.find('[node-type="name"]').html('&nbsp' + decodeURIComponent(gels[i].name));
        s.find('[node-type="size"]').html('&nbsp' + gels[i].size);
        s.find('[node-type="dim"]').html('&nbsp' + gels[i].dim);
        s.find('[node-type="exif"]').html(decodeURIComponent(gels[i].exif));
        s.find('[node-type="down"]').attr("href", gels[i].down);
    }
}

$.fn.imgpreview = function(){
    this.each(function(){
        if(!this.getAttribute('data-thumb-src')) return;
        this.className = 'ga-loading';
        this.onload = function(){
            this.className = '';
            this.removeAttribute('data-thumb-src');
        };
        this.onerror = function(){
            this.src = this.getAttribute('data-thumb-src');
        };
        this.src = this.getAttribute('data-thumb-src');
    });
};
$(window).load(function(){

     var imageThumbNav = $("div[node-type='imageThumbNav']").sliderkit({
        auto:false,
        shownavitems:8,
        scroll:8,
        mousewheel:true,
        circular:false,
        start: currId,
        height: 56,
        keyboard: true,
        navfxafter: function(){
            var cid = typeof imageThumbNav.currId == "undefined" ? window.currId : imageThumbNav.currId;
            var sid = Math.max(0, (cid - 8));
            var eid = Math.min((cid + 8), imageThumbNav.allItems || allItems);
            $('div[node-type="imageThumbList"] img:gt('+ sid +'):lt('+ eid +')').imgpreview();   
        },
        selectThumbnailFn: function(i){
            var obj = $('div[node-type="imageThumbList"] img:eq(' + i + ')');
            window.settings.contentUrl = obj.attr('data-medium-src');
            window.settings.mapThumb = obj.attr('data-thumb-src');
            $('#image-show-dom').lhpMegaImgViewer('destroy');
            $('#image-show-dom').lhpMegaImgViewer(window.settings).focus();
            showImageInfo(obj.parents('li').index());
        }
    }).data('sliderkit');

    window.runtime_sliderkit = imageThumbNav;

    $('div[node-type="imageThumbList"] img:lt(8)').imgpreview();
});

$(document).ready(function() {
    window.settings = {
        'viewportWidth': '100%',
        'viewportHeight': '100%',
        'fitToViewportShortSide': false,
        'contentSizeOver100': false,
        'startScale': .5,
        'startX': 0,
        'startY': 0,
        'animTime': 500,
        'draggInertia': 10,
        'contentUrl': '<?=$default_img_medium_url?>',
        'intNavEnable': false,
        'intNavPos': 'B',
        'intNavAutoHide': true,
        'intNavMoveDownBtt': true,
        'intNavMoveUpBtt': true,
        'intNavMoveRightBtt': true,
        'intNavMoveLeftBtt': true,
        'intNavZoomBtt': true,
        'intNavUnzoomBtt': true,
        'intNavFitToViewportBtt': true,
        'intNavFullSizeBtt': true,
        'intNavBttSizeRation': 1,
        'mapEnable': false,
        'mapThumb': '<?=$default_img_thumb_url?>',
        'mapPos': 'BL',
        'popupShowAction': 'click',
        'testMode': false
    };

    $('#image-show-dom').lhpMegaImgViewer(window.settings).focus();

    $('div[node-type="imageThumbList"] img').click(function(e){
        e.preventDefault();
        window.settings.contentUrl = $(this).attr('data-medium-src');
        window.settings.mapThumb = $(this).attr('data-thumb-src');
        $('#image-show-dom').lhpMegaImgViewer('destroy');
        $('#image-show-dom').lhpMegaImgViewer(window.settings).focus();
        showImageInfo($(this).parents('li').index());
    });

    $('a[node-type="imageArrow"]').click(function(){
        var currId = allItems = 0;
        var rDom = $('a[node-type="imageArrow"][node-data="right"]');
        var lDom = $('a[node-type="imageArrow"][node-data="left"]');
        var action = $(this).attr('node-data');

        if(window.runtime_sliderkit && window.runtime_sliderkit.currId){
            currId = window.runtime_sliderkit.currId;
            allItems = window.runtime_sliderkit.allItems;
        }

        if(action == "left")
        {   
            if(currId == 0)
            {
                return;
            }
            else
            {
                window.runtime_sliderkit.stepBackward();
                $('div[node-type="imageThumbList"] img:eq(' + (currId -1) + ')').click();
                if((currId - 1) == 0)
                    lDom.addClass("disabled");
                else
                    rDom.removeClass("disabled");
            }
        }else{
            if(currId == (allItems - 1))
            {
                return;
            }else{
                window.runtime_sliderkit.stepForward();
                $('div[node-type="imageThumbList"] img:eq(' + (currId + 1) + ')').click();
                if((currId + 1) == (allItems - 1))
                    rDom.addClass("disabled");
                else
                    lDom.removeClass("disabled");
            }
        }
    });

    $(document).keydown(function(e){
        e.preventDefault();
        e.stopPropagation();
        var e = e||event;
        var currKey=e.keyCode||e.which||e.charCode;
        if(currKey == 37 || currKey == 39)
        {
            switch(currKey)
            {
                case 37:
                    if(window.runtime_sliderkit.currId == 0)
                        return;    
                    else
                        window.runtime_sliderkit && window.runtime_sliderkit.stepBackward();
                    break;
                case 39:
                    if(window.runtime_sliderkit.currId == (window.runtime_sliderkit.allItems - 1))
                        return;
                    else
                        window.runtime_sliderkit && window.runtime_sliderkit.stepForward();
                    break;
                default:
                    break;
            }
        }
    });

    $('a[node-type="ivt"][node-data="fullscreen"]').click(function(e){
        e.stopPropagation();
        e.preventDefault();
        if($('body').hasClass('fullscreen'))
        {
            $('body').removeClass('fullscreen');
            if(typeof(window.external.OA_SMS) == 'undefined')
            {
                h = 664;
                w = 950;
                t = (screen.availHeight - h)/2 - 30,
                l = (screen.availWidth - w)/2 -10;
                window.resizeTo(w, h);
                top.moveTo(l, t);
            }else{
                window.external.OA_SMS('', '', 'SET_MAX');
                //window.external.OA_SMS(780, 548, 'SET_SIZE');
            }
            $(this).attr('title', '<?=_("全屏")?>');
        }
        else
        {
            $('body').addClass('fullscreen');
            if(typeof(window.external.OA_SMS) == 'undefined')
            {
                top.moveTo(0, 0);
                window.resizeTo(screen.width, screen.height);    
            }else{
                window.external.OA_SMS('', '', 'SET_MAX');
            }
            $(this).attr('title', '<?=_("收起")?>');
        }

        //重新计算图片的位置
        var currId = window.runtime_sliderkit && window.runtime_sliderkit.currId;
        var currDom = $('div[node-type="imageThumbList"] img:eq(' + currId + ')');
        window.settings.contentUrl = currDom.attr('data-medium-src');
        window.settings.mapThumb = currDom.attr('data-thumb-src');
        $('#image-show-dom').lhpMegaImgViewer('destroy');
        $('#image-show-dom').lhpMegaImgViewer(window.settings).focus();
    });

    $(window).resize(function(){
        $('#image-show-dom').lhpMegaImgViewer('destroy');
        $('#image-show-dom').lhpMegaImgViewer(window.settings).focus();
        return;
    });

}); 
</script>
</body>
</html>