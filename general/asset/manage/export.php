<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");

ob_end_clean();
$filename = _("固定资产");
Header("Cache-control: private");
Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; ".get_attachment_filename($filename.".xls"));

$HTML_PAGE_TITLE = _("固定资产查询");
include_once("inc/header.inc.php");
?>
<body>
  <table class="TableList" width="95%">
      <tr style="BACKGROUND: #D3E5FA; color: #000000; font-weight: bold;">
        <td nowrap align="center"><?=_("资产编号")?></td>
        <td nowrap align="center"><?=_("资产名称")?></td>
        <td nowrap align="center"><?=_("资产类别")?></td>
        <td nowrap align="center"><?=_("所属部门")?></td>
        <td nowrap align="center"><?=_("资产原值")?></td>
        <td nowrap align="center"><?=_("残值率")?>)</td>
        <td nowrap align="center"><?=_("折旧年限")?></td>
        <td nowrap align="center"><?=_("月折旧额")?></td>
        <td nowrap align="center"><?=_("累计折旧")?></td>
        <td nowrap align="center"><?=_("资产性质")?></td>
        <td nowrap align="center"><?=_("折旧提足")?></td>
        <td nowrap align="center"><?=_("增加日期")?></td>
        <td nowrap align="center"><?=_("增加类型")?></td>
        <td nowrap align="center"><?=_("启用日期")?></td>
        <td nowrap align="center"><?=_("减少日期")?></td>
        <td nowrap align="center"><?=_("减少类型")?></td>
        <td nowrap align="center"><?=_("折旧记录")?></td>
        <td nowrap align="center"><?=_("保管人")?></td>
        <td nowrap align="center"><?=_("备注")?></td>
        
      </tr>
<?
 //------------------------ 生成条件字符串 ------------------
 
 
 
  $CONDITION_STR="";
 if($CPTL_NO!="")
    $CONDITION_STR.=" and CPTL_NO like '%".$CPTL_NO."%'";
 if($CPTL_NAME!="")
    $CONDITION_STR.=" and CPTL_NAME like '%".$CPTL_NAME."%'";
 if($TYPE_ID!="")
    $CONDITION_STR.=" and TYPE_ID=$TYPE_ID";
 if($DEPT_ID!="")
    $CONDITION_STR.=" and CP_CPTL_INFO.DEPT_ID=$DEPT_ID";
 if($CPTL_KIND!="")
    $CONDITION_STR.=" and CPTL_KIND=$CPTL_KIND";
 if($PRCS_ID!="")
    $CONDITION_STR.=" and PRCS_ID=$PRCS_ID";
 if($DCR_PRCS_ID!="")
    $CONDITION_STR.=" and DCR_PRCS_ID=$DCR_PRCS_ID";
 if($FINISH_FLAG!="")
    $CONDITION_STR.=" and FINISH_FLAG=$FINISH_FLAG";
 if($CPTL_VAL_MIN!="")
    $CONDITION_STR.=" and CPTL_VAL>=$CPTL_VAL_MIN";
 if($CPTL_VAL_MAX!="")
    $CONDITION_STR.=" and CPTL_VAL<=$CPTL_VAL_MAX";
 if($CPTL_BAL_MIN!="")
    $CONDITION_STR.=" and CPTL_BAL>=$CPTL_BAL_MIN";
 if($CPTL_BAL_MAX!="")
    $CONDITION_STR.=" and CPTL_BAL<=$CPTL_BAL_MAX";
 if($CREATE_DATE_MIN!="")
    $CONDITION_STR.=" and CREATE_DATE>='$CREATE_DATE_MIN'";
 if($CREATE_DATE_MAX!="")
    $CONDITION_STR.=" and CREATE_DATE<='$CREATE_DATE_MAX'";
 if($DCR_DATE_MIN!="")
    $CONDITION_STR.=" and DCR_DATE>='$DCR_DATE_MIN'";
 if($DCR_DATE_MAX!="")
    $CONDITION_STR.=" and DCR_DATE<='$DCR_DATE_MAX'";
 if($FROM_YYMM_MIN!="")
    $CONDITION_STR.=" and FROM_YYMM>='$FROM_YYMM_MIN'";
 if($FROM_YYMM_MAX!="")
    $CONDITION_STR.=" and FROM_YYMM<='$FROM_YYMM_MAX'";
 if($KEEPER!="")
    $CONDITION_STR.=" and KEEPER like '%".$KEEPER."%'";
 if($REMARK!="")
    $CONDITION_STR.=" and REMARK like '%".$REMARK."%'";

