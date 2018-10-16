<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("��־ͳ��");
include_once("inc/header.inc.php");

//ģ�����ԱȨ��
$module_priv ="";
if(is_module_manager(2))
{
	$module_priv = 1;
}
//��֤Ȩ��
if($_SESSION["LOGIN_USER_PRIV"]!=1 && $module_priv !=1)
{
	Message(_("��ʾ"),_("��û�и�ģ��鿴Ȩ��"));
	exit;
}

$cur_date    = date("Y-m-d");
$week_begin  = date("Y-m-d",strtotime("-".(date("w",time())==0 ? 6 : date("w",time())-1)."days",time()));
$week_end    = date("Y-m-d",strtotime("+6 days",strtotime($week_begin)));
$month_begin = date("Y-m-")."01";
$month_end   = date("Y-m-").date("t");
$year_begin  = date("Y-")."01-01";
$year_end    = date("Y-")."12-31";
$dept_id_select = ($dept_type==1 ? $dept_id_str : 0);//$dept_type (0-ȫѡ����,1-��ѡ����,2-��ѡ����)

while (list($key, $value) = each($_GET))
    $$key=$value;
while (list($key, $value) = each($_POST))
    $$key=$value;

if($date_begin=="" || $date_end=="")
{
    $date_begin = $month_begin;
    $date_end = $month_end;
}

if($more_dept_id!="" && $more_dept_name=="")
{
    $more_dept_name = GetDeptNameById($more_dept_id);
}

//�������б�(dept_type_back:0-ȫ������,1-������,2-�ಿ��)
if($dept_type==1 && $select_user==""){
    //�Ӷಿ���еĲ����б��й���
    if($more_dept_id!=''){
        $dept_type_back =2;
    }else{
    //�����в��ŵĲ����б��н���
        $dept_type_back = 0;    
    }
}else if($dept_type==1 && $select_user!=""){
    //���۶ಿ�Ż������в��ţ��������;�Ϊ1
     $dept_type_back = 1;
}
else if($dept_type==0 || $dept_type==2)
{
    //ѡ��ಿ��ͳ��
    if($more_dept_id!='' && $dept_id_str==''){
       $dept_type_back = 2;
    }else{
       $dept_type_back = 0;
    }
    
    $dept_id_str = ($dept_type==2 && $more_dept_id!="" ? $more_dept_id : "");
}
$back_url = "index.php?dept_id=".$dept_id."&date_begin=".$date_begin."&date_end=".$date_end."&dept_type=".$dept_type_back."&dept_id_str=".$dept_id_str."&more_dept_id=".$more_dept_id."";
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script>
$(document).ready(function(){
    $("td[id^=dept-]").click(function(){
        var dept_id = $(this).attr("id").substring(5);
        $("[name='dept_id']").val(dept_id);
        $("[name='dept_type']").val(1);
        $("[name='dept_id_str']").val(dept_id);
        document.form1.submit();
    });
    
    var more_flag_c = true;
    var more_flag_k = true;
    var more_flag_u = true;
    $("#dept_id").change(function(){
        if($("#dept_id").val()!='-1' && $("#dept_id").val()!='-2'){
            $("[name='dept_type']").val(1);
            $("[name='dept_id_str']").val($("#dept_id").val());
            document.form1.submit();
        }else if($("#dept_id").val() != '-2'){
            $("[name='dept_type']").val(0);
            $("[name='dept_id_str']").val($("#dept_id").val());
            document.form1.submit();
        }
        else if('-2' == $("#dept_id").val())
        {
        	if(more_flag_k)
        	{
        		$("#more_dept_a").click();
        		if(more_flag_u)
        		{
        			more_flag_c = false;
        		}
        	}
        }
    });
    $("#more_dept_select").click(function()
    {
    	if(more_flag_c)
    	{
    		$("#more_dept_a").click();
    	}
    	else
    	{
    		more_flag_c = true;
    	}
    });
    $("#dept_id").keydown(function(e)
	{
		var e = e || event;
	    var currKey = e.keyCode || e.which || e.charCode;
	    if($("#more_dept_select"))
	    {
		    if(2 < $("#dept_id").get(0).options.length)
		    {
			    if((0==$("#dept_id").get(0).selectedIndex && 40==currKey) || (2==$("#dept_id").get(0).selectedIndex && 38==currKey))
				{
					$("#more_dept_a").click();
					more_flag_k = false;
				}
				else if(33 == currKey)
				{
					more_flag_u = false;
				}
			}
			else if(2 == $("#dept_id").get(0).options.length)
			{
				if(40==currKey || 34==currKey || 35==currKey)
				{
					$("#more_dept_a").click();
					more_flag_k = false;
				}
			}
		}
	});
    
    $("#submit_more").click(function(){
        $("[name='dept_type']").val(2);
        $("[name='dept_id_str']").val($("input[name='more_dept_id']").val());
        document.form1.submit();
    });
    
    $("#reset").click(function(){
        window.location.href="index.php";
    });
    
    $("#back_button").click(function(){
        window.location.href="<?=$back_url?>";
    });
    
    //ҳ��������¼��������Ӱ�ָ���Ч��
    $(window.document).scroll(function(){
        var scrolltop = $(window.document).scrollTop();
        if(scrolltop =='0'){
            $("#head").removeClass("s_show");
        }else{
            $("#head").addClass("s_show");
        }
    });
});

