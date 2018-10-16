<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("����ͳ��");
include_once("inc/header.inc.php");

$is_notify_manager = is_module_manager(3);
if(!$is_notify_manager && $_SESSION['LOGIN_USER_PRIV']!='1' && !find_id($_SESSION['LOGIN_USER_PRIV_OTHER'], "1"))
{
    Message(_("����"),_("��û�й���ԱȨ�ޣ�����ϵϵͳ����Ա��"));
    exit;
}

$cur_date = date("Y-m-d");
$week_begin = date("Y-m-d",strtotime("-".(date("w",time())==0 ? 6 : date("w",time())-1)."days",time()));
$week_end = date("Y-m-d",strtotime("+6 days",strtotime($week_begin)));
$month_begin = date("Y-m-")."01";
$month_end = date("Y-m-").date("t");
$year_begin = date("Y-")."01-01";
$year_end = date("Y-")."12-31";
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
       $dept_type_back =2;
    }else{
       $dept_type_back = 0;
    }
    
    $dept_id_str = ($dept_type==2 && $more_dept_id!="" ? $more_dept_id : "");
}
$back_url = "statistics.php?dept_id=".$dept_id."&date_begin=".$date_begin."&date_end=".$date_end."&dept_type=".$dept_type_back."&dept_id_str=".$dept_id_str."&more_dept_id=".$more_dept_id."";
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
    
    $("td[id^=user-]").click(function(){
        var user_id_el = $(this).attr("id").substring(5);
        $("[name='select_user']").val(user_id_el);
        document.form1.submit();
    });
    
    $("td[id^=subject-]").click(function(){
        var notify_id_el = $(this).attr("id").substring(8);
        var notify_url = "../show/read_notify.php?IS_MANAGE=1&NOTIFY_ID="+notify_id_el;
        
        myleft = (screen.availWidth-780)/2;
        mytop = 100
        mywidth = 780;
        myheight = 500;
        
        window.open(notify_url, "read_notify", "height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
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
        window.location.href="statistics.php";
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
})

function CheckForm()
{
    if($.trim($("#date_begin").val()) == "")
    {
        alert("<?=_("��ʼ���ڲ���Ϊ��")?>");
        $("#date_begin").focus();
        return false;
    }
    if($.trim($("#date_end").val()) == "")
    {
        alert("<?=_("�������ڲ���Ϊ��")?>");
        $("#date_end").focus();
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
<form name="form1" method="post" action="statistics.php" onSubmit="return CheckForm();" style="margin: 0;">
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
                <input type="button" value="<?=_("����")?>" class="SmallButton" title="<?=_("����")?>Excel<?=_("�ļ�")?>" onClick="export_exl();">
                
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
            <textarea name="more_dept_name" class="BigStatic" rows="4" cols="60" wrap="yes" readonly=""><?=$more_dept_name?></textarea>
            <a class="orgAdd" onclick="SelectDept('','more_dept_id','more_dept_name')" href="javascript:;">ѡ��</a>
            <a class="orgClear" onclick="ClearUser('more_dept_id','more_dept_name')" href="javascript:;">���</a>
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
    $query = "SELECT FROM_ID,count(FROM_ID),USER_NAME FROM notify,user WHERE NOT_LOGIN=0 AND user.DEPT_ID='$dept_id_str' AND user.USER_ID=FROM_ID AND FROM_ID<>'' AND SEND_TIME >= '$date_begin 00:00:00' AND SEND_TIME <= '$date_end 23:59:59' group by FROM_ID ORDER BY FROM_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $count++;
        $show_arr[1][$row[0]]['count'] = $row[1];
        $show_arr[1][$row[0]]['user_name'] = $row[2];
    }
}
else if($dept_type==1 && $select_user!="")
{
    $cur_time = date("Y-m-d", time());
    $type_id_str = "";
    $query = "SELECT * FROM notify WHERE FROM_ID='$select_user' AND SEND_TIME >= '$date_begin 00:00:00' AND SEND_TIME <= '$date_end 23:59:59' order by TOP desc,SEND_TIME desc";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $count++;
        
        $subject         = $row['SUBJECT'];
        $notify_id       = $row['NOTIFY_ID'];
        $type_id         = $row['TYPE_ID'];
        $publish         = $row['PUBLISH'];
        $send_time       = $row['SEND_TIME'];
        $begin_date      = date("Y-m-d",$row['BEGIN_DATE']);
        $end_date        = $row['END_DATE'];
        $to_id           = $row['TO_ID'];
        $priv_id         = $row['PRIV_ID'];
        $user_id         = td_trim($row['USER_ID']);
        $auditer         = $row['AUDITER'];
        $reason          = $row['REASON'];
        if($end_date == "0")
        {
            $end_date = "";
        } else {
            $end_date = date("Y-m-d",$end_date);
        }
        //��������
        if($type_id)
        {
            $type_id_str .= "'".$type_id."',";
        }
        
        //״̬����
        if($publish == "1") //������
        {
            if(compare_date($cur_time, $begin_date)<0)
            {
                $notify_status = _("����Ч");
            }

            if(compare_date($cur_time,$begin_date)>0 || compare_date($cur_time,$begin_date)== 0)
            {
                $notify_status = "<font color='#00AA00'><b>"._("��Ч")."</font>";
            }

            if($end_date != "" && (compare_date($cur_time,$end_date)>0 || compare_date($cur_date,$end_date)== 0))
            {
                $notify_status = "<font color='#FF0000'><b>"._("��ֹ")."</font>";
            }
        }
        else if($publish == "2")//������
        {
            $notify_status = "<font color='blue'><b>"._("������")."</font>";
        }
        else if($publish=="3")//����δͨ��
        {
            $notify_status = "<font color='red'><b>"._("δͨ��")."</font>"; 
        }
        else if($publish=="0")
        {
            $notify_status = "<font color=red>"._("δ����")."</font>";
        }
        
        //������Χ
        $to_name = ($to_id=="ALL_DEPT" ? _("ȫ�岿��") : td_trim(GetDeptNameById($to_id)));
        $priv_name = td_trim(GetPrivNameById($priv_id));
        $user_name = "";
        
        if($user_id != "")
        {
            $user_id_arr = explode(',', $user_id);
            $user_id_arr = array_unique($user_id_arr);
            $user_id_arr = array_filter($user_id_arr);
            $user_id = td_trim(implode(',', $user_id_arr));
            
            $user_name = ($user_id!="" ? GetUserNameById($user_id) : "");
        }
        
        $to_name_title = "";
        $to_name_str = "";
        if($to_name != "")
        {
            $to_name_title .= _("���ţ�").$to_name;
            $to_name_str .= "<font color=#0000FF><b>"._("���ţ�")."</b></font>".csubstr(strip_tags($to_name),0,20).(strlen($to_name)>20 ? "..." : "")."<br>";
        }
        if($priv_name!="")
        {
            if($to_name_title != "")
            {
                $to_name_title .= "\n\n";
            }
            
            $to_name_title .= _("��ɫ��").$priv_name;
            $to_name_str .= "<font color=#0000FF><b>"._("��ɫ��")."</b></font>".csubstr(strip_tags($priv_name),0,20).(strlen($priv_name)>20 ? "..." : "")."<br>";
        }
        if($user_name!="")
        {
            if($to_name_title != "")
            {
                $to_name_title .= "\n\n";
            }
            
            $to_name_title .= _("��Ա��").$user_name;
            $to_name_str .= "<font color=#0000FF><b>"._("��Ա��")."</b></font>".csubstr(strip_tags($user_name),0,20).(strlen($user_name)>20 ? "..." : "")."<br>";
        }
        
        $show_arr[2][$count]['subject']         = (strlen($subject)>50 ? csubstr($subject, 0, 50)."..." : $subject);
        $show_arr[2][$count]['notify_id']       = $notify_id;
        $show_arr[2][$count]['title']           = td_htmlspecialchars($subject);
        $show_arr[2][$count]['type_id']         = $type_id;
        $show_arr[2][$count]['to_name_title']   = $to_name_title;
        $show_arr[2][$count]['to_name_str']     = $to_name_str;
        $show_arr[2][$count]['create_time']     = $send_time;
        $show_arr[2][$count]['begin_date']      = $begin_date;
        $show_arr[2][$count]['end_date']        = ($end_date==0 ? "-" : $end_date);
        $show_arr[2][$count]['notify_status']   = $notify_status;
        $show_arr[2][$count]['reason']          = td_trim(GetUserNameById($auditer))._("��").$reason;
        $show_arr[2][$count]['publish']         = $publish;
    }
    
    $type_id_str = td_trim($type_id_str);
    $type_name_array = array();
    $type_name = "";
    if($type_id_str)
    {
        $query1 = "select CODE_NAME,CODE_EXT,CODE_NO from SYS_CODE where PARENT_NO='NOTIFY' and CODE_NO in ($type_id_str)";
        $cursor1= exequery(TD::conn(),$query1);
        while($row_code=mysql_fetch_array($cursor1))
        {
            $type_id_key    =$row_code["CODE_NO"];
            $type_name      =$row_code["CODE_NAME"];
            $code_ext       =unserialize($row_code["CODE_EXT"]);
            
            if(is_array($code_ext) && $code_ext[MYOA_LANG_COOKIE] != "")
            {
                $type_name = $code_ext[MYOA_LANG_COOKIE];
            }
            
            $type_name_array[$type_id_key]= $type_name;
        }
    }
}
else if($dept_type==0 || $dept_type==2)
{
    $sql_str = '';
    if($dept_type == 2)
    {
        $sql_str = " AND find_in_set(user.DEPT_ID, '$dept_id_str') ";
    }
    
    $query = "SELECT DEPT_ID,count(DEPT_ID) FROM notify,user WHERE NOT_LOGIN=0 AND DEPT_ID<>0 AND user.USER_ID=FROM_ID AND FROM_ID<>'' AND SEND_TIME >= '$date_begin 00:00:00' AND SEND_TIME <= '$date_end 23:59:59' ".$sql_str." group by DEPT_ID ORDER BY DEPT_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $count++;
        $show_arr[0][$row[0]]['count'] = $row[1];
        $show_arr[0][$row[0]]['dept_name'] = td_trim(GetDeptNameById($row[0]));
        $show_arr[0][$row[0]]['dept_id'] = $row[0];
    }
}

