<?php
include_once("inc/auth.inc.php");
include("inc/FusionCharts/FusionCharts.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");


include_once("inc/header.inc.php");
?>

<script language="Javascript" src="<?=MYOA_JS_SERVER?>/inc/FusionCharts/FusionCharts.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>


<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("日程安排统计结果")?></span><br>
    </td>
  </tr>
</table>
<body class="bodycolor">
	 <table class="TableBlock" width="450" align="center">
  <form action="count.php?SEND_TIME_MIN=<?=$SEND_TIME_MIN?>&SEND_TIME_MAX=<?=$SEND_TIME_MAX?>&CAL_LEVEL=<?=$CAL_LEVEL?>&OVER_STATUS=<?=$OVER_STATUS?>"  method="post" name="form1">
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("日期：")?></td>
      <td class="TableData">
        <input type="text" name="SEND_TIME_MIN" size="12" maxlength="10" class="BigInput" value="<?=$SEND_TIME_MIN?>" onClick="WdatePicker()">
      <?=_("至")?>&nbsp;
        <input type="text" name="SEND_TIME_MAX" size="12" maxlength="10" class="BigInput" value="<?=$SEND_TIME_MAX?>" onClick="WdatePicker()">
      
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("优先程度：")?></td>
      <td class="TableData">
        <select name="CAL_LEVEL" class="BigSelect">
          <option value=""  <? if($CAL_LEVEL=="" )echo "selected";?>><?=_("所有")?></option>
          <option value="0" <? if($CAL_LEVEL=="0") echo "selected";?>><?=cal_level_desc("")?></option>
          <option value="1" <? if($CAL_LEVEL=="1") echo "selected";?>><?=cal_level_desc("1")?></option>
          <option value="2" <? if($CAL_LEVEL=="2") echo "selected";?>><?=cal_level_desc("2")?></option>
          <option value="3" <? if($CAL_LEVEL=="3") echo "selected";?>><?=cal_level_desc("3")?></option>
          <option value="4" <? if($CAL_LEVEL=="4") echo "selected";?>><?=cal_level_desc("4")?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("状态：")?></td>
      <td class="TableData">
        <select name="OVER_STATUS" class="BigSelect">
          <option value="" <? if($OVER_STATUS=="") echo "selected";?>><?=_("所有")?></option>
          <option value="1" <? if($OVER_STATUS==1) echo "selected";?>><?=_("未开始")?></option>
          <option value="2" <? if($OVER_STATUS==2) echo "selected";?>><?=_("进行中")?></option>
          <option value="3" <? if($OVER_STATUS==3) echo "selected";?>><?=_("已超时")?></option>
          <option value="4" <? if($OVER_STATUS==4) echo "selected";?>><?=_("已完成")?></option>
        </select>
      </td>
    </tr>
  
    <tr>
    	<td align="center" colspan=2>
     <input type="submit" value="<?=_("确定")?>"  class="BigButton">&nbsp; <input type="button" value="<?=_("返回")?>"  class="BigButton" onclick="window.location='index.php'">
     </td>
    </tr>
 </table>
 </form>
<center>
<?
$CUR_DATE=date("Y-m-d",time());
 $CUR_TIME=date("Y-m-d H:i:s",time());
 $CUR_TIME=strtotime($CUR_TIME);
  //-----------合法性校验---------
  if($SEND_TIME_MIN!="")
  {
    $TIME_OK=is_date($SEND_TIME_MIN);

    if(!$TIME_OK)
    { 
    	$MSG1 = sprintf(_("日期的格式不对，应形如 %s"), $CUR_DATE);
			Message(_("错误"),$MSG1);
      Button_Back();
      exit;
    }
    $SEND_TIME_MIN=$SEND_TIME_MIN." 00:00:00";
    $SEND_TIME_MIN=strtotime($SEND_TIME_MIN);
  }

  if($SEND_TIME_MAX!="")
  {
    $TIME_OK=is_date($SEND_TIME_MAX);

    if(!$TIME_OK)
    { 
			$MSG2 = sprintf(_("日期的格式不对，应形如 %s"), $CUR_DATE);
			Message(_("错误"),$MSG2);
      Button_Back();
      exit;
    }
    $SEND_TIME_MAX=$SEND_TIME_MAX." 23:59:59";
    $SEND_TIME_MAX=strtotime($SEND_TIME_MAX);
  }

 //------------------------ 生成条件字符串 ------------------
 $CONDITION_STR="";
 if($CAL_LEVEL!="")
 {
	 if($CAL_LEVEL=="0")
	    $CONDITION_STR.=" and CAL_LEVEL=''";
	 else if($CAL_LEVEL=="1")
	    $CONDITION_STR.=" and CAL_LEVEL='1'";
	 else if($CAL_LEVEL=="2")
	    $CONDITION_STR.=" and CAL_LEVEL='2'";
	 else if($CAL_LEVEL=="3")
	    $CONDITION_STR.=" and CAL_LEVEL='3'";
	 else if($CAL_LEVEL=="4")
	    $CONDITION_STR.=" and CAL_LEVEL='4'";
}
 if($CONTENT!="")
    $CONDITION_STR.=" and CONTENT like '%".$CONTENT."%'";
 if($SEND_TIME_MIN!="")
    $CONDITION_STR.=" and CAL_TIME>='$SEND_TIME_MIN'";
 if($SEND_TIME_MAX!="")
    $CONDITION_STR.=" and END_TIME<='$SEND_TIME_MAX'";

 if($OVER_STATUS=="1")
    $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME>'$CUR_TIME'";
 else if($OVER_STATUS=="2")
    $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME<='$CUR_TIME' and END_TIME>='$CUR_TIME'";
 else if($OVER_STATUS=="3")
    $CONDITION_STR.=" and OVER_STATUS='0' and END_TIME<'$CUR_TIME'";
 else if($OVER_STATUS=="4")
    $CONDITION_STR.=" and OVER_STATUS='1'";
     $arrData=array();
     $HRMS_COUNT=0;
    	 $query1="select CODE_NO,CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='CAL_TYPE' order by CODE_ORDER";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
      	$CODE_NO=$ROW["CODE_NO"];
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["CODE_NAME"];
         $CODE_EXT=unserialize($ROW["CODE_EXT"]);
         if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
            $arrData[$i][1] = $CODE_EXT[MYOA_LANG_COOKIE];
         
         $arrData[$i][2]=counts($CODE_NO,$_SESSION["LOGIN_USER_ID"],$CONDITION_STR);
         $i++;
    	 }
    	 $strXML = "<chart caption='"._("按事务类型统计（条）")."' formatNumberScale='0'>";
   if(empty($arrData))
      Message("",_("无记录"));
   else
   {  
      foreach ($arrData as $arSubData)
      $strXML .= "<set label='" . $arSubData[1] . "' value='" . $arSubData[2] . "' />";
      $strXML .= "</chart>";
      echo renderChart("/inc/FusionCharts/Pie3D.swf", "", $strXML, "FactorySum", "600", "300", false, false);
      
   }


 function counts($CAL_TYPE,$USER_ID,$CONDITION_STR)
 {
 	 	 $query="SELECT count(*) as sum from CALENDAR where (USER_ID='".$USER_ID."' or find_in_set('".$USER_ID."',TAKER) or find_in_set('".$USER_ID."',OWNER))".$CONDITION_STR."  and CAL_TYPE='$CAL_TYPE' order by CAL_TIME,END_TIME";
 	 $cursor= exequery(TD::conn(),$query);
 	 if($ROW=mysql_fetch_array($cursor))
 	 	$SUM=$ROW['sum'];
 	 return $SUM;
 	
 } 
 ?>