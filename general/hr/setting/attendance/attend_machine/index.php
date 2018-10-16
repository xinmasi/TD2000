<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("考勤机数据库设置");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>

<body class="attendance" onLoad="checking_data()">
<h5 class="attendance-title"><?=_("考勤机及数据库设置")?></h5>
<br>
<?
$query = "SELECT * from ATTEND_MACHINE where MACHINEID=1";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $MACHINEID      = $ROW["MACHINEID"];
    $MACHINE_BRAND  = $ROW["MACHINE_BRAND"];
    $DATABASE_TYPE  = $ROW["DATABASE_TYPE"];
    $ACCESS_PATH    = $ROW["ACCESS_PATH"]; 
    $DATABASE_IP    = $ROW["DATABASE_IP"];
    $DATABASE_PORT  = $ROW["DATABASE_PORT"];
    $DATABASE_NAME  = $ROW["DATABASE_NAME"];
    $DATABASE_USER  = $ROW["DATABASE_USER"];
    $DATABASE_PASS  = $ROW["DATABASE_PASS"];
    $DUTY_TABLE     = $ROW["DUTY_TABLE"];
    $DUTY_USER      = $ROW["DUTY_USER"];
    $DUTY_TIME      = $ROW["DUTY_TIME"];
}
?>
<form action="submit.php"  method="post" name="form1" onSubmit="return CheckForm();">
<table class="table table-bordered"  align="center">
   
    <tr>
      <td nowrap class="TableData"><span class="center"><?=_("考勤机品牌：")?></td>
      <td class="TableData">
        <select id="MACHINE_BRAND" name="MACHINE_BRAND" class="">
          <option value="ZK_iclock660" <? if($MACHINE_BRAND=="ZK_iclock660") echo "selected";?>><?=_("中控iclock660")?></option>
        </select>
    </tr>
    <tr>
      <td nowrap class="TableData"><span class="center"><?=_("考勤机数据库类型：")?></td>
      <td class="TableData">
        <select id="DATABASE_TYPE" name="DATABASE_TYPE" class="" onChange="checking_data()">
          <option value="access" <? if($DATABASE_TYPE=="access")echo "selected";?>>access</option>         
          <option value="mysql" <? if($DATABASE_TYPE=="mysql")echo "selected";?>>mysql</option>
          <option value="sqlserver" <? if($DATABASE_TYPE=="sqlserver")echo "selected";?>>sql server</option>
        </select>
    </tr>
        <tr id="access_div_id1">
          <td nowrap  class="TableData"><span class="center">access<?=_("数据文件路径：")?></td>
          <td class="TableData">
            <input type="text" id="ACCESS_PATH" name="ACCESS_PATH" class="input-xxlarge" value="<?=$ACCESS_PATH?>">
          </td>
        </tr>
        <tr id="access_div_id2">
          <td nowrap class="TableData"><span class="center"><?=_("启用说明：")?></td>
          <td class="TableData">
            <?=_("1、到人力资源->人力资源设置->考勤设置，考勤方式选择考勤机。")?><br>
            <?=_("2、到OA的系统管理->定时任务管理->间隔执行任务中，启用同步考勤机数据。")?><br>
            <?=_("3、要求OA中人力资源->人力资源设置->考勤设置中的排班类型、登记时间段，与考勤机中的相应人的排班、时段一致。")?><br>
            <?=_("4、如果考勤机管理软件采用acces数据库，需要将该考勤软件安装在OA服务器上。")?><br>
            <?=_("5、指定好access数据库文件路径，如：C:/Program Files/Att2008/att2000.mdb。")?><br>
            <?=_("6、仅支持中控iclock660, 8.0以下的版本，对于其它版本或不支持mysql/access数据库的版本或其它型号或品牌的考勤机与OA的集成，需要定制开发。")?>
          </td>
        </tr>

        <tr id="mysql_id1">
          <td nowrap class="TableData"><?=_("考勤机数据库ip地址：")?></td>
          <td class="TableData">
            <input type="text" id="DATABASE_IP" name="DATABASE_IP" size="20" maxlength="20" value="<?=$DATABASE_IP?>">
            <span class="big4"><?=_("（注意：Mysql数据库服务不在本机，则需要授权OA服务器计算机有权连接）")?></span>
          </td>
        </tr>
        <tr id="mysql_id2">
          <td nowrap class="TableData"><?=_("考勤机数据库端口：")?></td>
          <td class="TableData">
            <input type="text" id="DATABASE_PORT" name="DATABASE_PORT" size="6" class="" value="<?=$DATABASE_PORT?>">
            <span class="big4">
            <?=_("（说明：考勤机数据库端口，一般默认为：“17770”）")?>
            </span>
          </td>
        </tr>
        <tr id="mysql_id3">
          <td nowrap class="TableData"><?=_("数据库名称：")?></td>
          <td class="TableData">
            <input type="text" id="DATABASE_NAME" name="DATABASE_NAME" size="20" maxlength="100" class="" value="<?=$DATABASE_NAME?>">
            <span class="big4">
            <?=_("（说明：考勤机数据库名称，一般默认为：“zkeco_db”）")?>
            </span>
          </td>
        </tr>
        <tr id="mysql_id4">
          <td nowrap class="TableData"><?=_("数据库用户名：")?></td>
          <td class="TableData">
            <input type="text" id="DATABASE_USER" name="DATABASE_USER" size="20" maxlength="100" class="" value="<?=$DATABASE_USER?>">
            <span class="big4">
            <?=_("（说明：考勤机数据库用户名，一般默认为：“root”，密码一般为空不需要填写）")?>
            </span>
          </td>
        </tr>
        <tr id="mysql_id5">
          <td nowrap class="TableData"><?=_("数据库密码：")?></td>
          <td class="TableData">
            <input type="password" id="DATABASE_PASS" name="DATABASE_PASS" size="21" maxlength="100" class="" value="<?=$DATABASE_PASS?>">&nbsp;
            <input type="button" class="btn btn-primary" value="<?=_('测试连接')?>" onClick="test_database()">
          </td>
        <tr id="mysql_id6">
          <td nowrap class="TableData"><span class="center"><?=_("启用说明：")?></td>
          <td class="TableData">
          <span class="big4">
            <?=_("1、以上需要填写的全部内容必须保证无误，否则将导致考勤数据无法同步到OA中以及系统出错。")?><br>
            <?=_("2、到OA的系统管理->定时任务管理->定点执行任务中，启用同步考勤机数据。")?><br>
            <?=_("3、要求OA中人力资源->人力资源设置->考勤设置中的排班类型、登记时间段，与考勤机中的相应人的排班、时段一致。")?><br>
            <?=_("4、其他考勤机与OA的集成，需要定制开发。")?><br>
           </span><br>
          </td>
        </tr>
        </tr>
        <tr id="mysql_id7">
          <td nowrap class="TableData" valign="top"><?=_("上下班打卡数据存储表表名：")?></td>
          <td class="TableData">
            <input type="text"  id="DUTY_TABLE" name="DUTY_TABLE" size="20" maxlength="100" class="" value="<?=$DUTY_TABLE?>">
            <span class="big4">
            <?=_("（说明：此处填写存放在考勤机中上下班打卡时间相关信息的表名一般默认为：“CHECKINOUT”）")?>
            </span>
          </td>
        </tr>
        <tr id="mysql_id8">
          <td nowrap class="TableData" valign="top"><?=_("上下班打卡数据存储表用户字段名：")?></td>
          <td class="TableData">
            <input type="text" id="DUTY_USER" name="DUTY_USER" size="20" maxlength="100" class="" value="<?=$DUTY_USER?>">
            <span class="big4">
            <?=_("（说明：此处填写考勤机的用户ID，系统将通过此ID找到考勤机中的用户姓名，来完成数据同步）")?>
            </span>
          </td>
        </tr>    
        <tr id="mysql_id9">
          <td nowrap class="TableData"><?=_("打卡时间字段名：")?></td>
          <td class="TableData">
            <input type="text"  id="DUTY_TIME" name="DUTY_TIME" size="20" maxlength="100" class="" value="<?=$DUTY_TIME?>">
            <span class="big4">
            <?=_("（说明：此处填写考勤机中上下班打卡时间的字段）")?>
            </span>
          </td>
        </tr>
        
        <tr id="sqlserver_id1">
          <td nowrap class="TableData"><?=_("考勤机数据库IP地址：")?></td>
          <td class="TableData">
             <input type="text" name="DATABASE_IP_SQL" id="DATABASE_IP_SQL" size="20" maxlength="20" value="<?=$DATABASE_IP?>">
             <span class="big4"><?=_("（注意：sql server 数据库服务不在本机，则需要授权OA服务器计算机有权连接）")?></span>
          </td>
        </tr>
        <tr id="sqlserver_id2">
          <td nowrap class="TableData"><?=_("考勤机数据库端口：")?></td>
          <td class="TableData">
             <input type="text" name="DATABASE_PORT_SQL" id="DATABASE_PORT_SQL" class="" size="6" value="<?=$DATABASE_PORT?>">
             <span class="big4">
            <?=_("（说明：考勤机数据库端口，一般默认为：“1433”）")?>
            </span>
          </td>
        </tr>
        <tr id="sqlserver_id3">
          <td nowrap class="TableData"><?=_("数据库名称：")?></td>
          <td class="TableData">
            <input type="text" id="DATABASE_NAME_SQL" name="DATABASE_NAME_SQL" size="20" maxlength="100" class="" value="<?=$DATABASE_NAME?>">
            <span class="big4">
            <?=_("（说明：考勤机数据库名称，一般默认为：“zkeco_db”）")?>
            </span>
          </td>
        </tr>
        <tr id="sqlserver_id4">
          <td nowrap class="TableData"><?=_("数据库用户名：")?></td>
          <td class="TableData">
            <input type="text" name="DATABASE_USER_SQL" id="DATABASE_USER_SQL" class="" value="<?=$DATABASE_USER?>" size="20" maxlength="100" maxlength="20">
            <span class="big4">
            <?=_("（说明：考勤机数据库用户名，一般默认为：“sa”）")?>
            </span>
          </td>
        </tr>
        <tr id="sqlserver_id5">
          <td nowrap class="TableData"><?=_("数据库密码：")?></td>
          <td class="TableData">
            <input type="password" name="DATABASE_PASS_SQL" id="DATABASE_PASS_SQL" size="21" maxlength="100" class="" value="<?=$DATABASE_PASS?>">&nbsp;
            <input type="button" class="btn  btn-primary" value="<?=_('测试连接')?>" onClick="test_database()">
          </td>
        </tr>
        <tr id="sqlserver_id6">
          <td nowrap class="TableData"><span class="center"><?=_("启用说明：")?></span></td>
          <td class="TableData">
          <span class="big4">
            <?=_("1、以上需要填写的全部内容必须保证无误，否则将导致考勤数据无法同步到OA中以及系统出错。")?><br>
            <?=_("2、到OA的系统管理->定时任务管理->定点执行任务中，启用同步考勤机数据。")?><br>
            <?=_("3、要求OA中人力资源->人力资源设置->考勤设置中的排班类型、登记时间段，与考勤机中的相应人的排班、时段一致。")?><br>
            <?=_("4、其他考勤机与OA的集成，需要定制开发。")?><br>
           </span><br>
          </td>
        </tr>
        <tr id="sqlserver_id7">
          <td nowrap class="TableData" valign="top"><?=_("上下班打卡数据存储表表名：")?></td>
          <td class="TableData">
            <input type="text"  id="DUTY_TABLE_SQL" name="DUTY_TABLE_SQL" size="20" maxlength="100" class="" value="<?=$DUTY_TABLE?>">
            <span class="big4">
            <?=_("（说明：此处填写存放在考勤机中上下班打卡时间相关信息的表名一般默认为：“CHECKINOUT”）")?>
            </span>
          </td>
        </tr>
        <tr id="sqlserver_id8">
          <td nowrap class="TableData" valign="top"><?=_("上下班打卡数据存储表用户字段名：")?></td>
          <td class="TableData">
            <input type="text" id="DUTY_USER_SQL" name="DUTY_USER_SQL" size="20" maxlength="100" class="" value="<?=$DUTY_USER?>">
            <span class="big4">
            <?=_("（说明：此处填写考勤机的用户ID，系统将通过此ID找到考勤机中的用户姓名，来完成数据同步）")?>
            </span>
          </td>
        </tr>    
        <tr id="sqlserver_id9">
          <td nowrap class="TableData"><?=_("打卡时间字段名：")?></td>
          <td class="TableData">
            <input type="text"  id="DUTY_TIME_SQL" name="DUTY_TIME_SQL" size="20" maxlength="100" class="" value="<?=$DUTY_TIME?>">
            <span class="big4">
            <?=_("（说明：此处填写考勤机中上下班打卡时间的字段）")?>
            </span>
          </td>
        </tr>
        
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap style="text-align: center;">
        <input type="submit" value="<?=_("保存")?>" class="btn  btn-primary">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="btn" onClick="location='../#machineSetting'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>
