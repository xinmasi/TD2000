<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/chinese_date.php");

$HTML_PAGE_TITLE = _("���°࿼�ڼ�¼");
include_once("inc/header.inc.php");

if(!isset($mtype))
    $mtype = "today";

switch ($mtype) 
{
    case 'today':
        $nav_text = _('����');
        $start_date = date("Y-m-d 00:00:00", time());
        $end_date = date("Y-m-d 23:59:59", time());
        $total_day = 1;
        $dskey = date("Y-m-d", time());
        $dekey = date("Y-m-d", time());
        break;
    case 'month':
        $nav_text = _('����');
        $start_date = date("Y-m-01 00:00:00", time());
        $end_date = date("Y-m-d 23:59:59", time());
        $total_day = intval(date("d"));
        $dskey = date("Y-m-01", time());
        $dekey = date("Y-m-t", time());
        break;
    case 'more':
        $nav_text = _('���ڲ�ѯ');
        if($_GET['start_date']=="" || $_GET['end_date']=="" || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['start_date']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['end_date']))
        {
            echo _("��������");
            exit;
        }
        if($_GET['start_date'] > $_GET['end_date']){
            echo _('��ʼ���ڲ��ܴ��ڽ�ֹ����');
            exit;
        }
        $start_date = $_GET['start_date']." 00:00:00";
        $end_date = $_GET['end_date']." 23:59:59";
        $total_day = ceil((strtotime($end_date) - strtotime($start_date))/24/3600);
        $dskey = $_GET['start_date'];
        $dekey = $_GET['end_date'];
        break;
    default:
        $nav_text = _('����');
        break;
}
?>

<?
    //���ģ�����޹��򣬲���ʾ������Ϣ
    //checkComponents::showBuy('MOBILE_ATTENDANCE');
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/scrollup/css/themes/image.css">
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" href="/static/common/iconfont/iconfont.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/scrollup/jquery.scrollUp.min.js<?=$GZIP_POSTFIX?>"></script>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
 $(function(){
   $('.accordion-toggle').on('click',function(){
        var $this = $(this);
        var $parent = $this.parents('.accordion-group');
        $parent.toggleClass('active');
   });
 });