function CheckForm()
{
    if(document.form1.date_begin.value.trim() == "")
    {
        alert("<?=_("��ʼ���ڲ���Ϊ��")?>");
        document.form1.date_begin.focus();
        return false;
    }
    if(document.form1.date_end.value.trim() == "")
    {
        alert("<?=_("�������ڲ���Ϊ��")?>");
        document.form1.date_end.focus();
        return false;
    }
}

function set_date(date_begin, date_end)
{
    document.getElementById("date_begin").value = date_begin;
    document.getElementById("date_end").value = date_end;
    document.form1.submit();
}

function export_exl()
{
    var date_begin1 = $("#date_begin").val();
    var date_end1 = $("#date_end").val();
    
    window.location.href = "export.php?date_begin="+date_begin1+"&date_end="+date_end1+"&select_user=<?=$select_user?>&dept_type=<?=$dept_type?>&dept_id_str=<?=$dept_id_str?>";
}
</script>
<style>
#head {
    position: fixed;
    padding: 10px 0 0 0;
    margin: 0;
    width: 100%;
    background: #fff;
    z-index: 1;
    border-bottom: 1px solid #ebebeb;
}

.s_show {
    box-shadow: 0 0 5px #888;
}
</style>

<body class="bodycolor" style="padding: 0px;margin: 0px;background: #fff;">
<form name="form1" method="post" action="index.php" onSubmit="return CheckForm();" style="margin: 0;">
<div id="head">
<table border="0" style="width: 750px;min-width: 750px;" cellspacing="0" cellpadding="3" class="small" align="center">
    <tr>
        <td nowrap>
            <div style="float:left;">
                <select name="dept_id" id="dept_id" class="input-large" style="margin-right: 5px;">
                    <option value="-1" <?=($dept_type==0 ? " selected" : "")?>><?=_("���в���")?></option>
                    <option value="-2" id="more_dept_select" <?=($dept_type==2 ? " selected" : "")?>><?=_("��ѡ����")?></option>
                    <?=my_dept_tree(0, $dept_id_select, 0);?>
                </select>
                
                <input type="text" id="date_begin" name="date_begin" value="<?=$date_begin?>" onClick="WdatePicker()" style="width: 70px;margin-right: 2px;">
                <?=_("��")?>
                <input type="text" id="date_end" name="date_end" value="<?=$date_end?>" onClick="WdatePicker()" style="width: 70px;margin-left: 2px;margin-right: 5px;">
                <input type="submit" value="<?=_("ͳ��")?>" class="SmallButton" title="<?=_("ͳ��")?>">
                <input type="button" value="<?=_("����")?>" class="SmallButton" title="<?=_("����")?>Excel<?=_("�ļ�")?>" onClick="export_exl()">
                
                <input type="button" value="<?=_("����")?>" class="SmallButton" title="<?=_("����")?>" onClick="set_date('<?=$cur_date?>','<?=$cur_date?>');" style="margin-left: 20px;">
                <input type="button" value="<?=_("����")?>" class="SmallButton" title="<?=_("����")?>" onClick="set_date('<?=$week_begin?>','<?=$week_end?>');">
                <input type="button" value="<?=_("����")?>" class="SmallButton" title="<?=_("����")?>" onClick="set_date('<?=$month_begin?>','<?=$month_end?>');">
                <input type="button" value="<?=_("����")?>" class="SmallButton" title="<?=_("����")?>" onClick="set_date('<?=$year_begin?>','<?=$year_end?>');">
                
                <?
                if($dept_type != "" && $dept_type !='0')
                {
                ?>
                    <input type="button" value="<?=_("����")?>" class="SmallButton" id="reset" style="margin-left: 20px;">
                    <input type="button" value="<?=_("����")?>" class="SmallButton" id="back_button">
                <?
                }
                ?>
                
                <input type="hidden" name="select_user" value="">
                <input type="hidden" name="dept_type" value="<?=$dept_type?>">
                <input type="hidden" name="dept_id_str" value="<?=$dept_id_str?>">
                
                <div style="display: none;">
                    <a href="#more_dept" id="more_dept_a" role="button" data-toggle="modal"><?=_("���ಿ��")?></a>
                </div>
            </div>
        </td>
    </tr>
