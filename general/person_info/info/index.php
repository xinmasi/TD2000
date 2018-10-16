<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/lunar.class.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
    $QUERY_MASTER=true;
else
    $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("编辑个人资料");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/person_info.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/person_info/index.css" />
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script Language="JavaScript">
function CheckDate(op)
{
    var mon=document.form1.BIR_MON.value;
    var year=document.form1.BIR_YEAR.value;
    if("0000"==year || "00"==mon)
    {
        document.form1.BIR_DAY.remove(31);
        document.form1.BIR_DAY.remove(30);
        document.form1.BIR_DAY.remove(29);
        return false;
    }
    
    if(mon=="04" || mon=="06" || mon=="09" || mon=="11")
    {
        if(document.form1.BIR_DAY.options.length>31)
            document.form1.BIR_DAY.remove(31);
        else if(document.form1.BIR_DAY.options.length<31)
        {
            if(document.form1.BIR_DAY.options.length==29)
            {
                var my_option = document.createElement("OPTION");
                my_option.text="29";
                my_option.value="29";
                document.form1.BIR_DAY.add(my_option);
            }
            
            var my_option = document.createElement("OPTION");
            my_option.text="30";
            my_option.value="30";
            document.form1.BIR_DAY.add(my_option);
        }
    }
    
    else if(mon=="02")
    {
        document.form1.BIR_DAY.remove(31);
        document.form1.BIR_DAY.remove(30);
        
        if(document.form1.BIR_DAY.options.length>29)
        if (!(year%400==0 || (year%4==0 && year%100!=0)))
        document.form1.BIR_DAY.remove(29);
        
        if(document.form1.BIR_DAY.options.length<30)
        if ((year%400==0 || (year%4==0 && year%100!=0)))
        {
            var my_option = document.createElement("OPTION");
            my_option.text="29";
            my_option.value="29";
            document.form1.BIR_DAY.add(my_option);
        }
    }
    else
    {
        if(document.form1.BIR_DAY.options.length<32)
        {
            if(document.form1.BIR_DAY.options.length==29)
            {
                var my_option = document.createElement("OPTION");
                my_option.text="29";
                my_option.value="29";
                document.form1.BIR_DAY.add(my_option);
            }
            
            if(document.form1.BIR_DAY.options.length==30)
            {
                var my_option = document.createElement("OPTION");
                my_option.text="30";
                my_option.value="30";
                document.form1.BIR_DAY.add(my_option);
            }
            
            var my_option = document.createElement("OPTION");
            my_option.text="31";
            my_option.value="31";
            document.form1.BIR_DAY.add(my_option);
        }
    }
    
    if(op==1)
        document.form1.USER_NAME.focus();
}
//获取生肖、星座
function get_animal_sign(){
    var IS_LUNAR = document.getElementById("IS_LUNAR").checked?1:0;
    var BIR_YEAR = document.getElementById("BIR_YEAR_ID").value;
    var BIR_MON = document.getElementById("BIR_MON_ID").value;
    var BIR_DAY = document.getElementById("BIR_DAY_ID").value;
    var STAFF_BIRTH = ""+BIR_YEAR+"-"+BIR_MON+"-"+BIR_DAY+"";
    if("0000"==BIR_YEAR || "00"==BIR_MON || "00"==BIR_DAY)
    {
        $("#animal_id").val("");
        $("#sign_id").val("");
        return false;
    }
    if(STAFF_BIRTH!="" && STAFF_BIRTH!="0000-00-00"){
        jQuery.post("get_lunar.inc.php",{STAFF_BIRTH:STAFF_BIRTH,IS_LUNAR:IS_LUNAR},function(date){
            //console.log(date);
            if(date.length==5){
                document.getElementById("animal_id").value = date.substring(0,1);
                document.getElementById("sign_id").value = date.substring(2,6);
            }
        });
    }
}

$(document).ready(function()
{
    $("#form1").submit(function()
    {
        if(!(("0000"!=$("#BIR_YEAR_ID").val() && "00"!=$("#BIR_MON_ID").val() && "00"!=$("#BIR_DAY_ID").val()) || ("0000"==$("#BIR_YEAR_ID").val() && "00"==$("#BIR_MON_ID").val() && "00"==$("#BIR_DAY_ID").val())))
        {
            alert("生日格式不正确，请填写全或全部为空");
            return false;
        }
    });
});
</script>
<style>
input[type="text"] {
  padding: 4px 6px;
  margin: 0;
}
</style>

<body class="bodycolor" onLoad="CheckDate(1);">
<?

$PARA_ARRAY = get_sys_para("OPEN_DEVICE_NUMBER", false);
$OPEN_DEVICE_NUMBER = $PARA_ARRAY['OPEN_DEVICE_NUMBER'];

//============================ 个人资料 =======================================
$query = "SELECT * from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);

