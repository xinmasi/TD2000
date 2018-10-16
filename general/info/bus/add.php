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
   Message(_("提示"),_("新建成功！"));
else
   Message(_("提示"),_("修改成功！"));
?>
<center><input type="button" class="BigButton" value="<?=_("返回")?>" onclick="history.go(-2);"></center>
</body>
</html>
