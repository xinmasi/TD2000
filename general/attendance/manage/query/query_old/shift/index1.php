<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");


$query_string=$_SERVER['QUERY_STRING'];

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

$HTML_PAGE_TITLE = _("考勤情况查询");
include_once("inc/header.inc.php");
?>


<style>
.AutoNewline
{
  word-break: break-all;/*必须*/
}
</style>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript">
function onloadd()
{
	$("#app").hide();
	$.ajax(
		{
			url : "search_shift.php",
			data : "<?=$query_string?>",
			success : function(dataa)
			{                            
				var ind=dataa.indexOf("[");
		   	jsons= dataa.slice(ind);
		   	var aaa=eval(jsons);
		   	$("#addRssBody").hide();
		   	$("#app").show();
		   	var strr="";
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
					strr+="<td nowrap align=\"center\"><a href=\"../../user_manage/user_shift.php?USER_ID="+aaa[i]['key_11']+"&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>\"><?=_("详细记录")?></a>&nbsp;<a href=\"../../user_manage/user_shift_export.php?USER_ID="+aaa[i]['key_11']+"&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>\"><?=_("导出个人明细")?></a>&nbsp;</td></tr>"
				}
				var reg=new RegExp("undefined","g");
				strr=strr.replace(reg,"");
				$("#app").append(strr);
			}
		});
}
</script>


<body class="bodycolor" onload="onloadd()">
<!------------------------------------- 轮班考勤 ------------------------------->
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("轮班考勤统计")?>
    (<?=_("从")?> <?=format_date($DATE1)?> <?=_("至")?> <?=format_date($DATE2)?> <?=$MSG?>)
    </span>
    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?=_(_("导出Excel"))?>" class="BigButton" onClick="location='export_shift.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE1=<?=$DUTY_TYPE1?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_(_("导出上下班统计信息"))?>" name="button">
    &nbsp;&nbsp;<input type="button" value="<?=_(_("所有人详细记录"))?>" class="BigButton" onClick="location='all_users_shift.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE1=<?=$DUTY_TYPE1?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_(_("所有人详细记录"))?>" name="button">
    &nbsp;&nbsp;<input type="button" value="<?=_(_("导出详细记录"))?>" class="BigButton" onClick="location='export_all_shift.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE1=<?=$DUTY_TYPE1?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_(_("导出详细记录"))?>" name="button">
    <br><font color=red size=2><?=_(_("（注：不对免签人员进行统计）"))?></font>
    </td>
  </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" id="check">
<div id="addRssBody" class="module_body"><img src='<?=MYOA_STATIC_SERVER?>/static/images/loading.gif' height='20' width='20' align='absMiddle'> <?=_(_("正在加载数据，请稍候…… "))?></div>
</table>
<div id="appp">
</div>
<table class="TableList" width="80%" id="app">
<?
$query = "SELECT * from SYS_PARA where PARA_NAME='NO_DUTY_USER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $NO_DUTY_USER=$ROW["PARA_VALUE"];

$query4 = "select USER.USER_NAME,USER.USER_ID,DEPARTMENT.DEPT_NAME from USER,USER_PRIV,DEPARTMENT,ATTEND_DUTY_SHIFT,USER_EXT where not find_in_set(USER.USER_ID,'$NO_DUTY_USER') and USER_EXT.USER_ID=USER.USER_ID and USER_EXT.DUTY_TYPE='99' and USER.USER_PRIV=USER_PRIV.USER_PRIV and DEPARTMENT.DEPT_ID = USER.DEPT_ID and ATTEND_DUTY_SHIFT.USER_ID=USER.USER_ID group by ATTEND_DUTY_SHIFT.USER_ID order by DEPT_NO,PRIV_NO,USER_NO,USER_NAME";
$cursor4 = exequery(TD::conn(),$query4);
$COUNT=0;
 $TITLE_IS_NO=mysql_num_rows($cursor4);
 if($TITLE_IS_NO>0)
 {
 ?>
     <tr class="TableHeader">
        <td nowrap align="center"><?=_("部门")?></td>
        <td nowrap align="center"><?=_("姓名")?></td>
        <td nowrap align="center"><?=_("登记次数")?></td>
        <td nowrap align="center"><?=_("操作")?></td>
    </tr>
 <?
 }
while($ROW4=mysql_fetch_array($cursor4))
{
    $COUNT++;
   $USER_NAME=$ROW4["USER_NAME"];
   $DEPT_NAME=$ROW4["DEPT_NAME"];
   $USER_ID=$ROW4["USER_ID"];
   
   $query5 = "select count(*) from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)>=to_days('$DATE1') and to_days(REGISTER_TIME)<=to_days('$DATE2')";
   $cursor5 = exequery(TD::conn(),$query5);
   $DJCS=mysql_fetch_row($cursor5);
?>
   <tr class="TableData">
     <td nowrap align="center"><?=$DEPT_NAME?></td>
     <td nowrap align="center"><?=$USER_NAME?></td>
     <td nowrap align="center"><?=$DJCS[0]?></td>
     <td nowrap align="center">
          <a href="../../user_manage/user_shift.php?USER_ID=<?=$USER_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>"><?=_("详细记录")?></a>&nbsp;
     </td>
   </tr>
<?
}
 if($DJCS<=0)
 {
	 message("",_("无轮班考勤记录"));  
 }
?>
</table>
<br>
</body>
</html>