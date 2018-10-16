<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$PRIV_NO_FLAG=2;
$MODULE_ID="4";
include_once("inc/my_priv.php");


$HTML_PAGE_TITLE = _("������־��ѯ");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<?
  //----------- �Ϸ���У�� ---------
if($DATE1!="")
{
  $TIME_OK=is_date($DATE1);

  if(!$TIME_OK)
  { Message(_("����"),_("��ʼ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
    Button_Back();
    exit;
  }
}

if($DATE2!="")
{
  $TIME_OK=is_date($DATE2);

  if(!$TIME_OK)
  { Message(_("����"),_("��ֹ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
    Button_Back();
    exit;
  }
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/diary.gif" WIDTH="18" HEIGHT="18" align="absmiddle"><span class="big3"> <?=_("��ѯ���")?></span>
    </td>
  </tr>
</table>
<?
$WHERE_STR="";
if($DATE1!="")
{
   $URL_DATE1 = $DATE1;
   $DATE1.=" 00:00:00";
   $WHERE_STR = " and DIA_DATE >= '$DATE1'";
}
if($DATE2!="")
{
   $URL_DATE2 = $DATE2;  
   $DATE2.=" 23:59:59";
   $WHERE_STR .= " and DIA_DATE <= '$DATE2'";
}
if($USER_ID!="")
   $WHERE_STR .= " and find_in_set(DIARY.USER_ID ,'$USER_ID')";
//if($DIA_TYPE=="")
  // $WHERE_STR .= " and DIA_TYPE!='2'";
//else
  // $WHERE_STR .= " and DIA_TYPE='$DIA_TYPE'";

//============================ ��ʾ��־ =======================================
$query = "SELECT DIARY.DIA_ID,DIARY.DIA_DATE,DIARY.DIA_TYPE,DIARY.SUBJECT,DIARY.COMPRESS_CONTENT,DIARY.CONTENT,DIARY.ATTACHMENT_ID,DIARY.ATTACHMENT_NAME,DIARY.LAST_COMMENT_TIME,DIARY.USER_ID from DIARY left join USER on USER.USER_ID = DIARY.USER_ID where 1=1 ".$WHERE_STR;
$query .= " order by DIA_DATE desc,DIA_ID desc";
$cursor= exequery(TD::conn(),$query);
$DIA_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $DIA_ID=$ROW["DIA_ID"];
   $DIA_DATE=$ROW["DIA_DATE"];
   $DIA_DATE=strtok($DIA_DATE," ");
   $DIA_TYPE=$ROW["DIA_TYPE"];
   $SUBJECT=$ROW["SUBJECT"];
   $NOTAGS_CONTENT=$ROW["CONTENT"];    
   $CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]);
   if($CONTENT=="")   
      $CONTENT=$NOTAGS_CONTENT;     
   $USER_ID=$ROW["USER_ID"];
   
   if(!is_user_priv($USER_ID, $MY_PRIV))
      continue;
   $DIA_COUNT++;   
   
   $query1 = "SELECT USER_NAME,DEPT_ID from USER where USER_ID='$USER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
   {
      $USER_NAME=$ROW1["USER_NAME"];
      $DEPT_ID=$ROW1["DEPT_ID"];      
   }
   
   $DEPT_NAME= dept_long_name($DEPT_ID);
       
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

   if($SUBJECT=="")
      $SUBJECT=csubstr(strip_tags($CONTENT),0,30).(strlen($CONTENT)>30?"...":"");

   $query1 = "SELECT count(*) from DIARY_COMMENT where DIA_ID='$DIA_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $COMMENT_COUNT=$ROW1[0];

   if($DIA_COUNT==1)
   {
?>
   <table class="TableList" width="95%" align="center">
<?
   }
   
   if($DIA_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
   <tr class="<?=$TableLine?>">
     <td nowrap align="center"><?=$DIA_DATE?></td>
     <td nowrap align="center" width="60"><?=$USER_NAME?></td> 
     <td nowrap align="center" width="60"><?=$DEPT_NAME?></td>     
     <td><a href="diary_read.php?DIA_ID=<?=$DIA_ID?>&FROM=1&USER_NAME=<?=urlencode($USER_NAME)?>&DATE1=<?=$URL_DATE1?>&DATE2=<?=$URL_DATE2?>&SUBJECT=<?=urlencode($URL_SUBJECT)?>"><?=$SUBJECT?></a></td>
     <td><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,0,1,1)?></td>
   </tr>
<?
}//while

if($DIA_COUNT==0)
{
  Message("",_("�޷�����������־��¼"));
}
else
{
?>
  <thead class="TableHeader">
     <td nowrap align="center" width="80"><?=_("����")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
     <td nowrap align="center" width="50"><?=_("����")?></td>
     <td nowrap align="center" width="50"><?=_("����")?></td>
     <td nowrap align="center"><?=_("��־����")?></td>
     <td nowrap align="center"><?=_("����")?></td>
  </thead>
  </table>
<?
}

if($DIA_COUNT > 0)
{
  Message("",sprintf(_("�� %d ƪ��־"), $DIA_COUNT));
}
?>
<div align="center">
	<input type="button"  class="BigButton" value="<?=_("�ر�")?>" onclick="window.close();">
</div>
</body>
</html>
