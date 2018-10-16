<?
include_once("inc/utility_sms2.php");
include_once("inc/utility_ubb.php");
include_once("inc/itask/itask.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");

$para_array = get_sys_para("USE_JPUSH");
$USE_JPUSH = $para_array["USE_JPUSH"];
if($USE_JPUSH == '1')
{
    include_once("jpush/autoload.php");
}
use JPush\Model as M;
use JPush\JPushClient;
use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;

/*
 * @param $params["send_time"] 发送时间
 * @param $params["from_id"] 发送人
 * @param $params["to_id"] 接收人
 * @param $params["sms_type"] 模块类型
 * @param $params["content"] 发送内容
 * @param $params["module_action"] 操作方式
 */
function send_dd_sms($params, $enqueue = true)
{
    if(PUSH_USE_RESQUE==1 && $enqueue==true)
    {
        $params = array(
            'qy_type' => 'dd',
            'type' => 'send_dd_sms',
            'params' => $params
        );
        
        $ret = sync_oa2qy($params);
        if($ret == 'ok')
        {
            return;
        }
    }
    
    $para_array = get_sys_para("DINGDING_CORPID,DINGDING_SECRET,DINGDING_TYPE");
    $dingding_corpid = $para_array["DINGDING_CORPID"];
    $dingding_secret = $para_array["DINGDING_SECRET"];
    $dingding_type = $para_array["DINGDING_TYPE"];

    if($dingding_corpid == "" || $dingding_secret == ""){
        return;
    }

    include_once("inc/dingding/class/dingding.funcs.php");
    include_once("inc/dingding/class/dingding.message.funcs.php");
    if($dingding_type == 1)
    {
        $DD = new DingDingMessage;
        $DD->sms($params);
    }
    else
    {
        return;
    }
}

function send_wx_sms($params, $enqueue = true)
{
    if(PUSH_USE_RESQUE==1 && $enqueue==true)
    {
        $params = array(
            'qy_type' => 'wx',
            'type' => 'send_wx_sms',
            'params' => $params
        );
        
        $ret = sync_oa2qy($params);
        if($ret == 'ok')
        {
            return;
        }
    }
    
    $para_array = get_sys_para("WEIXINQY_CORPID,WEIXINQY_SECRET,WEIXIN_TYPE");
    $weixinqy_corpid = $para_array["WEIXINQY_CORPID"];
    $weixinqy_secret = $para_array["WEIXINQY_SECRET"];
    $weixin_type = $para_array["WEIXIN_TYPE"];

    if($weixinqy_corpid == "" || $weixinqy_secret == ""){
        return;
    }

    include_once("inc/weixinqy/class/weixinqy.funcs.php");
    include_once("inc/weixinqy/class/weixinqy.message.funcs.php");
    if($weixin_type == 1)
    {
        $WX = new WeiXinQYMessage;
        $WX->sms($params);
    }
    else
    {
        return;
    }
}

function send_qywx_sms($params, $enqueue = true)
{
    if(PUSH_USE_RESQUE==1 && $enqueue==true)
    {
        $params = array(
            'qy_type' => 'qywx',
            'type' => 'send_qywx_sms',
            'params' => $params
        );
        
        $ret = sync_oa2qy($params);
        if($ret == 'ok')
        {
            return;
        }
    }
    
    $para_array = get_sys_para("QYWEIXIN_CORPID,QYWEIXIN_TYPE");
    $weixinqy_corpid = $para_array["QYWEIXIN_CORPID"];
    $qyweixin_type = $para_array["QYWEIXIN_TYPE"];
	$query = "SELECT * FROM qyweixin_app where app_name = 'department'";
	$cursor= exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
	{
		$QYWEIXIN_SECRET = $ROW["secret"];
	}
    if($weixinqy_corpid == "" || $QYWEIXIN_SECRET == ""){
        return;
    }

    include_once("inc/qyweixin/class/qyweixin.funcs.php");
    include_once("inc/qyweixin/class/qyweixin.message.funcs.php");
    if($qyweixin_type == 1)
    {
        switch ($params["sms_type"]) {
            case '13':
                $app_name = 'diary';
                break;
            case '14':
                $app_name = 'news';
                break;
            case '1':
                $app_name = 'notify';
                break;
            case '2':
                $app_name = 'email';
                break;
            case '5':
                $app_name = 'calendar';
                break;
            case '19':
                $app_name = 'wage';
                break;
            case '7':
            case '82':
            case '83':
                $app_name = 'work';
                break;
            case '16':
                $app_name = 'folder';
                break;
            case '11':
                $app_name = 'vote';
                break;
            case '8_1':
                $app_name = 'meeting';
                break;
            case 'a0':
                $app_name = 'apps';
                break;
            default:
                $app_name = '';
                break;
        }
        $WX = new QYWeiXinMessage($app_name);
        $WX->sms($params);
    }
    else
    {
        return;
    }
}

