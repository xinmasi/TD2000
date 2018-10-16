<?
$MODULE_FUNC_ID="15";
$MODULE_DESC=_("文件柜");
$MODULE_BODY=$MODULE_OP=$MODULE_TYPE="";
$MODULE_HEAD_CLASS = 'file_folder';

$COUNT=0;
if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_TYPE .= '<a href="javascript:get_file(\'0\');">'._("最新文件").'</a> ';
$MODULE_TYPE .= '<a href="javascript:get_file(\'1\');">'._("文件柜").'</a> ';

$MODULE_BODY.= "<ul>";
$query = "select a.CONTENT_ID,a.SUBJECT,a.SEND_TIME,b.SORT_ID,b.SORT_NAME,b.USER_ID,b.OWNER from FILE_CONTENT a,FILE_SORT b where a.SORT_ID=b.SORT_ID and b.SORT_TYPE!='4' and not find_in_set('".$_SESSION["LOGIN_USER_ID"]."',a.READERS) order by a.SEND_TIME desc limit 0,150";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $CONTENT_ID=$ROW["CONTENT_ID"];
   $SUBJECT=$ROW["SUBJECT"];
   $SEND_TIME=$ROW["SEND_TIME"];
   $SORT_ID=$ROW["SORT_ID"];
   $SORT_NAME=$ROW["SORT_NAME"];
   $USER_ID=$ROW["USER_ID"];
   $OWNER=$ROW["OWNER"];

   $SUBJECT_TITLE="";
   if(strlen($SUBJECT) > 50)
   {
      $SUBJECT_TITLE=$SUBJECT;
      $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
   }

   $ACCESS_PRIV=explode("|",$USER_ID);
   if($USER_ID!=$_SESSION["LOGIN_USER_ID"] && !check_priv($USER_ID) && !check_priv($OWNER))
      continue;
   $MODULE_BODY.='<li>'.$SORT_NAME.'&nbsp;<a href="javascript:open_files('.$SORT_ID.','.$CONTENT_ID.');"  title="'.$SUBJECT_TITLE.'" >'.$SUBJECT.'</a>&nbsp;('.$SEND_TIME.')</li>';

   $COUNT++;
   if($COUNT>$MAX_COUNT)
      break;
    
}//while
if($COUNT==0)
   $MODULE_BODY.= "<ul><li>"._("暂无最新文件")."</li></ul>";
$MODULE_BODY.= "<ul>";

$MODULE_BODY.= "<ul>";

$MODULE_BODY.='<script>
function get_file(req)
{
   var obj = $("module_'.$MODULE_ID.'_ul");
   if(!obj) return;
   
   if(typeof(req) != "object")
   {
      obj.innerHTML = \'<img src="'.MYOA_STATIC_SERVER.'/static/images/loading.gif" align="absMiddle"> '._("加载中，请稍候……").'\';
      _get("file_folder.php", "MAX_COUNT='.$MAX_COUNT.'&SHOW_FLAG="+req+"&MODULE_SCROLL='.$MODULE_SCROLL.'&MODULE_ID='.$MODULE_ID.'", arguments.callee);
   }
   else
   {
      obj.innerHTML = req.status==200 ? req.responseText : ("'._("获取内容错误，代码：").'"+req.status);
   }
}
function open_files(SORT_ID,CONTENT_ID)
{
 URL="/general/file_folder/read.php?SORT_ID="+SORT_ID+"&CONTENT_ID="+CONTENT_ID+"&FROM_TABLE=1&BTN_CLOSE=1&show_flag=CONTENT";
 myleft=(screen.availWidth-780)/2;
 mytop=100
 mywidth=780;
 myheight=500;

 window.open(URL,"read_file"+CONTENT_ID,"height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
</script>';
}
?>