if($ROW=mysql_fetch_array($cursor))
{
    $USER_NAME=$ROW["USER_NAME"];
    $IS_LUNAR=$ROW["IS_LUNAR"];
    
    $SEX=$ROW["SEX"];
    $BIRTHDAY=$ROW["BIRTHDAY"];
    $STR=strtok($BIRTHDAY,"-");
    $BIR_YEAR=$STR;
    $STR=strtok("-");
    $BIR_MON=$STR;
    $STR=strtok(" ");
    $BIR_DAY=$STR;
    
    $TEL_NO_DEPT=$ROW["TEL_NO_DEPT"];
    $FAX_NO_DEPT=$ROW["FAX_NO_DEPT"];

    $ADD_HOME=$ROW["ADD_HOME"];
    $POST_NO_HOME=$ROW["POST_NO_HOME"];
    $TEL_NO_HOME=$ROW["TEL_NO_HOME"];

    $MOBIL_NO=$ROW["MOBIL_NO"];
    $BP_NO=$ROW["BP_NO"];
    $EMAIL=$ROW["EMAIL"];
    $OICQ_NO=$ROW["OICQ_NO"];
    $ICQ_NO=$ROW["ICQ_NO"];
    $MSN=$ROW["MSN"];
    $MOBIL_NO_HIDDEN=$ROW["MOBIL_NO_HIDDEN"];
    
    if($BIRTHDAY!="0000-00-00")
    {
        //从lunar.php中获取生肖
        $ANIMAL = get_animal($BIRTHDAY,$IS_LUNAR);
        //从lunar.php中获取星座
        $SIGN = get_zodiac_sign($BIRTHDAY,$IS_LUNAR);
    }
}
$query1 = "SELECT BLOOD_TYPE from HR_STAFF_INFO where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW1=mysql_fetch_array($cursor1))
{
    $BLOOD_TYPE=$ROW1["BLOOD_TYPE"];
}
?>
<form action="update.php"  method="post" id="form1" name="form1">
<table class="table table-bordered">
    <colgroup>
        <col width="100">
        <col width="400">
    </colgroup>
    <thead>
        <tr>
            <td colspan="2"><?=_("基本信息")?></td>
        </tr>
    </thead>
    <tbody>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe630;</i><?=_("姓名：")?></td>
            <td>
                <input type="text" name="USER_NAME" size="10" maxlength="10" class="BigStatic" value="<?=$USER_NAME?>" readonly>
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe62f;</i><?=_("性别：")?></td>
            <td>
                <select name="SEX" class="input-medium" style="margin:0">
                  <option value="0" <? if($SEX=="0") echo "selected";?>><?=_("男")?></option>
                  <option value="1" <? if($SEX=="1") echo "selected";?>><?=_("女")?></option>
                </select>
            </td>
        </tr>
        <!--<script>console.log('y'+<?=$BIR_YEAR?>+'m'+<?=$BIR_MON?>+'d'+<?=$BIR_DAY?>+'<? if(isset($BIR_YEAR)) echo 'o1';  if(isset($BIR_MON)) echo 'o2'; if(isset($BIR_DAY)) echo 'o3'; echo gettype($BIR_YEAR); echo gettype($BIR_MON); echo gettype($BIR_DAY); ?>');</script>-->
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe633;</i><?=_("生日：")?></td>
            <td>
                <select name="BIR_YEAR" id="BIR_YEAR_ID" class="input-medium" onChange="CheckDate(),get_animal_sign();" style="width:70px;margin:0">
                    <option value="0000" <? if('0000' == $BIR_YEAR) echo "selected";?>></option>
                <?
				for($I=1900;$I<=2010;$I++)
				{
				?>
                    <option value="<?=$I?>" <? if($I==$BIR_YEAR) echo "selected";?>><?=$I?></option>	
                <?	
				}
                ?>
                </select><span><?=_("年")?></span>
                <select name="BIR_MON" id="BIR_MON_ID" class="input-medium" onChange="CheckDate(),get_animal_sign();" style="width:70px;">
                    <option value="00" <? if('00' == $BIR_MON) echo "selected";?>></option>
                <?
				for($I=1;$I<=12;$I++)
				{
					if($I<10)
					{
						$I="0".$I;
					}
				?>
                    <option value="<?=$I?>" <? if($I==$BIR_MON) echo "selected";?>><?=$I?></option>
				<?
				}
                ?>
                </select><span><?=_("月")?></span>
                <select name="BIR_DAY" id="BIR_DAY_ID" class="input-medium" onChange="get_animal_sign()"; style="width:70px;">
                    <option value="00" <? if('00' == $BIR_DAY) echo "selected";?>></option>
                <?
				for($I=1;$I<=31;$I++)
				{
					if($I<10)
					{
						$I="0".$I;
					}
				?>
                    <option value="<?=$I?>" <? if($I==$BIR_DAY) echo "selected";?>><?=$I?></option>
				<?
				}
                ?>
                </select><span><?=_("日")?></span>
                <label for="IS_LUNAR" style=" margin-top: 5px; ">
                <input type="checkbox" onClick="get_animal_sign()" name="IS_LUNAR" id="IS_LUNAR"  <? if($IS_LUNAR==1) echo "checked";?>>                
                <?=_("是农历生日")?></label>
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe623;</i><?=_("生肖：")?></td>
            <td>
                <input type="text" value="<?=$ANIMAL?>" id="animal_id" class="BigStatic" readonly />
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe62e;</i><?=_("星座：")?></td>
            <td>
                <input type="text" value="<?=$SIGN?>" id="sign_id" class="BigStatic" readonly />
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe646;</i><?=_("血型：")?></td>
            <td>
                <select name="BLOOD_TYPE" class="input-medium" style="margin:0">
                  <option value=""  <? if($BLOOD_TYPE=="")   echo "selected";?>></option>
                  <option value="A" <? if($BLOOD_TYPE=="A")  echo "selected";?>><?=_("A")?></option>
                  <option value="B" <? if($BLOOD_TYPE=="B")  echo "selected";?>><?=_("B")?></option>
                  <option value="O" <? if($BLOOD_TYPE=="O")  echo "selected";?>><?=_("O")?></option>
                  <option value="AB" <? if($BLOOD_TYPE=="AB")echo "selected";?>><?=_("AB")?></option>
                </select>
            </td>
        </tr>
    </tbody>
