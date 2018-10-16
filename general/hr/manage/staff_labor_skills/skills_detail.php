<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("劳动技能详细信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"> <?=_("劳动技能详细信息")?></span><br>
    </td>
  </tr>
</table>
<br>
<?
$query = "SELECT * from HR_STAFF_LABOR_SKILLS where SKILLS_ID='$SKILLS_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $ABILITY_NAME=$ROW["ABILITY_NAME"];
   $SPECIAL_WORK=$ROW["SPECIAL_WORK"];
   $SKILLS_LEVEL=$ROW["SKILLS_LEVEL"];
   $SKILLS_CERTIFICATE=$ROW["SKILLS_CERTIFICATE"];
   $ISSUE_DATE=$ROW["ISSUE_DATE"];
   $EXPIRE_DATE=$ROW["EXPIRE_DATE"];
   $EXPIRES=$ROW["EXPIRES"];
   $ISSUING_AUTHORITY=$ROW["ISSUING_AUTHORITY"];
   $REMARK=$ROW["REMARK"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $ADD_TIME=$ROW["ADD_TIME"];
   $LAST_UPDATE_TIME =$ROW["LAST_UPDATE_TIME"];
      
   if($LAST_UPDATE_TIME=="0000-00-00 00:00:00")
     $LAST_UPDATE_TIME="";
      
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
    if($STAFF_NAME1=="")
	  {
	     $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
         $cursor1= exequery(TD::conn(),$query1);
         if($ROW1=mysql_fetch_array($cursor1))
         $STAFF_NAME1=$ROW1["STAFF_NAME"];
	     $STAFF_NAME1=$STAFF_NAME1."("."<font color='red'>"._("用户已删除")."</font>".")";
	   }
?>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("姓名：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STAFF_NAME1?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("技能名称：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$ABILITY_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("特种作业：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?if($SPECIAL_WORK==1) echo _("是");if($SPECIAL_WORK==0)echo _("否");?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("级别：")?></td>
    <td align="left" class="TableData" width="180"><?=$SKILLS_LEVEL?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("技能证：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?if($SKILLS_CERTIFICATE==1) echo _("是");if($SKILLS_CERTIFICATE==0) echo _("否");?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("发证日期：")?></td>
    <td align="left" class="TableData" width="180"><?=$ISSUE_DATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("有效期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$EXPIRES?><?=_("（年）")?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("到期日期：")?></td>
    <td align="left" class="TableData" width="180"><?=$EXPIRE_DATE=="0000-00-00"?"":$EXPIRE_DATE;?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("发证机关/单位：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$ISSUING_AUTHORITY?></td>
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
