<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("固定资产详情");
include_once("inc/header.inc.php");
?>



<?
$CUR_DATE=date("Y-m-d",time());

$query="select * from CP_CPTL_INFO where CPTL_ID='$CPTL_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $CPTL_ID=$ROW["CPTL_ID"];
   $CPTL_NO=$ROW["CPTL_NO"];
   $CPTL_NAME=$ROW["CPTL_NAME"];
   $TYPE_ID=$ROW["TYPE_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $CPTL_VAL=$ROW["CPTL_VAL"];
   $CPTL_BAL=$ROW["CPTL_BAL"];
   $DPCT_YY=$ROW["DPCT_YY"];
   $MON_DPCT=$ROW["MON_DPCT"];
   $SUM_DPCT=$ROW["SUM_DPCT"];
   $CPTL_KIND=$ROW["CPTL_KIND"];
   $PRCS_ID=$ROW["PRCS_ID"];
   $FINISH_FLAG=$ROW["FINISH_FLAG"];
   $CREATE_DATE=$ROW["CREATE_DATE"];
   $DCR_DATE=$ROW["DCR_DATE"];
   $DCR_PRCS_ID=$ROW["DCR_PRCS_ID"];
   $FROM_YYMM=$ROW["FROM_YYMM"];
   $KEEPER=$ROW["KEEPER"];
   $REMARK=$ROW["REMARK"];
	 $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

   $query_name = "SELECT USER_NAME from USER where USER_ID = '$KEEPER'";
   $cursor_name= exequery(TD::conn(),$query_name);
   if($ROW_NAME=mysql_fetch_array($cursor_name)){
      $KEEPER = $ROW_NAME["USER_NAME"] != ""?$ROW_NAME["USER_NAME"]:$KEEPER;
   }

   $query = "SELECT * from CP_ASSET_TYPE where TYPE_ID='$TYPE_ID'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW1=mysql_fetch_array($cursor1))
    $TYPE_NAME=$ROW1["TYPE_NAME"];
   
   if($CPTL_KIND=="01")
      $CPTL_KIND_DESC=_("资产");
   else if($CPTL_KIND=="02")
      $CPTL_KIND_DESC=_("费用");
   $PRCS_ID=intval($PRCS_ID);  
   $query1="select * from CP_PRCS_PROP where PRCS_ID='$PRCS_ID'";
   $cursor1=exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $PRCS_LONG_DESC=$ROW1["PRCS_LONG_DESC"];
   $DCR_PRCS_ID=intval($DCR_PRCS_ID);
   $query1="select * from CP_PRCS_PROP where PRCS_ID='$DCR_PRCS_ID'";
   $cursor1=exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $DCR_PRCS_LONG_DESC=$ROW1["PRCS_LONG_DESC"];
   
   $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
       $DEPT_NAME=$ROW1["DEPT_NAME"];
   
   if($FINISH_FLAG=="1")
      $FINISH_FLAG_DESC=_("提足");
   else if($FINISH_FLAG=="0")
      $FINISH_FLAG_DESC=_("未提足");
?>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif"  width="24" height="24"><span class="big3"> <?=_("固定资产详情")?> - <?=$CPTL_NAME?></span><br>
    </td>
    </tr>
</table>

<hr width="100%" height="1" align="left" color="#FFFFFF">

<table class="TableBlock" width="100%">
  <tr>
      <td nowrap align="center" class="TableContent" width="30%"><?=_("资产编号")?></td>
      <td align="left" class="TableData"><?=$CPTL_NO?></td>
			<td align="center" class="TableData" rowspan="6" width="280">
				<?
					 $MODULE=attach_sub_dir();
					 $ATTACHMENT_ID1=$ATTACHMENT_ID;
					 $YM=substr($ATTACHMENT_ID1,0,strpos($ATTACHMENT_ID1,"_"));
					 if($YM)
							$ATTACHMENT_ID1=substr($ATTACHMENT_ID1,strpos($ATTACHMENT_ID,"_")+1);
					 $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID1,$ATTACHMENT_NAME); 
							
					 if($ATTACHMENT_NAME=="")
							echo _("暂无照片");
					 else
					 {
				?>
							 <a href="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME)?>&DIRECT_VIEW=1?>" title="<?=_("点击查看放大图片")?>" target="_blank"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME)?>"  width='250' border=1 alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME?>"></a>	
				<?
				}
				?>	
			</td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("资产名称")?></td>
      <td align="left" class="TableData"><?=$CPTL_NAME?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("资产类别")?></td>
      <td align="left" class="TableData"><?=$TYPE_NAME?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("所属部门")?> </td>
      <td align="left" class="TableData"><?=$DEPT_NAME?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("资产原值")?></td>
      <td nowrap align="left" class="TableData"><?=$CPTL_VAL?></td>
  </tr>
<?
 $query = "SELECT * from CP_ASSETCFG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $BAL_SORT=$ROW["BAL_SORT"];
 if($BAL_SORT=="01")
 {
    $BAL_DESC=_("残值");
    $PERCENTAGE="";
 }
 else if($BAL_SORT=="02")
 {
    $BAL_DESC=_("残值率");
    $PERCENTAGE="%";
 }
?>
  <tr>
      <td nowrap align="center" class="TableContent"><?=$BAL_DESC?></td>
      <td nowrap align="left" class="TableData"><?=$CPTL_BAL.$PERCENTAGE?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("折旧年限")?></td>
      <td nowrap align="left" class="TableData" colspan="2"><?=$DPCT_YY?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("月折旧额")?></td>
      <td align="left" class="TableData" colspan="2"><?=$MON_DPCT?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("累计折旧")?></td>
      <td nowrap align="left" class="TableData" colspan="2"><?=$SUM_DPCT?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("资产性质")?></td>
      <td nowrap align="left" class="TableData" colspan="2"><?=$CPTL_KIND_DESC?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("折旧提足")?> </td>
      <td nowrap align="left" class="TableData" colspan="2"><?=$FINISH_FLAG_DESC?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("增加日期")?></td>
      <td nowrap align="left" class="TableData" colspan="2"><?=$CREATE_DATE == "0000-00-00"?"":$CREATE_DATE?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("增加类型")?> </td>
      <td nowrap align="left" class="TableData" colspan="2"><?=$PRCS_LONG_DESC?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("启用日期")?></td>
      <td nowrap align="left" class="TableData" colspan="2"><?if($FROM_YYMM=="0000-00-00") echo _("未启用"); else echo $FROM_YYMM;?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("减少日期")?></td>
      <td nowrap align="left" class="TableData" colspan="2"><?if($DCR_DATE!="0000-00-00") echo $DCR_DATE;?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("减少类型")?></td>
      <td nowrap align="left" class="TableData"  colspan="2"><?=$DCR_PRCS_LONG_DESC?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("保管人")?></td>
      <td nowrap align="left" class="TableData"  colspan="2"><?=$KEEPER?></td>
  </tr>
  <tr>
      <td nowrap align="center" class="TableContent"><?=_("备注")?></td>
      <td align="left" class="TableData"  colspan="2"><?=$REMARK?></td>
  </tr>
  <tr>
      <td class="TableData" colspan="4">
        <?=get_field_table(get_field_text("CP_CPTL_INFO",$CPTL_ID))?>
      </td>
  </tr>  
  <tr class="TableControl">
      <td colspan="9" align="center">
           <input type="button" value="<?=_("打印")?>" class="BigButton" onclick="document.execCommand('Print');" title="<?=_("直接打印表格页面")?>">&nbsp;&nbsp;&nbsp;&nbsp;
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