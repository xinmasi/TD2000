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
   Message("",_("����ɹ�"));
}
?>
<script Language="JavaScript">window.parent.opener.location.reload();</script>
<br>
<center><input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="parent.close();"></center>