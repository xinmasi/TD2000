<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("�½���ϵ��");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//----------- �Ϸ���У�� ---------
if($BIRTHDAY!="")
{
  $TIME_OK=is_date($BIRTHDAY);

  if(!$TIME_OK)
  { Message(_("����"),_("��ʼ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
?>

<br>
<div align="center">
 <input type="button" value="<?=_("����")?>" class="BigButton" onClick="history.back();">
</div>

<?
    exit;
  }
}

//--------- �ϴ����� ----------
if($ATTACHMENT!="")
{
   $ATTACHMENTS=upload();
   $ATTACHMENT_ID=trim($ATTACHMENTS["ID"],",");
   $ATTACHMENT_NAME=trim($ATTACHMENTS["NAME"],"*");
}

//------------------- ���� -----------------------
$query="insert into ADDRESS(USER_ID,GROUP_ID,PSN_NAME,SEX,BIRTHDAY,NICK_NAME,MINISTRATION,MATE,CHILD,DEPT_NAME,ADD_DEPT,POST_NO_DEPT,TEL_NO_DEPT,FAX_NO_DEPT,ADD_HOME,POST_NO_HOME,TEL_NO_HOME,MOBIL_NO,BP_NO,EMAIL,OICQ_NO,ICQ_NO,NOTES,PSN_NO,ATTACHMENT_ID,ATTACHMENT_NAME) ";
$query.=" values ('','$GROUP_ID','$PSN_NAME','$SEX','$BIRTHDAY','$NICK_NAME','$MINISTRATION','$MATE','$CHILD','$DEPT_NAME','$ADD_DEPT','$POST_NO_DEPT','$TEL_NO_DEPT','$FAX_NO_DEPT','$ADD_HOME','$POST_NO_HOME','$TEL_NO_HOME','$MOBIL_NO','$BP_NO','$EMAIL','$OICQ_NO','$ICQ_NO','$NOTES','$PSN_NO','$ATTACHMENT_ID','$ATTACHMENT_NAME')";
exequery(TD::conn(),$query);

$ADD_ID1=mysql_insert_id();
save_field_data("ADDRESS",$ADD_ID1,$_POST);
?>

<script>
//parent.menu.location.reload();
location="index.php?GROUP_ID=<?=$GROUP_ID?>";
</script>

</body>
</html>