// jQuery.noConflict();
// (function($){
//     $.scrollUp({scrollImg: { active: true}});
// })(jQuery)
function checkFrom()
{
    var start_date = jQuery("#START_DATE").val();
    var end_date = jQuery("#END_DATE").val();
    if(start_date == "" || end_date == "")
    {
        alert("<?=_('����д��������')?>");
        return false;
    }
    if(start_date > end_date)
    {
        alert("<?=_('��ʼ���ڲ��ܴ��ڽ�ֹ����')?>");
        return false;
    }
    return true;    
}
</script>
<style>
.T_gc{color: #51a351;}
.T_rc{color: #bd362f;}
.theader{font-size: 14px;font-weight: bold;line-height: 30px}
.theader span.error, .theader span.success{background-color: #ccc;color: #fff;padding: 3px 5px;border-radius: 4px;height: 24px;line-height: 24px;font-size: 12px;margin-top: 3px;margin-left: 10px;}
.theader span.success{background-color: #51a351;}
.nav{margin: 0 auto;margin-top:15px;width:95%;text-align: left;font-size: 14px;}
.nav img{vertical-align: -2px;}
#datepick{width: 200px;margin-right: 40px;visibility: hidden;}
.accordion-group{position: relative;background: #fff;width: 90%;margin:0px auto;}
.accordion-group-month {
    margin-bottom: 0px;
    border-bottom: 0px solid #e5e5e5 ;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
}
.accordion-group-today {
    margin-bottom: 0px;
    border: 1px solid #e5e5e5 ;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
}
.arrow{
    display: none;
    font-size: 22px;
    position: absolute;
    right: 0px;
    top: 10px;
    color: #999;
}
.arrow-down{
    display: block;
}
.active .arrow-down{
    display: none;
}
.active .arrow-up{
    display: block;
}
</style>
<body class="bodycolor attendance">
    <div >
        <div style="display: inline-block;float: left;">
            <h5 class="attendance-title"><span class="big3">&nbsp;<?=_("���°࿼�ڼ�¼")?> - <?=$nav_text?></span></h5>
        </div>
        <div style="text-align:right;display: inline-block;float: right;margin-right: 60px;">
            <input type="button" value="<?=_('���տ���')?>" class="btn" onclick="location='?mtype=today';" title="<?=_('���տ���')?>">
            &nbsp;<input type="button" value="<?=_('���¿���')?>" class="btn" onclick="location='?mtype=month';" title="<?=_('���¿���')?>">
            &nbsp;<input type="button" value="<?=_('���ڲ�ѯ')?>" data-toggle="modal" data-target="#form_div" class="btn" title="<?=_('���ڲ�ѯ')?>">
        </div>
        <div style="clear: both;"></div>
    </div> 
    
<?

 //--- ��ѯ���°�ǼǼ�¼ ---
$CUR_DATE = date("Y-m-d",time());
$data = array();
$mids = '';
$query = "SELECT * from ATTEND_DUTY where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and REGISTER_TIME > '".$start_date."' and REGISTER_TIME <= '".$end_date."' ORDER BY REGISTER_TIME DESC";
$cursor= exequery(TD::conn(),$query,true);
while($ROW=mysql_fetch_array($cursor))
{
    if($ROW['ATTEND_MOBILE_ID']!= 0)
        $mids.= $ROW['ATTEND_MOBILE_ID'].",";

    $data[date("Y-m-d", strtotime($ROW['REGISTER_TIME']))][$ROW['REGISTER_TYPE']] = $ROW;
}

//��ѯ�Ǽǵص�
$location_data = array();
if($mids!="")
{
    $mids = rtrim($mids, ",");
    $query = "SELECT * from ATTEND_MOBILE where M_ID in ($mids)";
    $cursor= exequery(TD::conn(),$query,true);
    while($ROW=mysql_fetch_array($cursor))
    {
        $location_data[$ROW['M_ID']] = $ROW['M_LOCATION'];
    }
}


$rest = 0;
$work = 0;
for($J=$total_day; $J > 0; $J--)
{
    $current_day = date("Y-m-d", (strtotime($dskey) + ($J-1)*24*3600));

    //��ѯ�����Ƿ����Ű�
    $sql = "SELECT duty_type FROM user_duty WHERE uid = '{$_SESSION['LOGIN_UID']}' AND duty_date = '$current_day'";
    $cursor= exequery(TD::conn(),$sql);
    if($row=mysql_fetch_array($cursor))
    {
        $work++;
        $user_duty = $row[0];
    }
    else
    {
        if($mtype == "today")
        {
            $query = "select * from ATTEND_HOLIDAY where BEGIN_DATE <='$current_day' and END_DATE>='$current_day'";
            $cursor = exequery(TD::conn(),$query);
            if($ROW = mysql_fetch_array($cursor))
            {
                Message("",_("�ڼ���"));
                exit;
            }
            else
            {
                Message("",_("����δ���Ű��"));
                exit;
            }
        }
        else
        {
            $rest++;
            continue;
        }
    }

    $sql1 = "SELECT * FROM attend_config WHERE DUTY_TYPE = '$user_duty' and DUTY_TYPE!='99'";
    $cursor1= exequery(TD::conn(),$sql1);
    if($row1=mysql_fetch_array($cursor1))
    {

        $DUTY_NAME    = $row1["DUTY_NAME"];
        $GENERAL      = $row1["GENERAL"];
        $DUTY_TYPE_ARR = array();
        for($I=1;$I<=6;$I++)
        {
            $cn = $I%2==0?"2":"1";
            if($row1["DUTY_TIME".$I]!="")
                $DUTY_TYPE_ARR[$I]=array( "DUTY_TIME" => $row1["DUTY_TIME".$I] ,"DUTY_TYPE" => $cn);
        }
    }

    $_id = '#'.$current_day;
    if($current_day > $dekey)
        continue;
    if(count($data[$current_day]) > 0)
    {
        $html = '<span class="success">'.sprintf(_("%s�εǼ�"), count($data[$current_day])).'</span>';
    }
    else
    {
        $html = '<span class="error">'._('δ�Ǽ�').'</span>';
    }
?>

<div <?= _(($mtype)=='today'?'class="accordion-group accordion-group-today active"':'class="accordion-group accordion-group-month active"')?>>


        <div class="accordion-heading">
            <div class=" accordion-toggle theader collapsed " data-toggle="collapse" data-parent="#accordion2" data-target="<?=$_id?>">
                <?=$current_day?><?=$html?>
                <span class="arrow arrow-up iconfont">&#xe6b9;</span>
                <span class="arrow arrow-down iconfont">&#xe6ba;</span>
            </div>
        </div>
        <div id="<?=$current_day?>" class="accordion-body collapse">
              <div class="accordion-inner">
                  <table class="TableList" align="center" style="margin-top:15px;width:95%;" id="<?=$current_day?>">
                        <colgroup>
                            <col width="140px"></col>
                            <col width="140px"></col>
                            <col width="140px"></col>
                            <col width="140px"></col>
                            <col></col>
                        </colgroup>
                        <tr class="">
                            <td nowrap align="center"><?=_("�ǼǴ���")?></td>
                            <td nowrap align="center"><?=_("�Ǽ�����")?></td>
                            <td nowrap align="center"><?=_("�涨ʱ��")?></td>
                            <td nowrap align="center"><?=_("�Ǽ�ʱ��")?></td>
                            <td nowrap align="center"><?=_("�Ǽǵص�")?></td>
                        </tr>
            <?
                for($I=1;$I<=6;$I++)
                {
                    $DUTY_TIME = $DUTY_TYPE_ARR[$I]['DUTY_TIME'];

                    if($DUTY_TIME=="")
                        continue;

                    $DUTY_TYPE = $DUTY_TYPE_ARR[$I]['DUTY_TYPE'];

                    $REGISTER_TIME="";
                    $SIGN="0";
                    //�ж�ֵ
                    $REGISTER_TIME = $data[$current_day][$I]["REGISTER_TIME"];
                    if($REGISTER_TIME!="")
                    {
                        $REGISTER_TIME = strtok($REGISTER_TIME," ");
                        $REGISTER_TIME = strtok(" ");


                        if($data[$current_day][$I]["DUTY_TIME"]!="")
                        {
                            //����
                            $str1 = this_compare_time($REGISTER_TIME,$DUTY_TIME,$data[$current_day][$I]['TIME_EARLY'],1);
                            //�ٵ�
                            $str2 = this_compare_time($REGISTER_TIME,$DUTY_TIME,$data[$current_day][$I]['TIME_LATE'],0);
                        }else
                        {
                            if($DUTY_TYPE=="1" && compare_time($REGISTER_TIME,$DUTY_TIME)==1)
                                $str2 = 1;
                            if($DUTY_TYPE=="2" && compare_time($REGISTER_TIME,$DUTY_TIME)==-1)
                                $str1 = -1;
                        }

                        if($HOLIDAY=="" && $DUTY_TYPE=="1" && $str2==1)
                        {
                            $REGISTER_TIME.= " <span class=big4>"._("�ٵ�")."</span>";
                            $SIGN ="1";
                        }
                        if($HOLIDAY=="" && $DUTY_TYPE=="2" && $str1==-1)
                        {
                            $REGISTER_TIME .= " <span class=big4>"._("����")."</span>";
                            $SIGN = "1";
                        }
                    }
                    else
                    {
                        $HOLIDAY = "";
                        $WEEK = date("w",strtotime($current_day));

                        $current_day2 = date("Y-m-d H:i:s", (strtotime($dskey) + ($J-1)*24*3600));
                        $query="select * from ATTEND_EVECTION where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and ALLOW='1' and EVECTION_DATE1<='$current_day2' and EVECTION_DATE2>='$current_day2'";
                        $cursor= exequery(TD::conn(),$query);
                        if($ROW=mysql_fetch_array($cursor))
                            $HOLIDAY="<font color='#32803f'>"._("����")."</font>";

                        $query="select LEAVE_TYPE2 from ATTEND_LEAVE where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1 <= '$current_day $DUTY_TIME' and LEAVE_DATE2 >= '$current_day $DUTY_TIME'";
                        $cursor= exequery(TD::conn(),$query);
                        if($ROW=mysql_fetch_array($cursor))
                        {
                            $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
                            $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
                            $HOLIDAY="<font color='#32803f'>"._("���")."-$LEAVE_TYPE2</font>";
                        }

                        $query="select * from ATTEND_OUT where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$current_day2') and OUT_TIME1<='".substr($DUTY_TIME,0,strrpos($DUTY_TIME,":"))."' and OUT_TIME2>='".substr($DUTY_TIME,0,strrpos($DUTY_TIME,":"))."'";
                        $cursor= exequery(TD::conn(),$query);
                        if($ROW=mysql_fetch_array($cursor))
                            $HOLIDAY="<font color='#32803f'>"._("���")."</font>";

                        $REGISTER_TIME.=$HOLIDAY;
                    }
                    $DUTY_INTERVAL_BEFORE="DUTY_INTERVAL_BEFORE".$DUTY_TYPE;
                    $DUTY_INTERVAL_AFTER="DUTY_INTERVAL_AFTER".$DUTY_TYPE;

                    if($DUTY_TYPE=="1")
                        $DUTY_TYPE_NAME=_("�ϰ�ǩ��");
                    else
                        $DUTY_TYPE_NAME=_("�°�ǩ��");

                    if($REGISTER_TIME=="")
                        $REGISTER_TIME_DESC=" <span class=big4>"._("δ�Ǽ�")."</span>";
                    else
                        $REGISTER_TIME_DESC=$REGISTER_TIME;

                    $MSG = sprintf(_("��%d�εǼ�"), $I);
            ?>
                <tr class="TableData">
                    <td nowrap align="center"><?=$MSG?></td>
                    <td nowrap align="center"><?=$DUTY_TYPE_NAME?></td>
                    <td nowrap align="center"><?=$DUTY_TIME?></td>
                    <td nowrap align="center"><?=$REGISTER_TIME_DESC?></td>
                    <td nowrap align="center"><?=$location_data[$data[$current_day][$I]['ATTEND_MOBILE_ID']]?></td>
                </tr>
            <?
                }
            ?>

            </table>
        </div>
    </div>
</div>
<?
}
?>
    <div class="nav" style="margin-bottom: 20px;">
    <?
    if($total_day > 1)
    {
        echo sprintf(_('&nbsp;&nbsp;&nbsp;���Ǽǹ�%s��'), "<span class='T_gc'>".count($data)."</span>").",".sprintf(_('δ�Ǽ�%s��'), "<span class='T_rc'>".(abs($total_day-count($data)-$rest))."</span>").",".sprintf(_('��%s��'), "<span class='T_rc'>".($rest)."</span>");
    }
    ?>
    </div>

<div id="form_div" class="modal hide fade" tabindex="-1" role="dialog">
    <form action="list.php" name="form1" method="GET" onsubmit="return checkFrom();" class="form-horizontal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">��</button>
            <h3 id="myModalLabel"><span id="title" class="title"><?=_("���ڲ�ѯ")?></span></h3>
        </div>
        <div id="form_body" class="modal-body" style="overflow-y:auto;">
           
            <div class="control-group">
                <label class="control-label" for="START_DATE"><?=_('��ʼ���ڣ�')?></label>
                <div class="controls">
                  <input type="text" name="start_date" id="START_DATE" size="20" maxlength="20"  value="" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="END_DATE"><?=_('��ֹ���ڣ�')?></label>
                <div class="controls">
                  <input type="text" name="end_date" id="END_DATE" size="20" maxlength="20"  value="<?=date("Y-m-d", time())?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})">
                </div>
            </div>            
        </div>
        <div class="modal-footer">
            <input type="submit" value="<?=_('ȷ��')?>" class="btn btn-primary">
            <button class="btn" value="<?=_('�ر�')?>" data-dismiss="modal" aria-hidden="true"><?=_('�ر�')?></button>
            <input type="hidden" name="mtype" value="more">
        </div>
    </form>
</div>
</body>
</html>