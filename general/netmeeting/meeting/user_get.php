<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_DATE=date("Y-m-d",time());
$CUR_HOUR=date("H",time());
$CUR_MIN=date("i",time());
$USR_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".usr";

if(!file_exists($USR_FILE))
{
   $fp=td_fopen($USR_FILE,"a+");
   flock ($fp,2);
   fclose($fp);
}
$LINES=file($USR_FILE);
$LINES_COUNT=count($LINES);

if($LINES_COUNT>500)
{
   $fp=td_fopen($USR_FILE,"w");
   flock ($fp,2);
   fclose($fp);
   $LINES=file($USR_FILE);
   $LINES_COUNT=count($LINES);
}
$USER_FOUND=0;
$MSG_NEW="";
$USER_LIST="";
//print_r($LINES);

for($I=0;$I<$LINES_COUNT;$I++)
{
    //-- 找到 USER_ID 行 --
    if($I%4==0)
    {
      $POS=strpos($LINES[$I],chr(10));
      $USER_ID=substr($LINES[$I],0,$POS);
      //
    
      //-- 如果是本用户，则更新最新在线时间 --
      if($USER_ID==$_SESSION["LOGIN_USER_ID"])
      {
         $LINES[$I+1]=$USER_NAME;
         $LINES[$I+2]=$CUR_TIME;
     //   
     		$LINES[$I+3]=$USER_IP;
      //
         $USER_FOUND=1;
         $USER_LIST.=$USER_ID.",";
         $USER_NAME_LIST.=$USER_NAME.",";
         $USER_IP_LIST.=$USER_IP.",";
      }

      //-- 更新时间在1分钟内的用户视为在线用户 --
      else
      {
         $POS=strpos($LINES[$I+2],chr(10));
         $REFRESH_TIME=substr($LINES[$I+2],0,$POS); 
         $STR=strtok($REFRESH_TIME," ");
         $DATE=$STR;

         if(compare_date($CUR_DATE,$DATE)==0)
         {
            $STR=strstr($REFRESH_TIME," ");
            $STR=strtok($STR,":");
            $HOUR=$STR;
            $STR=strtok(":");
            $MIN=$STR;
            
            if((($CUR_HOUR*60+$CUR_MIN)-($HOUR*60+$MIN))<2)
            {
               $USER_LIST.=$USER_ID.",";
               //$USER_IP_LIST.=$USER_IP.",";
               $POS=strpos($LINES[$I+1],chr(10));
               $STR=substr($LINES[$I+1],0,$POS);
               $POSS=strpos($LINES[$I+3],chr(10));
               $STRS=substr($LINES[$I+3],0,$POSS);
               $USER_NAME_LIST.=$STR.",";
               $USER_IP_LIST.=$STRS.",";
            }
         }
      }
    }

    //-- 存储所有的用户信息 --
    $STR=$LINES[$I];
    $POS=strpos($STR,chr(10));

    if($POS>0)
       $STR=substr($STR,0,$POS);

    $STR.="\n";
    $MSG_NEW=$MSG_NEW.$STR;
}


//-- 未找到本用户，则加入本用户信息 --
if($USER_FOUND==0)
{
  $MSG_NEW.=$_SESSION["LOGIN_USER_ID"]."\n";
  $MSG_NEW.=$USER_NAME."\n";
  $MSG_NEW.=$CUR_TIME."\n";  //
  $MSG_NEW.=$USER_IP."\n";
  //
  $USER_LIST.=$_SESSION["LOGIN_USER_ID"].",";
  $USER_NAME_LIST.=$USER_NAME.",";
  //
  $USER_IP_LIST.=$USER_IP.",";
}

//-- 写入新文件 ---
$fp=td_fopen($USR_FILE,"w");
flock ($fp,2);
fwrite($fp,$MSG_NEW);


//-- 显示在线用户 --
$ARRAY_ID=explode(",", $USER_LIST);
$ARRAY_NAME=explode(",", $USER_NAME_LIST);
$ARRAY_IP=explode(",", $USER_IP_LIST);
$USER_COUNT=count($ARRAY_ID)-1;

?>

<span align="left" class="big1" ><b><?=sprintf(_("在线人员：%s人"),"</b></span><font color='red'><b>".$USER_COUNT."</b></font>")?><br><br> 

<?
for($I=0;$I<$USER_COUNT;$I++)
{
	 if(substr(GetUserNameById($ARRAY_ID[$I]),-1)==",")
		$USERNAME=substr(GetUserNameById($ARRAY_ID[$I]),0,-1);
    $USERIP=$ARRAY_IP[$I];
   
?>

    <a href="javascript:parent.chat_input.say_to('<?=$ARRAY_ID[$I]?>','<?=$ARRAY_NAME[$I]?>');" title="<?=$USERNAME.$USERIP?>">[<?=$USERNAME?>]</font></a><br>
<?
}

?>
