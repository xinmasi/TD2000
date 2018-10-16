<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("学习经历详细信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"> <?=_("学习经历详细信息")?></span><br>
    </td>
  </tr>
</table>
<br>
<?
$query = "SELECT * from HR_STAFF_LEARN_EXPERIENCE where L_EXPERIENCE_ID='$L_EXPERIENCE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $START_DATE=$ROW["START_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $SCHOOL=$ROW["SCHOOL"];
   $SCHOOL_ADDRESS=$ROW["SCHOOL_ADDRESS"];
   $MAJOR=$ROW["MAJOR"];
   $ACADEMY_DEGREE=$ROW["ACADEMY_DEGREE"];
   $ACADEMY_DEGREE=get_hrms_code_name($ACADEMY_DEGREE,'STAFF_HIGHEST_SCHOOL');
   $DEGREE=$ROW["DEGREE"];
   $DEGREE=get_hrms_code_name($DEGREE,"EMPLOYEE_HIGHEST_DEGREE");
   $POSITION=$ROW["POSITION"];
   $AWARDING=$ROW["AWARDING"];
   $CERTIFICATES=$ROW["CERTIFICATES"];
   $WITNESS=$ROW["WITNESS"];
   $REMARK=$ROW["REMARK"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $ADD_TIME=$ROW["ADD_TIME"];
   $LAST_UPDATE_TIME =$ROW["LAST_UPDATE_TIME"];   
   
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
    if($STAFF_NAME1=="")
    {
    	 $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
         $cursor1= exequery(TD::conn(),$query1);
         if($ROW1=mysql_fetch_array($cursor1))
         $STAFF_NAME1=$ROW1["STAFF_NAME"];
	     $STAFF_NAME1=$STAFF_NAME1."("."<font color='red'>"._("用户已删除")."</font>".")";
      }
   
   if($LAST_UPDATE_TIME=="0000-00-00 00:00:00")
     $LAST_UPDATE_TIME="";
?>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("姓名：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STAFF_NAME1?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("所学专业：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$MAJOR?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("所获学历：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$ACADEMY_DEGREE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("所获学位：")?></td>
    <td align="left" class="TableData" width="180"><?=$DEGREE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("曾任班干：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$POSITION?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("证明人：")?></td>
    <td align="left" class="TableData" width="180"><?=$WITNESS?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("所在院校：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$SCHOOL?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("院校所在地：")?></td>
    <td align="left" class="TableData" width="180"><?=$SCHOOL_ADDRESS?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("开始日期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$START_DATE=="0000-00-00"?"":$START_DATE;?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("结束日期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$END_DATE=="0000-00-00"?"":$END_DATE;?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("获奖情况：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$AWARDING?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("所获证书：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$CERTIFICATES?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("备注：")?></td>
    <td align="left" class="TableData" colspan="3"><?=$REMARK?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("附件文档：")?></td>
    <td nowrap align="left" class="TableData" colspan="3">
<?
    if($ATTACHMENT_ID=="")
       echo _("无附件");
    else
       echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,0,1,0);

?>
    </td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("登记时间：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$ADD_TIME?></td>
  </tr>
  <tr>    
    <td nowrap align="left" width="120" class="TableContent"><?=_("最后修改时间：")?></td>
    <td nowrap align="left" class="TableData" width="180" colspan="3"><?=$LAST_UPDATE_TIME?></td>
  </tr>
  <tr align="center" class="TableControl">
    <td colspan="4">
      <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>">
    </td>
  </tr>
</table>
<?
}
else
  Message("",_("未找到相应记录！"));
?>
</body>

</html>
