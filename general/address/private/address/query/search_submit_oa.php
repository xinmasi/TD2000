<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_field.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

//2013-4-11 主服务查询
if($IS_MAIN==1)
{
    $QUERY_MASTER = true;
}
else
{
    $QUERY_MASTER = "";
}
  
$HTML_PAGE_TITLE = _("联系人查询");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script>
function add_detail(ADD_ID)
{
    URL="add_detail.php?ADD_ID="+ADD_ID+"&where_str=<?=$s_where_str?>";
    myleft=(screen.availWidth-750)/2;
    window.open(URL,"detail","height=620,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=50,left="+myleft+",resizable=yes");
}

function check_all()
{
    for (i=0;i<document.getElementsByName('add_select').length;i++)
    {
        if(document.getElementsByName("allbox")[0].checked)
        {
            document.getElementsByName('add_select').item(i).checked=true;
        }
        else
        {
            document.getElementsByName('add_select').item(i).checked=false;
        }
    }
    
    if(i==0)
    {
        if(document.getElementsByName("allbox")[0].checked)
        {
            document.getElementsByName('add_select').checked=true;
        }
        else
        {
            document.getElementsByName('add_select').checked=false;
        }
    }
}

function check_one(el)
{
    if(!el.checked)
    {
        document.getElementsByName("allbox")[0].checked=false;
    }
}

function get_checked()
{
    checked_str="";
    for(i=0;i<document.getElementsByName('add_select').length;i++)
    {
        el=document.getElementsByName('add_select').item(i);
        if(el.checked)
        {
            val=el.value;
            checked_str+=val + ",";
        }
    }
    
    if(i==0)
    {
        el=document.getElementsByName('add_select');
        if(el.checked)
        {
            val=el.value;
            checked_str+=val + ",";
        }
    }
    return checked_str;
}

function group_send(send_type)
{
    send_str=get_checked();
    
    if(send_str=="")
    {
        alert("<?=_("请至少选择一个联系人")?>");
        return;
    }
    
    URL="group_send_oa.php?send_str="+send_str+"&send_type="+send_type;
    
    myleft=(screen.availWidth-750)/2;
    window.open(URL,"group_send_oa","height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=50,left="+myleft+",resizable=yes");
}

function export_excel()
{
   document.form2.submit();
}

$(function(){
	
	<? if($ORDER == 'dept')
	{
	?>
	 $("#img_b").attr("src", "<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif");
	 $("#ORDER").val("dept");
	<?
	}
	?>
	<? if($ORDER == 'name')
	{
	?>
	 $("#img_a").attr("src", "<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif");
	 $("#ORDER").val("name");
	<?
	}
	?>
	<? if($ORDER == 'priv')
	{
	?>
	 $("#img_c").attr("src", "<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif");
	 $("#ORDER").val("priv");
	<?
	}
	?>
	$("#name").click(function(){
		$("#ORDER").val("name");
		document.form3.submit();
	});
	$("#dept").click(function(){
		$("#ORDER").val("dept");
		document.form3.submit();
	});
	$("#priv").click(function(){
		$("#ORDER").val("priv");
		document.form3.submit(); 	
	});
	
});

</script>



<?
//============================ 显示 =======================================

$query = "SELECT * FROM user,user_priv,department WHERE  department.DEPT_ID = user.DEPT_ID and user.USER_PRIV = user_priv.USER_PRIV ";

if($USER_NAME!="")
{
    $query        .= " and USER_NAME like '%$USER_NAME%'";
    
}
if($DEPT_ID!="" )
{
    $DEPT_ID_CHILD = td_trim(GetChildDeptId($DEPT_ID));
	$query.=" and (USER.DEPT_ID in ($DEPT_ID_CHILD) or ".str_replace(",", " in (USER.DEPT_ID_OTHER) or ", $DEPT_ID_CHILD)." in (USER.DEPT_ID_OTHER))";
}

if($USER_PRIV!="")
{
    $query        .= " and user.USER_PRIV='$USER_PRIV'";
}

if($MOBIL_NO!="")
{
    $query        .= " and MOBIL_NO like '%$MOBIL_NO%'";
}

if($TEL_NO_DEPT!="")
{
    $query        .= " and TEL_NO_DEPT like '%$TEL_NO_DEPT%'";
}

if($TEL_NO_HOME!="")
{
    $query        .= " and TEL_NO_HOME like '%$TEL_NO_HOME%'";
}

if($OICQ_NO!="")
{
    $query        .= " and OICQ_NO like '%$OICQ_NO%'";
}

