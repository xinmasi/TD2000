<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("�����ѯ");
include_once("inc/header.inc.php");
?>

<script>
function delete_borrow(BORROW_ID,TO_ID,BOOK_NO,BOOK_STATUS1,START_B,END_B)
{
    msg='<?=_("�˲����������������ϵ�ɾ�������Ե���ʷ��¼��ѯ�в鵽")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?BORROW_ID=" + BORROW_ID + "&TO_ID=" + TO_ID + "&BOOK_NO=" + BOOK_NO + "&BOOK_STATUS1=" + BOOK_STATUS1 + "&START_B=" + START_B + "&END_B=" + END_B;
        window.location=URL;
    }
}
</script>

<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d",time());
//----------- �Ϸ���У�� ---------
if($START_B!="")
{
    $TIME_OK=is_date($START_B);
    if(!$TIME_OK)
    {
        $MSG1 = sprintf(_("��ʼʱ��ĸ�ʽ���ԣ�Ӧ����%s"), $CUR_DATE);
        Message(_("����"),$MSG1);
        Button_Back();
        exit;
    }
}

if($END_B!="")
{
    $TIME_OK=is_date($END_B);
    if(!$TIME_OK)
    {
        $MSG2 = sprintf(_("��ʼʱ��ĸ�ʽ���ԣ�Ӧ����%s"), $CUR_DATE);
        Message(_("����"),$MSG2);
        Button_Back();
        exit;
    }
}

$query1 = "SELECT * from BOOK_MANAGER where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MANAGER_ID)";
$cursor1= exequery(TD::conn(),$query1);
while($ROW1=mysql_fetch_array($cursor1))
{
    $MANAGE_DEPT_ID.=$ROW1["MANAGE_DEPT_ID"];
}
   
//-----------����֯SQL���-----------
if($TO_ID!="")
    $WHERE_STR.=" and BUSER_ID='$TO_ID'";
if($BOOK_NO!="")
    $WHERE_STR.=" and BOOK_NO='$BOOK_NO'";
if($START_B!="")
    $WHERE_STR.=" and BORROW_DATE>='$START_B'";
if($END_B!="")
    $WHERE_STR.=" and BORROW_DATE<='$END_B'";   
if($BOOK_STATUS1=="1")
    $WHERE_STR.=" and BOOK_STATUS='1' and STATUS='1'";
if($BOOK_STATUS1=="0")
    $WHERE_STR.=" and ((BOOK_STATUS='0' and STATUS='1') or (BOOK_STATUS='1' and STATUS='0'))";
if($BOOK_STATUS1=="")
    $WHERE_STR.=" and ((BOOK_STATUS='0' and STATUS='1') or (BOOK_STATUS='1' and STATUS='0') or (BOOK_STATUS='1' and STATUS='1'))";   

$query="SELECT * from BOOK_MANAGE where DELETE_FLAG='0' and STATUS!='2'".$WHERE_STR." order by RETURN_DATE desc";
$cursor= exequery(TD::conn(),$query);
$BOOK_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $BOOK_NO1=$ROW["BOOK_NO"];  
    
    $query2 = "SELECT DEPT from BOOK_INFO where BOOK_NO='$BOOK_NO1'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW2=mysql_fetch_array($cursor2))
    {
        $DEPT=$ROW2["DEPT"];
    }
    
    if(!find_id($MANAGE_DEPT_ID,$DEPT)  && $MANAGE_DEPT_ID!="ALL_DEPT")
    {
        continue;
    }
    
    $BOOK_COUNT++;    	
}

