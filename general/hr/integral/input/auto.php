<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("OA使用积分自动录入");
include_once("inc/header.inc.php");
?>


<script>
function man_submit()
{
    var autoinfo="";
    for(i=0;i<document.getElementsByName("DO_LIST").length;i++)
    {
        if(document.getElementsByName("DO_LIST").item(i).checked)
        {
            autoinfo+=document.getElementsByName("DO_LIST").item(i).value+',';
        }
    }
    autoinfo1="collect.php?autoinfo="+autoinfo;
	document.form1.action=autoinfo1;
	document.form1.submit();
}
function csh_submit()
{
	document.form1.action='csh_zz.php';
	document.form1.submit();
}
</script>


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("计算OA使用积分")?></span>
    </td>
  </tr>
</table>
<br />
<form name="form1" action="#" method="post">
	<table width="80%" class="TableBlock" align="center">
   <tr class="TableHeader">
      <td colspan=2><?=_("请选择要计算积分的OA模块")?></td>
   </tr>
   <tr class="TableData" align="center">
      <td><?=_("请选择模块：")?></td>
      <td class="TableData"  align="left" nowrap>
  <input type="checkbox" name="DO_LIST" id="email" value="email" /><label for="email" ><?=_("邮件模块")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="workflow" value="workflow" /><label for="workflow" ><?=_("工作流模块")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="attend" value="attend" /><label for="attend" ><?=_("考勤")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="calendar" value="calendar" /><label for="calendar" ><?=_("日程安排")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="diary" value="diary" /><label for="diary" ><?=_("工作日志")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="project" value="project" /><label for="project" ><?=_("项目管理")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="news" value="news" /><label for="news" ><?=_("新闻公告")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="knowledge" value="knowledge" /><label for="knowledge" ><?=_("知识库")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="hrms" value="hrms" /><label for="hrms" ><?=_("人事档案")?></label>&nbsp;
	</td>
   </tr>
   <tr class="TableFooter" align="center">
      <td colspan=2>
      	<input type="button" class="BigButton" value="<?=_("初始化积分")?>" onClick="csh_submit()"/>&nbsp;&nbsp;
      	<input type="button" class="BigButton" value="<?=_("计算OA使用积分")?>" onClick="man_submit()"/><br><font color=red>
      (<?=_("注意：计算积分需要较长时间，较多服务器资源，请在OA系统使用人少的时候进行计算，并且需要耐心等待一段时间，直到出现计算完成提示。");?>)</font>
      </td>
   </tr>
</table>
<div align="center">
<br /><br />
<?
Message(_("提示"),_("请先在“积分项设置”中，进行OA使用积分的各项分值设置。点击“计算OA使用积分”按钮进行计算。"));

?>
</div>
</form>
</body>
</html>
