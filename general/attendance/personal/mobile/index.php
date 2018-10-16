<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/chinese_date.php");
include_once("check_priv.inc.php");
$HTML_PAGE_TITLE = _("手机考勤记录");
include_once("inc/header.inc.php");
if($IS_MAIN==1)
    $QUERY_MASTER=true;
else
    $QUERY_MASTER="";
$year = date("Y-m", time());
$DEPT_ID = $_GET['DEPT_ID'] ? $_GET['DEPT_ID'] : $_SESSION['LOGIN_DEPT_ID'];
$USER_ID = $_GET['USER_ID'] ? $_GET['USER_ID'] : $_SESSION['LOGIN_USER_ID'];
?>
<body>
<?
//检查模块有无购买，并显示购买信息
//include_once("inc/check_components.php");
//checkComponents::showBuy('MOBILE_ATTENDANCE', _("手机考勤为移动端收费组件,请联系销售人员"));
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/scrollup/css/themes/image.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/fullcalendar/2.1.1/fullcalendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/fullcalendar/2.1.1/fullcalendar.print.css" media="print">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/moment.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/scrollup/jquery.scrollUp.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/fullcalendar/2.1.1/fullcalendar.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/fullcalendar/2.1.1/lang/zh-cn.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">
    var T = {
        year: '<?=$year?>'
    };
    var gmid;
    $.scrollUp({scrollImg: { active: true}});

    function checkAttend(obj){
        mid = obj.id;
        date = obj.date;
        m_isfoot = obj.m_isfoot;
        if(!mid) return;
        gmid = mid;
        $('#myModal').modal();
        $('#myModalLabel').text(date);
    }

    $(document).ready(function(){
        renderCalendar('<?=$year?>');
        //My_Submit();
        /*$.ajax({
         url:'index.php',
         data:{
         org_id: org?org:1
         },
         async: true,
         type: 'get',
         success:function(data){
         //var data = JSON.parse(data);
         //console.log(data)
         //location.href = "index.php?org_id="+org;
         }
         })*/


        $('#myModal').on('shown', function () {
            var user_id = '<?=$USER_ID?>';
            $(this).find("#mapiframe").attr("src", "view.php?mid=" + gmid + "&m_isfoot=" + m_isfoot + "&user_id=" + user_id);
        });
    })

    function renderCalendar(syear){
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            lang: 'zh-cn',
            start: syear,
            editable: false,
            eventLimit: true,
            //weekMode: 'liquid',
            buttonIcons: false,
            contentHeight: 600,
            /*   buttonText: {
             agendaWeek: '<?=_("切换列表视图")?>',
             },  */
            events: {
                url: "data.php?user_id=<?=$USER_ID?>",
                type: 'post'
            },
            eventClick: function(calEvent, jsEvent, view) {
                checkAttend(calEvent);
            },
            eventRender: function(event, element) {
                element.date = event.date;
            }
        });

        $(".fc-agendaWeek-button").off('click').click(function(){
            location.href = 'list.php';
        });


    }
    //events: function(start, end, callback){
    function reset_user()
    {
        var dept = $('select[name="DEPT_ID"] option:selected').val();
        //alert(dept);
        $.ajax({
            type : "post",
            url : "reset_user.php",
            data : {
                dept : dept
            },
            success : function(data){
                var data = JSON.parse(data);
                $("#USER_ID").empty().append(data['op_str']);
            },
        });
    }
    function my_submit(){
        var dept = $('select[name="DEPT_ID"] option:selected').val();
        var user = $('select[name="USER_ID"] option:selected').val();

        if(dept!='' && user!=''){
            location.href = "index.php?DEPT_ID="+dept+"&USER_ID="+user;
        }
    }
    /*
     } */
</script>
<style>
body{font-family: Arial, Simsun, sans-serif;}
.T_gc{color: #51a351;}
.T_rc{color: #bd362f;}
.theader{font-size: 14px;font-weight: bold;border-top: 3px solid #ccc;line-height: 30px}
.theader span.error, .theader span.success{background-color: #ccc;color: #fff;padding: 3px 5px;border-radius: 4px;height: 24px;line-height: 24px;font-size: 12px;margin-top: 3px;margin-left: 10px;}
.theader span.success{background-color: #51a351;}
.nav{margin: 0 auto;margin-top:15px;width:95%;text-align: left;font-size: 14px;}
.nav img{vertical-align: -2px;}
#datepick{width: 200px;margin-right: 40px;visibility: hidden;}
#calendar {margin: 20px 40px;}
.fc-widget-header th{font-weight: bold;padding: 5px 0;font-size: 14px;background-color: #efefef;}
.fc-title, .fc-event{font-size: 12px;}
.fc-day-grid-event .fc-time{font-weight: normal;}
.fc-event{cursor: pointer;}
#mapiframe{width: 100%;height: 325px;}
.select-org{position:absolute;top: 20px;right: 41px;}
#myModal .modal-body{max-height:455px;}
#mapiframe{height:455px;}
.modal.fade.in{top: 4%;}

</style>
<div id='calendar'></div>
<!-- Modal -->

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><?=_("手机考勤查看")?></h3>
    </div>
    <div class="modal-body">
        <iframe src="" frameborder="0" id="mapiframe"></iframe>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"><?=_("关闭")?></button>
    </div>
</div>
<div class="select-org">
    <select name="DEPT_ID" style="width:130px;" onChange="reset_user();">
        <?=my_dept_tree(0,$DEPT_ID,array("DEPT_PRIV" => $DEPT_PRIV,"DEPT_ID_STR" => $DEPT_ID_STR));?>
    </select>
    <select name="USER_ID" id="USER_ID" style="width:130px;" onChange="my_submit();">
        <option value="0"></option>
        <?
        $COUNT=0;
        $query = "SELECT USER_ID,USER_NAME from USER,USER_PRIV where USER.USER_PRIV=USER_PRIV.USER_PRIV and (USER.NOT_LOGIN='0' or USER.NOT_MOBILE_LOGIN='0') and DEPT_ID = '$DEPT_ID' order by PRIV_NO,USER_NO,USER_NAME";
        $cursor1= exequery(TD::conn(),$query,$QUERY_MASTER);
        while($ROW=mysql_fetch_array($cursor1))
        {
            $COUNT++;
            $USER_ID1   = $ROW["USER_ID"];
            $USER_NAME  = $ROW["USER_NAME"];
            ?>

            <option value="<?=$USER_ID1?>"<?if($USER_ID1==$USER_ID) echo "selected";?>><?=$USER_NAME?></option>
            <?
            if(!$USER_ID && $COUNT==1)
                $USER_ID = $USER_ID1;
        }
        ?>
    </select>
</div>
</body>
</html>