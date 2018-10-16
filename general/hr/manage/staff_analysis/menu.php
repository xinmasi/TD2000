<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("人事分析菜单");
include_once("inc/header.inc.php");
?>
<script>
function iFrameWidth()
{
    var ifm= document.body.clientWidth;
    var ifmwidth=ifm-200;
    document.getElementById("hr_main1").style.width=ifmwidth+"px";
}
</script>
<style>
#center iframe{
    width: 94%;
    height: 142%;
    display: block;
    position: absolute;
    top:0;
    bottom:0;
    left:200px;
    right:0;
}
</style>

<body class="bodycolor">
<div style="width:200px">
<table class="small" width="100%" align="center" style='border-collapse:collapse' border=1 cellspacing=0 cellpadding=3 >
  <tr class="TableContent">
     <td><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/finance1.gif" WIDTH="18" HEIGHT="18" align="absmiddle"> <b><?=_("人事分析")?></b></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_INFO" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("人事档案")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_HT" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("合同管理")?></b></a></td>
   </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_JC" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("奖惩管理")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_ZZ" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("证照管理")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_XX" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("学习经历")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_JL" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("工作经历")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_JN" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("劳动技能")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_GX" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("社会关系")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_DD" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("人事调动")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_LZ" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("离职管理")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_FZ" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("复职管理")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_ZC" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("职称评定")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_GH" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("员工关怀")?></b></a></td>
  </tr>
<?
$sql = "select id from crscell.crs_report where id=771 and repno='BI53'";
$cursor = exequery(TD::conn(), $sql);
if($row = mysql_fetch_array($cursor))
{
?>
  <tr class="TableData">
    <td><a href="/general/reportshop/workshop/report/phpcell/index.php?repid=771&openmode=write&isquery=y&inline" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("入职时间统计")?></b></a></td>
  </tr>

  <tr class="TableData">
    <td><a href="/general/reportshop/workshop/report/phpcell/index.php?repid=774&openmode=write&isquery=y&inline" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("考勤统计分析")?></b></a></td>
  </tr>

   <tr class="TableData">
    <td><a href="/general/reportshop/workshop/report/phpcell/index.php?repid=772&openmode=write&isquery=y&inline" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("人员离职分析")?></b></a></td>
  </tr>

  <tr class="TableData">
    <td><a href="/general/reportshop/workshop/report/phpcell/index.php?repid=775&openmode=write&isquery=y&inline" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("人事档案一览表")?></b></a></td>
  </tr>

  <tr class="TableData">
    <td><a href="/general/reportshop/workshop/report/phpcell/index.php?repid=778&openmode=write&isquery=y&inline" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("当月试用到期人员名单")?></b></a></td>
  </tr>
<?}?>
</table>
</div>
<div id="center">
    <iframe name="hr_main" id="hr_main1" scrolling="no" noresize src="index1.php?MODULE=HR_INFO" frameborder="0" onLoad="iFrameWidth()"></iframe>
</div>
</body>
</html>
