<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("���ڻ����ݿ�����");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>

<body class="attendance" onLoad="checking_data()">
<h5 class="attendance-title"><?=_("���ڻ������ݿ�����")?></h5>
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
      <td nowrap class="TableData"><span class="center"><?=_("���ڻ�Ʒ�ƣ�")?></td>
      <td class="TableData">
        <select id="MACHINE_BRAND" name="MACHINE_BRAND" class="">
          <option value="ZK_iclock660" <? if($MACHINE_BRAND=="ZK_iclock660") echo "selected";?>><?=_("�п�iclock660")?></option>
        </select>
    </tr>
    <tr>
      <td nowrap class="TableData"><span class="center"><?=_("���ڻ����ݿ����ͣ�")?></td>
      <td class="TableData">
        <select id="DATABASE_TYPE" name="DATABASE_TYPE" class="" onChange="checking_data()">
          <option value="access" <? if($DATABASE_TYPE=="access")echo "selected";?>>access</option>         
          <option value="mysql" <? if($DATABASE_TYPE=="mysql")echo "selected";?>>mysql</option>
          <option value="sqlserver" <? if($DATABASE_TYPE=="sqlserver")echo "selected";?>>sql server</option>
        </select>
    </tr>
        <tr id="access_div_id1">
          <td nowrap  class="TableData"><span class="center">access<?=_("�����ļ�·����")?></td>
          <td class="TableData">
            <input type="text" id="ACCESS_PATH" name="ACCESS_PATH" class="input-xxlarge" value="<?=$ACCESS_PATH?>">
          </td>
        </tr>
        <tr id="access_div_id2">
          <td nowrap class="TableData"><span class="center"><?=_("����˵����")?></td>
          <td class="TableData">
            <?=_("1����������Դ->������Դ����->�������ã����ڷ�ʽѡ���ڻ���")?><br>
            <?=_("2����OA��ϵͳ����->��ʱ�������->���ִ�������У�����ͬ�����ڻ����ݡ�")?><br>
            <?=_("3��Ҫ��OA��������Դ->������Դ����->���������е��Ű����͡��Ǽ�ʱ��Σ��뿼�ڻ��е���Ӧ�˵��Űࡢʱ��һ�¡�")?><br>
            <?=_("4��������ڻ������������acces���ݿ⣬��Ҫ���ÿ��������װ��OA�������ϡ�")?><br>
            <?=_("5��ָ����access���ݿ��ļ�·�����磺C:/Program Files/Att2008/att2000.mdb��")?><br>
            <?=_("6����֧���п�iclock660, 8.0���µİ汾�����������汾��֧��mysql/access���ݿ�İ汾�������ͺŻ�Ʒ�ƵĿ��ڻ���OA�ļ��ɣ���Ҫ���ƿ�����")?>
          </td>
        </tr>

        <tr id="mysql_id1">
          <td nowrap class="TableData"><?=_("���ڻ����ݿ�ip��ַ��")?></td>
          <td class="TableData">
            <input type="text" id="DATABASE_IP" name="DATABASE_IP" size="20" maxlength="20" value="<?=$DATABASE_IP?>">
            <span class="big4"><?=_("��ע�⣺Mysql���ݿ�����ڱ���������Ҫ��ȨOA�������������Ȩ���ӣ�")?></span>
          </td>
        </tr>
        <tr id="mysql_id2">
          <td nowrap class="TableData"><?=_("���ڻ����ݿ�˿ڣ�")?></td>
          <td class="TableData">
            <input type="text" id="DATABASE_PORT" name="DATABASE_PORT" size="6" class="" value="<?=$DATABASE_PORT?>">
            <span class="big4">
            <?=_("��˵�������ڻ����ݿ�˿ڣ�һ��Ĭ��Ϊ����17770����")?>
            </span>
          </td>
        </tr>
        <tr id="mysql_id3">
          <td nowrap class="TableData"><?=_("���ݿ����ƣ�")?></td>
          <td class="TableData">
            <input type="text" id="DATABASE_NAME" name="DATABASE_NAME" size="20" maxlength="100" class="" value="<?=$DATABASE_NAME?>">
            <span class="big4">
            <?=_("��˵�������ڻ����ݿ����ƣ�һ��Ĭ��Ϊ����zkeco_db����")?>
            </span>
          </td>
        </tr>
        <tr id="mysql_id4">
          <td nowrap class="TableData"><?=_("���ݿ��û�����")?></td>
          <td class="TableData">
            <input type="text" id="DATABASE_USER" name="DATABASE_USER" size="20" maxlength="100" class="" value="<?=$DATABASE_USER?>">
            <span class="big4">
            <?=_("��˵�������ڻ����ݿ��û�����һ��Ĭ��Ϊ����root��������һ��Ϊ�ղ���Ҫ��д��")?>
            </span>
          </td>
        </tr>
        <tr id="mysql_id5">
          <td nowrap class="TableData"><?=_("���ݿ����룺")?></td>
          <td class="TableData">
            <input type="password" id="DATABASE_PASS" name="DATABASE_PASS" size="21" maxlength="100" class="" value="<?=$DATABASE_PASS?>">&nbsp;
            <input type="button" class="btn btn-primary" value="<?=_('��������')?>" onClick="test_database()">
          </td>
        <tr id="mysql_id6">
          <td nowrap class="TableData"><span class="center"><?=_("����˵����")?></td>
          <td class="TableData">
          <span class="big4">
            <?=_("1��������Ҫ��д��ȫ�����ݱ��뱣֤���󣬷��򽫵��¿��������޷�ͬ����OA���Լ�ϵͳ����")?><br>
            <?=_("2����OA��ϵͳ����->��ʱ�������->����ִ�������У�����ͬ�����ڻ����ݡ�")?><br>
            <?=_("3��Ҫ��OA��������Դ->������Դ����->���������е��Ű����͡��Ǽ�ʱ��Σ��뿼�ڻ��е���Ӧ�˵��Űࡢʱ��һ�¡�")?><br>
            <?=_("4���������ڻ���OA�ļ��ɣ���Ҫ���ƿ�����")?><br>
           </span><br>
          </td>
        </tr>
        </tr>
        <tr id="mysql_id7">
          <td nowrap class="TableData" valign="top"><?=_("���°�����ݴ洢�������")?></td>
          <td class="TableData">
            <input type="text"  id="DUTY_TABLE" name="DUTY_TABLE" size="20" maxlength="100" class="" value="<?=$DUTY_TABLE?>">
            <span class="big4">
            <?=_("��˵�����˴���д����ڿ��ڻ������°��ʱ�������Ϣ�ı���һ��Ĭ��Ϊ����CHECKINOUT����")?>
            </span>
          </td>
        </tr>
        <tr id="mysql_id8">
          <td nowrap class="TableData" valign="top"><?=_("���°�����ݴ洢���û��ֶ�����")?></td>
          <td class="TableData">
            <input type="text" id="DUTY_USER" name="DUTY_USER" size="20" maxlength="100" class="" value="<?=$DUTY_USER?>">
            <span class="big4">
            <?=_("��˵�����˴���д���ڻ����û�ID��ϵͳ��ͨ����ID�ҵ����ڻ��е��û����������������ͬ����")?>
            </span>
          </td>
        </tr>    
        <tr id="mysql_id9">
          <td nowrap class="TableData"><?=_("��ʱ���ֶ�����")?></td>
          <td class="TableData">
            <input type="text"  id="DUTY_TIME" name="DUTY_TIME" size="20" maxlength="100" class="" value="<?=$DUTY_TIME?>">
            <span class="big4">
            <?=_("��˵�����˴���д���ڻ������°��ʱ����ֶΣ�")?>
            </span>
          </td>
        </tr>
        
        <tr id="sqlserver_id1">
          <td nowrap class="TableData"><?=_("���ڻ����ݿ�IP��ַ��")?></td>
          <td class="TableData">
             <input type="text" name="DATABASE_IP_SQL" id="DATABASE_IP_SQL" size="20" maxlength="20" value="<?=$DATABASE_IP?>">
             <span class="big4"><?=_("��ע�⣺sql server ���ݿ�����ڱ���������Ҫ��ȨOA�������������Ȩ���ӣ�")?></span>
          </td>
        </tr>
        <tr id="sqlserver_id2">
          <td nowrap class="TableData"><?=_("���ڻ����ݿ�˿ڣ�")?></td>
          <td class="TableData">
             <input type="text" name="DATABASE_PORT_SQL" id="DATABASE_PORT_SQL" class="" size="6" value="<?=$DATABASE_PORT?>">
             <span class="big4">
            <?=_("��˵�������ڻ����ݿ�˿ڣ�һ��Ĭ��Ϊ����1433����")?>
            </span>
          </td>
        </tr>
        <tr id="sqlserver_id3">
          <td nowrap class="TableData"><?=_("���ݿ����ƣ�")?></td>
          <td class="TableData">
            <input type="text" id="DATABASE_NAME_SQL" name="DATABASE_NAME_SQL" size="20" maxlength="100" class="" value="<?=$DATABASE_NAME?>">
            <span class="big4">
            <?=_("��˵�������ڻ����ݿ����ƣ�һ��Ĭ��Ϊ����zkeco_db����")?>
            </span>
          </td>
        </tr>
        <tr id="sqlserver_id4">
          <td nowrap class="TableData"><?=_("���ݿ��û�����")?></td>
          <td class="TableData">
            <input type="text" name="DATABASE_USER_SQL" id="DATABASE_USER_SQL" class="" value="<?=$DATABASE_USER?>" size="20" maxlength="100" maxlength="20">
            <span class="big4">
            <?=_("��˵�������ڻ����ݿ��û�����һ��Ĭ��Ϊ����sa����")?>
            </span>
          </td>
        </tr>
        <tr id="sqlserver_id5">
          <td nowrap class="TableData"><?=_("���ݿ����룺")?></td>
          <td class="TableData">
            <input type="password" name="DATABASE_PASS_SQL" id="DATABASE_PASS_SQL" size="21" maxlength="100" class="" value="<?=$DATABASE_PASS?>">&nbsp;
            <input type="button" class="btn  btn-primary" value="<?=_('��������')?>" onClick="test_database()">
          </td>
        </tr>
        <tr id="sqlserver_id6">
          <td nowrap class="TableData"><span class="center"><?=_("����˵����")?></span></td>
          <td class="TableData">
          <span class="big4">
            <?=_("1��������Ҫ��д��ȫ�����ݱ��뱣֤���󣬷��򽫵��¿��������޷�ͬ����OA���Լ�ϵͳ����")?><br>
            <?=_("2����OA��ϵͳ����->��ʱ�������->����ִ�������У�����ͬ�����ڻ����ݡ�")?><br>
            <?=_("3��Ҫ��OA��������Դ->������Դ����->���������е��Ű����͡��Ǽ�ʱ��Σ��뿼�ڻ��е���Ӧ�˵��Űࡢʱ��һ�¡�")?><br>
            <?=_("4���������ڻ���OA�ļ��ɣ���Ҫ���ƿ�����")?><br>
           </span><br>
          </td>
        </tr>
        <tr id="sqlserver_id7">
          <td nowrap class="TableData" valign="top"><?=_("���°�����ݴ洢�������")?></td>
          <td class="TableData">
            <input type="text"  id="DUTY_TABLE_SQL" name="DUTY_TABLE_SQL" size="20" maxlength="100" class="" value="<?=$DUTY_TABLE?>">
            <span class="big4">
            <?=_("��˵�����˴���д����ڿ��ڻ������°��ʱ�������Ϣ�ı���һ��Ĭ��Ϊ����CHECKINOUT����")?>
            </span>
          </td>
        </tr>
        <tr id="sqlserver_id8">
          <td nowrap class="TableData" valign="top"><?=_("���°�����ݴ洢���û��ֶ�����")?></td>
          <td class="TableData">
            <input type="text" id="DUTY_USER_SQL" name="DUTY_USER_SQL" size="20" maxlength="100" class="" value="<?=$DUTY_USER?>">
            <span class="big4">
            <?=_("��˵�����˴���д���ڻ����û�ID��ϵͳ��ͨ����ID�ҵ����ڻ��е��û����������������ͬ����")?>
            </span>
          </td>
        </tr>    
        <tr id="sqlserver_id9">
          <td nowrap class="TableData"><?=_("��ʱ���ֶ�����")?></td>
          <td class="TableData">
            <input type="text"  id="DUTY_TIME_SQL" name="DUTY_TIME_SQL" size="20" maxlength="100" class="" value="<?=$DUTY_TIME?>">
            <span class="big4">
            <?=_("��˵�����˴���д���ڻ������°��ʱ����ֶΣ�")?>
            </span>
          </td>
        </tr>
        
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap style="text-align: center;">
        <input type="submit" value="<?=_("����")?>" class="btn  btn-primary">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="btn" onClick="location='../#machineSetting'">
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
                alert("<?=_('���ӳɹ���')?>");
            }
            else if(date=="-flase")
            {
                alert("<?=_('����ʧ�ܣ���ע���������ݿ������Ƿ���ȷ��')?>");
            }
            else
            {
                alert("<?=_('����ʧ�ܣ�����ȷ��д���ݿ�IP��ַ���˿ںţ��û��������룡')?>");
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
                alert("<?=_('���ӳɹ���')?>");
            }
            else
            {
                alert("<?=_('����ʧ�ܣ�����ȷ��д���ݿ�IP��ַ���˿ںţ��û��������룡')?>");
            }
        });
    }
}
</script>