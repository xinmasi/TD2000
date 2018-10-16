<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("������־��ѯ");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/diary.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function delete_diary(DIA_ID)
{
 msg='<?=_("ȷ��Ҫɾ�����յĹ�����־��")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?DIA_ID=" + DIA_ID;
  window.location=URL;
 }
}
</script>


<body class="bodycolor">

<?
  //----------- �Ϸ���У�� ---------
  if($BEGIN_DATE!="")
  {
    $TIME_OK=is_date($BEGIN_DATE);

    if(!$TIME_OK)
    { Message(_("����"),_("��ʼ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if($END_DATE!="")
  {
    $TIME_OK=is_date($END_DATE);

    if(!$TIME_OK)
    { Message(_("����"),_("��ֹ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
      Button_Back();
      exit;
    }
  }

?>
<div class="PageHeader">
   <div class="title">[<?=$USER_NAME?> - <?=_("������־��ѯ")?>]</div>
</div>
<?
if($DIARY_COPY_TIME!="")
{
   $DIARY_TABLE_NAME=TD::$_arr_db_master['db_archive'].".DIARY". $DIARY_COPY_TIME;
   $DIARY_COMMENT_TABLE_NAME=TD::$_arr_db_master['db_archive'].".DIARY_COMMENT". $DIARY_COPY_TIME;  
}
else
{
   $DIARY_TABLE_NAME="DIARY";
   $DIARY_COMMENT_TABLE_NAME="DIARY_COMMENT";   
}
$WHERE_STR="";
if($BEGIN_DATE!="")
{
	 $BEGIN_DATE.=" 00:00:00";
   $WHERE_STR = " and DIA_DATE >= '$BEGIN_DATE'";
}
if($END_DATE!="")
{
   $END_DATE.=" 23:59:59";
   $WHERE_STR .= " and DIA_DATE <= '$END_DATE'";
}
if($SUBJECT!="")
   $WHERE_STR .= " and SUBJECT like '%$SUBJECT%'";
$SUBJECT1=$SUBJECT;   
if($ATTACHMENT_NAME!="")
   $WHERE_STR .= " and ATTACHMENT_NAME like '%$ATTACHMENT_NAME%'";
if($KEY1!="" || $KEY2!="" || $KEY3!="")
{
	 if($KEY1=="")
	    $KEY1="!@#$%^&*()__)(*&^%$#@";
	 if($KEY2=="")
	    $KEY2="!@#$%^&*()__)(*&^%$#@";
	 if($KEY3=="")
	    $KEY3="!@#$%^&*()__)(*&^%$#@";		    	    
	 $WHERE_STR .= " and (CONTENT like '%$KEY1%' or CONTENT like '%$KEY2%' or CONTENT like '%$KEY3%')";
}    

//============================ ��ʾ��־ =======================================
$query = "SELECT DIA_ID,DIA_DATE,SUBJECT,COMPRESS_CONTENT,DIA_TYPE,ATTACHMENT_ID,ATTACHMENT_NAME,LAST_COMMENT_TIME,CONTENT from ".$DIARY_TABLE_NAME." where USER_ID='$USER_ID' and DIA_TYPE!='2'".$WHERE_STR;
$query .= " order by DIA_DATE desc,DIA_ID desc";
$cursor= exequery(TD::conn(),$query);
$DIA_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $DIA_COUNT++;

   $DIA_ID=$ROW["DIA_ID"];
   $DIA_DATE=$ROW["DIA_DATE"];
   $DIA_DATE=strtok($DIA_DATE," ");
   $SUBJECT=$ROW["SUBJECT"];
   $NOTAGS_CONTENT=$ROW["CONTENT"];    
   $CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]);   
   if($CONTENT=="")   
      $CONTENT=$NOTAGS_CONTENT;   
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  if($SUBJECT=="")
     $SUBJECT=csubstr(strip_tags($CONTENT),0,30).(strlen($CONTENT)>30?"...":"");

   if($DIA_COUNT==1)
   {
?>
<table class="TableList" width="100%">
<?
   }
   if($DIA_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
   <tr class="<?=$TableLine?>">
     <td nowrap align="center" width="100"><?=$DIA_DATE?></td>
     <td><a href="read.php?DIARY_COPY_TIME=<?=$DIARY_COPY_TIME?>&DIA_ID=<?=$DIA_ID?>&USER_ID=<?=$USER_ID?>&USER_NAME=<?=$USER_NAME?>"><?=$SUBJECT?></a></td>
     <td><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,0,1,1)?></td>
     <?
     if($DIARY_COPY_TIME=="")
     {
     ?>
     <td nowrap align="center" width="100">
     	 <a href="share.php?DIA_ID=<?=$DIA_ID?>&USER_ID=<?=$USER_ID?>&SUBJECT=<?=$SUBJECT?>&USER_NAME=<?=$USER_NAME?>"><?=_("ָ������")?></a>
     	 <a href="comment.php?DIA_ID=<?=$DIA_ID?>&USER_ID=<?=$USER_ID?>"><?=_("����")?></a>
     </td>
    <?
     }
    ?>
   </tr>
<?
}

if($DIA_COUNT==0)
{
  Message("",_("�޷�����������־��¼"));
}
else
{
?>
  <thead class="TableHeader">
     <td nowrap align="center"><?=_("����")?> <img border=0 src="<?=MYOA_IMG_SERVER?>/images/arrow_down.gif" width="11" height="10"></td>
     <td nowrap align="center"><?=_("��־����")?></td>
     <td nowrap align="center"><?=_("����")?></td>
     <?
     if($DIARY_COPY_TIME=="")
     {
     ?>
     <td nowrap align="center"><?=_("����")?></td>
     <?
     }
     ?>
  </thead>
  </table>
<?
}
?>

<br>
<?
if($DIA_COUNT>0)
{
	 $MSG2 = sprintf(_("�� %d ƪ��־"), $DIA_COUNT);
   Message("",$MSG2);
}
?>
<div align="center">
 <input type="button"  value="<?=_("����")?>" class="BigButton" onClick="location='export.php?DIARY_COPY_TIME=<?=$DIARY_COPY_TIME?>&USER_ID=<?=$USER_ID?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&SUBJECT=<?=$SUBJECT1?>&KEY1=<?=$KEY1?>&KEY2=<?=$KEY2?>&KEY3=<?=$KEY3?>';" title="<?=_("����word�ļ�")?>">&nbsp;&nbsp;	
 <input type="button"  value="<?=_("����")?>" class="BigButton" onClick="location='user_diary.php?USER_ID=<?=$USER_ID?>&USER_NAME=<?=$USER_NAME?>';">
</div>

</body>
</html>
