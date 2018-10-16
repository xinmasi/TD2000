<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("考勤情况查询");
include_once("inc/header.inc.php");

$query_string=$_SERVER['QUERY_STRING'];

$PAGE_SIZE = 15;
$CUR_DATE = date("Y-m-d",time());
if(!isset($start) || $start=="")
{
   $start=0;
}

//----------- 合法性校验 ---------
if($DATE1!="")
{
  $TIME_OK=is_date($DATE1);

  if(!$TIME_OK)
  {
  	Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
}

if($DATE2!="")
{
  $TIME_OK=is_date($DATE2);

  if(!$TIME_OK)
  {
  	Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
}

if(compare_date($DATE1,$DATE2)==1)
{
	Message(_("错误"),_("查询的起始日期不能晚于截止日期"));
    Button_Back();
    exit;
}

$CUR_DATE=date("Y-m-d",time());

$query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
  $DAY_TOTAL=$ROW[0]+1;

$MSG = sprintf(_("共 %d 天"), $DAY_TOTAL);

//获得总记录数
$query = "SELECT * from SYS_PARA where PARA_NAME='NO_DUTY_USER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $NO_DUTY_USER=$ROW["PARA_VALUE"];
$WHERE_STR=" where 1=1";
if($DEPARTMENT1!="ALL_DEPT")
{
   $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
   $WHERE_STR.=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
}
$query1 = "SELECT count(*) from USER,USER_EXT,USER_PRIV,DEPARTMENT ".$WHERE_STR." and (USER.NOT_LOGIN = 0 or USER.NOT_MOBILE_LOGIN = 0) and USER_EXT.USER_ID=USER.USER_ID and not find_in_set(USER.USER_ID,'$NO_DUTY_USER') and USER_EXT.DUTY_TYPE!='99' and DEPARTMENT.DEPT_ID = USER.DEPT_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV";
$cursor1= exequery(TD::conn(),$query1);
if($ROW1=mysql_fetch_array($cursor1))
{
   $count= $ROW1[0];
}
?>
<style>
.AutoNewline
{
  word-break: break-all;/*必须*/
}
</style>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/fudong.js"></script>
<script type="text/javascript">
function onloadd()
{
	$("#app").hide();
	$.ajax(
		{
			url : "search_duty.php?start=<?=$start?>",
			data : "<?=$query_string?>",
			success : function(dataa)
			{
				var ind=dataa.indexOf("[");
			   	jsons= dataa.slice(ind);
			   	var aaa=eval(jsons);
			   	$("#addRssBody").hide();
			   	var strr="";
			   	if(aaa.length >1)
			   	{

			   		$("#app").show();
					for(i=1;i<aaa.length;i++)
					{
						var row=aaa[i];
						if(i%2==0)
						strr+="<tr class=TableData>";
						if(i%2==1)
						strr+="<tr class=TableContent>";
						for(j=1;j<11;j++)
						{
							j=String(j);
							if(row)
							{
								strr+="<td nowrap align=center>"+row['key_'+j]+"</td>";
							}
							else
							{
								strr+="<td nowrap align=center></td>";
							}
						}
						strr+="<td nowrap align=\"center\"><a href=\"../../../records/user_duty1.php?USER_ID="+aaa[i]['key_11']+"&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>\"><?=_("详细记录")?></a>&nbsp;<a href=\"../../../records/user_duty_export1.php?USER_ID="+aaa[i]['key_11']+"&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>\"><?=_("导出个人明细")?></a>&nbsp;</td></tr>"
					}
					var reg=new RegExp("undefined","g");
					strr=strr.replace(reg,"");
					$("#app").append(strr);
				}
				else
				{
					$("#show_error").show();
                                        $("#xianshi"). hide();
				}
			}
		});
}
</script>
<body class="bodycolor" onLoad="onloadd()">
<!------------------------------------- 上下班 ------------------------------->
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("上下班统计")?>
    (<?=_("从")?> <?=format_date($DATE1)?> <?=_("至")?> <?=format_date($DATE2)?> <?=$MSG?>)
    </span>
    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?=_(_("导出Excel"))?>" class="BigButton" onClick="location='export_duty.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE1=<?=$DUTY_TYPE1?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_(_("导出上下班统计信息"))?>" name="button">
    &nbsp;&nbsp;<input type="button" value="<?=_(_("所有人详细记录"))?>" class="BigButton" onClick="location='all_users_duty.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE1=<?=$DUTY_TYPE1?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_(_("所有人详细记录"))?>" name="button">
    &nbsp;&nbsp;<input type="button" value="<?=_(_("导出详细记录"))?>" class="BigButton" onClick="location='export_all_users.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE1=<?=$DUTY_TYPE1?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_(_("导出详细记录"))?>" name="button">
    <br><font color=red size=2><?=_(_("（注：不对免签人员进行统计）"))?><?=page_bar($start,$count,$PAGE_SIZE)?></font>
    </td>
  </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<div id="addRssBody" class="module_body"><img src='<?=MYOA_STATIC_SERVER?>/static/images/loading.gif' height='20' width='20' align='absMiddle'> <?=_(_("正在加载数据，请稍候…… "))?></div>
</table>
<div id="appp">
</div>
<table class="TableList" width="100%" id="app">
  <tr class="TableHeader" id="xianshi">
    <td nowrap align="center"><?=_("部门")?></td>
    <td nowrap align="center"><?=_("姓名")?></td>
    <td nowrap align="center"><?=_("全勤(天)")?></td>
    <td nowrap align="center"><?=_("时长")?></td>
    <td nowrap align="center"><?=_("迟到")?></td>
    <td nowrap align="center"><?=_("上班未登记")?></td>
    <td nowrap align="center"><?=_("早退")?></td>
    <td nowrap align="center"><?=_("下班未登记")?></td>
    <td nowrap align="center"><?=_("加班上班登记")?></td>
    <td nowrap align="center"><?=_("加班下班登记")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
  </tr>
</table>
<br>
<div style="display:none;align:center;" id="show_error">
	<br />
<?
	Message(_("提示"),_("无上下班记录！"));
?>
</div>
</body>
</html>