if($count == 0)
{
    Message("",_("�޷����Ĺ�����Ϣ"));
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
    //�����Ź���֪ͨ��Ϣ��ʾ
    $dept_name = (td_trim(GetDeptNameById($dept_id)) ? td_trim(GetDeptNameById($dept_id)) : _("��ְ��Ա/�ⲿ��Ա"));
    $dept_title = (td_trim(dept_long_name($dept_id)) ? td_trim(dept_long_name($dept_id)) : _("��ְ��Ա/�ⲿ��Ա"));
    $show_str = '<table class="table table-bordered table-hover" style="width:50%;" align="center">
    <thead style="background-color:#EBEBEB;">
        <th nowrap style="text-align: center;width:5%;">'._("���").'</th>
        <th nowrap style="text-align: center;width:10%;">'._("����").'</th>
        <th nowrap style="text-align: center;width:10%;">'._("����������鿴���飩").'</th>
        <th nowrap style="text-align: center;width:5%;">'._("��������").'</th>
    </thead>';
    foreach($show_arr[1] as $key => $val)
    {
        $class_css = ($count_css%2==1 ? "TableLine1" : "TableLine2");
        $count_css++;
        $show_str .= '<tr class="'.$class_css.'">
                        <td nowrap style="text-align: center;">'.$count_css.'</td>
                        <td style="text-align: center;" title="'.$dept_title.'">'.$dept_name.'</td>
                        <td style="text-align: center;cursor: pointer;" id="user-'.$key.'">'.$val['user_name'].'</td>
                        <td style="text-align: center;">'.$val['count'].'</td>
                    </tr>';
    }
}
else if($dept_type==1 && $select_user!="")
{
    //������Ա����֪ͨ��Ϣ��ʾ
    $dept_name = (td_trim(GetDeptNameById($dept_id)) ? td_trim(GetDeptNameById($dept_id)) : _("��ְ��Ա/�ⲿ��Ա"));
    $dept_title = (td_trim(dept_long_name($dept_id)) ? td_trim(dept_long_name($dept_id)) : _("��ְ��Ա/�ⲿ��Ա"));
    $show_str = '<table class="table table-bordered table-hover" style="width:80%;" align="center">
    <thead style="background-color:#EBEBEB;">
        <th nowrap style="text-align: center;width: 45px;">'._("���").'</th>
        <th nowrap style="text-align: center;width: 80px;">'._("������").'</th>
        <th nowrap style="text-align: center;width: 60px;">'._("����").'</th>
        <th nowrap style="text-align: center;width: 170px;">'._("������Χ").'</th>
        <th nowrap style="text-align: center;width: 350px;">'._("���⣨����鿴���飩").'</th>
        <th nowrap style="text-align: center;width: 85px;">'._("����ʱ��").'</th>
        <th nowrap style="text-align: center;width: 80px;">'._("��Ч����").'</th>
        <th nowrap style="text-align: center;width: 80px;">'._("��ֹ����").'</th>
        <th nowrap style="text-align: center;width: 50px;">'._("״̬").'</th>
    </thead>';
    foreach($show_arr[2] as $val)
    {
        $class_css = ($count_css%2==1 ? "TableLine1" : "TableLine2");
        $count_css++;
        $status_title = ($val['publish']==3) ? " title='".$val['reason']."' " : "";
        $show_str .= '<tr class="'.$class_css.'">
            <td nowrap style="text-align: center;">'.$count_css.'</td>
            <td nowrap style="text-align: center;" title="'._("���ţ�").$dept_title.'">'.td_trim(GetUserNameById($select_user)).'</td>
            <td nowrap style="text-align: center;">'.$type_name_array[$val['type_id']].'</td>
            <td style="text-align: left;" title="'.$val['to_name_title'].'">'.td_trim($val['to_name_str']).'</td>
            <td nowrap style="text-align: left;cursor: pointer;" id="subject-'.$val['notify_id'].'" title="'.$val['title'].'">'.td_htmlspecialchars($val['subject']).'</td>
            <td style="text-align: center;">'.$val['create_time'].'</td>
            <td nowrap style="text-align: center;">'.$val['begin_date'].'</td>
            <td nowrap style="text-align: center;">'.$val['end_date'].'</td>
            <td nowrap style="text-align: center;" '.$status_title.' >'.$val['notify_status'].'</td>
        </tr>';
    }
}
else if($dept_type==0 || $dept_type==2)
{
    //�ಿ�Ź���֪ͨ��ʾ
    $show_str = '<table class="table table-bordered table-hover" style="width:50%;" align="center">
    <thead style="background-color:#EBEBEB;">
        <th nowrap style="text-align: center;width:5%;">'._("���").'</th>
        <th nowrap style="text-align: center;width:10%;">'._("���ţ�����鿴���飩").'</th>
        <th nowrap style="text-align: center;width:5%;">'._("��������").'</th>
    </thead>';
    foreach($show_arr[0] as $val)
    {
        $dept_name = (td_trim(GetDeptNameById($val['dept_id'])) ? td_trim(GetDeptNameById($val['dept_id'])) : _("��ְ��Ա/�ⲿ��Ա"));
        $dept_title = (td_trim(dept_long_name($val['dept_id'])) ? td_trim(dept_long_name($val['dept_id'])) : _("��ְ��Ա/�ⲿ��Ա"));
        $class_css = ($count_css%2==1 ? "TableLine1" : "TableLine2");
        $count_css++;
        $show_str .= '<tr class="'.$class_css.'">
            <td nowrap style="text-align: center;">'.$count_css.'</td>
            <td style="text-align: center;cursor: pointer;" id="dept-'.$val['dept_id'].'" title="'.$dept_title.'">'.$dept_name.'</td>
            <td style="text-align: center;">'.$val['count'].'</td>
        </tr>';
    }
}

echo $show_str;
?>
</table>
</div>
</body>
</html>
