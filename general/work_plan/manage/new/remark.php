<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�����ƻ�˵��");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<table class="TableBlock" width="90%" align="center">
  <tr>
      <td nowrap class="TableContent"><?=_("˵����")?></td>
      <td class="TableData">
      	<b><?=_("������Χ")?> </b><?=_("��ָ�ڡ������ƻ���ѯ���У����Բ�ѯ���ù����ƻ�����Ա��")?><br>
      	<b><?=_("������")?> </b><?=_("��ָ�ù����ƻ���ִ����Ա������д������־��")?><br>
      	<b><?=_("������")?> </b><?=_("��ִָ�С�����ù����ƻ�����Ա������д������־��")?><br>   
      	<b><?=_("��ע�쵼")?> </b><?=_("��Ĭ�ϰ��������ˡ������ˡ���ע�쵼�Ըù����ƻ�����עȨ��")?>  	      	
      </td>
    </tr>    
</table>
<br>
<center>
<input type="button" value="<?=_("ȷ��")?>" class="BigButton" onclick="window.close();">
</center>
</body>
</html>