<?
function chinese_date($year,$month,$day)
{
  $everymonth=array(
  0=>array(8,0,0,0,0,0,0,0,0,0,0,0,29,30,7,1),
  1=>array(0,29,30,29,29,30,29,30,29,30,30,30,29,0,8,2),
  2=>array(0,30,29,30,29,29,30,29,30,29,30,30,30,0,9,3),
  3=>array(5,29,30,29,30,29,29,30,29,29,30,30,29,30,10,4),
  4=>array(0,30,30,29,30,29,29,30,29,29,30,30,29,0,1,5),
  5=>array(0,30,30,29,30,30,29,29,30,29,30,29,30,0,2,6),
  6=>array(4,29,30,30,29,30,29,30,29,30,29,30,29,30,3,7),
  7=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,4,8),
  8=>array(0,30,29,29,30,30,29,30,29,30,30,29,30,0,5,9),
  9=>array(2,29,30,29,29,30,29,30,29,30,30,30,29,30,6,10),
  10=>array(0,29,30,29,29,30,29,30,29,30,30,30,29,0,7,11),
  11=>array(6,30,29,30,29,29,30,29,29,30,30,29,30,30,8,12),
  12=>array(0,30,29,30,29,29,30,29,29,30,30,29,30,0,9,1),
  13=>array(0,30,30,29,30,29,29,30,29,29,30,29,30,0,10,2),
  14=>array(5,30,30,29,30,29,30,29,30,29,30,29,29,30,1,3),
  15=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,2,4),
  16=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,3,5),
  17=>array(2,30,29,29,30,29,30,30,29,30,30,29,30,29,4,6),
  18=>array(0,30,29,29,30,29,30,29,30,30,29,30,30,0,5,7),
  19=>array(7,29,30,29,29,30,29,29,30,30,29,30,30,30,6,8),
  20=>array(0,29,30,29,29,30,29,29,30,30,29,30,30,0,7,9),
  21=>array(0,30,29,30,29,29,30,29,29,30,29,30,30,0,8,10),
  22=>array(5,30,29,30,30,29,29,30,29,29,30,29,30,30,9,11),
  23=>array(0,29,30,30,29,30,29,30,29,29,30,29,30,0,10,12),
  24=>array(0,29,30,30,29,30,30,29,30,29,30,29,29,0,1,1),
  25=>array(4,30,29,30,29,30,30,29,30,30,29,30,29,30,2,2),
  26=>array(0,29,29,30,29,30,29,30,30,29,30,30,29,0,3,3),
  27=>array(0,30,29,29,30,29,30,29,30,29,30,30,30,0,4,4),
  28=>array(2,29,30,29,29,30,29,29,30,29,30,30,30,30,5,5),
  29=>array(0,29,30,29,29,30,29,29,30,29,30,30,30,0,6,6),
  30=>array(6,29,30,30,29,29,30,29,29,30,29,30,30,29,7,7),
  31=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,8,8),
  32=>array(0,30,30,30,29,30,29,30,29,29,30,29,30,0,9,9),
  33=>array(5,29,30,30,29,30,30,29,30,29,30,29,29,30,10,10),
  34=>array(0,29,30,29,30,30,29,30,29,30,30,29,30,0,1,11),
  35=>array(0,29,29,30,29,30,29,30,30,29,30,30,29,0,2,12),
  36=>array(3,30,29,29,30,29,29,30,30,29,30,30,30,29,3,1),
  37=>array(0,30,29,29,30,29,29,30,29,30,30,30,29,0,4,2),
  38=>array(7,30,30,29,29,30,29,29,30,29,30,30,29,30,5,3),
  39=>array(0,30,30,29,29,30,29,29,30,29,30,29,30,0,6,4),
  40=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,7,5),
  41=>array(6,30,30,29,30,30,29,30,29,29,30,29,30,29,8,6),
  42=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,9,7),
  43=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,10,8),
  44=>array(4,30,29,30,29,30,29,30,29,30,30,29,30,30,1,9),
  45=>array(0,29,29,30,29,29,30,29,30,30,30,29,30,0,2,10),
  46=>array(0,30,29,29,30,29,29,30,29,30,30,29,30,0,3,11),
  47=>array(2,30,30,29,29,30,29,29,30,29,30,29,30,30,4,12),
  48=>array(0,30,29,30,29,30,29,29,30,29,30,29,30,0,5,1),
  49=>array(7,30,29,30,30,29,30,29,29,30,29,30,29,30,6,2),
  50=>array(0,29,30,30,29,30,30,29,29,30,29,30,29,0,7,3),
  51=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,8,4),
  52=>array(5,29,30,29,30,29,30,29,30,30,29,30,29,30,9,5),
  53=>array(0,29,30,29,29,30,30,29,30,30,29,30,29,0,10,6),
  54=>array(0,30,29,30,29,29,30,29,30,30,29,30,30,0,1,7),
  55=>array(3,29,30,29,30,29,29,30,29,30,29,30,30,30,2,8),
  56=>array(0,29,30,29,30,29,29,30,29,30,29,30,30,0,3,9),
  57=>array(8,30,29,30,29,30,29,29,30,29,30,29,30,29,4,10),
  58=>array(0,30,30,30,29,30,29,29,30,29,30,29,30,0,5,11),
  59=>array(0,29,30,30,29,30,29,30,29,30,29,30,29,0,6,12),
  60=>array(6,30,29,30,29,30,30,29,30,29,30,29,30,29,7,1),
  61=>array(0,30,29,30,29,30,29,30,30,29,30,29,30,0,8,2),
  62=>array(0,29,30,29,29,30,29,30,30,29,30,30,29,0,9,3),
  63=>array(4,30,29,30,29,29,30,29,30,29,30,30,30,29,10,4),
  64=>array(0,30,29,30,29,29,30,29,30,29,30,30,30,0,1,5),
  65=>array(0,29,30,29,30,29,29,30,29,29,30,30,29,0,2,6),
  66=>array(3,30,30,30,29,30,29,29,30,29,29,30,30,29,3,7),
  67=>array(0,30,30,29,30,30,29,29,30,29,30,29,30,0,4,8),
  68=>array(7,29,30,29,30,30,29,30,29,30,29,30,29,30,5,9),
  69=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,6,10),
  70=>array(0,30,29,29,30,29,30,30,29,30,30,29,30,0,7,11),
  71=>array(5,29,30,29,29,30,29,30,29,30,30,30,29,30,8,12),
  72=>array(0,29,30,29,29,30,29,30,29,30,30,29,30,0,9,1),
  73=>array(0,30,29,30,29,29,30,29,29,30,30,29,30,0,10,2),
  74=>array(4,30,30,29,30,29,29,30,29,29,30,30,29,30,1,3),
  75=>array(0,30,30,29,30,29,29,30,29,29,30,29,30,0,2,4),
  76=>array(8,30,30,29,30,29,30,29,30,29,29,30,29,30,3,5),
  77=>array(0,30,29,30,30,29,30,29,30,29,30,29,29,0,4,6),
  78=>array(0,30,29,30,30,29,30,30,29,30,29,30,29,0,5,7),
  79=>array(6,30,29,29,30,29,30,30,29,30,30,29,30,29,6,8),
  80=>array(0,30,29,29,30,29,30,29,30,30,29,30,30,0,7,9),
  81=>array(0,29,30,29,29,30,29,29,30,30,29,30,30,0,8,10),
  82=>array(4,30,29,30,29,29,30,29,29,30,29,30,30,30,9,11),
  83=>array(0,30,29,30,29,29,30,29,29,30,29,30,30,0,10,12),
  84=>array(10,30,29,30,30,29,29,30,29,29,30,29,30,30,1,1),
  85=>array(0,29,30,30,29,30,29,30,29,29,30,29,30,0,2,2),
  86=>array(0,29,30,30,29,30,30,29,30,29,30,29,29,0,3,3),
  87=>array(6,30,29,30,29,30,30,29,30,30,29,30,29,29,4,4),
  88=>array(0,30,29,30,29,30,29,30,30,29,30,30,29,0,5,5),
  89=>array(0,30,29,29,30,29,29,30,30,29,30,30,30,0,6,6),
  90=>array(5,29,30,29,29,30,29,29,30,29,30,30,30,30,7,7),
  91=>array(0,29,30,29,29,30,29,29,30,29,30,30,30,0,8,8),
  92=>array(0,29,30,30,29,29,30,29,29,30,29,30,30,0,9,9),
  93=>array(3,29,30,30,29,30,29,30,29,29,30,29,30,29,10,10),
  94=>array(0,30,30,30,29,30,29,30,29,29,30,29,30,0,1,11),
  95=>array(8,29,30,30,29,30,29,30,30,29,29,30,29,30,2,12),
  96=>array(0,29,30,29,30,30,29,30,29,30,30,29,29,0,3,1),
  97=>array(0,30,29,30,29,30,29,30,30,29,30,30,29,0,4,2),
  98=>array(5,30,29,29,30,29,29,30,30,29,30,30,29,30,5,3),
  99=>array(0,30,29,29,30,29,29,30,29,30,30,30,29,0,6,4),
  100=>array(0,30,30,29,29,30,29,29,30,29,30,30,29,0,7,5),
  101=>array(4,30,30,29,30,29,30,29,29,30,29,30,29,30,8,6),
  102=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,9,7),
  103=>array(0,30,30,29,30,30,29,30,29,29,30,29,30,0,10,8),
  104=>array(2,29,30,29,30,30,29,30,29,30,29,30,29,30,1,9),
  105=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,2,10),
  106=>array(7,30,29,30,29,30,29,30,29,30,30,29,30,30,3,11),
  107=>array(0,29,29,30,29,29,30,29,30,30,30,29,30,0,4,12),
  108=>array(0,30,29,29,30,29,29,30,29,30,30,29,30,0,5,1),
  109=>array(5,30,30,29,29,30,29,29,30,29,30,29,30,30,6,2),
  110=>array(0,30,29,30,29,30,29,29,30,29,30,29,30,0,7,3),
  111=>array(0,30,29,30,30,29,30,29,29,30,29,30,29,0,8,4),
  112=>array(4,30,29,30,30,29,30,29,30,29,30,29,30,29,9,5),
  113=>array(0,30,29,30,29,30,30,29,30,29,30,29,30,0,10,6),
  114=>array(9,29,30,29,30,29,30,29,30,30,29,30,29,30,1,7),
  115=>array(0,29,30,29,29,30,29,30,30,30,29,30,29,0,2,8),
  116=>array(0,30,29,30,29,29,30,29,30,30,29,30,30,0,3,9),
  117=>array(6,29,30,29,30,29,29,30,29,30,29,30,30,30,4,10),
  118=>array(0,29,30,29,30,29,29,30,29,30,29,30,30,0,5,11),
  119=>array(0,30,29,30,29,30,29,29,30,29,29,30,30,0,6,12),
  120=>array(4,29,30,30,30,29,30,29,29,30,29,30,29,30,7,1)
  );
  ##############################
  #ũ�����
  $mten=array("null",_("��"),_("��"),_("��"),_("��"),_("��"),_("��"),_("��"),_("��"),_("��"),_("��"));
  #ũ����֧
  $mtwelve=array("null",_("�ӣ���"),_("��ţ��"),_("��������"),_("î���ã�"),_("��������"),
                 _("�ȣ��ߣ�"),_("�磨����"),_("δ����"),_("�꣨�"),_("�ϣ�����"),_("�磨����"),_("��������"));
  #ũ���·�
  $mmonth=array(_("��"),_("��"),_("��"),_("��"),_("��"),_("��"),_("��"),
                _("��"),_("��"),_("��"),_("ʮ"),_("ʮһ"),_("ʮ��"),_("��"));
  #ũ����
  $mday=array("null",_("��һ"),_("����"),_("����"),_("����"),_("����"),_("����"),_("����"),_("����"),_("����"),_("��ʮ"),
              _("ʮһ"),_("ʮ��"),_("ʮ��"),_("ʮ��"),_("ʮ��"),_("ʮ��"),_("ʮ��"),_("ʮ��"),_("ʮ��"),_("��ʮ"),
              _("إһ"),_("إ��"),_("إ��"),_("إ��"),_("إ��"),_("إ��"),_("إ��"),_("إ��"),_("إ��"),_("��ʮ"));
  
  ##############################
  #������ֵ
  #ũ����
  $md=0;
  #ũ����
  $mm=0;
  #���������� ��1900��12��21��
  $total=11;
  #����������
  $mtotal=0;

  ##############################
  #���㵽��������������������-��1900��12��21��ʼ
  #������ĺ�
  for ($y=1901;$y<$year;$y++)
  {
        $total+=365;
        if ($y%4==0) $total ++;
  }
  #�ټӵ���ļ�����
  switch ($month)
  {
           case 12:
                $total+=30;
           case 11:
                $total+=31;
           case 10:
                $total+=30;
           case 9:
                $total+=31;
           case 8:
                $total+=31;
           case 7:
                $total+=30;
           case 6:
                $total+=31;
           case 5:
                $total+=30;
           case 4:
                $total+=31;
           case 3:
                $total+=28;
           case 2:
                $total+=31;
  }

  #������������껹Ҫ��һ��
  if ($year%4==0 and $month>2)
       $total++;

  ##############################
  #��ũ���������ۼ����ж��Ƿ񳬹�����������
  $flag1=0;#�ж�����ѭ��������
  $j=0;
  while ($j<=120)
  {
        $i=1;
        while ($i<=13)
        {
              $mtotal+=$everymonth[$j][$i];
              if ($mtotal>=$total)
              {
                   $flag1=1;
                   break;
              }
              $i++;
        }
        if ($flag1==1) break;
        $j++;
  }

  ##############################
  #���������·�1�ŵ�ũ������
  $md=$everymonth[$j][$i]-($mtotal-$total);

  #�Ƿ��Խһ��
  switch ($month)
  {
           case 1:
           case 3:
           case 5:
           case 7:
           case 8:
           case 10:
           case 12:
                $dd=31;
                break;
           case 4:
           case 6:
           case 9:
           case 11:
                $dd=30;
                break;
           case 2:
                if ($year%4==0)
                {
                    $dd=29;
                }
                else
                {
                    $dd=28;
                }
                break;
  }

  #����1�ŵ����������ָ���յ�ũ����
  $day_i=1;
  while ($day_i<$day)
  {
     $day_i++;
     $md++;
     if ($md>$everymonth[$j][$i])
     {
          $md=1;
          $i++;
     }
     if (($i>12 and $everymonth[$j][0]==0) or ($i>13 and $everymonth[$j][0]<>0))
     {
           $i=1;
           $j++;
     }
  }

  #����ũ����
  if ($everymonth[$j][0]<>0 and $everymonth[$j][0]<$i)
      $mm=$i-1;
  else
      $mm=$i;

  if ($i==$everymonth[$j][0]+1 and $everymonth[$j][0]<>0)
      $chi=$mmonth[0];#��

  $chi.=$mmonth[$mm].$mmonth[13].$mday[$md];
  
  return $chi;
}