<script type="text/javascript">
document.getElementById("mysql_id7").style.display = "none";
document.getElementById("mysql_id8").style.display = "none";
document.getElementById("mysql_id9").style.display = "none";
document.getElementById("sqlserver_id7").style.display = "none";
document.getElementById("sqlserver_id8").style.display = "none";
document.getElementById("sqlserver_id9").style.display = "none";
function checking_data()
{
    if(document.getElementById("DATABASE_TYPE").value=="access")
    {
        for(var i=0;i<6;i++)
        {
            var mysql_id = "mysql_id"+(i+1);
            document.getElementById(mysql_id).style.display = "none";
        }
        for(var i=0;i<2;i++)
        {
            var access_id = "access_div_id"+(i+1);
            document.getElementById(access_id).style.display = "";
        }
        for(var i=0;i<6;i++)
        {
            var sqlserver_id = "sqlserver_id"+(i+1);
            document.getElementById(sqlserver_id).style.display = "none";
        }
    }
    else if(document.getElementById("DATABASE_TYPE").value=="mysql")
    {
        for(var i=0;i<6;i++)
        {
            var mysql_id = "mysql_id"+(i+1);
            document.getElementById(mysql_id).style.display = "";
        }
        for(var i=0;i<2;i++)
        {
            var access_id = "access_div_id"+(i+1);
            document.getElementById(access_id).style.display = "none";
        }
        for(var i=0;i<6;i++)
        {
            var sqlserver_id = "sqlserver_id"+(i+1);
            document.getElementById(sqlserver_id).style.display = "none";
        }
    
    }
    else if(document.getElementById("DATABASE_TYPE").value=="sqlserver")
    {
        for(var i=0;i<6;i++)
        {
            var mysql_id = "mysql_id"+(i+1);
            document.getElementById(mysql_id).style.display = "none";
        }
        for(var i=0;i<2;i++)
        {
            var access_id = "access_div_id"+(i+1);
            document.getElementById(access_id).style.display = "none";
        }
        for(var i=0;i<6;i++)
        {
            var sqlserver_id = "sqlserver_id"+(i+1);
            document.getElementById(sqlserver_id).style.display = "";
        }
    }
}
function test_database()
{
    var DATABASE_TYPE = document.getElementById("DATABASE_TYPE").value;
    var DATABASE_IP   = document.getElementById("DATABASE_IP").value;
    var DATABASE_PORT = document.getElementById("DATABASE_PORT").value;
    var DATABASE_NAME = document.getElementById("DATABASE_NAME").value;
    var DATABASE_USER = document.getElementById("DATABASE_USER").value;
    var DATABASE_PASS = document.getElementById("DATABASE_PASS").value;
    if(DATABASE_TYPE == "mysql")
    {
        jQuery.post("test_database.inc.php","DATABASE_IP="+DATABASE_IP+"&DATABASE_PORT="+DATABASE_PORT+"&DATABASE_NAME="+DATABASE_NAME+"&DATABASE_USER="+DATABASE_USER+"&DATABASE_PASS="+DATABASE_PASS,function(date){
            if(date=="+OK")
            {
                alert("<?=_('连接成功！')?>");
            }
            else if(date=="-flase")
            {
                alert("<?=_('连接失败！请注意您的数据库名称是否正确！')?>");
            }
            else
            {
                alert("<?=_('连接失败！请正确填写数据库IP地址，端口号，用户名，密码！')?>");
            }
        });
    }else if(DATABASE_TYPE == "sqlserver")
    {
        var DATABASE_IP   = document.getElementById("DATABASE_IP_SQL").value;
        var DATABASE_PORT = document.getElementById("DATABASE_PORT_SQL").value;
        var DATABASE_NAME = document.getElementById("DATABASE_NAME_SQL").value;
        var DATABASE_USER = document.getElementById("DATABASE_USER_SQL").value;
        var DATABASE_PASS = document.getElementById("DATABASE_PASS_SQL").value;
        
        jQuery.post("test_database_sqlserver.php","DATABASE_IP="+DATABASE_IP+"&DATABASE_PORT="+DATABASE_PORT+"&DATABASE_NAME="+DATABASE_NAME+"&DATABASE_USER="+DATABASE_USER+"&DATABASE_PASS="+DATABASE_PASS,function(date1){
            if(date1== "+OK")
            {
                alert("<?=_('连接成功！')?>");
            }
            else
            {
                alert("<?=_('连接失败！请正确填写数据库IP地址，端口号，用户名，密码！')?>");
            }
        });
    }
}
</script>