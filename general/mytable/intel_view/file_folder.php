<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();
$FILE_MODULE_BODY.= "<ul>";
$COUNT=0;
if($SHOW_FLAG=="1")
{
   $query = "SELECT SORT_ID,SORT_NAME,USER_ID,OWNER from FILE_SORT where SORT_TYPE!='4' and SORT_PARENT=0 order by SORT_NO,SORT_NAME limit 0,100";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $SORT_ID=$ROW["SORT_ID"];
      $SORT_NAME=$ROW["SORT_NAME"];
      $USER_ID=$ROW["USER_ID"];
      $OWNER=$ROW["OWNER"];

      $SHOW_ARRAY[$COUNT]['SORT_ID'] = $SORT_ID;
      $SHOW_ARRAY[$COUNT]['SORT_NAME'] = $SORT_NAME;

      $ACCESS_PRIV=explode("|",$USER_ID);
      if($USER_ID!=$_SESSION["LOGIN_USER_ID"] && !check_priv($USER_ID) && !check_priv($OWNER))
         continue;

      $COUNT++;
      if($COUNT>$MAX_COUNT*2)
         break;

   }//while

   if($COUNT==0)
      $FILE_MODULE_BODY.= "<ul><li>"._("暂无可访问的目录")."</li></ul>";
   else
   {
      for($I = 0;$I < $COUNT;$I++)
      {
         $LI='<li><a target="_blank" href="/general/file_folder/index1.php?SORT_ID='.$SHOW_ARRAY[$I]['SORT_ID'].'">'.td_htmlspecialchars($SHOW_ARRAY[$I]['SORT_NAME']).'</a></li>';
         if($I%2 == 0)
            $FILE_MODULE_BODY_LEFT .=$LI;
         else
            $FILE_MODULE_BODY_RIGHT.=$LI;
      }

      if($FILE_MODULE_BODY_LEFT != "")
         $FILE_MODULE_BODY.='<div style="float:left;width:49%;"><ul>'.$FILE_MODULE_BODY_LEFT .'</ul></div>';
      if($FILE_MODULE_BODY_RIGHT!= "")
         $FILE_MODULE_BODY.='<div style="float:left;width:49%;"><ul>'.$FILE_MODULE_BODY_RIGHT.'</ul></div>';
   }
   $FILE_MODULE_BODY.= "<ul>";
}
else if($SHOW_FLAG=="0"||$SHOW_FLAG=="")
{
   $FILE_MODULE_BODY.= "<ul>";
   $COUNT=0;
   $query = "select a.CONTENT_ID,a.SUBJECT,a.SEND_TIME,b.SORT_ID,b.SORT_NAME,b.USER_ID,b.OWNER from FILE_CONTENT a,FILE_SORT b where a.SORT_ID=b.SORT_ID and b.SORT_TYPE!='4' and not find_in_set('".$_SESSION["LOGIN_USER_ID"]."',a.READERS) order by a.SEND_TIME desc limit 0,100";
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
      $FILE_MODULE_BODY.='<li>'.$SORT_NAME.'&nbsp;<a href="javascript:open_files('.$SORT_ID.','.$CONTENT_ID.');"  title="'.$SUBJECT_TITLE.'">'.$SUBJECT.'</a>&nbsp;('.$SEND_TIME.')</li>';

      $COUNT++;
      if($COUNT>$MAX_COUNT)
         break;

   }//while

   if($COUNT==0)
      $FILE_MODULE_BODY.= "<ul><li>"._("暂无最新文件")."</li></ul>";
   $FILE_MODULE_BODY.= "<ul>";
}

if($MODULE_SCROLL=="true" && stristr($FILE_MODULE_BODY, "href"))
{
   $FILE_MODULE_BODY='<marquee id="module_'.$MODULE_ID.'_marquee" height="100%" direction="up" behavior=scroll scrollamount=2 scrolldelay=100 onmouseover="this.stop()" onmouseout="this.start()" border=0>'.$FILE_MODULE_BODY.'</marquee>';
}
echo $FILE_MODULE_BODY;
?>