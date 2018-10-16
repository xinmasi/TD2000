<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("证照详细信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>

<body class="bodycolor">
<?
$query = "SELECT * from HR_STAFF_LICENSE where LICENSE_ID='$LICENSE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $STAFF_NAME=$ROW["STAFF_NAME"];   
  $LICENSE_TYPE=$ROW["LICENSE_TYPE"];
  $LICENSE_NO=$ROW["LICENSE_NO"];
  $LICENSE_NAME=$ROW["LICENSE_NAME"];
  $NOTIFIED_BODY=$ROW["NOTIFIED_BODY"];
  $GET_LICENSE_DATE=$ROW["GET_LICENSE_DATE"];
  $EFFECTIVE_DATE=$ROW["EFFECTIVE_DATE"];
  $EXPIRATION_PERIOD=$ROW["EXPIRATION_PERIOD"];
  $EXPIRE_DATE=$ROW["EXPIRE_DATE"];
  $STATUS=$ROW["STATUS"];
  $REMARK=$ROW["REMARK"];
	$ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
  $ADD_TIME =$ROW["ADD_TIME"];
   
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
   
	 $LICENSE_TYPE=get_hrms_code_name($LICENSE_TYPE,"HR_STAFF_LICENSE1");
	 $STATUS=get_hrms_code_name($STATUS,"HR_STAFF_LICENSE2");
?>
<table class="TableBlock" align="center" width="82%">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("姓名：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STAFF_NAME1?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("证照类型：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$LICENSE_TYPE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("证照编号：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$LICENSE_NO?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("证照名称：")?></td>
    <td align="left" class="TableData" width="180"><?=$LICENSE_NAME?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("取证日期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$GET_LICENSE_DATE?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("生效日期：")?></td>
    <td align="left" class="TableData" width="180"><?=$EFFECTIVE_DATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("期限限制：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?if($EXPIRATION_PERIOD==1) echo _("是");if($EXPIRATION_PERIOD==0) echo _("否");?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("到期日期：")?></td>
    <td align="left" class="TableData" width="180"><?=$EXPIRE_DATE?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("发证机构：")?></td>
    <td align="left" class="TableData" width="180"><?=$NOTIFIED_BODY?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("状态：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STATUS?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("备注：")?></td>
    <td align="left" class="TableData" colspan="3"><?=$REMARK?></td>
  </tr>
  
<?
$ATTACH_ARRAY = trim_inserted_image($CONTENT, $ATTACHMENT_ID, $ATTACHMENT_NAME);
if($ATTACH_ARRAY["NAME"]!="")
{
?>
    <tr>
      <td nowrap align="left" width="120" class="TableContent"><?=_("附件文档")?>:</td> <br>
      <td> <?=attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],1,1,1)?></td>
    </tr>
<?
}

if($ATTACH_ARRAY["IMAGE_COUNT"]>0)
{
?>
    <tr>
      <td nowrap align="left" width="120" class="TableContent">
        <?=_("附件图片")?>: <br><br>
			</td>
		 <td>	
<?
   $ATTACHMENT_ID_ARRAY=explode(",",$ATTACH_ARRAY["ID"]);
   $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACH_ARRAY["NAME"]);
   $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
      if($ATTACHMENT_ID_ARRAY[$I]=="")
         continue;

      $MODULE=attach_sub_dir();
      $ATTACHMENT_ID1=$ATTACHMENT_ID_ARRAY[$I];
      $YM=substr($ATTACHMENT_ID1,0,strpos($ATTACHMENT_ID1,"_"));
      if($YM)
         $ATTACHMENT_ID1=substr($ATTACHMENT_ID1,strpos($ATTACHMENT_ID,"_")+1);
      $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID1,$ATTACHMENT_NAME_ARRAY[$I]);

      if(is_image($ATTACHMENT_NAME_ARRAY[$I]))
      {
         $IMG_ATTR=td_getimagesize(attach_real_path($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]));
         if(is_array($IMG_ATTR) && $IMG_ATTR[0]>0 && $IMG_ATTR[1]>0)
            $WIDTH=floor($IMG_ATTR[0]*100/$IMG_ATTR[1]);
         else
            $WIDTH=100;
?>
                <a href="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="0"  width="<?=$WIDTH?>" height="100" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>"></a><?=_("　")?>
<?
      }
   }
?>
     </td>
   </tr>
<?
}
?>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("登记时间：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$ADD_TIME?></td>
  </tr>
</table>
<?
}
else
  Message("",_("未找到相应记录！"));
?>
</body>

</html>
