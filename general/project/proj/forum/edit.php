<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/editor.php");

$HTML_PAGE_TITLE = _("�༭�򷢱�����");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css" />
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<SCRIPT>
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
  if(document.form1.SUBJECT.value == "")
  {
    alert("<?=_("���ⲻ��Ϊ�գ�")?>");
    return false;
  }

  if(document.form1.SUBJECT.value.length > "100")
  {
    alert("<?=_("�����ַ����ȳ���")?>100!");
    return false;
  }

  document.form1.OP.value="1";

  return true;
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?PROJ_ID=<?=$PROJ_ID?>&PAGE_START=<?=$PAGE_START?>&MSG_ID=<?=$MSG_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}

function InsertImage(src)
{
   AddImage2Editor('CONTENT', src);
}
</SCRIPT>


<body class="bodycolor">

<?
if($MSG_ID!="")
   $TITLE = _("�༭����");
else
   $TITLE = _("��������");

//------- ��������Ϣ -------
$PROJ_ID = intval($PROJ_ID);
$query = "SELECT PROJ_NAME from PROJ_PROJECT where PROJ_ID=".$PROJ_ID;
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $PROJ_NAME = $ROW["PROJ_NAME"];

   $PROJ_NAME=str_replace("<","&lt",$PROJ_NAME);
   $PROJ_NAME=str_replace(">","&gt",$PROJ_NAME);
   $PROJ_NAME=stripslashes($PROJ_NAME);
}

//----------������Ȩ�޿���---------
//if(!($DEPT_ID=="ALL_DEPT" || find_id($DEPT_ID,$_SESSION["LOGIN_DEPT_ID"]) || find_id($PRIV_ID,$_SESSION["LOGIN_USER_PRIV"]) || find_id($USER_ID1,$_SESSION["LOGIN_USER_ID"]) || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
//	 exit;

//------- ������Ϣ -------
if($MSG_ID!="")
{
   $MSG_ID = intval($MSG_ID);
   //------- �༭��ظ� -------
   $query = "SELECT * from PROJ_FORUM where MSG_ID='$MSG_ID'";
   $cursor = exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $MSG_ID = $ROW["MSG_ID"];
      $USER_ID = $ROW["USER_ID"];
      $SUBJECT = $ROW["SUBJECT"];
      $SUBJECT=td_htmlspecialchars($SUBJECT);
      $CONTENT = $ROW["CONTENT"];
      $ATTACHMENT_ID = $ROW["ATTACHMENT_ID"];
      $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
      $CONTENT=str_replace("\"","'",$CONTENT);

   }

   //------- �ظ� -------
   if($REPLY==1)
   {
      $SUBJECT = "Re:".$SUBJECT;
      $CONTENT = "";
   }
   
   if($REPLY!=1 && $USER_ID!=$_SESSION["LOGIN_USER_ID"])
   {
	    message(_("��ʾ"),_("��Ч����"));
	    exit;
    }   
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="title_list"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><?=$PROJ_NAME?>  &raquo; <a href="index.php?PROJ_ID=<?=$PROJ_ID?>"><?=_("��Ŀ������")?> </a>  &raquo; <?=$TITLE?>
    </td>
  </tr>
</table>
<form name="form1" enctype="multipart/form-data" action="<? if($MSG_ID!="" && $REPLY=="") echo"update.php"; else echo "insert.php";?>" method="post" onSubmit="return CheckForm();">
<table class="TableBlock" width="100%" align="center">
     <tr>
      <td nowrap class="TableData" width="150px"><?=_("���⣺")?></td>
      <td class="TableData">
        <input type="text" name="SUBJECT" size="50" maxlength="90" class="BigInput" value="<?=$SUBJECT?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("���ݣ�")?></td>
      <td class="TableData">
<?
$editor = new Editor('CONTENT');
$editor->Height = '280';
$editor->Config = array('model_type' => '10');
$editor->Value = $CONTENT ;
$editor->Create() ;
?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("�����ĵ���")?></td>
      <td class="TableData">
<?
if($ATTACHMENT_ID=="" || $REPLY==1)
   echo _("�޸���");
else
   echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);
?>
      </td>
    </tr>
    <tr height="25">
      <td nowrap class="TableData"><?=_("����ѡ��")?></td>
      <td class="TableData">
         <script>ShowAddFile();</script>
      </td>
    </tr>
<?
if($MSG_ID!="" && $REPLY!="")
{
?>
    <tr>
      <td nowrap class="TableData"><?=_("���ѷ����ˣ�")?></td>
      <td class="TableData">
<?=sms_remind(42);?>
    </td>
    </tr>
<?
}

if($MSG_ID=="" && $REPLY=="")
{
	 $SEND_TITLE_FLAG=1;
?>
    <tr>
      <td nowrap class="TableData"><?=_("���ѱ���Ŀ������Ա��")?></td>
      <td class="TableData">
<?=sms_remind(42);?>
    </td>
    </tr>
<?
}
?>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
      <input type="hidden" value="<?=$SEND_TITLE_FLAG?>" name="SEND_TITLE_FLAG">
      <input type="hidden" value="<?=$ANONYMITY_YN?>" name="ANONYMITY_YN">
      <input type="hidden" value="<?=$PROJ_ID?>" name="PROJ_ID">
    	<input type="hidden" value="<?=$MSG_ID?>" name="MSG_ID">
    	<input type="hidden" value="<?=$REPLY?>" name="REPLY">
    	<input type="hidden" value="<?=$PAGE_START?>" name="PAGE_START">
    	<input type="hidden" name="OP" value="">
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
      <input type="submit" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
      <input type="button" class="BigButton" value="<?=_("����")?>" onClick="history.go(-1);">
      </td>
    </tr>
</table>
</form>

</body>
</html>