</table>
</div>

<div id="more_dept" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 50%;margin-top: -130px;left: 50%; margin-left: -200px;width: 400px;">
    <div class="modal-header">
        <button class="close" aria-hidden="true" type="button" data-dismiss="modal">��</button>
        <h3 id="myModalLabel"><?=_("�ಿ��ѡ��")?></h3>
    </div>
    
    <div class="modal-body" style="max-height: 150px;height: 150px;padding: 0px;overflow: hidden;">
        <div style="margin-top: 30px;margin-left: 20px;">
            <input name="more_dept_id" type="hidden" value="<?=$more_dept_id?>">
            <textarea name="more_dept_name" class="BigStatic" rows="4" cols="60" wrap="yes" readonly><?=$more_dept_name?></textarea>
            <a class="orgAdd" onClick="SelectDept('','more_dept_id','more_dept_name')" href="javascript:;">ѡ��</a>
            <a class="orgClear" onClick="ClearUser('more_dept_id','more_dept_name')" href="javascript:;">���</a>
        </div>
    </div>
    <div class="modal-footer" style="padding-bottom: 10px;padding-top: 10px;text-align:center;">
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="submit_more"><?=_("ͳ��")?></button> <button class="btn" data-dismiss="modal" aria-hidden="true"><?=_("�ر�")?></button>
    </div>
</div>
</form>
<div style="padding-top: 60px;">
<?
//�ж�����������������ѯ����������Ϣ
if(!is_date($date_begin))
{
    Message(_("����"),sprintf(_("��ʼ���ڸ�ʽ����ȷ��Ӧ�磺%s"),date("Y-m-d")));
    exit;
}
if(!is_date($date_end))
{
    Message(_("����"),sprintf(_("�������ڸ�ʽ����ȷ��Ӧ�磺%s"),date("Y-m-d")));
    exit;
}
if($date_begin > $date_end)
{
    Message(_("����"),_("��ʼ���ڲ��ܴ��ڽ�������"));
    exit;
}

$count = 0;
$show_arr = array();
if($dept_type==1 && $select_user=="")
{
	$query = "SELECT user.USER_ID,USER_NAME FROM user,diary WHERE NOT_LOGIN=0 AND DEPT_ID='$dept_id_str' AND user.USER_ID<>'' GROUP BY user.USER_ID ORDER BY user.USER_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $show_arr[1][$row[0]]['count'] = 0;
        $show_arr[1][$row[0]]['user_name'] = $row[1];
    }
    
	$query = "SELECT diary.USER_ID,count(diary.USER_ID) FROM user,diary WHERE NOT_LOGIN=0 AND user.USER_ID=diary.USER_ID AND DEPT_ID='$dept_id_str' AND diary.USER_ID<>'' AND DIA_DATE >= '$date_begin' AND DIA_DATE <= '$date_end' GROUP BY diary.USER_ID ORDER BY diary.USER_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $count++;
        $show_arr[1][$row[0]]['count'] = $row[1];
    }
}
else if($dept_type==0 || $dept_type==2)
{
    $sql_str = '';
    if($dept_type == 2)
    {
    	if('ALL_DEPT' != $dept_id_str)
    	{
        	$sql_str = " AND FIND_IN_SET(DEPT_ID, '$dept_id_str')";
        }
    }
    $dept_list = '';
    $send_list_str = '';
    $send_list_arr = array();
    $send_count = 0;
    $notsend_list = array();
    $notsend_count = 0;
    
    $query = "SELECT DEPT_ID,COUNT(DEPT_ID) FROM user,diary WHERE NOT_LOGIN=0 AND DEPT_ID<>0 AND user.USER_ID = diary.USER_ID AND user.USER_ID<>'' AND DIA_DATE >= '$date_begin' AND DIA_DATE <= '$date_end'".$sql_str." GROUP BY DEPT_ID ORDER BY DEPT_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $count++;
        $show_arr[0][$row[0]]['count'] = $row[1];
        $show_arr[0][$row[0]]['dept_name'] = td_trim(GetDeptNameById($row[0]));
        $show_arr[0][$row[0]]['dept_id'] = $row[0];
        $dept_list .= $row[0].',';
        $show_arr[0][$row[0]]['send_count'] = 0;
        $show_arr[0][$row[0]]['notsend_count'] = 0;
    }
    
    $query = "SELECT DISTINCT DEPT_ID,user.USER_ID FROM user,diary WHERE NOT_LOGIN=0 AND DEPT_ID<>0 AND user.USER_ID = diary.USER_ID AND user.USER_ID<>'' AND DIA_DATE >= '$date_begin' AND DIA_DATE <= '$date_end' AND FIND_IN_SET(DEPT_ID, '$dept_list') ORDER BY DEPT_ID,user.USER_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $send_list_str .= $row[1].',';
        $send_list_arr[$row[0]][$send_count] = $row[1];
        ++$send_count;
    }
    foreach($send_list_arr as $key => $val)
    {
    	$show_arr[0][$key]['send_count'] = count($val);
    }
    
    $query = "SELECT DEPT_ID,USER_NAME FROM user WHERE NOT_LOGIN=0 AND DEPT_ID<>0 AND USER_ID<>'' AND FIND_IN_SET(DEPT_ID, '$dept_list') AND NOT FIND_IN_SET(USER_ID, '$send_list_str') ORDER BY DEPT_ID,USER_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
    	$show_arr[0][$row[0]]['notsend_list'] .= $row[1].',';
    	$notsend_list[$row[0]][$notsend_count] = $row[1];
    	++$notsend_count;
    }
    foreach($notsend_list as $key => $val)
    {
    	$show_arr[0][$key]['notsend_count'] = count($val);
    }
}

