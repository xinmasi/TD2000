<?
include_once("inc/utility_all.php");
include_once("inc/lunar.class.php");
if($STAFF_BIRTH!="0000-00-00" && $STAFF_BIRTH!=""){
		//��lunar.php�л�ȡ��Ф
		$animal = get_animal($STAFF_BIRTH,$IS_LUNAR);
		//��lunar.php�л�ȡ����
		$sign = get_zodiac_sign($STAFF_BIRTH,$IS_LUNAR);
}
echo $animal."!".$sign;
?>