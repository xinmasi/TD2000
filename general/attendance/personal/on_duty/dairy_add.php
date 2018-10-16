<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
<?
if($submit!="")
{
	 $CUR_TIME=date("Y-m-d H:i:s",time());
   $query="update ZBAP_PAIBAN set ZB_RZ='$ZB_RZ',ZB_RZ_TIME='$CUR_TIME' where PAIBAN_ID='$PAIBAN_ID'";     
   exequery(TD::conn(),$query);  	 	
   Message("",_("保存成功"));
}
?>
<script Language="JavaScript">window.parent.opener.location.reload();</script>
<br>
<center><input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="parent.close();"></center>