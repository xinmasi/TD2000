<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�����ջ�����");
include_once("inc/header.inc.php");

$query = "SELECT * from SYS_PARA where PARA_NAME ='VECHICLE_TAKE_BACK';";
$cursor= exequery(TD::conn(),$query);
$ROW=mysql_fetch_array($cursor);
$BACK_TYPE=$ROW["PARA_VALUE"];
?>


<body class="bodycolor">
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
		<tr>
			<td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" HEIGHT="20"><span class="big3"> <?=_("�����ջ�����")?></span></td>
		</tr>
	</table>
	
	<form  action="vechicle_take_back.submit.php" method="post" name="form1">
		<table class="TableBlock" width="60%" align="center" style="wideth:70%; margin-top:20px;">
			<tr >
				<td nowrap class="TableData" width="20%"><?=_("�����ջط�ʽ��")?></td>
				<td class="TableData">
					<input type="radio" name="BACK_TYPE" value="1" <?if($BACK_TYPE!='0') echo 'checked';?> > �ֶ�&nbsp;&nbsp;
					<input type="radio" name="BACK_TYPE" value="0" <?if($BACK_TYPE=='0') echo 'checked';?> > �Զ�        
				</td>
			</tr>
			<tr>
				<td nowrap class="TableData" colspan="2" style="text-align: center;"><input type="submit" value="����" class="BigButtonA"></td>				
	    	</tr>
		</table>
	</form>

</body>
</html>