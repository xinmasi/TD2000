<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("���·����˵�");
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
     <td><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/finance1.gif" WIDTH="18" HEIGHT="18" align="absmiddle"> <b><?=_("���·���")?></b></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_INFO" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("���µ���")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_HT" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("��ͬ����")?></b></a></td>
   </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_JC" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("���͹���")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_ZZ" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("֤�չ���")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_XX" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("ѧϰ����")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_JL" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("��������")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_JN" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("�Ͷ�����")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_GX" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("����ϵ")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_DD" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("���µ���")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_LZ" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("��ְ����")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_FZ" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("��ְ����")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_ZC" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("ְ������")?></b></a></td>
  </tr>
  <tr class="TableData">
    <td><a href="index1.php?MODULE=HR_GH" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("Ա���ػ�")?></b></a></td>
  </tr>
<?
$sql = "select id from crscell.crs_report where id=771 and repno='BI53'";
$cursor = exequery(TD::conn(), $sql);
if($row = mysql_fetch_array($cursor))
{
?>
  <tr class="TableData">
    <td><a href="/general/reportshop/workshop/report/phpcell/index.php?repid=771&openmode=write&isquery=y&inline" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("��ְʱ��ͳ��")?></b></a></td>
  </tr>

  <tr class="TableData">
    <td><a href="/general/reportshop/workshop/report/phpcell/index.php?repid=774&openmode=write&isquery=y&inline" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("����ͳ�Ʒ���")?></b></a></td>
  </tr>

   <tr class="TableData">
    <td><a href="/general/reportshop/workshop/report/phpcell/index.php?repid=772&openmode=write&isquery=y&inline" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("��Ա��ְ����")?></b></a></td>
  </tr>

  <tr class="TableData">
    <td><a href="/general/reportshop/workshop/report/phpcell/index.php?repid=775&openmode=write&isquery=y&inline" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("���µ���һ����")?></b></a></td>
  </tr>

  <tr class="TableData">
    <td><a href="/general/reportshop/workshop/report/phpcell/index.php?repid=778&openmode=write&isquery=y&inline" target="hr_main"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" WIDTH="16" HEIGHT="16" align="absmiddle"> <b><?=_("�������õ�����Ա����")?></b></a></td>
  </tr>
<?}?>
</table>
</div>
<div id="center">
    <iframe name="hr_main" id="hr_main1" scrolling="no" noresize src="index1.php?MODULE=HR_INFO" frameborder="0" onLoad="iFrameWidth()"></iframe>
</div>
</body>
</html>
