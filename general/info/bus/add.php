<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
mysql_select_db("BUS", TD::conn());

if($ID=="")
   $query="insert into $TABLE (LINEID,STARTTIME,ENDTIME,BUSTYPE,PASSBY) values('$LINEID','$STARTTIME','$ENDTIME','$BUSTYPE','$PASSBY')";
else
   $query="update $TABLE set LINEID='$LINEID',STARTTIME='$STARTTIME',ENDTIME='$ENDTIME',BUSTYPE='$BUSTYPE',PASSBY='$PASSBY' where ID='$ID'";

exequery(TD::conn(),$query);

if($ID=="")
   Message(_("��ʾ"),_("�½��ɹ���"));
else
   Message(_("��ʾ"),_("�޸ĳɹ���"));
?>
<center><input type="button" class="BigButton" value="<?=_("����")?>" onclick="history.go(-2);"></center>
</body>
</html>
