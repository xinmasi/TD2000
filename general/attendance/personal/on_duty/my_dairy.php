<?
include_once("inc/auth.inc.php");

$query = "SELECT ZB_RZ from ZBAP_PAIBAN where PAIBAN_ID='$PAIBAN_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $ZB_RZ0=$ROW["ZB_RZ"];

$HTML_PAGE_TITLE = _("ֵ����־");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">

<body class="bodycolor">	
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/calendar.gif" align="absMiddle" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("ֵ����־")?></span>
    </td>
  </tr>
</table>
<br>
<form action="dairy_add.php"  method="post" name="form1">
<table class="TableBlock" width="100%" align="center">
  <tr>
    <td width="80"> <?=_("��־���ݣ�")?></td>
    <td class="TableData">
      <textarea name="ZB_RZ" class="BigInput" cols="34" rows="6"><?=$ZB_RZ0?></textarea>
    </td>     	  
  </tr>
  <tr>
    <td colspan="2" class="TableControl" align="center">
    	 <input type="hidden" name="PAIBAN_ID" value="<?=$PAIBAN_ID?>">
    	 <input type="submit" value="<?=_("ȷ��")?>" class="BigButton" name="submit">&nbsp;
    	 <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="javascript:window.close();">
    </td>    	  
  </tr>      
</table>
</form> 	
</body>
</html>