</table>
<table class="table table-bordered">
    <colgroup>
        <col width="100">
        <col width="400">
    </colgroup>
    <thead>
        <tr>
            <td colspan="2"><?=_("联系方式")?></td>
        </tr>
    </thead>
    <tbody>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe610;</i><?=_("工作电话：")?></td>
            <td>
                <input type="text" name="TEL_NO_DEPT" size="25" maxlength="25" class="" value="<?=$TEL_NO_DEPT?>">
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe60f;</i><?=_("工作传真：")?></td>
            <td>
                <input type="text" name="FAX_NO_DEPT" size="25" maxlength="25" class="" value="<?=$FAX_NO_DEPT?>">
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe625;</i><?=_("手机：")?></td>
            <td>
                <input type="text" name="MOBIL_NO" size="23" maxlength="23" class="" value="<?=$MOBIL_NO?>">
                <label for="MOBIL_NO_HIDDEN">
                <input type="checkbox" name="MOBIL_NO_HIDDEN" id="MOBIL_NO_HIDDEN" <?if($MOBIL_NO_HIDDEN=="1") echo "checked";?>>                    
                    <?=_("手机号码不公开（仍可接收短信）")?></label>
                <span><?=_("填写后可接收OA系统发送的手机短信")?></span>
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe610;</i><?=_("工作电话2：")?></td>
            <td>
                <input type="text" name="BP_NO" size="25" maxlength="25" class="" value="<?=$BP_NO?>">
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe607;</i><?=_("电子邮件：")?></td>
            <td>
                <input type="text" name="EMAIL" size="25" maxlength="50" class="" value="<?=$EMAIL?>">
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe63a;</i><?=_("QQ号码：")?></td>
            <td>
                <input type="text" name="OICQ_NO" size="25" maxlength="25" class="" value="<?=$OICQ_NO?>">
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe639;</i><?=_("MSN：")?></td>
            <td>
                <input type="text" name="MSN" size="25" maxlength="50" class="" value="<?=$MSN?>">
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe600;</i><?=_("ICQ号码：")?></td>
            <td>
                <input type="text" name="ICQ_NO" size="25" maxlength="25" class="" value="<?=$ICQ_NO?>">
            </td>
        </tr>
    </tbody>
</table>
<table class="table table-bordered">
    <colgroup>
        <col width="100">
        <col width="400">
    </colgroup>
    <thead>
        <tr>
            <td colspan="2"><?=_("家庭信息")?></td>
        </tr>
    </thead>
    <tbody>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe614;</i><?=_("家庭住址：")?></td>
            <td>
                <input type="text" name="ADD_HOME" size="40" maxlength="100" class="" value="<?=$ADD_HOME?>">
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe63c;</i><?=_("家庭邮编：")?></td>
            <td>
                <input type="text" name="POST_NO_HOME" size="25" maxlength="25" class="" value="<?=$POST_NO_HOME?>">
            </td>
        </tr>
        <tr class="TableData">
            <td class="td-head"><i class="iconfont">&#xe613;</i><?=_("家庭电话：")?></td>
            <td>
                <input type="text" name="TEL_NO_HOME" size="25" maxlength="25" class="" value="<?=$TEL_NO_HOME?>">
            </td>
        </tr>
    </tbody>
</table>

  <div style="width:800px;text-align:center;">
    <input type="submit" value="<?=_("保存修改")?>" class="btn btn-primary">
  </div>
</form>

</body>
</html>