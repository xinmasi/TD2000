<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");
include_once("inc/utility_org.php");
?>
<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());

if(strtolower(substr($FILE_NAME,-3))!="xls")
{
    Message(_("����"),_("ֻ�ܵ���Excel�ļ�!"));
    Button_Back();
    exit;
}

if(MYOA_IS_UN == 1)
{
    $title=array("NAME"=>"STAFF_NAME","ID"=>"BYNAME","DEPT_NAME"=>"DEPT_NAME","USER_PRIV_NAME"=>"USER_PRIV_NAME","BEFORE_NAME"=>"BEFORE_NAME","ENGLISH_NAME"=>"STAFF_E_NAME","STAFF_NO"=>"STAFF_NO","WORK_NO"=>"WORK_NO","WORK_JOB"=>"WORK_JOB","SEX"=>"STAFF_SEX","CARD_NO"=>"STAFF_CARD_NO","BIRTH"=>"STAFF_BIRTH","AGE"=>"STAFF_AGE","NATIONALITY"=>"STAFF_NATIONALITY","MARITAL_STATUS"=>"STAFF_MARITAL_STATUS","POLITICAL_STATUS"=>"STAFF_POLITICAL_STATUS","WORK_STATUS"=>"WORK_STATUS","JOIN_PARTY_TIME"=>"JOIN_PARTY_TIME","PHONE"=>"STAFF_PHONE","MOBILE"=>"STAFF_MOBILE","MSN"=>"STAFF_MSN","QQ"=>"STAFF_QQ","EMAIL"=>"STAFF_EMAIL","ADDRESS"=>"HOME_ADDRESS","JOB_BEGINNING"=>"JOB_BEGINNING","OTHER_CONTACT"=>"OTHER_CONTACT","WORK_AGE"=>"WORK_AGE","HEALTH"=>"STAFF_HEALTH","DOMICILE_PLACE"=>"STAFF_DOMICILE_PLACE","DOMICILE_TYPE"=>"STAFF_TYPE","DATES_EMPLOYED"=>"DATES_EMPLOYED","BANK1"=>"BANK1","BANK_ACCOUNT1"=>"BANK_ACCOUNT1","BANK2"=>"BANK2","BANK_ACCOUNT2"=>"BANK_ACCOUNT2","HIGHEST_SCHOOL"=>"STAFF_HIGHEST_SCHOOL","HIGHEST_DEGREE"=>"STAFF_HIGHEST_DEGREE","GRADUATION_DATE"=>"GRADUATION_DATE","MAJOR"=>"STAFF_MAJOR","GRADUATION_SCHOOL"=>"GRADUATION_SCHOOL","COMPUTER_LEVEL"=>"COMPUTER_LEVEL","FOREIGN_LANGUAGE1"=>"FOREIGN_LANGUAGE1","FOREIGN_LANGUAGE2"=>"FOREIGN_LANGUAGE2","FOREIGN_LANGUAGE3"=>"FOREIGN_LANGUAGE3","FOREIGN_LEVEL1"=>"FOREIGN_LEVEL1","FOREIGN_LEVEL2"=>"FOREIGN_LEVEL2","FOREIGN_LEVEL3"=>"FOREIGN_LEVEL3","SKILLS"=>"STAFF_SKILLS","WORK_TYPE"=>"WORK_TYPE","ADMINISTRATION_LEVEL"=>"ADMINISTRATION_LEVEL","OCCUPATION"=>"STAFF_OCCUPATION","JOB_POSITION"=>"JOB_POSITION","PRESENT_POSITION"=>"PRESENT_POSITION","PRESENT_POSITION_LEVEL"=>"WORK_LEVEL","JOB_AGE"=>"JOB_AGE","BEGIN_SALSRY_TIME"=>"BEGIN_SALSRY_TIME","LEAVE_TYPE"=>"LEAVE_TYPE","RESUME"=>"RESUME","SURETY"=>"SURETY","CERTIFICATE"=>"CERTIFICATE","INSURE"=>"INSURE","BODY_EXAMIM"=>"BODY_EXAMIM","MEMO"=>"REMARK","NATIVE_PLACE(PROVINCE)"=>"STAFF_NATIVE_PLACE","NATIVE_PLACE(CITY)"=>"STAFF_NATIVE_PLACE2","BLOOD_TYPE"=>"BLOOD_TYPE");
    $fieldAttr = array("BIRTH" => "date","JOIN_PARTY_TIME" => "date","JOB_BEGINNING" => "date","DATES_EMPLOYED" => "date","GRADUATION_DATE" => "date","BEGIN_SALSRY_TIME" => "date");
}
else
{
    $title=array(_("����")=>"STAFF_NAME",_("�û���")=>"BYNAME",_("����")=>"DEPT_NAME",_("��ɫ")=>"USER_PRIV_NAME",_("������")=>"BEFORE_NAME",_("Ӣ����")=>"STAFF_E_NAME",_("���")=>"STAFF_NO",_("����")=>"WORK_NO",_("��λ")=>"WORK_JOB",_("�Ա�")=>"STAFF_SEX",_("���֤����")=>"STAFF_CARD_NO",_("��������")=>"STAFF_BIRTH",_("����")=>"STAFF_AGE",_("����")=>"STAFF_NATIONALITY",_("����״��")=>"STAFF_MARITAL_STATUS",_("������ò")=>"STAFF_POLITICAL_STATUS",_("��ְ״̬")=>"WORK_STATUS",_("�뵳ʱ��")=>"JOIN_PARTY_TIME",_("��ϵ�绰")=>"STAFF_PHONE",_("�ֻ�����")=>"STAFF_MOBILE","MSN"=>"STAFF_MSN","QQ"=>"STAFF_QQ",_("�����ʼ�")=>"STAFF_EMAIL",_("��ͥ��ַ")=>"HOME_ADDRESS",_("�μӹ���ʱ��")=>"JOB_BEGINNING",_("������ϵ��ʽ")=>"OTHER_CONTACT",_("�ܹ���")=>"WORK_AGE",_("����״��")=>"STAFF_HEALTH",_("�������ڵ�")=>"STAFF_DOMICILE_PLACE",_("�������")=>"STAFF_TYPE",_("��ְʱ��")=>"DATES_EMPLOYED",_("������1")=>"BANK1",_("�����˻�1")=>"BANK_ACCOUNT1",_("������2")=>"BANK2",_("�����˻�2")=>"BANK_ACCOUNT2",_("ѧ��")=>"STAFF_HIGHEST_SCHOOL",_("ѧλ")=>"STAFF_HIGHEST_DEGREE",_("��ҵʱ��")=>"GRADUATION_DATE",_("רҵ")=>"STAFF_MAJOR",_("��ҵԺУ")=>"GRADUATION_SCHOOL",_("�����ˮƽ")=>"COMPUTER_LEVEL",_("��������1")=>"FOREIGN_LANGUAGE1",_("��������2")=>"FOREIGN_LANGUAGE2",_("��������3")=>"FOREIGN_LANGUAGE3",_("����ˮƽ1")=>"FOREIGN_LEVEL1",_("����ˮƽ2")=>"FOREIGN_LEVEL2",_("����ˮƽ3")=>"FOREIGN_LEVEL3",_("�س�")=>"STAFF_SKILLS",_("����")=>"WORK_TYPE",_("��������")=>"ADMINISTRATION_LEVEL",_("Ա������")=>"STAFF_OCCUPATION",_("ְ��")=>"JOB_POSITION",_("ְ��")=>"PRESENT_POSITION",_("ְ�Ƽ���")=>"WORK_LEVEL",_("����λ����")=>"JOB_AGE",_("��нʱ��")=>"BEGIN_SALSRY_TIME",_("���ݼ�")=>"LEAVE_TYPE",_("����")=>"RESUME",_("������¼")=>"SURETY",_("ְ�����")=>"CERTIFICATE",_("�籣�������")=>"INSURE",_("����¼")=>"BODY_EXAMIM",_("��ע")=>"REMARK",_("���ᣨʡ�ݣ�")=>"STAFF_NATIVE_PLACE",_("���ᣨ���У�")=>"STAFF_NATIVE_PLACE2",_("Ѫ��")=>"BLOOD_TYPE");
    $fieldAttr = array(_("��нʱ��") => "date",_("��ҵʱ��") => "date",_("�뵳ʱ��") => "date",_("�μӹ���ʱ��") => "date",_("��ְʱ��") => "date",_("��������") => "date");
}
$ext_title = get_field_title("HR_STAFF_INFO");

