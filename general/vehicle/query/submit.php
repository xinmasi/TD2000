<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("����ʹ����Ϣ�޸�");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle" width="22" height="18"><span class="big3"> <?=_("����ʹ����Ϣ�޸�")?></span>
    </td>
  </tr>
</table>	

<?
//----------- �Ϸ�?�У�---------
if($VU_END!="")
{
  $TIME_OK=is_date_time($VU_END);

  if(!$TIME_OK)
  { Message(_("����"),_("����ʱ���ʽ���ԣ�Ӧ���� 1999-1-2 09:30:00"));
    Button_Back();
    exit;
  }
}

if($VU_START!=""&&$VU_END!=""&&$VU_START> $VU_END)
{
   Message(_("����"),_("��ʼ���ڲ���С�ڽ������ڣ�"));
   Button_Back();
   exit;
}

if($VU_MILEAGE!=""&&!is_numeric($VU_MILEAGE))
{
   Message(_("����"),_("�������ӦΪ���֣�"));
   Button_Back();
   exit;
}

$query="update VEHICLE_USAGE set VU_END='$VU_END',VU_DESTINATION='$VU_DESTINATION',VU_MILEAGE='$VU_MILEAGE',VU_REMARK='$VU_REMARK' where VU_ID='$VU_ID'";
exequery(TD::conn(),$query);

Message(_("��ʾ"),_("�޸ĳɹ���"));
?>
<br><br>
<center>
        <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();" title="<?=_("�رմ���")?>">
</center>
</body>
</html>