function message_push($action, $args = NULL, $enqueue = true)
{
    if(PUSH_USE_RESQUE==1 && $enqueue==true && $action != 'del')
    {
        $params = array(
            'm_type' => 'mobile_push',
            'op_type' => 'message_push',
            'action' => $action,
            'args' => $args
        );
        
        $ret = module2queue($params);
        if($ret['status'] == 1)
        {
            return;
        }
    }
    
    $SYS_PARA_ARRAY = get_sys_para("MOBILE_PUSH_OPTION,USE_JPUSH", FALSE);
    $MOBILE_PUSH_OPTION = $SYS_PARA_ARRAY["MOBILE_PUSH_OPTION"];
    $USE_JPUSH = $SYS_PARA_ARRAY["USE_JPUSH"];
    if($USE_JPUSH == '1' && $action != 'del')
    {
        message_jpush($action, $args);
        return;
    }

    if($MOBILE_PUSH_OPTION == "0")
        return;

    //仅提醒使用移动设备登陆过的有效用户
    $C_MOBILE_DEVICES = TD::get_cache('C_MOBILE_DEVICES');
    if($C_MOBILE_DEVICES === FALSE)
    {
        rebuildMobileCache();
        $C_MOBILE_DEVICES = TD::get_cache('C_MOBILE_DEVICES');
    }

    if($action == "add")
    {
        $value_str_push = "";

        $s_from_id = $args['from_id'];
        $i_sent_time = $args['send_time'];
        $s_content = $args['content'];
        $s_sms_type = $args['sms_type'];
        $s_to_id = $args['to_id'];
        $a_user_ids = $a_user_uids = array();

        $a_user_ids = array_filter(explode(",", $s_to_id));
        $array_count = sizeof($a_user_ids);
        
        if(!in_array($s_from_id, $a_user_ids))
            array_push($a_user_ids, $s_from_id);

        $a_user_uids = UserId2Uid($a_user_ids, TRUE);     
        $user_info = GetUserInfoByUID($a_user_uids, 'UID,USER_NAME', TRUE);
        $from_user_name = $user_info[$a_user_uids[td_trim($s_from_id)]]['USER_NAME'];

        for($I=0;$I<$array_count;$I++)
        {
            if($a_user_ids[$I]=="" || !$C_MOBILE_DEVICES[$a_user_uids[$a_user_ids[$I]]])
                continue;

            $value_str_push.="('".$from_user_name."','".$user_info[$a_user_uids[$a_user_ids[$I]]]['UID']."','".$s_content."','".$s_sms_type."','0','".$i_sent_time."'),";
        }

        $value_str_push = td_trim($value_str_push);
        
        if($value_str_push!="")
        {
            $query = "insert into message_push(mp_from,mp_to,mp_content,mp_module,mp_status,mp_delivery) values".$value_str_push;
            exequery(TD::conn(),$query);
        }
    }
    else if($action == "del")
    {
        //10分钟前的消息，直接删除，不再推送
        $atime = time() - 10 * 60;
        $query = "delete from message_push where mp_status = 1 or mp_delivery<='".$atime."'";
        exequery(TD::conn(),$query);
    }
}