function is_festival($mdate)
{
  $mfestival=array(_("���³�һ")=>_("����"),_("����ʮ��")=>_("Ԫ����"),_("���³���")=>_("�����"),_("���³���")=>_("��Ϧ"),_("����ʮ��")=>_("�����"),_("���³���")=>_("������"),_("ʮ���³���")=>_("���˽�"),_("ʮ����إ��")=>_("С��"));
  return $mfestival[$mdate];
}

function is_holiday($date)
{
  $date=explode("-",$date);
  list($year,$month,$date,$H,$M,$S) = DateTimeEx(hexdec(dechex(mktimeEx(0,0,0,$date[1],$date[2],$date[0]))));
  $md=$month.$date;
  $holiday=array("0101"=>_("����Ԫ��"),"0214"=>_("���˽�"),"0308"=>_("��Ů��"),"0312"=>_("ֲ����"),"0315"=>_("������Ȩ����"),"0401"=>_("���˽�"),"0501"=>_("��һ�Ͷ���"),"0504"=>_("�����"),"0601"=>_("��ͯ��"),"0701"=>_("������"),"0801"=>_("������"),"0910"=>_("��ʦ��"),"1001"=>_("�����"),"1224"=>_("ƽ��ҹ"),"1225"=>_("ʥ����"));
  return $holiday[$md];
}

