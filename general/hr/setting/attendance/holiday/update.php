<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if(!is_date($BEGIN_DATE))
{ Message(_("����"),_("��ʼ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
  Button_Back();
  exit;
}

if(!is_date($END_DATE))
{ Message(_("����"),_("�������ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
  Button_Back();
  exit;
}

if(strtotime($BEGIN_DATE) > strtotime($END_DATE))
{ Message(_("����"),_("��ʼ���ڲ��ܴ��ڽ�������"));
  Button_Back();
  exit;
}

$query="update ATTEND_HOLIDAY set BEGIN_DATE='$BEGIN_DATE',END_DATE='$END_DATE',HOLIDAY_NAME='$HOLIDAY_NAME' where HOLIDAY_ID='$HOLIDAY_ID'";
exequery(TD::conn(),$query);

header("location: index.php?connstatus=1");
?>
</body>
</html>