function message_jpush($action, $args = NULL)
{
    $SYS_PARA_ARRAY = get_sys_para("MOBILE_PUSH_OPTION,PCONLINE_MOBILE_PUSH", FALSE);
    $MOBILE_PUSH_OPTION = $SYS_PARA_ARRAY["MOBILE_PUSH_OPTION"];
    $PCONLINE_MOBILE_PUSH = $SYS_PARA_ARRAY["PCONLINE_MOBILE_PUSH"];
    if($MOBILE_PUSH_OPTION == "0")
        return;

    //仅提醒使用移动设备登陆过的有效用户
    $C_MOBILE_DEVICES = TD::get_cache('C_MOBILE_DEVICES');
    if($C_MOBILE_DEVICES === FALSE)
    {
        rebuildMobileCache();
        $C_MOBILE_DEVICES = TD::get_cache('C_MOBILE_DEVICES');
    }

    if($action == "add")
    {
        $value_str_push = "";

        $s_from_id = $args['from_id'];
        $i_sent_time = $args['send_time'];
        $s_content = $args['content'];
        $s_sms_type = $args['sms_type'];
        $s_to_id = $args['to_id'];
        $s_work_id = $args['work_id'];
        $s_remind_url = $args['remind_url'];
        $s_to_uid = GetUidByUserID($s_to_id);
        $mp_to_uids = td_trim($s_to_uid);
        $a_user_ids = $a_unpush_uid = array();
        $a_user_ids = explode(",", $s_to_id);
        //$a_user_ids = array_filter(explode(",", $s_to_id));
        //如果开启在线不提醒，去掉PC、浏览器在线的UID
        if($PCONLINE_MOBILE_PUSH == "0")
        {
            $query = "select DISTINCT(UID) from user_online where UID in($mp_to_uids) and CLIENT!=5 and CLIENT!=6";
            $cursor= exequery(TD::conn(),$query);
            while($ROW = mysql_fetch_array($cursor))
            {
                $a_unpush_uid[] = td_trim(GetUserIDByUid($ROW['UID']));
            }

            //开启在线不提醒，则剔除在线的
            foreach ($a_user_ids as $k => $v) 
            {
                if($PCONLINE_MOBILE_PUSH == "0" && is_array($a_unpush_uid))
                {
                    if(in_array($v, $a_unpush_uid))
                        unset($a_user_ids[$k]);
                }
            }

            //$a_user_ids = array_filter($a_user_ids);
            //$uid_sent = implode(",",$a_uid_sent);
        }
        $a_user_ids = array_filter($a_user_ids);
        $array_count = sizeof($a_user_ids);
        
        if(!in_array($s_from_id, $a_user_ids))
            array_push($a_user_ids, $s_from_id);

        $a_user_uids = UserId2Uid($a_user_ids,TRUE);
        $user_info = GetUserInfoByUID($a_user_uids, 'UID,USER_NAME', TRUE);
        $from_user_name = $user_info[$a_user_uids[td_trim($s_from_id)]]['USER_NAME'];

        for($I=0;$I<$array_count;$I++)
        {
            if($a_user_ids[$I]==""){
                continue;
            }
            $jpush_id = UserId2Uid($a_user_ids[$I]);
            $query = "select jpush_id from user_jpush where uid = '".$jpush_id."'";
            $cursor= exequery(TD::conn(),$query);
            if($row = mysql_fetch_array($cursor)){
                $id[] = $row["jpush_id"]; 
            }
        }
        $extras=array("sms_type"=>$s_sms_type,"work_id"=>iconv("GBK","UTF-8", $s_work_id),"remind_url"=>iconv("GBK","UTF-8", $s_remind_url));
          //极光推送 
        /*/**获取 Jpush_id**/
        if(is_array($id)){
            if($s_sms_type==0)
           {
            $contents= $from_user_name.':'.$s_content;
           }else{
            $contents= _("发送人:").$from_user_name."\n".$s_content;
           }
            $content = iconv("GBK","UTF-8", $contents) ;
            $content=str_replace('\&quot;','"',$content);
            $content=str_replace("\'","'",$content);
            //极光推送 
            $app_key = "549300e0ba68cf2ac5a9ea5a";
            $master_secret = "e23eb50242cbf7b19d820b44";
            $client = new JPushClient($app_key, $master_secret);
            try {
                $result = $client->push()
                       ->setPlatform(M\all)
                       ->setAudience(M\registration_id($id))
                       ->setNotification(M\notification($content,
                            M\android($content, null, null, $extras),
                            M\ios($content, null, null, null, $extras)
                        ))
                       ->setOptions(M\options(null, null, null, true))
                       ->send(); 
            } catch (APIRequestException $e) {
                $results = array(
                    'from_name' => $from_user_name,
                    'to_jpushid' => $id,
                    'content' => $s_content,
                    'error_message' => $e->json
                );
                $ROOT_PATH=$_SERVER["DOCUMENT_ROOT"];
                if($ROOT_PATH == "")
                    $ROOT_PATH = str_replace("\\", "/", realpath(dirname(__FILE__).'/../'));
                if(substr($ROOT_PATH,-1)!="/")
                    $ROOT_PATH.="/";
                $LOG_PATH = realpath($ROOT_PATH."../")."/logs/Jpush.log";
                file_put_contents($LOG_PATH,var_export($results,true)."\r\n",FILE_APPEND);
            }
        }
    }
}

