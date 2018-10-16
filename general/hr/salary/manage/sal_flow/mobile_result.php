<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms2.php");
$BAOXIAN_XIANG_ARRAY = array(
    "ALL_BASE"=>_("保险基数"),
    "PENSION_BASE"=>_("养老保险"),
    "PENSION_U"=>_("单位养老"),
    "PENSION_U"=>_("个人养老"),
    "MEDICAL_BASE"=>_("医疗保险"),
    "MEDICAL_U"=>_("单位医疗"),
    "MEDICAL_P"=>_("个人医疗"),
    "FERTILITY_BASE"=>_("生育保险"),
    "FERTILITY_U"=>_("单位生育"),
    "UNEMPLOYMENT_BASE"=>_("失业保险"),
    "UNEMPLOYMENT_U"=>_("单位失业"),
    "UNEMPLOYMENT_P"=>_("个人失业"),
    "INJURIES_BASE"=>_("工伤保险"),
    "INJURIES_U"=>_("单位工伤"),
    "HOUSING_BASE"=>_("住房公积金"),
    "HOUSING_U"=>_("单位住房"),
    "HOUSING_P"=>_("个人住房")
   );
if($fld_str=="")
{
   $query = "SELECT ITEM_ID from SAL_ITEM";
   $cursor= exequery(TD::conn(),$query);
   $FLOW_COUNT=0;
   while($ROW=mysql_fetch_array($cursor))
   {
    	$STYLE.="S".$ROW["ITEM_ID"].",";
   }
   $STYLE.="MEMO";
}
else
{
  $STYLE=substr($fld_str,0,-1);
}


$ITEM_ID_ARRAY = explode(",",$STYLE);
$TMP_STR="";
foreach($ITEM_ID_ARRAY as $tmp_value)
{
	 if(substr($tmp_value,0,1)=="S")
	    $TMP_STR.=substr($tmp_value,1).",";
	 else
	    $TMP_STR.=$tmp_value.",";
}

$query = "update SAL_FLOW set STYLE='$TMP_STR' where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);

$query = "SELECT CONTENT from SAL_FLOW where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $CONTENT=$ROW["CONTENT"];
$HTML_PAGE_TITLE = _("手机工资条");
include_once("inc/header.inc.php");
?>

<body class="bodycolor" >
<?
 $query = "SELECT count(*) from SAL_ITEM";
 $ITEM_COUNT=0;
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $ITEM_COUNT=$ROW[0];

 if($ITEM_COUNT==0)
 {
   Message(_("提示"),_("尚未定义工资项目"));
   Button_back();
   exit;
 }

$STYLE_ARRAY=explode(",",$TMP_STR);
$ARRAY_COUNT=sizeof($STYLE_ARRAY);

 if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
$COUNT=0;
 for($I=0;$I<$ARRAY_COUNT;$I++)
 {
    $STYLE_ARRAY[$I];
    if(array_key_exists($STYLE_ARRAY[$I], $BAOXIAN_XIANG_ARRAY))
    {

        $ITEM_ID[$COUNT]=$STYLE_ARRAY[$I];
        $ITEM_NAME[$COUNT]=$BAOXIAN_XIANG_ARRAY[$STYLE_ARRAY[$I]];
        $COUNT++;
        continue;
    }
    $query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='$STYLE_ARRAY[$I]'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
    {
        $ITEM_NAME[$COUNT]=$ROW["ITEM_NAME"];
        $ITEM_ID[$COUNT]='S'.$ROW["ITEM_ID"];
    }
    if($STYLE_ARRAY[$I]=="MEMO")
    {
        $ITEM_NAME[$COUNT]=_("备注");
        $ITEM_ID[$COUNT]="MEMO";
    }
     $COUNT++;
 }

if($COPY_TO_ID!="")
   {
   	$COPY_TO_ID="'".str_replace(",","','",substr($COPY_TO_ID,0,-1))."'";
    $WHERE_STR.=" and USER.USER_ID in ($COPY_TO_ID)";
   }

$query = "SELECT * from USER,USER_PRIV,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV".$WHERE_STR." order by DEPT_NO,PRIV_NO,USER_NAME";
$cursor= exequery(TD::conn(),$query);
$USER_COUNT=0;
$MOBILE_MESSAGE;

$DATAS=array();
$wx_user_id = array();
while($ROW=mysql_fetch_array($cursor))
 {
    $USER_COUNT++;
    $MOBILE_MESSAGE="";
    $MOBILE_MESSAGE=$MOBILE_MESSAGE.$CONTENT.":";
    $USER_ID=$ROW["USER_ID"];
	$wx_user_id[]=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    $DATAS[$USER_COUNT]["USER_NAME"]=$USER_NAME;
    $MOBILE_MESSAGE=$USER_NAME._("：").$MOBILE_MESSAGE;
    $FLOW_ID = intval($FLOW_ID);
   $query1="select * from SAL_DATA where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
   {
     for($I=0;$I<$ARRAY_COUNT;$I++)
     {
       $STR=$ITEM_ID[$I];
       if($ROW1[$STR]==0)
       {
           $$STR=format_money($ROW1["$STR"]);
           continue;
       }
       if($ITEM_ID[$I]!="MEMO")
      	  $$STR=format_money($ROW1["$STR"]);
       else
       {
       	  $STR="SMEMO";
          $$STR=$ROW1["MEMO"];
       }
     }
   }
   else
   {
     for($I=0;$I< $COUNT;$I++)
     {
       $STR=$ITEM_ID[$I];
       $$STR="";
     }
   }


   $SAL_ITEM_SUM=0;
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
      $STR=$ITEM_ID[$I];
      $STR_COUNT=$STR."_COUNT";
      $$STR_COUNT+=$$STR;
      if($$STR=="")
         $$STR=0;
      if($$STR==0)
      {
          continue;
      }
      $MOBILE_MESSAGE=$MOBILE_MESSAGE.$ITEM_NAME[$I].":".$$STR." ";
      $SAL_ITEM_SUM++;
   }
   if($SAL_ITEM_SUM<=0)
   	  $MOBILE_MESSAGE.=_("没有录入工资项！");
   $DATAS[$USER_COUNT]["Message"]=$MOBILE_MESSAGE;
    send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID,$MOBILE_MESSAGE,"19");
 }
	/* if(count($wx_user_id) > 0){
		include_once("inc/itask/itask.php");
            $WX_OPTIONS = array(
                "module" => "wage",
                "module_action" => "wage.read",
                "user" => $wx_user_id,
                "content" => $_SESSION["LOGIN_USER_NAME"]._("：").$MOBILE_MESSAGE,
                "params" => array(
                    "USER_ID" => $USER_ID
                )
            );
            WXQY_SMS($WX_OPTIONS);
		
	} */
?>
<table class="TableList" align="center" width="80%">
	<tr class="TableHeader">
		<td nowrap align="center" width="20%"><?=_("姓名")?></td>
		<td nowrap align="center"><?=_("微信内容")?></td>
	</tr>
<?
foreach($DATAS as $value)
{
?>
	<tr class="TableData">
		<td align="center"><?=$value["USER_NAME"]?></td>
		<td align="center"><?=$value["Message"]?></td>
	</tr>
<?
}
?>
</table>
</div>
<br>
<div align="center">
 <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';">
</div>
</body>
</html>