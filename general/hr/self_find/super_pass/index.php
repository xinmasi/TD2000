<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("pass_check_common.php");

$SYS_PARA_ARRAY = get_sys_para("SALARY_PASS");
$PARA_VALUE=$SYS_PARA_ARRAY["SALARY_PASS"]; 

$HTML_PAGE_TITLE = _("ÐÞ¸ÄÃÜÂë");
include_once("inc/header.inc.php");
?>
<body class="bodycolor" onload="document.form1.PASS0.focus();">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/login.gif" align="absmiddle"><span class="big3"> <?=_("ÐÞ¸ÄÃÜÂë")?></span><br>
    </td>
  </tr>
</table>

<table class="TableBlock" width="400" align="center">
  <form method="post" action="update.php" name="form1" >
  <tr>
  	<td class="TableData" width="40%"><?=_("Ô­ÃÜÂë£º")?></td>
  	<td class="TableData" >
  	  <input type="password" name="PASS0"  class="BigInput" size="30">
  	</td>
  </tr>
  
  <tr>
  	<td class="TableData" ><?=_("ÐÂÃÜÂë£º")?></td>
  	<td class="TableData" >
  	  <input type="password" name="PASS1"  class="BigInput" size="30" maxlength="20">
  	</td>
  </tr>
  
  <tr>
  	<td class="TableData" ><?=_("È·ÈÏÐÂÃÜÂë£º")?></td>
  	<td class="TableData" >
  	  <input type="password" name="PASS2"  class="BigInput" size="30" maxlength="20">
  	</td>
  </tr>
  
  <tr align="center" >
      <td class="TableData" colspan="2" >
        <input type="submit" value="<?=_("±£´æÐÞ¸Ä")?>" class='BigButton'>
      </td>
  </tr>
</table>
</form>

</body>
</html>
