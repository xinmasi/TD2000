<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���Ӵ���");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query = "SELECT * from HR_CODE where CODE_NO='$CODE_NO' and PARENT_NO='$PARENT_NO'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   Message(_("��ʾ"),_("������").$CODE_NO._("�Ѵ��ڣ�"));
   Button_Back();
   exit;
}
    
$query="insert into HR_CODE (CODE_NO,CODE_NAME,CODE_ORDER,PARENT_NO) values ('$CODE_NO','$CODE_NAME','$CODE_ORDER','$PARENT_NO')";
exequery(TD::conn(),$query);
Message("",_("���ӳɹ���"));

$query = "SELECT * from HR_CODE where CODE_NO='$PARENT_NO';";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $CODE_ID=$ROW["CODE_ID"];
}
?>

<center>
<input type="button" value="����" class="BigButtonA" onclick="location='index.php?CODE_ID=<?=$CODE_ID?>';">
</center>


</body>
</html>