function send_sms($SEND_TIME,$FROM_ID,$TO_ID,$SMS_TYPE,$CONTENT,$REMIND_URL="",$WORK_ID="0")
{
    if($SEND_TIME==""){
        $SEND_TIME = time();
    }else if(!is_int($SEND_TIME)){
        $SEND_TIME = strtotime($SEND_TIME);
    }
    $CONTENT = strip_tags($CONTENT, '<br><p><a><span><strong><b><em><u><img><ul><li><ol><font><div>');
    $query="insert into SMS_BODY(FROM_ID,SMS_TYPE,CONTENT,SEND_TIME,REMIND_URL) values ('$FROM_ID','$SMS_TYPE','$CONTENT','$SEND_TIME','$REMIND_URL')";
    exequery(TD::conn(),$query);
    $BODY_ID=mysql_insert_id();
    if($BODY_ID == 0)
    {
        Message(_("错误"), _("写入数据库错误(SMS_BODY)，请联系管理员到 系统管理->数据库管理 中修复数据库"));
        Button_Back();
        exit;
    }

    $MY_ARRAY=explode(",",$TO_ID);
    $ARRAY_COUNT=sizeof($MY_ARRAY);

    if($MY_ARRAY[$ARRAY_COUNT-1]=="")
        $ARRAY_COUNT--;

    $REMIND_TIME = $SEND_TIME;
    $value_str="";

    for($I=0;$I<$ARRAY_COUNT;$I++)
    {
        if($MY_ARRAY[$I]=="" || find_id($USER_ID_SENT, $MY_ARRAY[$I]))
            continue;

        if($I > 0 && $I%MYOA_SMS_DELAY_PER_ROWS == 0)
            $REMIND_TIME += MYOA_SMS_DELAY_SECONDS;
        if($I < MYOA_SMS_DELAY_PER_ROWS)
            $USER_ID_SENT.=$MY_ARRAY[$I].",";
        if($I < MYOA_IM_REMIND_ROWS)
            $USER_ID_IM_REMIND.=$MY_ARRAY[$I].",";
        $value_str.="('".$MY_ARRAY[$I]."','1','0','$BODY_ID','$REMIND_TIME','$SMS_TYPE','$WORK_ID'),";
    }

    $value_str = td_trim($value_str);

    if($value_str != "")
    {
        $query="insert into SMS(TO_ID,REMIND_FLAG,DELETE_FLAG,BODY_ID,REMIND_TIME,MODULE_TYPE,MODULE_ID) values ".$value_str;
        exequery(TD::conn(),$query);
        if(mysql_affected_rows() <= 0)
        {
            Message(_("错误"), _("写入数据库错误(SMS)，请联系管理员到 系统管理->数据库管理 中修复数据库"));
            Button_Back();
            exit;
        }

        if($SEND_TIME - time() < 60)
        {
            //增加移动端信息推送 lp 2014/7/8 5:41:57
            if($SMS_TYPE!='8')
            {
                message_push('add', array('from_id' => $FROM_ID, 'send_time' => $SEND_TIME, 'content' => $CONTENT, 'sms_type' => $SMS_TYPE, 'to_id' => $TO_ID, 'work_id' => $WORK_ID,'remind_url' => $REMIND_URL));
            }
            $params = array(
                'send_time' => $SEND_TIME,
                'from_id' => $FROM_ID,
                'to_id' => $TO_ID,
                'sms_type' => $SMS_TYPE,
                'content' => $CONTENT,
                'sms_url' =>$REMIND_URL
            ); 
            send_dd_sms($params);
            send_wx_sms($params);
            send_qywx_sms($params);
            $UID_IM_REMIND = "";
            $USER_ID_STR = $USER_ID_SENT.check_id($USER_ID_SENT, $USER_ID_IM_REMIND, false);
            $query="select UID,USER_ID from USER where find_in_set(USER_ID,'$USER_ID_STR')";
            $cursor=exequery(TD::conn(),$query);
            while($ROW=mysql_fetch_array($cursor))
            {
                if(find_id($USER_ID_SENT, $ROW["USER_ID"]))
                    new_sms_remind($ROW["UID"], 1);
                if(find_id($USER_ID_IM_REMIND, $ROW["USER_ID"]))
                    $UID_IM_REMIND .= $ROW["UID"].",";
            }

            if(MYOA_IM_REMIND_ROWS > 0 && $UID_IM_REMIND!= "")
            {
                imtask("S^a^".$UID_IM_REMIND);
            }
        }
    }
}

