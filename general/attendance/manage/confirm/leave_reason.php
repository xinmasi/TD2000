<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("��׼ԭ��");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("��׼ԭ��")?></span>
    </td>
  </tr>
</table>
<br>

<table class="TableBlock" width="450" align="center" >
  <form action="leave_confirm.php"  method="post" name="form1">  
   <tr>
     <td nowrap class="TableContent"> <?=_("��׼ԭ��")?></td>
     <td class="TableData" colspan="1">
       <textarea name="REASON" class="BigInput" cols="50" rows="16"></textarea>
     </td>
   </tr> 
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      <input type="hidden" value="<?=$LEAVE_ID?>" name="LEAVE_ID">
      <input type="hidden" value="<?=$CONFIRM?>" name="CONFIRM">
      <input type="hidden" value="<?=$USER_ID?>" name="USER_ID">
      <input type="hidden" value="<?=$MOBILE_FLAG?>" name="MOBILE_FLAG">
      <input type="submit" value="<?=_("ȷ��")?>" class="BigButton">&nbsp;&nbsp;
      <input type="button" class="BigButton" value="<?=_("�ر�")?>" onClick="window.close();" title="<?=_("�رմ���")?>">
    </td>
  </form>
</table>

</body>
</html>