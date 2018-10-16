<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
include_once("inc/utility_org.php");
include_once("../func.func.php");

$HTML_PAGE_TITLE = _("录入积分详情");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css" />
<script type="text/javascript" src="/inc/js_lang.php"></script>

<?
$query="select * from HR_INTEGRAL_DATA where ID='$ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$ID=$ROW["ID"];
	$ITEM_ID=$ROW["ITEM_ID"];
	$INTEGRAL_REASON=$ROW["INTEGRAL_REASON"];
	$INTEGRAL_TYPE=$ROW["INTEGRAL_TYPE"];
	$USER_ID=$ROW["USER_ID"];
	$INTEGRAL_DATA=$ROW["INTEGRAL_DATA"];
	$CREATE_PERSON=$ROW["CREATE_PERSON"];
	$CREATE_TIME=$ROW["CREATE_TIME"];
	$INTEGRAL_TIME=$ROW["INTEGRAL_TIME"];
	$INTEGRAL_TYPE_SHOW="";
	if($INTEGRAL_TYPE==0)
		$INTEGRAL_TYPE_SHOW=_("未定义项积分录入");
	else if($INTEGRAL_TYPE==1)
		$INTEGRAL_TYPE_SHOW=_("OA使用积分录入");
	else if($INTEGRAL_TYPE==2)
		$INTEGRAL_TYPE_SHOW=_("人事档案项自动生成");
	else if($INTEGRAL_TYPE==3)
		$INTEGRAL_TYPE_SHOW=_("自定义项积分录入");
	$ITEM_NAME=$ITEM_ID==0?_("未定义项"):getItemName($ITEM_ID);
	$STATUS=$ROW["STATUS"];
	$USER_NAME = substr(GetUserNameById($USER_ID),0,-1);
	$CREATE_PERSON_NAME = substr(GetUserNameById($CREATE_PERSON),0,-1);
  
}
 
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("查看已录入积分")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br />
<table class="TableBlock" width="80%" align="center">
	 <tr>
      <td nowrap class="TableData"><?=_("积分获得人：")?></td>
      <td class="TableData"><?=$USER_NAME?>
      </td>
      <td nowrap class="TableData"><?=_("积分项名称：")?></td>
      <td class="TableData">
      	 <?=$ITEM_NAME?>
      </td>
    </tr>   
   <tr>
      <td nowrap class="TableData"><?=_("积分来源：")?></td>
      <td class="TableData" >
			<?=$INTEGRAL_TYPE_SHOW?>
      </td>
      <td nowrap class="TableData"><?=_("分值：")?></td>
      <td class="TableData">
        <?=$INTEGRAL_DATA?>&nbsp;
      </td>    
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("打分人员：")?></td>
      <td class="TableData">
        <?=$CREATE_PERSON_NAME?>&nbsp;
      </td>
      <td nowrap class="TableData"> <?=_("积分获得日期：")?></td>
      <td class="TableData">
        <?=$INTEGRAL_TIME?>
      </td>
    </tr>
    <tr>
      <td class="TableData" nowrap> <?=_("积分理由：")?></td>
      <td class="TableData" colspan="3">
        <?=$INTEGRAL_REASON?>
      </td>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
<? 
if($from=="email")
{
?>
<input type="button" class="BigButton" onClick="window.close()" value="<?=_("关闭")?>" />
<?	
}
else
	Button_back(); 
?>
      </td>
    </tr>
  </table>

</body>
</html>