function send_sms1($SEND_TIME,$FROM_ID,$TO_ID,$SMS_TYPE,$CONTENT,$REMIND_URL="",$WORK_ID="0")
{
  if($SEND_TIME=="")
     $SEND_TIME = time();
  else
     $SEND_TIME = strtotime($SEND_TIME);

  $CONTENT = strip_tags($CONTENT, '<br><p><a><span><strong><b><em><u><img><ul><li><ol><font><div>');
  $query="insert into SMS_BODY(FROM_ID,SMS_TYPE,CONTENT,SEND_TIME,REMIND_URL) values ('$FROM_ID','$SMS_TYPE','$CONTENT','$SEND_TIME','$REMIND_URL')";
  exequery(TD::conn(),$query);
  
  $BODY_ID=mysql_insert_id();
  
  if($BODY_ID == 0)
  {
     Message(_("错误"), _("写入数据库错误(SMS_BODY)，请联系管理员到 系统管理->数据库管理 中修复数据库"));
     Button_Back();
     exit;
  }
  
  $MY_ARRAY=explode(",",$TO_ID);
  $ARRAY_COUNT=sizeof($MY_ARRAY);

  if($MY_ARRAY[$ARRAY_COUNT-1]=="")
     $ARRAY_COUNT--;

  $REMIND_TIME = $SEND_TIME;
  $value_str="";
  for($I=0;$I<$ARRAY_COUNT;$I++)
  {
    if($MY_ARRAY[$I]=="" || find_id($USER_ID_SENT, $MY_ARRAY[$I]))
       continue;

    if($I > 0 && $I%MYOA_SMS_DELAY_PER_ROWS == 0)
       $REMIND_TIME += MYOA_SMS_DELAY_SECONDS;
    if($I < MYOA_SMS_DELAY_PER_ROWS)
       $USER_ID_SENT.=$MY_ARRAY[$I].",";
    $value_str.="('".$MY_ARRAY[$I]."','1','0','$BODY_ID','$REMIND_TIME','$SMS_TYPE','$WORK_ID'),";
  }
  
  $value_str = td_trim($value_str);
  if($value_str != "")
  {
     $query="insert into SMS(TO_ID,REMIND_FLAG,DELETE_FLAG,BODY_ID,REMIND_TIME,MODULE_TYPE,MODULE_ID) values ".$value_str;
     exequery(TD::conn(),$query);
  }
     
  
  if(mysql_affected_rows() <= 0)
  {
     Message(_("错误"), _("写入数据库错误(SMS)，请联系管理员到 系统管理->数据库管理 中修复数据库"));
     Button_Back();
     exit;
  }

  if($SEND_TIME - time() < 60)
  {
     $query="select UID from USER where find_in_set(USER_ID,'$USER_ID_SENT')";
     $cursor=exequery(TD::conn(),$query);
     while($ROW=mysql_fetch_array($cursor))
        new_sms_remind($ROW["UID"], 1);
  }
}

