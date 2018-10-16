<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
if(!isset($TYPE))
   $TYPE="0";
if(!isset($PAGE_SIZE))
   $PAGE_SIZE =15;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("周期性资源申请");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/mouse_mon.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/mytable.js"></script>
<Script language="JavaScript">
function change(){
    var WEEKDAY_SET2=document.addform.WEEKDAY_SET2.value;
    var TIME_TITLE2=document.addform.TIME_TITLE2.value;
    var USER_ID=document.addform.USER_ID.value;

    var SOURCE_NOs=document.addform.SOURCE_NO.options[document.addform.SOURCE_NO.selectedIndex].value;
    var request=getXMLHttpObj();
    request.onreadystatechange=function()
    {
        if(request.readyState==4)
        {
            var obj=document.getElementById("map");
            obj.innerHTML=request.responseText;
        }
    }    
    request.open("get","get.php?WEEKDAY_SET2="+WEEKDAY_SET2+"&TIME_TITLE2="+TIME_TITLE2+"&SOURCE_NO="+SOURCE_NOs+"&USER_ID="+USER_ID,true);
    request.send(null);
    
 }
function add_onclick()
{
    var sources = document.getElementById("SOURCE_NO").value;
    if(sources=="")
    {
        alert("<?=_("资源名字不可以为空")?>");
        return ;
    }
    if(document.addform.B_APPLY_TIME.value == "")
    {
        alert("<?=_("请填写开始日期")?>");
        return ;
    }

    document.addform.submit()
}
</script>
<style>
.TableData{
    height:35px;
}
.small td{
    border:1px solid #ccc;
    padding:5px;
}
</style>
<?
if($SAVE)
{
    
    $query = "SELECT * FROM `OA_SOURCE_USED` where APPLY_DATE >= '$B_APPLY_TIME' and APPLY_DATE <= '$E_APPLY_TIME' and SOURCEID = '$SOURCE_NO'";
    $cursor = exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor)){
        
    }
    for($I=0;$I<7;$I++)
      {
        $VAR="WEEK".$I;
       if($$VAR=="on")
          $WEEKDAY_SET.=$I.",";
      }
   $WEEKDAY_SET=substr($WEEKDAY_SET,0,-1);
   for($J=0;$J<count($TIMESTR);$J++)
   {
       $TIME_TITLE_NEW.=$TIMESTR[$J].",";
   }
   $TIME_TITLE_NEW=substr($TIME_TITLE_NEW,0,-1);
    $APPLY_TIME=date("Y-m-d H:i:s");
    if($CYCID!="")
    {
       $query = "update OA_CYCLESOURCE_USED set SOURCEID='$SOURCE_NO',B_APPLY_TIME='$B_APPLY_TIME',E_APPLY_TIME='$E_APPLY_TIME',WEEKDAY_SET='$WEEKDAY_SET',TIME_TITLE='$TIME_TITLE_NEW',REMARK='$REMARK',USER_ID='$USER',APPLY_TIME='$APPLY_TIME' where CYCID='$CYCID'";
       exequery(TD::conn(),$query);
       if($USER!=$LOGIN_ORG_ID)
       {
           $MSG1 = sprintf(_("您可在 %s 至 %s 的以下时段内使用"),$B_APPLY_TIME,$E_APPLY_TIME);
           $SMS_CONTENT=$MSG1.GetSourceNameById($SOURCE_NO). _("：") . $TIME_TITLE_NEW;
           send_sms($APPLY_TIME, $_SESSION["LOGIN_USER_ID"],$USER,76, $SMS_CONTENT, $REMIND_URL);
       }
       Message("",_("编辑资源成功"));
    }
    else
    {
        $query = "insert into  OA_CYCLESOURCE_USED(SOURCEID,B_APPLY_TIME,E_APPLY_TIME,WEEKDAY_SET,TIME_TITLE,REMARK,USER_ID,APPLY_TIME) values ('$SOURCE_NO','$B_APPLY_TIME','$E_APPLY_TIME','$WEEKDAY_SET','$TIME_TITLE_NEW','$REMARK','$USER','$APPLY_TIME')";
        exequery(TD::conn(),$query);
        if($USER!=$LOGIN_ORG_ID)
       {
            $MSG2 = sprintf(_("您从 %s 到 %s 在%s 时间段内可以使用"),$B_APPLY_TIME,$E_APPLY_TIME,$TIME_TITLE_NEW);
            $SMS_CONTENT=$MSG2.GetSourceNameById($SOURCE_NO);
            send_sms($APPLY_TIME, $_SESSION["LOGIN_USER_ID"],$USER, 76, $SMS_CONTENT, $REMIND_URL);
        }
        Message("",_("添加资源成功"));
   }
