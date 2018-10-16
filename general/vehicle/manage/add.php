<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("添加车辆信息");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
    
<?
foreach($_POST as $key => $value){
    if(stripos($key,'V_DRIVER') === 0)
    {
        $V_DRIVER .= $value.','; 
    }
    if(stripos($key,'V_PHONE_NO') === 0)
    {
        $V_PHONE_NO .= $value.',';
    }
    if(stripos($key,'newTitles') === 0)
    {
        $V_TITLES .= $value.',';
    }
    if(stripos($key,'newFields') === 0)
    {
        $V_FIELDS .= $value.',';
    }
    if(stripos($key,'newNumbers') === 0)
    {
        $V_NUMBERS .= $value.',';
    }
}
//----------- 合法性校验 ---------
if($V_DATE!="")
{
    $TIME_OK=is_date($V_DATE);
    
    if(!$TIME_OK)
    {
        Message(_("错误"),_("购买日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($V_MOT!="")
{
    $TIME_OK=is_date($V_MOT);
    
    if(!$TIME_OK)
    {
        Message(_("错误"),_("年检日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($V_MOT_SMS!="")
{
    $TIME_OK=is_date($V_MOT_SMS);
    
    if(!$TIME_OK)
    {
        Message(_("错误"),_("年检到期提醒时间格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($V_INSURE!="")
{
    $TIME_OK=is_date($V_INSURE);
    
    if(!$TIME_OK)
    {
        Message(_("错误"),_("交强险日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($V_INSURE_SMS!="")
{
    $TIME_OK=is_date($V_INSURE_SMS);
    
    if(!$TIME_OK)
    {
        Message(_("错误"),_("交强险到期提醒时间格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($V_BINSURE!="")
{
    $TIME_OK=is_date($V_BINSURE);
    
    if(!$TIME_OK)
    {
        Message(_("错误"),_("商业险日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($V_BINSURE_SMS!="")
{
    $TIME_OK=is_date($V_BINSURE_SMS);
    
    if(!$TIME_OK)
    {
        Message(_("错误"),_("商业险到期提醒时间格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

$V_MOT_SMS_TYPE = 0;
$V_INSURE_SMS_TYPE = 0;
$V_BINSURE_SMS_TYPE = 0;
if($V_MOT_SMS1=="on" && $V_MOT_SMS2=="on")
{
    $V_MOT_SMS_TYPE = '3';
}
else if($V_MOT_SMS1=="on")
{
    $V_MOT_SMS_TYPE = '1';
}
else if($V_MOT_SMS2=="on")
{
    $V_MOT_SMS_TYPE = '2';
}

if($SMS_REMIND=="on" && $SMS2_REMIND=="on")
{
    $V_INSURE_SMS_TYPE = '3';
}
else if($SMS_REMIND=="on")
{
    $V_INSURE_SMS_TYPE = '1';
}
else if($SMS2_REMIND=="on")
{
    $V_INSURE_SMS_TYPE = '2';
}

if($V_BINSURE_SMS1=="on" && $V_BINSURE_SMS2=="on")
{
    $V_BINSURE_SMS_TYPE = '3';
}
else if($V_BINSURE_SMS1=="on")
{
    $V_BINSURE_SMS_TYPE = '1';
}
else if($V_BINSURE_SMS2=="on")
{
    $V_BINSURE_SMS_TYPE = '2';
}
//--------- 上传附件 ----------
if(count($_FILES)>=1)
{
   if($ATTACHMENT_ID_OLD!=""&&$ATTACHMENT_NAME_OLD!="")
      delete_attach($ATTACHMENT_ID_OLD,$ATTACHMENT_NAME_OLD);
   $ATTACHMENTS=upload();
   $ATTACHMENT_ID=trim($ATTACHMENTS["ID"],",");
   $ATTACHMENT_NAME=trim($ATTACHMENTS["NAME"],"*");
}
else
{
   $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
   $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}

$V_MANAGE = $_SESSION["LOGIN_USER_ID"];
if($V_ID=="")
{
   $query="insert into VEHICLE (V_MODEL,V_NUM,V_DRIVER,V_PHONE_NO,V_TYPE,V_DATE,V_PRICE,V_ENGINE_NUM,V_STATUS,V_REMARK,ATTACHMENT_ID,ATTACHMENT_NAME,USEING_FLAG,DEPT_RANGE,USER_RANGE,V_MANAGE,V_MOT,V_INSURE,V_MOT_SMS,V_INSURE_SMS,V_MOT_SMS_TYPE,V_INSURE_SMS_TYPE,V_DISPLACEMENT,V_COLOR,V_SEATING,V_FRAME,V_CERTIFICATION,V_NATURE,V_DEPART,V_ONWER,V_CARUSER,V_TAX,V_PAY,V_BINSURE,V_BINSURE_SMS,V_RECORD,V_BACKRECORD,V_MILEAGE,V_DEPART_PHONE,V_ONWER_PHONE,V_CARUSER_PHONE,V_BINSURE_SMS_TYPE,V_TITLES,V_FIELDS,V_NUMBERS) values('$V_MODEL','$V_NUM','$V_DRIVER','$V_PHONE_NO','$V_TYPE','$V_DATE','$V_PRICE','$V_ENGINE_NUM','$V_STATUS','$V_REMARK','$ATTACHMENT_ID','$ATTACHMENT_NAME','0','$DEPT_RANGE','$USER_RANGE','$V_MANAGE','$V_MOT','$V_INSURE','$V_MOT_SMS','$V_INSURE_SMS','$V_MOT_SMS_TYPE','$V_INSURE_SMS_TYPE','$V_DISPLACEMENT','$V_COLOR','$V_SEATING','$V_FRAME','$V_CERTIFICATION','$V_NATURE','$V_DEPART','$V_ONWER','$V_CARUSER','$V_TAX','$V_PAY','$V_BINSURE','$V_BINSURE_SMS','$V_RECORD','$V_BACKRECORD','$V_MILEAGE','$V_DEPART_PHONE','$V_ONWER_PHONE','$V_CARUSER_PHONE','$V_BINSURE_SMS_TYPE','$V_TITLES','$V_FIELDS','$V_NUMBERS')";
}
else
{
   $query="update VEHICLE set V_MODEL='$V_MODEL',V_NUM='$V_NUM',V_DRIVER='$V_DRIVER',V_PHONE_NO='$V_PHONE_NO',V_TYPE='$V_TYPE',V_DATE='$V_DATE',V_PRICE='$V_PRICE',V_ENGINE_NUM='$V_ENGINE_NUM',V_STATUS='$V_STATUS',V_REMARK='$V_REMARK',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',USEING_FLAG='0',DEPT_RANGE='$DEPT_RANGE',USER_RANGE='$USER_RANGE',V_MANAGE='$V_MANAGE',V_MOT='$V_MOT',V_INSURE='$V_INSURE',V_MOT_SMS='$V_MOT_SMS',V_INSURE_SMS='$V_INSURE_SMS',V_MOT_SMS_TYPE='$V_MOT_SMS_TYPE',V_INSURE_SMS_TYPE='$V_INSURE_SMS_TYPE',V_DISPLACEMENT='$V_DISPLACEMENT',V_COLOR='$V_COLOR',V_SEATING='$V_SEATING',V_FRAME='$V_FRAME',V_CERTIFICATION='$V_CERTIFICATION',V_NATURE='$V_NATURE',V_DEPART='$V_DEPART',V_ONWER='$V_ONWER',V_CARUSER='$V_CARUSER',V_TAX='$V_TAX',V_PAY='$V_PAY',V_BINSURE='$V_BINSURE',V_BINSURE_SMS='$V_BINSURE_SMS',V_RECORD='$V_RECORD',V_BACKRECORD='$V_BACKRECORD',V_MILEAGE='$V_MILEAGE',V_DEPART_PHONE='$V_DEPART_PHONE',V_ONWER_PHONE='$V_ONWER_PHONE',V_CARUSER_PHONE='$V_CARUSER_PHONE',V_BINSURE_SMS_TYPE='$V_BINSURE_SMS_TYPE',V_TITLES='$V_TITLES',V_FIELDS='$V_FIELDS',V_NUMBERS='$V_NUMBERS' where V_ID='$V_ID'";
}
exequery(TD::conn(),$query);

Message(_("提示"),_("车辆信息保存成功！"));
Button_Back();
?>

</body>
</html>