if($EMAIL!="")
{
    $query        .= " and EMAIL like '%$EMAIL%'";
}
if($ORDER == 'dept')
{
	$query       .= " ORDER BY DEPT_NO,PRIV_NO,USER_NO,USER_NAME";	
}elseif($ORDER == 'name')
{
	$query       .= " ORDER BY USER_NAME,DEPT_NO,PRIV_NO,USER_NO";
}elseif($ORDER == 'priv')
{
	$query       .= " ORDER BY PRIV_NO,DEPT_NO,USER_NO,USER_NAME";
}


$cursor       = exequery(TD::conn(),$query);
$ADD_COUNT    = 0;
?>

<body class="bodycolor">
<br>

<? 
if(mysql_affected_rows()>0)
{ 
?>
<table border="0" style="width:85%;" cellspacing="0" cellpadding="3" class="small" align="center">
    <tr>
        <td class="Big"><span class="big3"> <?=_("联系人查询结果")?></span>&nbsp;&nbsp;<button type="button" class="btn" onClick="location='search_oa.php';"><?=_("返回")?></button>
			<? if($_SESSION['LOGIN_USER_PRIV'] == 1)
			{
			?>
            &nbsp;<button type="button" class="btn" onClick="export_excel();;" title="导出查询结果">导出EXCEL</button>
            <?
			}
            ?>
        </td>
    </tr>
</table>
<br>
<?
}
?>
<?
while($ROW=mysql_fetch_array($cursor))
{
   
    $ADD_COUNT++;
    
    $ADD_ID         = $ROW["UID"];
	$USER_ID        = $ROW["USER_ID"];
    $USER_NAME      = $ROW["USER_NAME"];
    $SEX            = $ROW["SEX"];
    $DEPT_ID        = $ROW["DEPT_ID"];
	$USER_PRIV      = $ROW["USER_PRIV"];
    $TEL_NO_DEPT    = $ROW["TEL_NO_DEPT"];
    $EMAIL          = $ROW["EMAIL"];  
	$OICQ_NO        = $ROW["OICQ_NO"];
	$PRIV_NAME      = $ROW["PRIV_NAME"];
	$DEPT_NAME      = $ROW["DEPT_NAME"];
	
	if($ROW['MOBIL_NO_HIDDEN']==0)
	{
		$MOBIL_NO   = $ROW["MOBIL_NO"];
	}else
	{
		$MOBIL_NO   = "";
	}
	
    if($DEPT_ID == 0)
	{
		$DEPT_NAME = _("离职人员/外部人员");
	}
	
    if($MOBIL_NO!="")
    {
        $MOBIL_NO_STR.=$MOBIL_NO.",";
    }
    
    if($SEX=="0")
    {
        $SEX=_("男");
    }
    else if($SEX=="1")
    {
        $SEX=_("女");
    }
    else
    {
        $SEX="";
    }

    if($ADD_COUNT==1)
    {
?>

<table class="table table-bordered table-hover" style="width:85%;" align="center">
    <thead style="background-color:#EBEBEB;">
        <th nowrap style="text-align: center;width:3%;"><?=_("选择")?></th>
        <th nowrap style="text-align: center;width:9%;"><?=_("姓名")?>&nbsp;<a href="javascript:;" onClick="order_by(1);" id="name"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_up.gif" width="11" height="10" id="img_a"></a></th>
        <th nowrap style="text-align: center;width:5%;"><?=_("性别")?></th>
        <th nowrap style="text-align: center;width:15%;"><?=_("部门")?>&nbsp;<a href="javascript:;" onClick="order_by(2);" id="dept"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_up.gif" width="11" height="10" id="img_b"></a></th>
        <th nowrap style="text-align: center;width:13%;"><?=_("角色")?>&nbsp;<a href="javascript:;" onClick="order_by(3);" id="priv"><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_up.gif" width="11" height="10" id="img_c"></a></th>
        <th nowrap style="text-align: center;width:10%;"><?=_("工作电话")?></th>
        <th nowrap style="text-align: center;width:10%;"><?=_("手机")?> <a href="javascript:form1.submit();"><?=_("群发")?></a></th>
        <th nowrap style="text-align: center;width:10%;"><?=_("电子邮件")?></th>
        <th nowrap style="text-align: center;width:10%;"><?=_("操作")?></th>
    </thead>

<?
    }
?>
    <tr class="TableData">
        <td style="text-align: center;"><input type="checkbox" name="add_select" value="<?=$ADD_ID?>" onClick="check_one(self);"></td>
        
        <td style="text-align: center;"><a href="/general/ipanel/user/user_info.php?UID=<?=$ADD_ID?>&USER_ID=<?=$USER_ID?>&WINDOW=1&DEPT_ID=" target="_blank"><?=$USER_NAME?></a></td>
        <td style="text-align: center;"><?=$SEX?></td>
        <td style="text-align: center;"><?=$DEPT_NAME?></td>
        <td style="text-align: center;"><?=$PRIV_NAME?></td>
        <td style="text-align: center;"><?=$TEL_NO_DEPT?></td>
        <td style="text-align: center;"><a href="/general/mobile_sms/new/?TO_ID1=<?=$MOBIL_NO?>," target="_blank"><?=$MOBIL_NO?></a></td>
        <td style="text-align: center;"><a href="/general/email/new/?TO_WEBMAIL=<?=$EMAIL?>" target="_blank"><?=$EMAIL?></a></td>
        <td style="text-align: center;" width="100">
            <a href="/general/ipanel/user/user_info.php?UID=<?=$ADD_ID?>&USER_ID=<?=$USER_ID?>&WINDOW=1&DEPT_ID=" target="_blank"> <?=_("详情")?></a>
            <a href="/general/status_bar/sms_back.php?TO_UID=<?=$ADD_ID?>,&TO_NAME=<?=$USER_NAME?>," target="_blank"> <?=_("微信")?></a>
            <a href="/general/email/new/?TO_ID=<?=$USER_ID?>," target="_blank"> <?=_("邮件")?></a>
        </td>
    </tr>

<?
}

