<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("车辆使用信息修改");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle" width="22" height="18"><span class="big3"> <?=_("车辆使用信息修改")?></span>
    </td>
  </tr>
</table>	

<?
//----------- 合法?孕Ｑ---------
if($VU_END!="")
{
  $TIME_OK=is_date_time($VU_END);

  if(!$TIME_OK)
  { Message(_("错误"),_("结束时间格式不对，应形如 1999-1-2 09:30:00"));
    Button_Back();
    exit;
  }
}

if($VU_START!=""&&$VU_END!=""&&$VU_START> $VU_END)
{
   Message(_("错误"),_("起始日期不能小于结束日期！"));
   Button_Back();
   exit;
}

if($VU_MILEAGE!=""&&!is_numeric($VU_MILEAGE))
{
   Message(_("错误"),_("申请里程应为数字！"));
   Button_Back();
   exit;
}

$query="update VEHICLE_USAGE set VU_END='$VU_END',VU_DESTINATION='$VU_DESTINATION',VU_MILEAGE='$VU_MILEAGE',VU_REMARK='$VU_REMARK' where VU_ID='$VU_ID'";
exequery(TD::conn(),$query);

Message(_("提示"),_("修改成功！"));
?>
<br><br>
<center>
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>">
</center>
</body>
</html>