function delete_sms($SMS_ID_STR,$DEL_TYPE)//DEL_TYPE=1 删除收到的微讯，DEL_TYPE=2 删除发送的微讯
{
   $SMS_ID_STR = td_trim($SMS_ID_STR);
   if($SMS_ID_STR == "")
      return;
      
   $query="select SMS_ID,REMIND_FLAG,DELETE_FLAG,SMS.BODY_ID from SMS,SMS_BODY where SMS.BODY_ID=SMS_BODY.BODY_ID and SMS_ID in ($SMS_ID_STR)";
   if($DEL_TYPE=="1")
      $query.=" and TO_ID='".$_SESSION["LOGIN_USER_ID"]."'";
   else
      $query.=" and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
   $cursor=exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $SMS_ID=$ROW["SMS_ID"];
      $REMIND_FLAG=$ROW["REMIND_FLAG"];
      $DELETE_FLAG=$ROW["DELETE_FLAG"];
      $BODY_ID=$ROW["BODY_ID"];

      if($DEL_TYPE=="1")
      {
         if($DELETE_FLAG=="2")
            $SMS_ID_DEL.=$SMS_ID.",";
         else
            $SMS_ID_UPD1.=$SMS_ID.",";
      }
      else
      {
         if($DELETE_FLAG=="1" || $REMIND_FLAG!="0")
            $SMS_ID_DEL.=$SMS_ID.",";
         else
            $SMS_ID_UPD2.=$SMS_ID.",";
      }

      if(find_id($SMS_ID_DEL, $SMS_ID))
      {
         $query="select count(*) from SMS where BODY_ID='$BODY_ID'";
         $cursor1=exequery(TD::conn(),$query);
         if($ROW=mysql_fetch_array($cursor1))
            $GROUP_COUNT=$ROW[0];

         if($GROUP_COUNT<=1)
            $BODY_ID_DEL.=$BODY_ID.",";
      }
   }

   $SMS_ID_DEL = td_trim($SMS_ID_DEL);
   $BODY_ID_DEL = td_trim($BODY_ID_DEL);
   $SMS_ID_UPD1 = td_trim($SMS_ID_UPD1);
   $SMS_ID_UPD2 = td_trim($SMS_ID_UPD2);

   if($SMS_ID_DEL!="")
   {
      $query="delete from SMS where SMS_ID in ($SMS_ID_DEL)";
      exequery(TD::conn(),$query);
   }

   if($BODY_ID_DEL!="")
   {
      $query="delete from SMS_BODY where BODY_ID in ($BODY_ID_DEL)";
      exequery(TD::conn(),$query);
   }

   if($SMS_ID_UPD1!="")
   {
      $query="update SMS set REMIND_FLAG='0', DELETE_FLAG='1' where SMS_ID in ($SMS_ID_UPD1)";
      exequery(TD::conn(),$query);
   }

   if($SMS_ID_UPD2!="")
   {
      $query="update SMS set DELETE_FLAG='2' where SMS_ID in ($SMS_ID_UPD2)";
      exequery(TD::conn(),$query);
   }

   if($DEL_TYPE=="1")
      check_new_sms();
   else
   {
      $SMS_ID_NEW = td_trim($SMS_ID_DEL.",".$SMS_ID_UPD1);
      if($SMS_ID_NEW!="")
      {
         $USER_ARRAY=array();
         $query="select UID,USER_ID from SMS,USER where SMS.TO_ID=USER.USER_ID and SMS_ID in ($SMS_ID_NEW) group by TO_ID;";
         $cursor=exequery(TD::conn(),$query);
         while($ROW=mysql_fetch_array($cursor))
            $USER_ARRAY[] = array("UID" => $ROW["UID"], "USER_ID" => $ROW["USER_ID"]);
         
         check_new_sms($USER_ARRAY);
      }
   }
}

