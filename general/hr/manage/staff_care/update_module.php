<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("ģ���ļ�����ɹ�");
include_once("inc/header.inc.php");
?>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("���պؿ�ģ�����")?></span></td>
</tr>
</table>
<?
if($_FILES["ATTACHMENT"]["name"]==""&&$ACTION != 'update')
{
   Message("",_("����ѡ��һ��ģ���ļ���"));
   Button_back();
   exit;	
}
if(substr($_FILES["ATTACHMENT"]["name"],-4)!=".swf"&&$_FILES["ATTACHMENT"]["name"]!="")
{
   Message("",_("ģ���ļ�����Ϊswf�ļ���"));
   Button_back();
   exit;
}

if($_FILES["ATTACHMENT"]["size"]==0)
{
   Message("",_("���ϴ���ȷ��swf���ļ���"));
   Button_back();
   exit;
}

$CURRENT_TIME=date("Y-m-d H:i:s",time());
if($ACTION == 'update')
{
	 if($_FILES["ATTACHMENT"]["name"]!="")
	 {
      $ATTACH	= upload_card();
      delete_attach($ATTACH_ID_OLD,$ATTACH_NAME_OLD);
   }
   if($ATTACH!="")
      $ATTACH = ",ATTACH='$ATTACH'";
	 $query="update HR_CARD_MODULE set CREATE_TIME='$CURRENT_TIME',SUIT_USERS='$TO_ID',MODULE_NAME='$MODULE_NAME',GREETING='$GREETING' ".$ATTACH." where MODULE_ID='$MODULE_ID'";
   exequery(TD::conn(),$query);
	 Message("",_("ģ���޸ĳɹ�!"));
}
else
{
   $ATTACH	= upload_card();
   $query="insert into HR_CARD_MODULE(CREATE_TIME,SUIT_USERS,MODULE_NAME,ATTACH,GREETING) values ('$CURRENT_TIME','$TO_ID','$MODULE_NAME','$ATTACH','$GREETING')";
   exequery(TD::conn(),$query);
   Message("",_("ģ���ϴ��ɹ�!"));
}
?>
<br />
<table border="0" width="15%" cellspacing="0" cellpadding="3" class="small" align="center">
<tr>
  <td class="Big">
  	 <input type="button" class="BigButton" value="<?=_("�������ģ��")?>" onclick="history.back();">
  </td>
  <td align="right">
  	 <input type="button" class="BigButton" value="<?=_("����")?>" onclick="window.location='card_module_list.php?connstatus=1';">
  </td>
</tr>
</table>
</body>
</html>

<?
function upload_card()
{
		$ATTACHMENTS=upload();
		$ATTACH_ID=$ATTACHMENTS["ID"];
		$ATTACH_NAME=$ATTACHMENTS["NAME"];
		$ATTACH_ID=substr($ATTACH_ID,0,-1);
		$ATTACH_NAME=substr($ATTACH_NAME,0,-1);
		$ATTACH = $ATTACH_ID.",".$ATTACH_NAME;
    return $ATTACH;
}
?>
