<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("工作计划说明");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<table class="TableBlock" width="90%" align="center">
  <tr>
      <td nowrap class="TableContent"><?=_("说明：")?></td>
      <td class="TableData">
      	<b><?=_("发布范围")?> </b><?=_("：指在“工作计划查询”中，可以查询到该工作计划的人员。")?><br>
      	<b><?=_("参与人")?> </b><?=_("：指该工作计划的执行人员，可以写进度日志。")?><br>
      	<b><?=_("负责人")?> </b><?=_("：指执行、管理该工作计划的人员，可以写进度日志。")?><br>   
      	<b><?=_("批注领导")?> </b><?=_("：默认包括负责人、创建人。批注领导对该工作计划有批注权。")?>  	      	
      </td>
    </tr>    
</table>
<br>
<center>
<input type="button" value="<?=_("确定")?>" class="BigButton" onclick="window.close();">
</center>
</body>
</html>