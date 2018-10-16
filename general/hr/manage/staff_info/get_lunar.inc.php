<?
include_once("inc/utility_all.php");
include_once("inc/lunar.class.php");
if($STAFF_BIRTH!="0000-00-00" && $STAFF_BIRTH!=""){
		//从lunar.php中获取生肖
		$animal = get_animal($STAFF_BIRTH,$IS_LUNAR);
		//从lunar.php中获取星座
		$sign = get_zodiac_sign($STAFF_BIRTH,$IS_LUNAR);
}
echo $animal."!".$sign;
?>