if($ADD_COUNT==0)
{
    Message("",_("无符合条件的联系人"));
?>
<center>
    <button type="button" class="btn" onClick="location='search_oa.php';"><?=_("返回")?></button>
</center>
<?
    exit;
}
else
{
?>
    <tr class="TableControl" style="background:#fff">
        <td colspan="9" class="form-inline">
            &nbsp;<label class="checkbox" for="allbox_for"><input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();">全选</label>&nbsp;
            &nbsp;<button type="button" class="btn" onClick="group_send('tel');" title="群发所选联系人短信">群发微信</button>
            &nbsp;<button type="button" class="btn" onClick="group_send('email');" title="群发所选联系人邮件">群发邮件</button>
        </td>
    </tr>
</table>
<?
}
?>

<form name="form1" method="post" action="/general/mobile_sms/new/index.php">
    <input type="hidden" name="TO_ID1" value="<?=$MOBIL_NO_STR?>">
</form>

<form name="form2" method="post" action="user_export.php">
    <input type="hidden" name="DEPT_ID" value="<?=$_POST['DEPT_ID']?>">
    <input type="hidden" name="USER_PRIV" value="<?=$_POST['USER_PRIV']?>">
    <input type="hidden" name="MOBIL_NO" value="<?=$_POST['MOBIL_NO']?>">
    <input type="hidden" name="TEL_NO_DEPT" value="<?=$_POST['TEL_NO_DEPT']?>">
    <input type="hidden" name="TEL_NO_HOME" value="<?=$_POST['TEL_NO_HOME']?>">
    <input type="hidden" name="OICQ_NO" value="<?=$_POST['OICQ_NO']?>">
    <input type="hidden" name="EMAIL" value="<?=$_POST['EMAIL']?>">
    <input type="hidden" name="ORDER" value="<?=$_POST['ORDER']?>">
</form>

<form name="form3" method="post" action="search_submit_oa.php">
    <input type="hidden" name="DEPT_ID" value="<?=$_POST['DEPT_ID']?>">
    <input type="hidden" name="USER_PRIV" value="<?=$_POST['USER_PRIV']?>">
    <input type="hidden" name="MOBIL_NO" value="<?=$_POST['MOBIL_NO']?>">
    <input type="hidden" name="TEL_NO_DEPT" value="<?=$_POST['TEL_NO_DEPT']?>">
    <input type="hidden" name="TEL_NO_HOME" value="<?=$_POST['TEL_NO_HOME']?>">
    <input type="hidden" name="OICQ_NO" value="<?=$_POST['OICQ_NO']?>">
    <input type="hidden" name="EMAIL" value="<?=$_POST['EMAIL']?>">
    <input type="hidden" name="ORDER" id="ORDER" value="">
</form>
<center>
    <button type="button" class="btn" onClick="location='search_oa.php';"><?=_("返回")?></button>
</center>
<br>
</body>
</html>