if($BOOK_COUNT==0)
{
    Message(_("��ʾ"),_("û�з��������Ľ����¼"));
?>
    <br>
    <div align="center">
        <input type="button"  value="<?=_("����")?>" class="BigButton" onClick="location='index.php';">
    </div>
<?
    exit;
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/book.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�����ѯ")?> </span><br>
        </td>
    </tr>
</table>

<table class="TableList"  width="95%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("������")?></td>
        <td nowrap align="center"><?=_("ͼ����")?></td>
        <td nowrap align="center"><?=_("����")?></td>
        <td nowrap align="center"><?=_("��������")?></td>
        <td nowrap align="center"><?=_("��������")?></td>
        <td nowrap align="center"><?=_("ʵ������")?></td>
        <td nowrap align="center"><?=_("�Ǽ���")?></td>
        <td nowrap align="center"><?=_("״̬")?></td>
        <td nowrap align="center"><?=_("��ע")?></td>
        <td nowrap align="center"><?=_("����")?></td>
    </tr>

<?   
//echo $query1=str_replace("BOOK_NO","*",$query);
$cursor1 = exequery(TD::conn(), $query);

$BOOK_COUNT = 0;
while($ROW=mysql_fetch_array($cursor1))
{
    $BOOK_COUNT++;
    $BORROW_ID          = $ROW["BORROW_ID"];
    $BUSER_ID           = $ROW["BUSER_ID"];
    $BOOK_NO1           = $ROW["BOOK_NO"];
    $BORROW_DATE        = $ROW["BORROW_DATE"];
    $RUSER_ID           = $ROW["RUSER_ID"];
    $RETURN_DATE        = $ROW["RETURN_DATE"];
    $BOOK_STATUS2       = $ROW["BOOK_STATUS"];
    $STATUS             = $ROW["STATUS"];
    $BORROW_REMARK      = $ROW["BORROW_REMARK"];
    $REAL_RETURN_TIME   = ($ROW["REAL_RETURN_TIME"] != "0000-00-00") ? $ROW["REAL_RETURN_TIME"] : "-";
    
    $query2 = "SELECT DEPT from BOOK_INFO where BOOK_NO='$BOOK_NO1'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW2=mysql_fetch_array($cursor2))
    {
        $DEPT=$ROW2["DEPT"];
    }
    
    if(!find_id($MANAGE_DEPT_ID,$DEPT) && $MANAGE_DEPT_ID!="ALL_DEPT")
    {
        continue;
    }
    
    $query2="select USER_NAME from USER where USER_ID='$BUSER_ID'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW2=mysql_fetch_array($cursor2))
    {
        $USER_NAME1=$ROW2["USER_NAME"];
    }
    
    $query2="select USER_NAME from USER where USER_ID='$RUSER_ID'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW2=mysql_fetch_array($cursor2))
    {
        $USER_NAME2=$ROW2["USER_NAME"];
    }
    
    $query2="select BOOK_NAME from BOOK_INFO where BOOK_NO='$BOOK_NO1'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW2=mysql_fetch_array($cursor2))
    {
        $BOOK_NAME=$ROW2["BOOK_NAME"];
    }
    
    if($BOOK_STATUS2=='1' and $STATUS=='1')
        $BOOK_STATUS_DESC=_("�ѻ�");
    if(($BOOK_STATUS2=='0' and $STATUS=='1') or ($BOOK_STATUS2=='1' and $STATUS=='0'))
        $BOOK_STATUS_DESC=_("δ��");
    
    if($BOOK_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
        <td nowrap align="center"><?=$USER_NAME1?></td>
        <td nowrap align="center"><?=$BOOK_NO1?></td>
        <td nowrap align="center"><?=$BOOK_NAME?></td>
        <td nowrap align="center"><?=$BORROW_DATE?></td>
        <td nowrap align="center"><?=$RETURN_DATE?></td>
        <td nowrap align="center"><?=$REAL_RETURN_TIME?></td>
        <td nowrap align="center"><?=$USER_NAME2?></td>
        <td nowrap align="center"><?=$BOOK_STATUS_DESC?></td>
        <td nowrap align="center"><?=$BORROW_REMARK?></td>        
<?
if(($BOOK_STATUS2=='0' and $STATUS=='1') or ($BOOK_STATUS2=='1' and $STATUS=='0'))
{
?>
        <td nowrap align="center"><a href="manage.php?BORROW_ID=<?=$BORROW_ID?>&BOOK_NO1=<?=$BOOK_NO1?>&TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BOOK_STATUS1=<?=$BOOK_STATUS1?>&START_B=<?=$START_B?>&END_B=<?=$END_B?>"><?=_("����")?></a></td>
<?
}
if($BOOK_STATUS2=='1' and $STATUS=='1')
{
?>
        <td nowrap align="center">
            <a href="javascript:delete_borrow('<?=$BORROW_ID?>','<?=$TO_ID?>','<?=$BOOK_NO?>','<?=$BOOK_STATUS1?>','<?=$START_B?>','<?=$END_B?>');"> <?=_("ɾ������Ϊ��ʷ��¼")?></a>
        </td>
<?
}
?>
    </tr>
<?
}//while
?>
</table>

<br>
<div align="center">
    <input type="button"  value="<?=_("����")?>" class="BigButton" onClick="location='index.php';">
</div>

</body>
</html>