function cancel_sms_remind($SMS_ID_STR,$SEND_TIME="")
{

   $SMS_ID_STR = td_trim($SMS_ID_STR);
   if($SMS_ID_STR!="")
   {
      $query="update SMS set REMIND_FLAG='0' where TO_ID='".$_SESSION["LOGIN_USER_ID"]."' and SMS_ID in ($SMS_ID_STR)";
      exequery(TD::conn(),$query);
      check_new_sms();
   }
   else if(intval($SEND_TIME) > 0)
   {
      $query="update SMS set REMIND_FLAG='0' where TO_ID='".$_SESSION["LOGIN_USER_ID"]."' and REMIND_TIME <= '".intval($SEND_TIME)."'";
      exequery(TD::conn(),$query);
      new_sms_remind($_SESSION["LOGIN_UID"], 0);
   }
}

function delete_remind_sms($SMS_TYPE, $FROM_ID="", $CONTENT="", $SEND_TIME="", $REMIND_URL="",$TO_ID="")
{
   if($FROM_ID!="")
      $where.=" and FROM_ID='$FROM_ID'";
   if($CONTENT!="")
      $where.=" and CONTENT like '%$CONTENT%'";
   if($SEND_TIME!="")
   {
      if(strlen($SEND_TIME)==10)
         $where.=" and to_days(FROM_UNIXTIME(SEND_TIME))=to_days('$SEND_TIME')";
      else
         $where.=" and SEND_TIME='".strtotime($SEND_TIME)."'";
   }
   if($REMIND_URL!="")
      $where.=" and REMIND_URL='$REMIND_URL'";

   $query="select BODY_ID from SMS_BODY where SMS_TYPE='$SMS_TYPE'".$where;
   $cursor=exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $BODY_ID_STR.=$ROW["BODY_ID"].",";
   }
   if($SMS_TYPE==2 && $TO_ID!="") //邮件，删除单个人的提醒
   {
   	  $BODY_ID_STR = td_trim($BODY_ID_STR);
      if($BODY_ID_STR != "")
      {
      	$query="select BODY_ID,SMS_ID from SMS where BODY_ID in ($BODY_ID_STR) and TO_ID='$TO_ID'";
      	$cursor=exequery(TD::conn(),$query);
      	if($ROWS=mysql_fetch_array($cursor))
      	{
      	  $BODY_ID=$ROWS['BODY_ID'];
      	  $SMS_ID=$ROWS['SMS_ID'];
        }
      	if($BODY_ID!="" && $SMS_ID!="")
      	{
      	  $query="delete from SMS_BODY where BODY_ID='$BODY_ID'";
      	  $cursor=exequery(TD::conn(),$query);
      	  $query="delete from SMS where SMS_ID='$SMS_ID'";
      	  $cursor=exequery(TD::conn(),$query);
      	}  
      	
      }
   }
  else
  {
   $query="delete from SMS_BODY where SMS_TYPE='$SMS_TYPE'".$where;
   $cursor=exequery(TD::conn(),$query);
 
   $BODY_ID_STR = td_trim($BODY_ID_STR);
   if($BODY_ID_STR != "")
   {
      $query="delete from SMS where BODY_ID in ($BODY_ID_STR)";
      $cursor=exequery(TD::conn(),$query);
   }
   }
 
   check_new_sms();
}
function check_new_sms($USER_ARRAY=array())
{
   if(count($USER_ARRAY) == 0 || !is_array($USER_ARRAY))
      $USER_ARRAY=array(array("UID" => $_SESSION["LOGIN_UID"], "USER_ID" => $_SESSION["LOGIN_USER_ID"]));

   foreach($USER_ARRAY as $USER)
   {
      if($USER["UID"]=="" || $USER["USER_ID"]=="")
         continue;

      $query="select SMS_ID from SMS,SMS_BODY where SMS.BODY_ID=SMS_BODY.BODY_ID and REMIND_FLAG='1' and DELETE_FLAG!='1' and TO_ID='".$USER["USER_ID"]."' and REMIND_TIME<='".time()."' limit 0,1";
      $cursor=exequery(TD::conn(),$query);
      if(mysql_num_rows($cursor) > 0)
         new_sms_remind($USER["UID"], 1);
      else
         new_sms_remind($USER["UID"], 0);
   }
}