/*************************************************
��������ʱ�����չ����, ����֧��2038����ʱ��ֵ
*************************************************/
/*
   ��;:��ʱ������,����һ��������ʱ��������
   ԭ��:array myTime(int $t);
*/
function DateTimeEx($t) 
{
   //2147483647 = 2^16-1; Ϊphp���ܵ��������
   if($t <= 2147483647){ 
      return explode(',',  date('Y,m,d,H,i,s',$t));
   }
   $t -=   2145888000; // 2038-1-1 0:00:00
   $ds = floor($t / 86400);//����
   $year=2038;
   $month=1;
   $date=1;
   $is_366=false;
   while($ds>=365){
      $is_366 = (0==($year & 3) && $year%100)|| 0==($year%400);
      if($is_366){//����
         if($ds>=366)$ds -= 366;
         else break;
      }else{
         $ds -= 365;
      }
      $year++;
   }
   $days_of_month=array(31,28,31,30,31,30,31,31,30,31,30,31);
   if($is_366){
      $days_of_month[1]=29;
   }
   for($i=0;$i<12;$i++){
      if($ds>=$days_of_month[$i]){
         $month++;
         $ds -= $days_of_month[$i];
      }else{
         $date +=$ds;
         break;
      }
   }

   $mod = $t % 86400;
   $H = floor($mod / 3600);//ʱ
   $mod = $mod % 3600;
   $M = floor($mod / 60);//��
   $S = $mod % 60;//��
   return array($year,$month,$date,$H,$M,$S);
}
/*
   ��;:��ȡʱ������
   ԭ��: int mktimeEx([int $hour[,int $minute[,int $second[,int $month[,int $date[,int $year]]]]]]);

*/
function mktimeEx()
{
   $args = func_get_args();
   switch(func_num_args()){
      case 0:return time();
      case 1:return mktime($args[0]);
      case 2:return mktime($args[0],$args[1]);
      case 3:return mktime($args[0],$args[1],$args[2]);
      case 4:return mktime($args[0],$args[1],$args[2],$args[3]);
      case 5:return mktime($args[0],$args[1],$args[2],$args[3],$args[4]);
      default : if($args[5]<2038) return mktime($args[0],$args[1],$args[2],$args[3],$args[4],$args[5]);
   }
   $t = 2145888000;
   $year = 2038;
   $is_366=false;
   while($year < $args[5]){
      $is_366 = (0==($year & 3) && $year%100)|| 0==($year%400);
      $t += $is_366? 31536000:31622400;
      $year++;
   }
   if($is_366) $months=array(0,2678400,5097600,7776000,10368000,13046400,15638400,18316800,20995200,23587200,26265600,28857600,31536000);
   else   $months=array(0,2678400,5184000,7862400,10454400,13132800,15724800,18403200,21081600,23673600,26352000,28944000,31622400);
   $t += $months[min(11, $args[3]-1)]; 
   $t += 86400 * ($args[4]-1);
   $t += $args[0]*3600 + $args[1]*60 + $args[2];
   return $t;
}