$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];

require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE, array_merge($title, $ext_title), $fieldAttr);

$has_user_id_str = "";
$query = "SELECT UID,USER_ID FROM user";
$cursor = exequery(TD::conn(), $query);
while($row = mysql_fetch_array($cursor))
{
    $has_user_id_str .= $row['USER_ID'].",";
}

function get_user_id($has_user_id_str, $byname)
{
    $user_id = rand(1,100000);
    
    if(find_id($has_user_id_str, $user_id) || $byname==$user_id)
    {
        $user_id = get_user_id($has_user_id_str,$byname);
    }
    
    return $user_id;
}


$user_info_arr = array();
$query = "select UID,BYNAME,USER_ID from user where DEPT_ID!='0'";
$cursor = exequery(TD::conn(), $query);
while($row = mysql_fetch_array($cursor, MYSQL_ASSOC))
{
    $user_info_arr[$row['BYNAME']] = $row;
}

$ROW_COUNT = 0;
$SUCC_COUNT=0;
$MSG_ERROR = array();
$DATAS=array();
while($DATA = $objExcel->getNextRow())
{
    $DATAS[$ROW_COUNT]=$DATA;
    $EXT_DATA = get_field_row("HR_STAFF_INFO", $DATA, $ext_title);
    $STR_KEY="";
    $STR_VALUE="";
    $STR_UPDATE="";
    
    $success=1;
    //�������䲢�����ݿ�
    if($DATA['STAFF_BIRTH']!="0000-00-00" && $DATA['STAFF_BIRTH']!="")
    {
        $agearray = explode("-",$DATA['STAFF_BIRTH']);
        $cur = explode("-",$CUR_DATE);
        $year=$agearray[0];
        $STAFF_AGE=date("Y")-$year;
        if($cur[1] > $agearray[1] || $cur[1]==$agearray[1] && $cur[2]>=$agearray[2])
        {
            $STAFF_AGE++;
        }
    }
    else
    {
        $STAFF_AGE="";
    }
    
    if($STAFF_AGE!="")
    {
        $STAFF_AGE=$STAFF_AGE-1;
        $DATA['STAFF_AGE']=$STAFF_AGE;
    }
    
    reset($title);
    
    foreach($title as $key)
    {
       
        if(find_id($ID_STR, $key))
            continue;
        
        $value=ltrim($DATA[$key]);
        if(($key!="DEPT_NAME")&&($key!="STAFF_NAME")&&($key!="BYNAME")&&($key!="USER_PRIV_NAME"))
        {
            
            $STR_KEY.=$key.",";
            $STR_UPDATEKEY.=$key.",";
        }
        else
        {
            if($key=="DEPT_NAME")
            {
                $DEPT_NAME=$value;
            }
            if($key=="BYNAME")
            {
                if($value=="")  //�û���Ϊ�ղ�����
                {
                    $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("�û���Ϊ�գ�δ����")."</font>";
                    $ROW_COUNT++;
                    $success=0;
                    continue 2;
                }else
				{
					$BYNAME = td_trim($value);
				}
                /*else
                {
                    if(!$user_info_arr[$value]['USER_ID'])
                    {
                        $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("�û��������ڣ�δ����")."</font>";
                        $ROW_COUNT++;
                        $success=0;
                        continue 2;
                    }
                    else
                    {
                        $BYNAME = $value;         
                    }
                }*/
            }
            if($key=="STAFF_NAME")
            {
                if($value=="")  //����Ϊ�ղ�����
                {
                    $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("����Ϊ�գ�δ����")."</font>";
                    $ROW_COUNT++;  
                    $success=0;
                    continue 2;
                }else
				{
					$STAFF_NAME=td_trim($value);
				}
				/*else
                {
                    $sql = "SELECT 1 FROM user WHERE USER_NAME = '$value'";
                    $res = exequery(TD::conn(),$sql);
                    if(mysql_affected_rows()>0)
                    {
                        $STAFF_NAME=$value;
                    }
                    elseindex
                    {
                        $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("���������ڣ�δ����")."</font>";
                        $ROW_COUNT++;  
                        $success=0;
                        continue 2;
                    }
                }*/
            }
            
            if($key=="USER_PRIV_NAME")
            {
                $USER_PRIV_NAME=$value;
                if($value=="")  //��ɫΪ�ղ�����
                {
                    $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("��ɫΪ�գ�δ����")."</font>";
                    $ROW_COUNT++;  
                    $success=0;
                    continue 2;
                }
                else
                {
                    $query1="select * from USER_PRIV where PRIV_NAME='$USER_PRIV_NAME'";
                    $cursor1= exequery(TD::conn(),$query1);
                    if($ROW=mysql_fetch_array($cursor1))
                    {
                        $USER_PRIV=$ROW["USER_PRIV"];
                        $USER_PRIV_NO=$ROW["PRIV_NO"];
                    }
                    else
                    {
                        $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("��ɫ".$USER_PRIV_NAME."�����ڣ�δ����")."</font>";    
                        $ROW_COUNT++;
                        $success=0;
                        continue 2;
                    }
                }
            }
            
        }//end else

        if($key=="STAFF_BIRTH")
        {
            $STAFF_BIRTH=$value;
        }
        if($key=="STAFF_CARD_NO")
        {
            if($value!="")
            {
                if(preg_match("/^(\d{18,18}|\d{15,15}|\d{17,17}(x|X))$/",$value))
                {
                    $ts_user = $user_info_arr[$BYNAME]['USER_ID'];
                    $where = "";
                    if($ts_user!="")
                    {
                        $where = " and USER_ID !='$ts_user'";
                    }
                    $query3="select * from hr_staff_info where STAFF_CARD_NO='$value'".$where;
                    $cursor3=exequery(TD::conn(),$query3);
                    $count=mysql_num_rows($cursor3);
                    if($count > 0)
                    {
                        $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("���֤����ظ���δ����")."</font>";
                        $ROW_COUNT++;
                        $success=0;
                        continue 2;
                    }
                }else{
                    $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("���֤�����Ϲ�����ʹ����ȷ���֤��")."</font>";
                    $ROW_COUNT++;
                    $success=0;
                    continue 2;
                }
            }
        }

        if($key=="STAFF_SEX")
        {
            if($value==_("Ů"))
            {
                $STAFF_SEX="1";
                $value="1";
            }
            else
            {
                $STAFF_SEX="0";
                $value="0";
            }
        }
        
        switch ($value)
        {
            case _("δ��"):    {$value="0";break;}
            case _("�ѻ�"):    {$value="1";break;}
            case _("����"):    {$value="2";break;}
            case _("ɥż"):    {$value="3";break;}
            case _("��"):      {$value="0";break;}
            case _("Ů"):      {$value="1";break;}
        }
        
        if(($key!="STAFF_NATIVE_PLACE")&&($key!="STAFF_POLITICAL_STATUS")&&($key!="WORK_STATUS")&&($key!="STAFF_TYPE")&&($key!="STAFF_HIGHEST_SCHOOL")&&($key!="STAFF_HIGHEST_DEGREE")&&($key!="PRESENT_POSITION")&&($key!="STAFF_OCCUPATION")&&($key!="DEPT_NAME")&&($key!="STAFF_NAME")&&($key!="BYNAME")&&($key!="WORK_LEVEL")&&($key!="WORK_JOB")&&($key!="USER_PRIV_NAME"))
        {
            $STR_VALUE.="'$value',";
            if($value!='')
                $STR_UPDATE.="$key='$value',";
        }
        else
        {
            if(($key!="STAFF_NAME")&&($key!="BYNAME")&&($key!="DEPT_NAME")&&($key!="USER_PRIV_NAME"))
            {
                $query1="select CODE_NO from HR_CODE where PARENT_NO='$key' and CODE_NAME='$value'";
                if($key=="STAFF_NATIVE_PLACE")
                    $query1="select CODE_NO from HR_CODE where PARENT_NO='AREA' and CODE_NAME='$value'";
                if($key=="WORK_STATUS")
                    $query1="select CODE_NO from HR_CODE where PARENT_NO='WORK_STATUS' and CODE_NAME='$value'";
                if($key=="STAFF_POLITICAL_STATUS")
                    $query1="select CODE_NO from HR_CODE where PARENT_NO='STAFF_POLITICAL_STATUS' and CODE_NAME='$value'";
                if($key=="STAFF_TYPE")
                    $query1="select CODE_NO from HR_CODE where PARENT_NO='HR_STAFF_TYPE' and CODE_NAME='$value'";
                if($key=="STAFF_HIGHEST_SCHOOL")
                    $query1="select CODE_NO from HR_CODE where PARENT_NO='STAFF_HIGHEST_SCHOOL' and CODE_NAME='$value'";
                if($key=="STAFF_HIGHEST_DEGREE")
                    $query1="select CODE_NO from HR_CODE where PARENT_NO='EMPLOYEE_HIGHEST_DEGREE' and CODE_NAME='$value'";
                if($key=="PRESENT_POSITION")
                    $query1="select CODE_NO from HR_CODE where PARENT_NO='PRESENT_POSITION' and CODE_NAME='$value'";
                if($key=="STAFF_OCCUPATION")
                    $query1="select CODE_NO from HR_CODE where PARENT_NO='STAFF_OCCUPATION' and CODE_NAME='$value'";
                if($key=="WORK_LEVEL")
                    $query1="select CODE_NO from HR_CODE where PARENT_NO='WORK_LEVEL' and CODE_NAME='$value'";
                if($key=="WORK_JOB")
                    $query1="select CODE_NO from HR_CODE where PARENT_NO='POOL_POSITION' and CODE_NAME='$value'";
                $cursor1= exequery(TD::conn(),$query1);
                if($ROW=mysql_fetch_array($cursor1))
                {
                    $STR_VALUE.="'$ROW[0]',";
                    if($ROW[0]!='')
                        $STR_UPDATE.="$key='$ROW[0]',";
                }
                else
                {
                    $STR_VALUE.="''".",";
                }
            }
        }
    }//end foreach
    
    if(substr($STR_KEY,-1)==",")
    {
        $STR_KEY=substr($STR_KEY,0,-1);
        $STR_UPDATEKEY=substr($STR_UPDATEKEY,0,-1);
    }
    if(substr($STR_VALUE,-1)==",")
    {
        $STR_VALUE=substr($STR_VALUE,0,-1);
        $STR_UPDATE=substr($STR_UPDATE,0,-1);
    }
    
    $query1 = "SELECT UID,BYNAME,USER_ID,DEPT_ID from USER where USER_ID='$BYNAME' or BYNAME='$BYNAME'";
    $cursor1= exequery(TD::conn(),$query1);
    //����oa�û�
    if($ROW=mysql_fetch_array($cursor1))
    {
        $UID            = $ROW["UID"];
        $USER_ID        = $ROW["USER_ID"];
        $DEPT_ID_OLD    = $ROW["DEPT_ID"];
        
        $MSG_ERROR[$ROW_COUNT] = "<font color=red>"._("�����û��������Ѹ���")."</font>";
        
        $query1 = "SELECT * from HR_STAFF_INFO where USER_ID='$USER_ID'";
        $cursor= exequery(TD::conn(),$query1);
        if(mysql_fetch_array($cursor)===FALSE)  //����OA�û����޵���
        {
            $query="insert into HR_STAFF_INFO (USER_ID,STAFF_NAME,DEPT_ID,ADD_TIME,LAST_UPDATE_TIME,".$STR_KEY.") values ('$USER_ID','$STAFF_NAME','$DEPT_ID_OLD','$CUR_TIME','$CUR_TIME',".$STR_VALUE.")";
            exequery(TD::conn(),$query);
            $MSG_ERROR[$ROW_COUNT]=_("����ɹ���");
            $SUCC_COUNT++;
        }
        else
        {
            if($STR_UPDATE!="")
                $query1="update HR_STAFF_INFO SET ".$STR_UPDATE.",LAST_UPDATE_TIME='$CUR_TIME' where USER_ID='".$USER_ID."'";
            else
                $query1="update HR_STAFF_INFO SET LAST_UPDATE_TIME='$CUR_TIME' where USER_ID='".$USER_ID."'";
            
            $cursor1=exequery(TD::conn(),$query1);
            $SUCC_COUNT++;
            $query1="update USER SET SEX='".$STAFF_SEX."',USER_PRIV_NAME='$USER_PRIV_NAME',USER_PRIV='$USER_PRIV' where USER_ID='".$USER_ID."'";
            $cursor1=exequery(TD::conn(),$query1);
        }
    }
    else   //����oa�û�
    {
        if($DEPT_NAME=="" || $DEPT_NAME==_("��ְ��Ա/�ⲿ��Ա"))
            $DEPT_ID=0;
        else if(DeptLongNameIsMultiple($DEPT_NAME))
        {
            $MSG_ERROR[$ROW_COUNT]="<font color=red>".sprintf(_("���� %s ����ʧ��,�������� %s ��ϵͳ�д��ڶ��"),$STAFF_NAME,$DEPT_NAME)."</font>";
            $ROW_COUNT++;
            $success=0;
            continue;
        }
        else
        {
            $DEPT_ID = GetDeptIdByLongName($DEPT_NAME);
            if($DEPT_ID == "")
            {
                $MSG_ERROR[$ROW_COUNT]="<font color=red>".sprintf(_("���� %s ����ʧ��,�������� %s ��ϵͳ�в�����"),$STAFF_NAME,$DEPT_NAME)."</font>";
                $ROW_COUNT++;
                $success=0;
                continue;
            }
        }
        
        $PASSWORD = crypt("");
        $USER_ID = get_user_id($has_user_id_str, $BYNEME);//��������û���
        
        if($STAFF_NAME!="" && $BYNAME!="")
        {
            $NOT_LOGIN=1;
			$USER_NAME_INDEX=getChnprefix($STAFF_NAME);
            //---------------------------------�޸�---------------------------
            $query1="insert into USER (USER_ID,USER_NAME,SEX,PASSWORD,USER_PRIV,POST_PRIV,POST_DEPT,DEPT_ID,AVATAR,CALL_SOUND,SMS_ON,USER_PRIV_OTHER,USER_NO,NOT_LOGIN,NOT_VIEW_USER,NOT_VIEW_TABLE,BYNAME,BIRTHDAY,THEME,MOBIL_NO,MOBIL_NO_HIDDEN,USER_NAME_INDEX,USER_PRIV_NAME,USER_PRIV_NO)  values ('$USER_ID','$STAFF_NAME','$STAFF_SEX','$PASSWORD','$USER_PRIV','0','','$DEPT_ID','1','1','1','','$USER_NO','$NOT_LOGIN','1','1','$BYNAME','$STAFF_BIRTH','1','','','$USER_NAME_INDEX','$USER_PRIV_NAME','$USER_PRIV_NO')";
            exequery(TD::conn(),$query1);
            $UID = mysql_insert_id();
            $USER_ID = $UID;
            $query = "update user set USER_ID='$UID' where UID='$UID'";
            exequery(TD::conn(), $query);
            $query1="insert into USER_EXT(UID,USER_ID,TABLE_ICON,EMAIL_RECENT_LINKMAN,SCORE,TDER_FLAG,DUTY_TYPE,NICK_NAME,BBS_SIGNATURE,BBS_COUNTER,EMAIL_CAPACITY,FOLDER_CAPACITY,WEBMAIL_CAPACITY,WEBMAIL_NUM,CONCERN_USER,USE_POP3,IS_USE_POP3,POP3_PASS_STYLE,POP3_PASS) values('$UID','$USER_ID','','','','','1','','','','0','0','','','','','','','')";
            exequery(TD::conn(),$query1);
            //--------------------------------����-----------------------------
            add_log(6,$USER_ID,$_SESSION["LOGIN_USER_ID"]);
            
            $query="insert into HR_STAFF_INFO (USER_ID,STAFF_NAME,CREATE_USER_ID,CREATE_DEPT_ID,DEPT_ID,ADD_TIME,LAST_UPDATE_TIME,".$STR_KEY.") values ('$USER_ID','$STAFF_NAME','".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$DEPT_ID','$CUR_TIME','$CUR_TIME',".$STR_VALUE.")";
            exequery(TD::conn(),$query);
            $MSG_ERROR[$ROW_COUNT]=_("����ɹ���");
            $SUCC_COUNT++;
        }
    }
    
    //�����Զ����ֶ�
    if(count($EXT_DATA) > 0)
    {
        save_field_data("HR_STAFF_INFO",$USER_ID,$EXT_DATA);
    }
    if($STAFF_AGE!="")
    {
        $query10="update HR_STAFF_INFO set STAFF_AGE='$STAFF_AGE' where USER_ID='$USER_ID'";
        exequery(TD::conn(),$query10);
    }
    $ROW_COUNT++;
}

