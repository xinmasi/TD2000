<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("OAʹ�û����Զ�¼��");
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("����OAʹ�û���")?></span>
    </td>
  </tr>
</table>
<br />
<form name="form1" action="#" method="post">
	<table width="80%" class="TableBlock" align="center">
   <tr class="TableHeader">
      <td colspan=2><?=_("��ѡ��Ҫ������ֵ�OAģ��")?></td>
   </tr>
   <tr class="TableData" align="center">
      <td><?=_("��ѡ��ģ�飺")?></td>
      <td class="TableData"  align="left" nowrap>
  <input type="checkbox" name="DO_LIST" id="email" value="email" /><label for="email" ><?=_("�ʼ�ģ��")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="workflow" value="workflow" /><label for="workflow" ><?=_("������ģ��")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="attend" value="attend" /><label for="attend" ><?=_("����")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="calendar" value="calendar" /><label for="calendar" ><?=_("�ճ̰���")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="diary" value="diary" /><label for="diary" ><?=_("������־")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="project" value="project" /><label for="project" ><?=_("��Ŀ����")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="news" value="news" /><label for="news" ><?=_("���Ź���")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="knowledge" value="knowledge" /><label for="knowledge" ><?=_("֪ʶ��")?></label>&nbsp;
	<input type="checkbox" name="DO_LIST" id="hrms" value="hrms" /><label for="hrms" ><?=_("���µ���")?></label>&nbsp;
	</td>
   </tr>
   <tr class="TableFooter" align="center">
      <td colspan=2>
      	<input type="button" class="BigButton" value="<?=_("��ʼ������")?>" onClick="csh_submit()"/>&nbsp;&nbsp;
      	<input type="button" class="BigButton" value="<?=_("����OAʹ�û���")?>" onClick="man_submit()"/><br><font color=red>
      (<?=_("ע�⣺���������Ҫ�ϳ�ʱ�䣬�϶��������Դ������OAϵͳʹ�����ٵ�ʱ����м��㣬������Ҫ���ĵȴ�һ��ʱ�䣬ֱ�����ּ��������ʾ��");?>)</font>
      </td>
   </tr>
</table>
<div align="center">
<br /><br />
<?
Message(_("��ʾ"),_("�����ڡ����������á��У�����OAʹ�û��ֵĸ����ֵ���á����������OAʹ�û��֡���ť���м��㡣"));

?>
</div>
</form>
</body>
</html>