function Solar2LunarDate($solar_date)
{
  $solar_date = explode("-", $solar_date);
  $year = intval($solar_date[0]);
  $month = intval($solar_date[1]);
  $day = intval($solar_date[2]);
  
  if(!checkdate($month, $day, $year))
     return FALSE;
  
  $everymonth=array(
  0=>array(8,0,0,0,0,0,0,0,0,0,0,0,29,30,7,1),
  1=>array(0,29,30,29,29,30,29,30,29,30,30,30,29,0,8,2),
  2=>array(0,30,29,30,29,29,30,29,30,29,30,30,30,0,9,3),
  3=>array(5,29,30,29,30,29,29,30,29,29,30,30,29,30,10,4),
  4=>array(0,30,30,29,30,29,29,30,29,29,30,30,29,0,1,5),
  5=>array(0,30,30,29,30,30,29,29,30,29,30,29,30,0,2,6),
  6=>array(4,29,30,30,29,30,29,30,29,30,29,30,29,30,3,7),
  7=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,4,8),
  8=>array(0,30,29,29,30,30,29,30,29,30,30,29,30,0,5,9),
  9=>array(2,29,30,29,29,30,29,30,29,30,30,30,29,30,6,10),
  10=>array(0,29,30,29,29,30,29,30,29,30,30,30,29,0,7,11),
  11=>array(6,30,29,30,29,29,30,29,29,30,30,29,30,30,8,12),
  12=>array(0,30,29,30,29,29,30,29,29,30,30,29,30,0,9,1),
  13=>array(0,30,30,29,30,29,29,30,29,29,30,29,30,0,10,2),
  14=>array(5,30,30,29,30,29,30,29,30,29,30,29,29,30,1,3),
  15=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,2,4),
  16=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,3,5),
  17=>array(2,30,29,29,30,29,30,30,29,30,30,29,30,29,4,6),
  18=>array(0,30,29,29,30,29,30,29,30,30,29,30,30,0,5,7),
  19=>array(7,29,30,29,29,30,29,29,30,30,29,30,30,30,6,8),
  20=>array(0,29,30,29,29,30,29,29,30,30,29,30,30,0,7,9),
  21=>array(0,30,29,30,29,29,30,29,29,30,29,30,30,0,8,10),
  22=>array(5,30,29,30,30,29,29,30,29,29,30,29,30,30,9,11),
  23=>array(0,29,30,30,29,30,29,30,29,29,30,29,30,0,10,12),
  24=>array(0,29,30,30,29,30,30,29,30,29,30,29,29,0,1,1),
  25=>array(4,30,29,30,29,30,30,29,30,30,29,30,29,30,2,2),
  26=>array(0,29,29,30,29,30,29,30,30,29,30,30,29,0,3,3),
  27=>array(0,30,29,29,30,29,30,29,30,29,30,30,30,0,4,4),
  28=>array(2,29,30,29,29,30,29,29,30,29,30,30,30,30,5,5),
  29=>array(0,29,30,29,29,30,29,29,30,29,30,30,30,0,6,6),
  30=>array(6,29,30,30,29,29,30,29,29,30,29,30,30,29,7,7),
  31=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,8,8),
  32=>array(0,30,30,30,29,30,29,30,29,29,30,29,30,0,9,9),
  33=>array(5,29,30,30,29,30,30,29,30,29,30,29,29,30,10,10),
  34=>array(0,29,30,29,30,30,29,30,29,30,30,29,30,0,1,11),
  35=>array(0,29,29,30,29,30,29,30,30,29,30,30,29,0,2,12),
  36=>array(3,30,29,29,30,29,29,30,30,29,30,30,30,29,3,1),
  37=>array(0,30,29,29,30,29,29,30,29,30,30,30,29,0,4,2),
  38=>array(7,30,30,29,29,30,29,29,30,29,30,30,29,30,5,3),
  39=>array(0,30,30,29,29,30,29,29,30,29,30,29,30,0,6,4),
  40=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,7,5),
  41=>array(6,30,30,29,30,30,29,30,29,29,30,29,30,29,8,6),
  42=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,9,7),
  43=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,10,8),
  44=>array(4,30,29,30,29,30,29,30,29,30,30,29,30,30,1,9),
  45=>array(0,29,29,30,29,29,30,29,30,30,30,29,30,0,2,10),
  46=>array(0,30,29,29,30,29,29,30,29,30,30,29,30,0,3,11),
  47=>array(2,30,30,29,29,30,29,29,30,29,30,29,30,30,4,12),
  48=>array(0,30,29,30,29,30,29,29,30,29,30,29,30,0,5,1),
  49=>array(7,30,29,30,30,29,30,29,29,30,29,30,29,30,6,2),
  50=>array(0,29,30,30,29,30,30,29,29,30,29,30,29,0,7,3),
  51=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,8,4),
  52=>array(5,29,30,29,30,29,30,29,30,30,29,30,29,30,9,5),
  53=>array(0,29,30,29,29,30,30,29,30,30,29,30,29,0,10,6),
  54=>array(0,30,29,30,29,29,30,29,30,30,29,30,30,0,1,7),
  55=>array(3,29,30,29,30,29,29,30,29,30,29,30,30,30,2,8),
  56=>array(0,29,30,29,30,29,29,30,29,30,29,30,30,0,3,9),
  57=>array(8,30,29,30,29,30,29,29,30,29,30,29,30,29,4,10),
  58=>array(0,30,30,30,29,30,29,29,30,29,30,29,30,0,5,11),
  59=>array(0,29,30,30,29,30,29,30,29,30,29,30,29,0,6,12),
  60=>array(6,30,29,30,29,30,30,29,30,29,30,29,30,29,7,1),
  61=>array(0,30,29,30,29,30,29,30,30,29,30,29,30,0,8,2),
  62=>array(0,29,30,29,29,30,29,30,30,29,30,30,29,0,9,3),
  63=>array(4,30,29,30,29,29,30,29,30,29,30,30,30,29,10,4),
  64=>array(0,30,29,30,29,29,30,29,30,29,30,30,30,0,1,5),
  65=>array(0,29,30,29,30,29,29,30,29,29,30,30,29,0,2,6),
  66=>array(3,30,30,30,29,30,29,29,30,29,29,30,30,29,3,7),
  67=>array(0,30,30,29,30,30,29,29,30,29,30,29,30,0,4,8),
  68=>array(7,29,30,29,30,30,29,30,29,30,29,30,29,30,5,9),
  69=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,6,10),
  70=>array(0,30,29,29,30,29,30,30,29,30,30,29,30,0,7,11),
  71=>array(5,29,30,29,29,30,29,30,29,30,30,30,29,30,8,12),
  72=>array(0,29,30,29,29,30,29,30,29,30,30,29,30,0,9,1),
  73=>array(0,30,29,30,29,29,30,29,29,30,30,29,30,0,10,2),
  74=>array(4,30,30,29,30,29,29,30,29,29,30,30,29,30,1,3),
  75=>array(0,30,30,29,30,29,29,30,29,29,30,29,30,0,2,4),
  76=>array(8,30,30,29,30,29,30,29,30,29,29,30,29,30,3,5),
  77=>array(0,30,29,30,30,29,30,29,30,29,30,29,29,0,4,6),
  78=>array(0,30,29,30,30,29,30,30,29,30,29,30,29,0,5,7),
  79=>array(6,30,29,29,30,29,30,30,29,30,30,29,30,29,6,8),
  80=>array(0,30,29,29,30,29,30,29,30,30,29,30,30,0,7,9),
  81=>array(0,29,30,29,29,30,29,29,30,30,29,30,30,0,8,10),
  82=>array(4,30,29,30,29,29,30,29,29,30,29,30,30,30,9,11),
  83=>array(0,30,29,30,29,29,30,29,29,30,29,30,30,0,10,12),
  84=>array(10,30,29,30,30,29,29,30,29,29,30,29,30,30,1,1),
  85=>array(0,29,30,30,29,30,29,30,29,29,30,29,30,0,2,2),
  86=>array(0,29,30,30,29,30,30,29,30,29,30,29,29,0,3,3),
  87=>array(6,30,29,30,29,30,30,29,30,30,29,30,29,29,4,4),
  88=>array(0,30,29,30,29,30,29,30,30,29,30,30,29,0,5,5),
  89=>array(0,30,29,29,30,29,29,30,30,29,30,30,30,0,6,6),
  90=>array(5,29,30,29,29,30,29,29,30,29,30,30,30,30,7,7),
  91=>array(0,29,30,29,29,30,29,29,30,29,30,30,30,0,8,8),
  92=>array(0,29,30,30,29,29,30,29,29,30,29,30,30,0,9,9),
  93=>array(3,29,30,30,29,30,29,30,29,29,30,29,30,29,10,10),
  94=>array(0,30,30,30,29,30,29,30,29,29,30,29,30,0,1,11),
  95=>array(8,29,30,30,29,30,29,30,30,29,29,30,29,30,2,12),
  96=>array(0,29,30,29,30,30,29,30,29,30,30,29,29,0,3,1),
  97=>array(0,30,29,30,29,30,29,30,30,29,30,30,29,0,4,2),
  98=>array(5,30,29,29,30,29,29,30,30,29,30,30,29,30,5,3),
  99=>array(0,30,29,29,30,29,29,30,29,30,30,30,29,0,6,4),
  100=>array(0,30,30,29,29,30,29,29,30,29,30,30,29,0,7,5),
  101=>array(4,30,30,29,30,29,30,29,29,30,29,30,29,30,8,6),
  102=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,9,7),
  103=>array(0,30,30,29,30,30,29,30,29,29,30,29,30,0,10,8),
  104=>array(2,29,30,29,30,30,29,30,29,30,29,30,29,30,1,9),
  105=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,2,10),
  106=>array(7,30,29,30,29,30,29,30,29,30,30,29,30,30,3,11),
  107=>array(0,29,29,30,29,29,30,29,30,30,30,29,30,0,4,12),
  108=>array(0,30,29,29,30,29,29,30,29,30,30,29,30,0,5,1),
  109=>array(5,30,30,29,29,30,29,29,30,29,30,29,30,30,6,2),
  110=>array(0,30,29,30,29,30,29,29,30,29,30,29,30,0,7,3),
  111=>array(0,30,29,30,30,29,30,29,29,30,29,30,29,0,8,4),
  112=>array(4,30,29,30,30,29,30,29,30,29,30,29,30,29,9,5),
  113=>array(0,30,29,30,29,30,30,29,30,29,30,29,30,0,10,6),
  114=>array(9,29,30,29,30,29,30,29,30,30,29,30,29,30,1,7),
  115=>array(0,29,30,29,29,30,29,30,30,30,29,30,29,0,2,8),
  116=>array(0,30,29,30,29,29,30,29,30,30,29,30,30,0,3,9),
  117=>array(6,29,30,29,30,29,29,30,29,30,29,30,30,30,4,10),
  118=>array(0,29,30,29,30,29,29,30,29,30,29,30,30,0,5,11),
  119=>array(0,30,29,30,29,30,29,29,30,29,29,30,30,0,6,12),
  120=>array(4,29,30,30,30,29,30,29,29,30,29,30,29,30,7,1)
  );
  
  ##############################
  #������ֵ
  #ũ����
  $md=0;
  #ũ����
  $mm=0;
  #���������� ��1900��12��21��
  $total=11;
  #����������
  $mtotal=0;

  ##############################
  #���㵽��������������������-��1900��12��21��ʼ
  #������ĺ�
  for ($y=1901;$y<$year;$y++)
  {
        $total+=365;
        if ($y%4==0) $total ++;
  }
  #�ټӵ���ļ�����
  switch ($month)
  {
           case 12:
                $total+=30;
           case 11:
                $total+=31;
           case 10:
                $total+=30;
           case 9:
                $total+=31;
           case 8:
                $total+=31;
           case 7:
                $total+=30;
           case 6:
                $total+=31;
           case 5:
                $total+=30;
           case 4:
                $total+=31;
           case 3:
                $total+=28;
           case 2:
                $total+=31;
  }

  #������������껹Ҫ��һ��
  if ($year%4==0 and $month>2)
       $total++;

  ##############################
  #��ũ���������ۼ����ж��Ƿ񳬹�����������
  $flag1=0;#�ж�����ѭ��������
  $j=0;
  while ($j<=120)
  {
        $i=1;
        while ($i<=13)
        {
              $mtotal+=$everymonth[$j][$i];
              if ($mtotal>=$total)
              {
                   $flag1=1;
                   break;
              }
              $i++;
        }
        if ($flag1==1) break;
        $j++;
  }

  ##############################
  #���������·�1�ŵ�ũ������
  $md=$everymonth[$j][$i]-($mtotal-$total);

  #�Ƿ��Խһ��
  switch ($month)
  {
           case 1:
           case 3:
           case 5:
           case 7:
           case 8:
           case 10:
           case 12:
                $dd=31;
                break;
           case 4:
           case 6:
           case 9:
           case 11:
                $dd=30;
                break;
           case 2:
                if ($year%4==0)
                {
                    $dd=29;
                }
                else
                {
                    $dd=28;
                }
                break;
  }

  #����1�ŵ����������ָ���յ�ũ����
  $day_i=1;
  while ($day_i<$day)
  {
     $day_i++;
     $md++;
     if ($md>$everymonth[$j][$i])
     {
          $md=1;
          $i++;
     }
     if (($i>12 and $everymonth[$j][0]==0) or ($i>13 and $everymonth[$j][0]<>0))
     {
           $i=1;
           $j++;
     }
  }

  #����ũ����
  if ($everymonth[$j][0]<>0 and $everymonth[$j][0]<$i)
      $mm=$i-1;
  else
      $mm=$i;

  if($mm >= $month)
     $year--;

//  if ($i==$everymonth[$j][0]+1 and $everymonth[$j][0]<>0)
//      $chi=$mmonth[0];#��

  return $year."-".$mm."-".$md;
}
?>