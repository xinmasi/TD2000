<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("���������ѯ");
include_once("inc/header.inc.php");

$query_string=$_SERVER['QUERY_STRING'];

$PAGE_SIZE = 15;
$CUR_DATE = date("Y-m-d",time());
if(!isset($start) || $start=="")
{
   $start=0;
}

//----------- �Ϸ���У�� ---------
if($DATE1!="")
{
  $TIME_OK=is_date($DATE1);

  if(!$TIME_OK)
  {
  	Message(_("����"),_("��ʼ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
    Button_Back();
    exit;
  }
}

if($DATE2!="")
{
  $TIME_OK=is_date($DATE2);

  if(!$TIME_OK)
  {
  	Message(_("����"),_("��ֹ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
    Button_Back();
    exit;
  }
}

if(compare_date($DATE1,$DATE2)==1)
{
	Message(_("����"),_("��ѯ����ʼ���ڲ������ڽ�ֹ����"));
    Button_Back();
    exit;
}

$CUR_DATE=date("Y-m-d",time());

$query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
  $DAY_TOTAL=$ROW[0]+1;

$MSG = sprintf(_("�� %d ��"), $DAY_TOTAL);

//����ܼ�¼��
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
  word-break: break-all;/*����*/
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
						strr+="<td nowrap align=\"center\"><a href=\"../../../records/user_duty1.php?USER_ID="+aaa[i]['key_11']+"&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>\"><?=_("��ϸ��¼")?></a>&nbsp;<a href=\"../../../records/user_duty_export1.php?USER_ID="+aaa[i]['key_11']+"&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>\"><?=_("����������ϸ")?></a>&nbsp;</td></tr>"
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
<!------------------------------------- ���°� ------------------------------->
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("���°�ͳ��")?>
    (<?=_("��")?> <?=format_date($DATE1)?> <?=_("��")?> <?=format_date($DATE2)?> <?=$MSG?>)
    </span>
    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?=_(_("����Excel"))?>" class="BigButton" onClick="location='export_duty.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE1=<?=$DUTY_TYPE1?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_(_("�������°�ͳ����Ϣ"))?>" name="button">
    &nbsp;&nbsp;<input type="button" value="<?=_(_("��������ϸ��¼"))?>" class="BigButton" onClick="location='all_users_duty.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE1=<?=$DUTY_TYPE1?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_(_("��������ϸ��¼"))?>" name="button">
    &nbsp;&nbsp;<input type="button" value="<?=_(_("������ϸ��¼"))?>" class="BigButton" onClick="location='export_all_users.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE1=<?=$DUTY_TYPE1?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_(_("������ϸ��¼"))?>" name="button">
    <br><font color=red size=2><?=_(_("��ע��������ǩ��Ա����ͳ�ƣ�"))?><?=page_bar($start,$count,$PAGE_SIZE)?></font>
    </td>
  </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<div id="addRssBody" class="module_body"><img src='<?=MYOA_STATIC_SERVER?>/static/images/loading.gif' height='20' width='20' align='absMiddle'> <?=_(_("���ڼ������ݣ����Ժ򡭡� "))?></div>
</table>
<div id="appp">
</div>
<table class="TableList" width="100%" id="app">
  <tr class="TableHeader" id="xianshi">
    <td nowrap align="center"><?=_("����")?></td>
    <td nowrap align="center"><?=_("����")?></td>
    <td nowrap align="center"><?=_("ȫ��(��)")?></td>
    <td nowrap align="center"><?=_("ʱ��")?></td>
    <td nowrap align="center"><?=_("�ٵ�")?></td>
    <td nowrap align="center"><?=_("�ϰ�δ�Ǽ�")?></td>
    <td nowrap align="center"><?=_("����")?></td>
    <td nowrap align="center"><?=_("�°�δ�Ǽ�")?></td>
    <td nowrap align="center"><?=_("�Ӱ��ϰ�Ǽ�")?></td>
    <td nowrap align="center"><?=_("�Ӱ��°�Ǽ�")?></td>
    <td nowrap align="center"><?=_("����")?></td>
  </tr>
</table>
<br>
<div style="display:none;align:center;" id="show_error">
	<br />
<?
	Message(_("��ʾ"),_("�����°��¼��"));
?>
</div>
</body>
</html>