<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("ѡ���ڷ�ʽ");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script Language="JavaScript">

</script>

<body class="attendance">
<?
$SYS_PARA_ARRAY = get_sys_para("DUTY_MACHINE");
$DUTY_MACHINE=$SYS_PARA_ARRAY["DUTY_MACHINE"];
?>
<h5 class="attendance-title"><?=_("ѡ���ڷ�ʽ")?></h5><br>
<form action="submit.php" method="post" name="form1">
	<table width="60%" class="table table-small table-bordered" align="center">
     <tr class="">
      <th colspan=2 style="text-align:center;vertical-align:middle;"><?=_("ѡ���ڷ�ʽ")?></th>
     </tr>
   	 <tr>
	    <td class="" style="text-align:center;vertical-align:middle;">
	       <input type="radio" name="KAOQIN" value="1" <? if($DUTY_MACHINE==1) echo checked; ?> /><?=_("���ڻ�")?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	       <input type="radio" name="KAOQIN" value="0" <? if($DUTY_MACHINE==0) echo checked; ?> /><?=_("�ֶ�����")?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	       <input type="radio" name="KAOQIN" value="2" <? if($DUTY_MACHINE==2) echo checked; ?> /><?=_("�Զ�����")?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    </td>
    </tr>
   	<tr>
        <td align="center" valign="top" colspan="2" class="" style="text-align:center;vertical-align:middle;">
           <input type="submit" class="btn btn-primary" value="<?=_("����")?>">&nbsp;&nbsp;&nbsp;&nbsp;
           <input type="button" class="btn " value="<?=_("����")?>" onclick="location='../index.php#dutyOrno'">
        </td>
    </tr>
   </table>
</form>	
</body>
</html>