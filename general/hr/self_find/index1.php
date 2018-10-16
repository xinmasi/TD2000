<?
include_once("inc/auth.inc.php");

echo "<meta http-equiv=X-UA-Compatible content=IE=EmulateIE7>";
$HTML_PAGE_TITLE = _("员工自助查询");
include_once("inc/header.inc.php");
?>
<script>
var tmp="staff_detail.php";
function showDetail()
{
   if(document.form1.HR_SELF_TYPE.value==1)
   {
   	 frames["hr_self_detail"].location.href="staff_detail.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==2)
   {
   	 frames["hr_self_detail"].location.href="contract_list.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==3)
   {
   	 frames["hr_self_detail"].location.href="incentive_list.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==4)
   {
   	 frames["hr_self_detail"].location.href="license_list.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==5)
   {
   	 frames["hr_self_detail"].location.href="experience_list.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==6)
   {
   	 frames["hr_self_detail"].location.href="w_experience_list.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==7)
   {
   	 frames["hr_self_detail"].location.href="skills_list.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==8)
   {
   	 frames["hr_self_detail"].location.href="relatives_list.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==9)
   {
   	 frames["hr_self_detail"].location.href="transfer_list.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==10)
   {
   	 frames["hr_self_detail"].location.href="leave_list.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==11)
   {
   	 frames["hr_self_detail"].location.href="reinstatement_list.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==12)
   {
   	 frames["hr_self_detail"].location.href="evaluation_list.php";
         iFrameHeight();
   }
//   if(document.form1.HR_SELF_TYPE.value==13)
//   {
//   	 frames["hr_self_detail"].location.href="care_list.php";
//   	 iFrameHeight();
//   }
   if(document.form1.HR_SELF_TYPE.value==14)
   {
   	 frames["hr_self_detail"].location.href="record_list.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==15)
   {
   	 frames["hr_self_detail"].location.href="attendance_list.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==17)
   {
   	 frames["hr_self_detail"].location.href="welfare_list.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==18)
   {
   	 frames["hr_self_detail"].location.href="salary_list.php";
         iFrameHeight();
   }
   if(document.form1.HR_SELF_TYPE.value==19)
   {
   	 frames["hr_self_detail"].location.href="score_detail.php";
         iFrameHeight();
   }
}

//function iFrameHeight() {
//    var ifm= document.getElementById("hr_self_detail");
//    var subWeb = document.frames ? document.frames["hr_self_detail"].document : ifm.contentDocument;
//    if(ifm != null && subWeb != null)
//    {
//       ifm.height = subWeb.body.scrollHeight;
//    }
//}

function iFrameHeight()
{
    var pTar = document.getElementById("hr_self_detail");
    if (pTar)
    {
        if (pTar.contentDocument && pTar.contentDocument.body.offsetHeight)
        {
            pTar.height = pTar.contentDocument.body.offsetHeight;
        }
        else if (pTar.Document && pTar.Document.body.scrollHeight)
        {
            pTar.height = pTar.Document.body.scrollHeight;
        }

        if (pTar.contentDocument && pTar.contentDocument.body.offsetWidth)
        {
            pTar.width = pTar.contentDocument.body.offsetWidth;
        }
        else if (pTar.Document && pTar.Document.body.scrollWidth)
        {
            pTar.width = pTar.Document.body.scrollWidth;
        }
　　}
}
</script>
<body class="bodycolor">
<form enctype="multipart/form-data" action="" method="post" name="form1">
<table border="0" width="98%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td>
       <select name="HR_SELF_TYPE" class="BigSelect" align="left" onChange="showDetail();">
          <option value="1"><?=_("人事档案")?></option>
          <option value="2"><?=_("合同信息")?></option>
          <option value="3"><?=_("奖惩信息")?></option>
          <option value="4"><?=_("证照信息")?></option>
          <option value="5"><?=_("学习经历")?></option>
          <option value="6"><?=_("工作经历")?></option>
          <option value="7"><?=_("劳动技能")?></option>
          <option value="8"><?=_("社会关系")?></option>
          <option value="9"><?=_("人事调动")?></option>
          <option value="10"><?=_("离职信息")?></option>
          <option value="11"><?=_("复职信息")?></option>
          <option value="12"><?=_("职称评定")?></option>
          <option value="14"><?=_("培训记录")?></option>
          <option value="15"><?=_("考勤记录")?></option>
       		<option value="17"><?=_("福利信息")?></option>
       		<option value="18"><?=_("薪酬信息")?></option>
       		<option value="19"><?=_("绩效考核")?></option>
       </select>
    </td>
	</tr>
</table>
</form>
<iframe id="hr_self_detail" style="width: 100%; min-height: 95%" name="hr_self_detail" src="staff_detail.php" height="100%" width="100%" frameBorder="0" frameSpacing="0" align="center" onLoad="iFrameHeight()" ></iframe>
</body>
</html>