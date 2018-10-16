<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_all.php");
include_once('inc/utility_org.php');
include_once("inc/utility_file.php");
$TABLE_WIDTH=100;  //图片表格宽度
$TABLE_HEIGHT=100;  //图片表格高度
$IMG_TYPE_STR="gif,jpg,png,swf,swc,tiff,bmp,iff,jp2,jpx,jb2,jpc,xbm,wbmp"; //允许文件类型

if($VIEW_TYPE=="")
   $VIEW_TYPE="NAME";

if($SUB_DIR!="")
   $SUB_DIR=urldecode($SUB_DIR);
   
//读取新建图片目录路径及名称
$PIC_ID=intval($PIC_ID);
if($CUR_DIR=="")
{
    $query = "SELECT PIC_NAME,PIC_PATH,ROW_PIC_NUM,ROW_PIC from PICTURE where PIC_ID='$PIC_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $PIC_NAME=$ROW["PIC_NAME"];
       $PIC_PATH=$ROW["PIC_PATH"];
       $TD_COUNT = $ROW["ROW_PIC_NUM"];//每行图片数量
    	 $ONE_PAGE_PICS = $TD_COUNT * $ROW["ROW_PIC"];// 每页文件或文件夹数量
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
	  $query = "SELECT PIC_NAME,PIC_PATH,PIC_ID,ROW_PIC_NUM,ROW_PIC from PICTURE where PIC_ID='$PIC_ID'";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
      $PIC_PATH = $ROW["PIC_PATH"];
      if(stristr($CUR_DIR,$PIC_PATH))
      {
      	$PIC_ID = $ROW["PIC_ID"];
      	$PIC_NAME = $ROW["PIC_NAME"];
      	$TD_COUNT = $ROW["ROW_PIC_NUM"];//每行图片数量
    	  $ONE_PAGE_PICS = $TD_COUNT * $ROW["ROW_PIC"];// 每页文件或文件夹数量
      	
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

$HTML_PAGE_TITLE = _("图片浏览");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/netdisk.css"/>
<script src="js/pic_control.js"></script>

	<style>
	a.pic_border:link {border:3px solid #FFFFFF;}     /* 未访问的链接 */
  a.pic_border:visited {border:3px solid #FFFFFF;}  /* 已访问的链接 */
  a.pic_border:hover {border:3px solid #4BAEE9;}    /* 当有鼠标悬停在链接上 */
  a.pic_border:active {border:3px solid #4BAEE9;}   /* 被选择的链接 */
	body {
	background-color: #000;
}
    </style>


<?
//页码 初始化
if($PAGE_NO=="")
   $PAGE_NO=1;
//------------------
$SORT_COUNT=0;  //当前目录文件夹数量
$FILE_COUNT=0;  //当前文件夹数量
$dh = @opendir(iconv2os($CUR_DIR));
while(false !== ($FILE_NAME = @readdir($dh)))
{
	 $FILE_NAME = iconv2oa($FILE_NAME);
   if($FILE_NAME=='.' || $FILE_NAME=='..')
	   continue;

	 //遍历文件
   if(is_file($CUR_DIR."/".$FILE_NAME))
   {
     $TEP_TYPE = substr(strrchr($FILE_NAME,"."),1);
	   if($TEP_TYPE=="db")
	      continue;
	   $FILE_ATTR_ARRAY[$FILE_COUNT]["TYPE"]=substr(strrchr($FILE_NAME,"."),1);
	   $FILE_ATTR_ARRAY[$FILE_COUNT]["NAME"]=$FILE_NAME;
	   $FILE_ATTR_ARRAY[$FILE_COUNT]["TIME"]=date("Y-m-d H:i:s", filemtime($CUR_DIR."/".$FILE_NAME));
     $FILE_ATTR_ARRAY[$FILE_COUNT]["SIZE"]=sprintf("%u", filesize($CUR_DIR."/".$FILE_NAME));

     $FILE_COUNT++;  //文件计数
	   $TEMP_FILE_DIR=$CUR_DIR."/tdoa_cache/".$FILE_NAME; //当前文件缩略图路径
	   if(!file_exists(iconv2os($TEMP_FILE_DIR))) //是否有缩略图
	   {
		    $FILE_TYPE=substr(strrchr($FILE_NAME, "."), 1);
        $FILE_TYPE=strtolower($FILE_TYPE);

  		  if(find_id($IMG_TYPE_STR,$FILE_TYPE))
        {
  			  $DEFAULT_DIR=$CUR_DIR."/"."tdoa_cache";
  			  if(!file_exists(iconv2os($DEFAULT_DIR)))
  			     mkdir(iconv2os($DEFAULT_DIR));

  			  $NOW_FILE_DIR=$CUR_DIR."/".$FILE_NAME;
  			  $NOW_FILE_DIR=str_replace("//","/",$NOW_FILE_DIR);
  			  if($FILE_COUNT >= $ONE_PAGE_PICS*($PAGE_NO-1) && $FILE_COUNT < $ONE_PAGE_PICS*$PAGE_NO)
            CreateThumb($NOW_FILE_DIR,80,80,$TEMP_FILE_DIR);
  		  }
  	 }
   }
   else
   {
     if($FILE_NAME=='tdoa_cache')  //跳过缩略图目录
		    continue;

		 //遍历目录
	   $SORT_ATTR_ARRAY[$SORT_COUNT]["NAME"]=$FILE_NAME;
	   $TMP_FILE_URL = iconv2os($CUR_DIR."/".$FILE_NAME);
	   $SORT_ATTR_ARRAY[$SORT_COUNT]["TIME"]=date("Y-m-d H:i:s", filemtime($TMP_FILE_URL));
     //目录计数
     $SORT_COUNT++;
   }
} //当前目录遍历结束

//文件排序处理
if($FILE_COUNT!=0)
{
   $SORT_ASC=4;
   $SORT_DESC=3;
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
      array_multisort($SORTAUX1,$SORT_ASC,SORT_NUMERIC,$SORT_ATTR_ARRAY);
   else
      array_multisort($SORTAUX1,$SORT_DESC,SORT_NUMERIC,$SORT_ATTR_ARRAY);
}
//获取当前路径
if($SUB_DIR=="")
  $LOCATION=$PIC_NAME;
else
  $LOCATION=$PIC_NAME."/".$SUB_DIR;
  $LOCATION1=str_replace("/","\\",$LOCATION);

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

?>

<body class="bodycolor" style="text-align:center;" onLoad="slide_begin();page_slide('init');slide_start()">
<center>

  <div id="div_ceng" style="background-color:black; width:800px; height:430px;">

    <table style="width:100%;font-size:14px">
    	<tr style="background-color:#2C2C2C;color:gray;height:30px;">
    		<td style="width:33%;">&nbsp;<?=$LOCATION1?></td>
    		<td style="text-align:center;width:33%;">
    			<div id="slide_pic_name" style="color:white"></div>
    		</td>
    		<td style="text-align:right;width:33%;"><input type="image" id="div_close" src="<?=MYOA_STATIC_SERVER?>/static/images/close.png" onClick="pic_slide()">&nbsp;</td>
    	</tr>
    	<tr style="color:gray;height:350px">
    		<td colspan=3 style="text-align:center;">
    			<div id="slide_pic"></div>
    		</td>
    	</tr>
    	<tr style="background-color:#121212;color:gray;height:50px">
    		<td colspan=3>
    			<table style="width:100%;">
    				<tr>
    		      <td style="width:340px;"  nowarp="true">
    		      	<input type="image" src="<?=MYOA_STATIC_SERVER?>/static/images/player_png/01045_12.png" title="pre" style="width:40px;height:40px;" onClick="slide_next('pre')">
    		      	<input type="image" src="<?=MYOA_STATIC_SERVER?>/static/images/player_png/01045_10.png" title="slow" style="width:40px;height:40px;" onClick="slide_space('slow')">
    		      	<input id="pause" type="image" src="<?=MYOA_STATIC_SERVER?>/static/images/player_png/01045_13.png" title="pause" style="width:40px;height:40px;" onClick="slide_begin()">
    		      	<input type="image" src="<?=MYOA_STATIC_SERVER?>/static/images/player_png/01045_9.png" title="quick" style="width:40px;height:40px;" onClick="slide_space('quick')">
    		      	<input type="image" src="<?=MYOA_STATIC_SERVER?>/static/images/player_png/01045_11.png" title="next" style="width:40px;height:40px;" onClick="slide_next('next')">
    		      	<input type="image" src="<?=MYOA_STATIC_SERVER?>/static/images/player_png/01045_16.png" title="stop" style="width:40px;height:40px;" onClick="slide_end()">
    		      	<input type="image" src="<?=MYOA_STATIC_SERVER?>/static/images/player_png/01045_1.png" style="width:40px;height:40px;" onClick="page_slide('pre');slide_auto_handle()">
    		      </td   nowarp="true">
    		      <td id="pic_cache">
    	        </td>
    	        <td style="width:40px"   nowarp="true">
    	        	<input type="image" src="<?=MYOA_STATIC_SERVER?>/static/images/player_png/01045_3.png" style="width:40px;height:40px;" onClick="page_slide('next');slide_auto_handle()">
    	        </td>
    	      </tr>
    	    </table>
    	  </td>
    	</tr>
    </table>
  </div>
<!--</div>-->
</center>
<script>
var div_close=document.getElementById("div_close");
div_close.onmouseover=div_close_over;
div_close.onmouseout=div_close_out;
slide_begin();
jQuery.noConflict();
var Interval=0; //设置循环播放
var counter=0; //文件计数；
var space=4000; //设置循环播放间隔；
var temp_start=0; //分页起始值
var slide_str=""; //保存分页数据
var auto_handle=0; //换页手动参数
var slide_filename=new Array(); //文件名数组
var max_counter=<?=$FILE_COUNT?>;

<?

for($TEMP_I = 0;$TEMP_I < $FILE_COUNT;$TEMP_I++)
{	 
   echo "slide_filename[$TEMP_I] = '".urldecode($FILE_ATTR_ARRAY[$TEMP_I]['NAME'])."';\n\r";
}
?>
function div_close_over()
{
	div_close.src="<?=MYOA_STATIC_SERVER?>/static/images/close_mouseover.png";
}

function div_close_out()
{
	div_close.src="<?=MYOA_STATIC_SERVER?>/static/images/close.png";
}

function pic_slide()
{
	document.getElementById('div_ceng').style.left = (document.body.offsetWidth - 800) / 2; 
  document.getElementById('div_ceng').style.top = (document.body.offsetHeight - 430) / 2 + document.body.scrollTop;
	if(document.getElementById("div_ceng").style.display=='none')
	{
		 document.getElementById("div_ceng").style.display='block';
		 page_slide("init");
		 pic_cycle();
	   Interval=setInterval("pic_cycle()",space);
	}
	else
	{
		 document.getElementById("div_ceng").style.display='none';
		 slide_end();
		 counter=0;
		 document.getElementById("pause").title="pause";
		 document.getElementById("pause").src="<?=MYOA_STATIC_SERVER?>/static/images/player_png/01045_13.png";
	}
}

function slide_begin()
{
	if(document.getElementById("pause").title=="pause")
	{
		document.getElementById("pause").title="play";
		document.getElementById("pause").src="<?=MYOA_STATIC_SERVER?>/static/images/player_png/01045_14.png";
		Interval=clearInterval(Interval);
		counter=counter-1;
		auto_handle=1;
	}
	else
	{
		counter=counter+1;
		auto_handle=0;
		pic_cycle();
	  Interval=setInterval("pic_cycle()",space);
		document.getElementById("pause").title="pause";
		document.getElementById("pause").src="<?=MYOA_STATIC_SERVER?>/static/images/player_png/01045_13.png";
	}
}

function slide_end()
{
	document.getElementById("pause").title="play";
	document.getElementById("pause").src="<?=MYOA_STATIC_SERVER?>/static/images/player_png/01045_14.png";
  Interval=clearInterval(Interval);
  jQuery("img[id*='slide_']").css({border:""});
  counter=-1;
  temp_start=0;
}
function pic_cycle()
{
	if(counter > max_counter-1)
	{
	   counter=0;
	   temp_start=0;
	   page_slide("init");
	}
  if(auto_handle==0)
  {
	document.getElementById("slide_pic").innerHTML="<img src=\"header.php?PIC_ID=<?=$PIC_ID?>&Is_Thumb=2&SUB_DIR=<?=$SUB_DIR?>&FILE_NAME="+slide_filename[counter]+"\" id=\"pic_slide_id\">";
  document.getElementById("slide_pic_name").innerHTML=slide_filename[counter];
  if((counter%9)==0 && counter!=0 && auto_handle == 0) page_slide("next");
  jQuery("img[id*='slide_']").css({border:""});
  cycle_img_id="#slide_"+counter;
  jQuery(cycle_img_id).css({border:"1px solid yellow"});
	counter++;
  }
}
function slide_space(aaa)
{
	if(aaa=="slow")
	{
	  space=space+500;
	  Interval=clearInterval(Interval);
	  if(document.getElementById("pause").title=="pause")
	    Interval=setInterval("pic_cycle()",space);	  
	}
	else
  {
		space=space-500;
		if(space<=500)
		  space=500;
	  Interval=clearInterval(Interval);
	  if(document.getElementById("pause").title=="pause")
	    Interval=setInterval("pic_cycle()",space);
	}
}
function slide_next(bbb)
{
	document.getElementById("pause").title="play";
	document.getElementById("pause").src="<?=MYOA_STATIC_SERVER?>/static/images/player_png/01045_14.png";
  Interval=clearInterval(Interval);
	if(bbb=="next")
	{
		counter=counter+1;
    pic_cycle_add("next");
  }
  else
	{
		if(counter == 0) 
		  counter = max_counter-1;
		else 
			counter=counter-1;
    pic_cycle_add("pre");
  }
}
function pic_cycle_add(qqq)
{
	if(counter > max_counter-1)
	   counter=0;
	document.getElementById("slide_pic").innerHTML="<img src=\"header.php?PIC_ID=<?=$PIC_ID?>&Is_Thumb=2&SUB_DIR=<?=$SUB_DIR?>&FILE_NAME="+slide_filename[counter]+"\" id=\"pic_slide_id\">";
  document.getElementById("slide_pic_name").innerHTML=slide_filename[counter];
  if(qqq=="next")
  {
    jQuery("img[id*='slide_']").css({border:""});
    cycle_img_id="#slide_"+counter;
    jQuery(cycle_img_id).css({border:"1px solid yellow"});    
    if(counter%9==0) page_slide("next");
  }
  else
  { 
    if((counter+1)%9==0 || counter==max_counter-1) page_slide("pre");
  	jQuery("img[id*='slide_']").css({border:""});
    cycle_img_id="#slide_"+counter;
    jQuery(cycle_img_id).css({border:"1px solid yellow"});
  }
}


function page_slide(ccc)
{
//	auto_handle=0;
	slide_str="";
	if(ccc=="next")
	{
		if(temp_start+9 >= max_counter)
  		temp_start=0;
    else
  	  temp_start=temp_start+9;
  }
  if(ccc=="pre")
  {
  	if(temp_start == 0 )
  	{
      Math.ceil(max_counter/9);
      temp_start=(Math.ceil(max_counter/9)-1)*9;
  	}
  	else
      temp_start=temp_start-9;
  }

	for(var J=temp_start;J< max_counter && J< temp_start+9;J++)
  {
    var SLIDE_ID="slide_"+J;
    //alert(slide_filename[J]);
    var temp_file_name = slide_filename[J];
    read_info("<?=$CUR_DIR?>",temp_file_name,J);
  }
}

function read_info(cur_dir,filename,img_id) 
{ 
	filename1=encodeURI(encodeURI(filename));
  jQuery.ajax(
  {
  	type:"get",
  	async:false,
  	url:"slide_rows_play.php",
  	data:{CUR_DIR:"<?=urlencode($CUR_DIR)?>",PIC_FILENAME:filename1},
  	success:function(data)
  	{
  	 temp_img_id="slide_"+img_id;
  	 //alert(data);
  	 if(data!="") 
     {
        var fff=data.split(";");
        if(img_id%9==0)
          slide_str+="<img id=\""+temp_img_id+"\" src=\"header.php?PIC_ID=<?=$PIC_ID?>&Is_Thumb=1&SUB_DIR=<?=$SUB_DIR?>&FILE_NAME="+filename+"\" width=\""+fff[1]+"\" height=\""+fff[2]+"\" style=\"border:1px solid yellow;\"></a>&nbsp;";
        else
          slide_str+="<img id=\""+temp_img_id+"\" src=\"header.php?PIC_ID=<?=$PIC_ID?>&Is_Thumb=1&SUB_DIR=<?=$SUB_DIR?>&FILE_NAME="+filename+"\" width=\""+fff[1]+"\" height=\""+fff[2]+"\"></a>&nbsp;";
     }
     else
     {
        if(img_id%9==0)
          slide_str+="<img id=\""+temp_img_id+"\" src=\"<?=MYOA_STATIC_SERVER?>/static/images/unknown.gif\" width=\"38px\" height=\"40px\" style=\"border:1px solid yellow;\"></a>&nbsp;"; 
        else
        	slide_str+="<img id=\""+temp_img_id+"\" src=\"<?=MYOA_STATIC_SERVER?>/static/images/unknown.gif\" width=\"38px\" height=\"40px\"></a>&nbsp;"; 
     }
				document.getElementById("pic_cache").innerHTML=slide_str;

  	}
	}); 
} 
function slide_auto_handle()
{
	auto_handle=1;
	counter=temp_start;
	pic_cycle();
}
function slide_start()
{
	auto_handle=0;
	counter=temp_start;
	pic_cycle();
}
</script>
</body>
</html>