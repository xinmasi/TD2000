<?
function is_number($str)
{
  if(substr($str,0,1)=="-")
     $str=substr($str,1);
  $length=strlen($str);
  for($i=0;$i<$length;$i++)
  {
   $ascii_value=ord(substr($str,$i,1));
   if(!($ascii_value>=48 && $ascii_value<=57))
     return false;
  }

  if($str!="0")
  {
   $str=intval($str);
   if($str==0)
     return false;
  }
  return true;
}

function is_decimal($str)
{
  if(substr($str,0,1)=="-")
     $str=substr($str,1);
  $length=strlen($str);
  for($i=0;$i<$length;$i++)
  {
   $ascii_value=ord(substr($str,$i,1));
   if($i>0 && $ascii_value==46)continue;
   if(!($ascii_value>=48 && $ascii_value<=57))
     return false;
  }
  return true;
}

function is_money($str)
{
 $dot_pos=strpos($str,".");
 if(!$dot_pos)
    return false;

 $str1=substr($str,0,$dot_pos);
 if(strlen($str1)>14)
    return false;
 if(!is_number($str1))
   return false;

 $str2=substr($str,$dot_pos+1,strlen($str)-$dot_pos);
 if(strlen($str2)!=2)
    return false;
 if(!is_number($str2))
   return false;

 return true;
}


function is_money_len($str,$int_len,$dot_len)
{
 $dot_pos=strpos($str,".");
 if(!$dot_pos)
    return false;

 $str1=substr($str,0,$dot_pos);

 if(strlen($str1)>$int_len)
    return false;

 if(!is_number($str1))
   return false;


 $str2=substr($str,$dot_pos+1,strlen($str)-$dot_pos);
 if(strlen($str2)!=$dot_len)
    return false;
 if(!is_number($str2))
   return false;

 return true;
}

function is_date($str,$partition="-")
{
 $YEAR="";
 $MONTH="";
 $DAY="";

 $len=strlen($str);

 $offset=0;
 $i=strpos($str,$partition,$offset);
 $YEAR=substr($str,$offset,$i-$offset);

 $offset=$i+1;
 if($offset>$len)
   return false;

 if($i)
 { $i=strpos($str,$partition,$offset);
   $MONTH=substr($str,$offset,$i-$offset);
   $offset=$i+1;

   if($offset>$len)
     return false;

   if($i)
    $DAY=substr($str,$offset,$len-$offset);
 }

 if($YEAR=="" || $MONTH=="" || $DAY=="")
   return false;

 if(!checkdate(intval($MONTH),intval($DAY),intval($YEAR)))
   return false;

 return true;
}

function is_time($str)
{
 $TEMP="";
 $HOUR="";
 $MIN="";
 $SEC="";

 $TEMP=strtok($str,":");
 $HOUR=$TEMP;
 if($HOUR=="" || $HOUR>=24 || $HOUR<0 || !is_number($HOUR))
    return false;

 $TEMP=strtok(":");
 $MIN=$TEMP;
 if($MIN=="" || $MIN>=60 || $MIN<0 || !is_number($MIN))
    return false;

 $TEMP=strtok(":");
 $SEC=$TEMP;
 if($SEC=="" || $SEC>=60 || $SEC<0 || !is_number($SEC))
    return false;

 return true;
}

function is_date_time($DATE_TIME_STR)
{
    if($DATE_TIME_STR==null||strlen($DATE_TIME_STR)==0)
       return false;
    $DATE_TIME_ARRY=explode(" ",$DATE_TIME_STR);
    if(is_date($DATE_TIME_ARRY[0])&&is_time($DATE_TIME_ARRY[1]))
       return true;
    return false;
}

?>