?>
  <BR>
<DIV align=center>
    <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close()">
</DIV>
<script>opener.location.reload();
</script>
<?
    exit;
}

if($CYCID!="")
{
   $query = "select * from OA_CYCLESOURCE_USED where CYCID='$CYCID'";
   $cursor = exequery(TD::conn(),$query);
   if($ROWS=mysql_fetch_array($cursor))
   {
      $SOURCE_NO=$ROWS["SOURCEID"];
      $B_APPLY_TIME = $ROWS["B_APPLY_TIME"];
      $E_APPLY_TIME = $ROWS["E_APPLY_TIME"];
      $WEEKDAY_SET2 =$ROWS["WEEKDAY_SET"];
      $TIME_TITLE2 = $ROWS["TIME_TITLE"];
      $REMARK = $ROWS["REMARK"];
      $USER_ID=$ROWS["USER_ID"];
     
   }
}
?>
<body class="bodycolor" onLoad="change();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/source.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("周期性资源申请")?></span></td>
  </tr>
</table>
<br>
<FORM id=addform name=addform action=add.php?SAVE=1&WEEEKDAY_SET2=<?=$WEEKDAY_SET2?>&TIME_TITLE=<?=$TIME_TITLE2?> method=post>
    <table class="small" width=100% border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
        <TR class=TableHeader>
            <TD colspan=2><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absmiddle"> <?if($CYCID!="")echo _("编辑");else echo _("添加");?><?=_("周期资源申请")?></TD>
       </TR>
        <TR class=TableData>
            <TD nowrap><b><?=_("资源名称")?></b></TD>
            <TD>
                <select name="SOURCE_NO" id="SOURCE_NO" onChange="change()">
                <?
                $query="select SOURCEID,SOURCENAME from OA_SOURCE ";
                $cursor = exequery(TD::conn(),$query);
                while($ROW=mysql_fetch_array($cursor))
                {
                ?>
                     <option value="<?=$ROW['SOURCEID']?>" <? if($SOURCE_NO==$ROW['SOURCEID']) echo "selected";?>><?=$ROW['SOURCENAME']?></option> 
                <?
               }
                ?>
                </select>
            </TD>
        </TR>
        <TR class=TableData>
            <TD ><b><?=_("开始日期")?></b></TD>
            <TD>
                <input type="text" id="start_time" name="B_APPLY_TIME" size="10" maxlength="10" class="BigInput" value="<?=$B_APPLY_TIME?>"  onClick="WdatePicker()">
           
             </TD>
        </TR>
        <TR class=TableData>
            <TD><b><?=_("结束日期")?></b></TD>
            <TD>
                <input type="text" name="E_APPLY_TIME" size="10" maxlength="10" class="BigInput" value="<?=$E_APPLY_TIME?>"  onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})">
            
          </TD>
        </TR>
        <TR class=TableData>
            <TD><b><?=_("使用设定")?></b></TD>
            <TD>
                 <div id="map"> </div>
        <TR class=TableData>
            <TD><b><?=_("备注")?></b></TD>
            <TD style="padding-top:10px;"><textarea name=REMARK rows=5 cols=65><?=$REMARK?></textarea></TD>
        </TR>
        <TR class=TableControl>
            <TD colspan=2 align=center width="500">
                 <INPUT TYPE="hidden" NAME="USER_ID" value="<?=$USER_ID?>">
                 <INPUT TYPE="hidden" NAME="CYCID" value="<?=$CYCID?>">
                 <INPUT TYPE="hidden" NAME="WEEKDAY_SET2" value="<?=$WEEKDAY_SET2?>">
                 <INPUT TYPE="hidden" NAME="TIME_TITLE2" value="<?=$TIME_TITLE2?>">
                 <INPUT type="button" class="BigButton" value="<?=_("保存")?>" id=post name=post LANGUAGE=javascript onClick="return add_onclick()">&nbsp;&nbsp;
           <input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close()">
            </TD>
         </TR>
    </table>
</FORM>
</BODY>
</HTML>
<?
function GetSourceNameById($SOURCEID)
{  
        $query = "select SOURCENAME from OA_SOURCE where SOURCEID='$SOURCEID'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $SOURCENAME = $ROW["SOURCENAME"];
    }    
    return $SOURCENAME;
}

?>