if(0 == $count)
{
    Message("",_("�޷�������־��Ϣ"));
    ?>
    <script type="text/javascript">
    $(":button[value='����']").removeAttr("onclick");
    </script>
	<?
    exit;
}

$count_css = 0;
if($dept_type==1 && $select_user=="")
{
    //��������־��Ϣ��ʾ
    $dept_name = td_trim(GetDeptNameById($dept_id));
    $dept_title = td_trim(dept_long_name($dept_id));
    $show_str = '<table class="table table-bordered table-hover" style="width:60%;" align="center">
    <thead style="background-color:#EBEBEB;">
        <th nowrap style="text-align: center;width:5%;">'._("���").'</th>
        <th nowrap style="text-align: center;width:10%;">'._("����").'</th>
        <th nowrap style="text-align: center;width:10%;">'._("����").'</th>
        <th nowrap style="text-align: center;width:5%;">'._("��������").'</th>
        <th nowrap style="text-align: center;width:5%;">�վ�������</th>
    </thead>';
    $date_count = (strtotime($date_end) - strtotime($date_begin)) / 86400 + 1;
    $aver = 0;
    foreach($show_arr[1] as $key => $val)
    {
        $class_css = ($count_css%2==1 ? "TableLine1" : "TableLine2");
        $count_css++;
        $aver = $val['count'] / $date_count;
    	if(0 != $aver)
    	{
    		$aver = number_format($aver, 1, '.', '');
    	}
        $show_str .= '<tr class="'.$class_css.'">
                        <td nowrap style="text-align: center;">'.$count_css.'</td>
                        <td style="text-align: center;" title="'.$dept_title.'">'.$dept_name.'</td>
                        <td style="text-align: center;" id="user-'.$key.'">'.$val['user_name'].'</td>
                        <td style="text-align: center;">'.$val['count'].'</td>
                        <td style="text-align: center;">'.$aver.'</td>
                    </tr>';
    }
}
else if($dept_type==0 || $dept_type==2)
{
    //�ಿ����־��ʾ
    $show_str = '<table class="table table-bordered table-hover" style="width:60%;" align="center">
    <thead style="background-color:#EBEBEB;">
        <th nowrap style="text-align: center;width:5%;">'._("���").'</th>
        <th nowrap style="text-align: center;width:10%;">'._("���ţ�����鿴���飩").'</th>
        <th nowrap style="text-align: center;width:5%;">'._("��������").'</th>
        <th nowrap style="text-align: center;width:5%;">��������</th>
        <th nowrap style="text-align: center;width:5%;">δ��������</th>
        <th nowrap style="text-align: center;width:5%;">�˾�������</th>
    </thead>';
    foreach($show_arr[0] as $val)
    {
        $class_css = ($count_css%2==1 ? "TableLine1" : "TableLine2");
        $count_css++;
        $show_str .= '<tr class="'.$class_css.'">
            <td nowrap style="text-align: center;">'.$count_css.'</td>
            <td style="text-align: center;cursor: pointer;" id="dept-'.$val['dept_id'].'" title="'.td_trim(dept_long_name($val['dept_id'])).'">'.$val['dept_name'].'</td>
            <td style="text-align: center;">'.$val['count'].'</td>
            <td style="text-align: center;">'.$val['send_count'].'</td>
            <td style="text-align: center;" title="'.substr($val['notsend_list'], 0, -1).'">'.$val['notsend_count'].'</td>
            <td style="text-align: center;">'.number_format($val['count'] / ($val['send_count'] + $val['notsend_count']), 1, '.', '').'</td>
        </tr>';
    }
}

echo $show_str;
?>
</table>
</div>
</body>
</html>