$query="select * from CP_CPTL_INFO where 1=1 ".$CONDITION_STR." order by DEPT_ID,CREATE_DATE,CPTL_NO";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
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
   $DEF_FIELD_ARRAY=get_field_text("CP_CPTL_INFO", $CPTL_ID);
   
   $query = "SELECT * from CP_ASSET_TYPE where TYPE_ID='$TYPE_ID'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW1=mysql_fetch_array($cursor1))
    $TYPE_NAME=$ROW1["TYPE_NAME"];
   
   if($CPTL_KIND=="01")
      $CPTL_KIND_DESC=_("资产");
   else if($CPTL_KIND=="02")
      $CPTL_KIND_DESC=_("费用");
      
   $query1="select * from CP_PRCS_PROP where PRCS_ID='$PRCS_ID'";
   $cursor1=exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $PRCS_LONG_DESC=$ROW1["PRCS_LONG_DESC"];
   
   $query1="select * from CP_PRCS_PROP where PRCS_ID='$DCR_PRCS_ID'";
   $cursor1=exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $DCR_PRCS_LONG_DESC=$ROW1["PRCS_LONG_DESC"];
      
   $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
       $DEPT_NAME=$ROW1["DEPT_NAME"];
   
   $query1="select * from CP_DPCT_SUB where CPTL_ID='$CPTL_ID' order by PEPRE_DATE asc";
   $cursor1=exequery(TD::conn(),$query1);
   $CONTENT_COMENT="";
   while($ROW1=mysql_fetch_array($cursor1))
   {
     $PEPRE_DATE=$ROW1["PEPRE_DATE"];
     $FROM_YYMM=$ROW1["FROM_YYMM"];
     $TO_YYMM=$ROW1["TO_YYMM"];
     $DEPRE_AMT=$ROW1["DEPRE_AMT"];
     
     $CONTENT_COMENT.="<br>".$PEPRE_DATE."&nbsp;&nbsp;".$FROM_YYMM."&nbsp;&nbsp;&nbsp;&nbsp;".$TO_YYMM."&nbsp;&nbsp;&nbsp;&nbsp;".$DEPRE_AMT;
   }
   $CONTENT_COMENT=_("发生时间")."&nbsp;&nbsp;&nbsp;&nbsp;"._("起始时间")."&nbsp;&nbsp;".("结束时间")."&nbsp;&nbsp;".("折旧金额").$CONTENT_COMENT;
   
   if($FINISH_FLAG=="1")
      $FINISH_FLAG_DESC=_("提足");
   else if($FINISH_FLAG=="0")
      $FINISH_FLAG_DESC=_("未提足");

   $query1 = "SELECT * from CP_ASSETCFG";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
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
    <tr style="BACKGROUND: #FFFFFF;">
      <td><?=$CPTL_NO?></td>
      <td><?=$CPTL_NAME?></td>
      <td><?=$TYPE_NAME?></td>
      <td><?=$DEPT_NAME?></td>
      <td><?=$CPTL_VAL?></td>
      <td><?=$CPTL_BAL.$PERCENTAGE?></td>
      <td><?=$DPCT_YY?></td>
      <td><?=$MON_DPCT?></td>
      <td><?=$SUM_DPCT?></td>
      <td><?=$CPTL_KIND_DESC?></td>
      <td><?=$FINISH_FLAG_DESC?></td>
      <td><?=$CREATE_DATE?></td>
      <td><?=$PRCS_LONG_DESC?></td>
      <td><?if($FROM_YYMM=="0000-00-00") echo _("未启用"); else echo $FROM_YYMM;?></td>
      <td><?if($DCR_DATE!="0000-00-00") echo $DCR_DATE;?></td>
      <td><?=$DCR_PRCS_LONG_DESC?></td>
      <td><?=$CONTENT_COMENT?></td>     
      <td><?=$KEEPER?></td>
      <td><?=$REMARK?></td>
    </tr>
<?
 }
?>
  </table>

</body>
</html>