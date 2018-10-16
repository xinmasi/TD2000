<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/header.inc.php");
include_once("inc/td_core.php");
include_once ("inc/utility_project.php");

$proj_hook = project_hook("project_task_x1");

//修改事务提醒状态--yc
update_sms_status('42',0);

$query = "select PRIV_USER FROM PROJ_PRIV WHERE PRIV_CODE='NEW'";
$cursor = exequery(TD::conn(), $query);
if($ROW = mysql_fetch_array($cursor))
    $NEW_PRIV = $ROW["PRIV_USER"];

$NEW_PRIV = explode("|",$NEW_PRIV);
if(find_id($NEW_PRIV[0],$_SESSION["LOGIN_DEPT_ID"]) || $NEW_PRIV[0]=="ALL_DEPT"  || find_id($NEW_PRIV[1],$_SESSION["LOGIN_USER_PRIV"]) || find_id($NEW_PRIV[2],$_SESSION["LOGIN_USER_ID"]))
{
    $NewPriv=1;
}
else
    $NewPriv=0;//"alert('"._("您没有立项权限，如需项目立项权限请与管理员联系开通！")."')";
?>
<script>
    var index = 0;
    var NewPriv = <?=$NewPriv?>;

</script>
<script src="gridcolumn.php"></script>
<style type="text/css">
#loading {
    position:absolute;
    left:45%;
    top:40%;
    padding:2px;
    z-index:20001;
    height:auto;
}
#loading .loading-indicator {
    background:white;
    color:#444;
    font:bold 20px tahoma, arial, helvetica;
    padding:10px;
    margin:0;
    height:auto;
}
#loading-msg {
    font: normal 18px arial, tahoma, sans-serif;
}
</style>
<input type="hidden" name="CURR_TASK_ID" id="CURR_TASK_ID" value="">
<input type="hidden" name="CURR_PROJ_ID" id="CURR_PROJ_ID" value="">
<input type="hidden" name="PROJ_ID" id="PROJ_ID" value="">
<input type="hidden" name="HIDEFIN" id="HIDEFIN" value="">
<input type="hidden" name="RANGE" id="RANGE" value="">
<input type="hidden" name="limit" id="limit" value="">
<input type="hidden" name="start" id="start" value="">
<input type="hidden" name="page" id="page" value="">
<div id="loading">
    <div class="loading-indicator"> <img src="<?=MYOA_JS_SERVER?>/static/js/ext/resources/themes/images/default/shared/large-loading.gif" width="32" height="32" style="margin-right:8px;float:left;vertical-align:top;"/> <span id="loading-msg">Loading ... </div>
</div>


<?
include_once("app.html");
?>
<style>
*{font-size:12px;}
.newwarpLine div{
    overflow:hidden;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    padding:3px 3px 3px 5px;
    /*white-space: nowrap;*/
    white-space:normal !important;
}
</style>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<script type="text/javascript">

Ext.onReady(function(){
    Ext.get('loading').dom.style.display = 'none';
    //Ext.get('loading').setOpacity(0.0,{duration:1.0,callback:function(){this.hide();}});
});

jQuery.noConflict();

function taskDetail(TASK_ID)
{
    myleft=(screen.availWidth-800)/2;
    Ext.create('Ext.Window', {
        id:'tDetail_'+TASK_ID,
        title: "<?=_("任务详情")?>",
        resizable:true,
        width: 800,
        height: screen.availHeight/2,
        x: myleft,
        y: 50,
        layout: 'fit',
        html:'<iframe frameborder=0 width="100%" height="100%" src="task_detail.php?TASK_ID='+TASK_ID+'">'
    }).show();
}
function projDetail(PROJ_ID)
{
    myleft=(screen.availWidth-800)/2;
    Ext.create('Ext.Window', {
        id:'pDetail_'+PROJ_ID,
        title: "<?=_("项目基本信息")?>",
        resizable:true,
        width: 800,
        height: screen.availHeight/2,
        x: myleft,
        y: 50,
        layout: 'fit',
        html:'<iframe frameborder=0 width="100%" height="100%" src="proj_detail.php?PROJ_ID='+PROJ_ID+'">'
    }).show();
}
//-------------zfc-------------
var c_t_d = false;
function view_task_detail(PROJ_ID, TASK_ID)
{
    if(c_t_d)
        c_t_d.close();
    myleft=(screen.availWidth-800)/2;
    url = "view_task_detail.php?PROJ_ID=" + PROJ_ID + "&TASK_ID=" + TASK_ID;
    c_t_d = window.open(url, "", "status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=800,height=500,left="+myleft+",top=50");
}