/**
 * 获取未读事务提醒数量
 * 
 * @return  int 
 */
function count_new_sms()
{
	$CUR_TIME = time();
	$uid = $_SESSION["LOGIN_UID"];
	$cache = TD::cache();
	$key = "sms_count_$uid";
    $sms_file = MYOA_ATTACH_PATH."new_sms/".$uid.".sms";
	$mark = @file_get_contents($sms_file);
	$ret = $cache->get($key);
	//缓存失效或过期
	if(substr($mark, 0, 1) == '1' || $ret === false)
	{
		$query = "SELECT count(*) from SMS,SMS_BODY where SMS.BODY_ID=SMS_BODY.BODY_ID and TO_ID='".$_SESSION["LOGIN_USER_ID"]."' and SEND_TIME<='$CUR_TIME' and DELETE_FLAG!='1' and REMIND_FLAG!='0'";
		$cursor= exequery(TD::conn(),$query);
		if($ROW=mysql_fetch_array($cursor))
		{
			$ret = $ROW[0];
		}
		$cache->set("$key", $ret, 3600 * 24 * 15);
	}
	else
	{
		$ret = $cache->get($key);
	}
	return $ret;
}

/**
 * 分享到企业社区
 * 
 * @param  string $push_arr    (module/mid/user_id/priv_id/dept_id/subject)
 */
function push_to_wxshare($push_arr)
{
    if(!$push_arr['module'] || !$push_arr['mid'])
    {
        return false;
    }
    
    $modules_arr = array('news' => _("新闻"), 'notify' => _("公告通知"), 'diary' => _("工作日志"), 'file_folder' => _("公共文件柜"));
    
    include_once("general/sns/classes/TFeedLoader.php");
    include_once('inc/utility_org.php');
    
    $text = $_SESSION['LOGIN_USER_NAME']._(" 发布了").$modules_arr['module']._("《").$push_arr['subject']._("》");
    
    $push_feed_arr = array(
        'created_at' => time(),
        'text' => $text,
        'module' => $push_arr['module'],
        'mid' => $push_arr['mid']
    );
    
    $push_uid_strs = GetUidByOther($push_arr['user_id'], $push_arr['priv_id'], $push_arr['dept_id']);
    $to_uid_arr = explode(",", $push_uid_strs);
    
    $login_uid = $_SESSION['LOGIN_UID'];
    $poster = new TFeedPost($login_uid);
    $poster->create($push_feed_arr, $to_uid_arr);
}
?>