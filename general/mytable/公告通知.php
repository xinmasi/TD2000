<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="4";
$MODULE_DESC=_("公告通知");
$MODULE_BODY=$MODULE_OP=$MODULE_TYPE="";
$MODULE_HEAD_CLASS = 'notify';
if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_TYPE = '<a href="javascript:get_notify(\'\');">'._("全部公告").'</a>  <a href="javascript:get_notify(\'no_read0\');">'._("未读公告").'</a>  ';
$MODULE_OP='<a href="#" title="'._("全部").'" class="all_more" onclick="view_more(\'notify_show\',\''._("公告通知").'\',\'/general/notify/show/\');">'._("全部").'&nbsp;</a>';
$TYPE_ARRAY = get_code_array("NOTIFY");
while(list($TYPE_NO, $TYPE_NAME) = each($TYPE_ARRAY))
{
   $MODULE_TYPE .= '<a href="javascript:get_notify(\''.$TYPE_NO.'\');">'.$TYPE_NAME.'</a>  ';
}
$MODULE_BODY.= "<ul>";
$CUR_DATE=date("Y-m-d",time());
$CUR_DATE_U=time();
$COUNT=0;
$PARA_ARRAY = get_sys_para("NOTIFY_TOP_DAYS");
while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
   $$PARA_NAME = $PARA_VALUE;
$WHERE_CLAUSE 	= " WHERE PUBLISH='1' ";
$WHERE_CLAUSE  .= " AND BEGIN_DATE<='$CUR_DATE_U' and (END_DATE>='$CUR_DATE_U' or END_DATE='0') ";
$WHERE_CLAUSE  .= " AND (TO_ID='ALL_DEPT' 
												 OR find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) 
												 OR find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)
												 OR find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) "
								           . priv_other_sql("PRIV_ID").dept_other_sql("TO_ID")." )";
$LIST_CLAUSE = " NOTIFY_ID,SUBJECT,READERS,FORMAT,TOP,TOP_DAYS,TYPE_ID,BEGIN_DATE,SUBJECT_COLOR ";						           
$query = "SELECT ".$LIST_CLAUSE." from NOTIFY ".$WHERE_CLAUSE;
$query .="order by TOP desc,BEGIN_DATE desc,SEND_TIME desc limit 0,$MAX_COUNT ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $COUNT++;
   $NOTIFY_ID=$ROW["NOTIFY_ID"];
   $SUBJECT=$ROW["SUBJECT"];
   $READERS=$ROW["READERS"];
   $FORMAT=$ROW["FORMAT"];
   $TOP=$ROW["TOP"];
   $TYPE_ID=$ROW["TYPE_ID"];
   $TOP_DAYS=$ROW["TOP_DAYS"];
   $BEGIN_DATE=date("Y-m-d",$ROW["BEGIN_DATE"]);
   $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
   if($TOP==1)
   {     
      if($NOTIFY_TOP_DAYS=="")
      {  
         if($TOP_DAYS!=0)
         {
            $DAYS = (strtotime($CUR_DATE) - strtotime($BEGIN_DATE))/ 86400;
            if($DAYS>$TOP_DAYS)
            {
               $Tquery="update NOTIFY set TOP='0' where NOTIFY_ID='$NOTIFY_ID'";
               exequery(TD::conn(),$Tquery);   
               $TOP=0;
            }
         }
      }
      else
      {
         if($TOP_DAYS>$NOTIFY_TOP_DAYS||$TOP_DAYS==0)
            $TOP_DAYS=$NOTIFY_TOP_DAYS;
         $DAYS = (strtotime($CUR_DATE) - strtotime($BEGIN_DATE))/ 86400;
         if($DAYS>$TOP_DAYS)
         {
            $Tquery="update NOTIFY set TOP='0' where NOTIFY_ID='$NOTIFY_ID'";
            exequery(TD::conn(),$Tquery);   
            $TOP=0;
         }
      }   
   }
   
   $SUBJECT_TITLE="";
   if(strlen($SUBJECT) > 50)
   {
      $SUBJECT_TITLE=$SUBJECT;
      $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
   }
   $SUBJECT=td_htmlspecialchars($SUBJECT);
   $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);
   
   $READER_COUNT=0;
   $READER_ARRAY=explode(",",$READERS);
   for($K=0;$K<count($READER_ARRAY);$K++)
      if(trim($READER_ARRAY[$K])!="")
         $READER_COUNT++;

   $TYPE_NAME=$TYPE_ARRAY[$TYPE_ID];
   if($TYPE_NAME!="")
      $TSUBJECT='<a href="/general/notify/show/notify.php?TYPE='.$TYPE_ID.'">'._("【").$TYPE_NAME._("】").'</a>';
   else
      $TSUBJECT='';
   if($TOP=="1")
      $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";
   else
      $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";
   $READ_COUNT_TITLE=_("阅读人数:").$READER_COUNT;
   $MODULE_BODY.='<li>'.$TSUBJECT.'<a href="javascript:open_notify('.$NOTIFY_ID.','.$FORMAT.');" title="'.$SUBJECT_TITLE.'">'.$SUBJECT.'</a><label title='.$READ_COUNT_TITLE.'>('.$READER_COUNT.')</label>&nbsp;('.$BEGIN_DATE.')';
  
   if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]))
      $MODULE_BODY.= '<img src="'.MYOA_STATIC_SERVER.'/static/images/new.gif" align="absmiddle" />';

   $MODULE_BODY.= "</li>";
}
if($COUNT==0)
   $MODULE_BODY.= "<li>"._("暂无公告通知")."</li>";

$MODULE_BODY.= "<ul>";
$MODULE_BODY.='
<script>
function open_notify(NOTIFY_ID,FORMAT)
{
 URL="/general/notify/show/read_notify.php?NOTIFY_ID="+NOTIFY_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100
 mywidth=780;
 myheight=500;
 if(FORMAT=="1")
 {
    myleft=0;
    mytop=0
    mywidth=screen.availWidth-10;
    myheight=screen.availHeight-40;
 }
 window.open(URL,"read_notify"+NOTIFY_ID,"height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function get_notify(req)
{
   var obj = $("module_'.$MODULE_ID.'_ul");
   if(!obj) return;
   
   if(typeof(req) != "object")
   {
      obj.innerHTML = \'<img src="'.MYOA_STATIC_SERVER.'/static/images/loading.gif" align="absMiddle"> '._("加载中，请稍候……").'\';
      _get("notify.php", "MAX_COUNT='.$MAX_COUNT.'&TYPE_ID="+req+"&MODULE_SCROLL='.$MODULE_SCROLL.'&MODULE_ID='.$MODULE_ID.'", arguments.callee);
   }
   else
   {
      obj.innerHTML = req.status==200 ? req.responseText : ("'._("获取内容错误，代码：").'"+req.status);
   }
}
</script>';
}
?>
