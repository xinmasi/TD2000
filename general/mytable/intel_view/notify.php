<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();
$TYPE_NEW_ID=$TYPE_ID;
$NOTIFY_MODULE_BODY.= "<ul>";
$CUR_DATE=date("Y-m-d",time());
$CUR_DATE_U=time();
$COUNT=0;
$TYPE_ARRAY = get_code_array("NOTIFY");
$PARA_ARRAY=get_sys_para("NOTIFY_TOP_DAYS");
while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
   $$PARA_NAME = $PARA_VALUE;
$LIST_CLAUSE = " NOTIFY_ID,SUBJECT,READERS,FORMAT,TOP,TOP_DAYS,TYPE_ID,BEGIN_DATE,SUBJECT_COLOR ";
$WHERE_CLAUSE 	= " WHERE PUBLISH='1' ";
if($TYPE_ID=="")
	$WHERE_CLAUSE  .= " AND BEGIN_DATE<='$CUR_DATE_U' and (END_DATE>='$CUR_DATE_U' or END_DATE='0') ";
else
{
	if($TYPE_ID!="no_read0")
		$WHERE_CLAUSE  .= " AND TYPE_ID='$TYPE_ID' ";
	else
		$WHERE_CLAUSE  .= " AND not find_in_set('".$_SESSION["LOGIN_USER_ID"]."',READERS) ";
	$WHERE_CLAUSE  .= " AND BEGIN_DATE<='$CUR_DATE_U' and (END_DATE>='$CUR_DATE_U' or END_DATE='0') ";
}
$WHERE_CLAUSE  .= " AND (TO_ID='ALL_DEPT' 
													 OR find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) 
													 OR find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)
													 OR find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) "
									. priv_other_sql("PRIV_ID").dept_other_sql("TO_ID")." )";

$ORDER_CLAUSE 	= " ORDER BY TOP desc,BEGIN_DATE desc,SEND_TIME desc ";
$LIMIT_CLAUSE = " LIMIT 0,$MAX_COUNT ";
$query = "SELECT ".$LIST_CLAUSE ." from NOTIFY "
             . $WHERE_CLAUSE
				 . $ORDER_CLAUSE
				 . $LIMIT_CLAUSE ;
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
   $BEGIN_DATE=$ROW["BEGIN_DATE"];
   $BEGIN_DATE=date("Y-m-d",$BEGIN_DATE);
   $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
   if($TOP==1)
   {   
      if($_SESSION["LOGIN_USER_PRIV"]!="1")
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
   if($TYPE_NAME!=""&&$TYPE_NEW_ID=="")
      $TSUBJECT='<a href="/general/notify/show/notify.php?TYPE='.$TYPE_ID.'">'._("【").$TYPE_NAME._("】").'</a>';
   else
      $TSUBJECT='';
   if($TOP=="1")
      $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";
   else
       $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";
   $READ_COUNT_TITLE=_("阅读人数:").$READER_COUNT;  
   $NOTIFY_MODULE_BODY.='<li>'.$TSUBJECT.'<a href="javascript:open_notify('.$NOTIFY_ID.','.$FORMAT.');" title="'.$SUBJECT_TITLE.'">'.$SUBJECT.'</a><label title='.$READ_COUNT_TITLE.'>('.$READER_COUNT.')</label>&nbsp;('.$BEGIN_DATE.')';
   if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]))
      $NOTIFY_MODULE_BODY.= '<img src="'.MYOA_STATIC_SERVER.'/static/images/new.gif" align="absmiddle" />';

   $NOTIFY_MODULE_BODY.= "</li>";
}

if($COUNT==0)
{
   if($TYPE_NEW_ID=="")
      $NOTIFY_MODULE_BODY.= "<li>"._("暂无公告通知")."</li>";
   else
   {
      if($TYPE_ID!="no_read0")
         $NOTIFY_MODULE_BODY.= "<li>"._("暂无此类别的公告通知")."</li>";
      else
         $NOTIFY_MODULE_BODY.= "<li>"._("暂无未读的公告通知")."</li>";
   }
}
$NOTIFY_MODULE_BODY.= "</ul>";

if($MODULE_SCROLL=="true" && stristr($NOTIFY_MODULE_BODY, "href"))
{
   $NOTIFY_MODULE_BODY='<marquee id="module_'.$MODULE_ID.'_marquee" height="100%" direction="up" behavior=scroll scrollamount=2 scrolldelay=100 onmouseover="this.stop()" onmouseout="this.start()" border=0>'.$NOTIFY_MODULE_BODY.'</marquee>';
}
echo $NOTIFY_MODULE_BODY;
?>