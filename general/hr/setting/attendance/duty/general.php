<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���ù�����");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<body class="attendance">
<?
 $query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $DUTY_NAME=$ROW["DUTY_NAME"];
    $GENERAL=$ROW["GENERAL"];
 }
?>
<h5 class="attendance-title"><?=_("���ù�����")?>(<?=$DUTY_NAME?>)</h5>
<br>
<form action="general_submit.php"  method="post" name="form1">
 <table class="table table-middle table-bordered" width="450" align="center">
  
    <tr class="">
      <th nowrap> <?=_("��ѡ��")?> <?=$DUTY_NAME?> <?=_("�Ĺ�����")?></th>
    </tr>
    <tr>
      <td class="">
          <label for="WEEK1"><input type="checkbox" name="WEEK1" id="WEEK1"<?if(find_id($GENERAL,"1")) echo " checked";?>><?=_("����һ")?></label>
          <label for="WEEK2"><input type="checkbox" name="WEEK2" id="WEEK2"<?if(find_id($GENERAL,"2")) echo " checked";?>><?=_("���ڶ�")?></label>
          <label for="WEEK3"><input type="checkbox" name="WEEK3" id="WEEK3"<?if(find_id($GENERAL,"3")) echo " checked";?>><?=_("������")?></label>
          <label for="WEEK4"><input type="checkbox" name="WEEK4" id="WEEK4"<?if(find_id($GENERAL,"4")) echo " checked";?>><?=_("������")?></label>
          <label for="WEEK5"><input type="checkbox" name="WEEK5" id="WEEK5"<?if(find_id($GENERAL,"5")) echo " checked";?>><?=_("������")?></label>
          <label for="WEEK6"><input type="checkbox" name="WEEK6" id="WEEK6"<?if(find_id($GENERAL,"6")) echo " checked";?>><?=_("������")?></label>
          <label for="WEEK0"><input type="checkbox" name="WEEK0" id="WEEK0"<?if(find_id($GENERAL,"0")) echo " checked";?>><?=_("������")?></label>
      </td>
    </tr>
    
    <tr >
      <td colspan="2" nowrap style="text-align: center;">
        <input type="hidden" value="<?=$DUTY_TYPE?>" name="DUTY_TYPE">
        <input type="submit" value="<?=_("ȷ��")?>" class="btn btn-primary">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="btn" onClick="location='index.php'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>