if(file_exists($EXCEL_FILE))
    @unlink($EXCEL_FILE);
?>
<br>
<table class="TableList" width="100%" align="center">
    <thead class="TableHeader">
        <td nowrap align="center"><?=_("����")?></td>
        <td nowrap align="center"><?=_("�Ա�")?></td>
        <td nowrap align="center"><?=_("��������")?></td>
        <td nowrap align="center"><?=_("����")?></td>
        <td nowrap align="center"><?=_("����")?></td>
        <td nowrap align="center"><?=_("������ò")?></td>
        <td nowrap align="center"><?=_("���֤����")?></td>
        <td nowrap align="center"><?=_("��ע")?></td>
        <td nowrap align="center"><?=_("״̬")?></td>
    </thead>
<?
for($I=0;$I< count($DATAS);$I++)
{
?>
    <tr align="center" style="<?=$TR_STYLE?>" class="TableData">
        <td><?=$DATAS[$I]["STAFF_NAME"]?>(<?=$DATAS[$I]["BYNAME"]?>)</td>
        <td><?=$DATAS[$I]["STAFF_SEX"]?></td>
        <td><?=$DATAS[$I]["STAFF_BIRTH"]?></td>
        <td><?=$DATAS[$I]["STAFF_NATIONALITY"]?></td>
        <td><?=$DATAS[$I]["STAFF_NATIVE_PLACE"].$DATAS[$I]["STAFF_NATIVE_PLACE2"]?></td>
        <td><?=$DATAS[$I]["STAFF_POLITICAL_STATUS"]?></td>
        <td><?=$DATAS[$I]["STAFF_CARD_NO"]?></td>
        <td><?=$DATAS[$I]["REMARK"]?></td>
        <td align="left"><?=$MSG_ERROR[$I]?></td>
    </tr>
<?
}
?>
</table>
<?
Message("",sprintf(_("��%s�����ݵ���ɹ�!"), $SUCC_COUNT));
Button_Back();
?>