function check_task_finished(PROJ_ID, TASK_ID) {
    jQuery.post("check_task_flow_over.php",{TASK_ID:TASK_ID},function(data){
        if(data == 0){
            alert("您的上一个任务审批流程还未结束，请等待流程结束！");
        }else{
            document.getElementById("CURR_PROJ_ID").value = PROJ_ID;
            document.getElementById("CURR_TASK_ID").value = TASK_ID;
            _get("check_task_finished.php", "PROJ_ID=" + PROJ_ID + "&TASK_ID=" + TASK_ID, check_task_ret);
        }
    })
}

function check_task_ret(req)
{
    if(req.status==200)
    {
        var PROJ_ID = document.getElementById("CURR_PROJ_ID").value;
        var TASK_ID = document.getElementById("CURR_TASK_ID").value;
        if(req.responseText.indexOf('OK') > 0)
        {
            var msg='<?=_("确认要结束此任务吗？")?>';
            if(window.confirm(msg))
            {
                //end_task(PROJ_ID, TASK_ID);
                end_task_flow(PROJ_ID, TASK_ID);
            }
        }
        else
        {
            var msg='<?=_("该任务尚未完成100%，是否结束？")?>';
            if(window.confirm(msg))
            {
                //end_task(PROJ_ID, TASK_ID);
                end_task_flow(PROJ_ID, TASK_ID);
            }
        }
    }
}

function end_task_flow(PROJ_ID, TASK_ID){
    <?
    if($proj_hook == 1){
    ?>
    //走流程
    window.open("task_run_flow.php?PROJ_ID="+PROJ_ID+"&TASK_ID="+TASK_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");
    <?
    }else{
    ?>
    //不走流程
    end_task(PROJ_ID, TASK_ID);
    <?
    }
    ?>

}

function end_task(PROJ_ID, TASK_ID)
{
    _get("task_over.php", "PROJ_ID="+PROJ_ID + "&TASK_ID=" + TASK_ID,function(req){refresh_grid();});

}
function resume_task(TASK_ID)
{
    var msg='<?=_("确认要恢复执行此任务吗？")?>';
    if(window.confirm(msg))
    {
        _get("resume.php", "TASK_ID="+TASK_ID,function(req){refresh_grid();});
    }
}
function edit_task(PROJ_ID, TASK_ID, TASK_NAME)
{
    var tabs = Ext.getCmp("center");
    var newtab = tabs.add({
        id :'Tab '+ (tabs.items.length + 1),
        closable:true,
        html:'<iframe width=100% height=100% src="index1.php?PROJ_ID='+PROJ_ID+'&TASK_ID='+TASK_ID+'"></iframe>',
        title:  TASK_NAME ? TASK_NAME : '<?=_("修改任务")?>'

    });
    tabs.setActiveTab(newtab);
}

function task_filter_load(newValue)
{
    if(newValue){
        var prop = newValue.property;
        var val =  newValue.value;
        $(prop).value = val;
    }
    var RANGE = $("RANGE").value ? $("RANGE").value : '';
    var PROJ_ID = $("PROJ_ID").value ? $("PROJ_ID").value : '0';
    var HIDEFIN = $("HIDEFIN").value;
    if($("HIDEFIN").value == 0 )
    {
        HIDEFIN = false;
    }
    var filterscfg = [

        {
            'property':"RANGE",
            'value':RANGE
        },
        {
            'property':"PROJ_ID",
            'value':PROJ_ID
        },
        {
            'property':"HIDEFIN",
            'value':HIDEFIN
        }
    ];
    var sorterscfg = [
        new Ext.util.Sorter({
            property : 'name',
            direction: 'ASC'
        }),
        new Ext.util.Sorter({
            property : 'age',
            direction: 'DESC'
        })
    ];
    var ret = {
        params:{
            start:Ext.getCmp("gridpagebar").start,
            page:Ext.getCmp("gridpagebar").page,
            limit:Ext.getCmp("gridpagebar").pageSize
        },
        filters:filterscfg
    };
    return ret;
}
function refresh_grid(newValue)
{
    var pStore = Ext.getCmp("gridpagebar").store;
    pStore.clearFilter(true);
    var params = task_filter_load(newValue);
    params.params = {
        page:1,
        start:0,
        limit:10
    };
    pStore.load(params);
}
</script>

<script src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
