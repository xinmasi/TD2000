<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("������Ŀ����Ȩ��");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?= _("����������Ա") ?> </span><br>
            </td>
        </tr>
    </table>

    <div align="center">
        <input type="button"  value="<?= _("����������Ա") ?>" class="BigButton" onClick="location = 'new.php';" title="<?= _("����������Ա") ?>">&nbsp;&nbsp;
        <input type="button"  value="<?= _("������������Χ") ?>" class="BigButton" onClick="location = 'exempt.php';" title="<?= _("������������Χ") ?>">
    </div>
    <br>
    <table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
        <tr>
            <td background="<?= MYOA_STATIC_SERVER ?>/static/images/dian1.gif" width="100%"></td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?= _("������������") ?></span>
            </td>
        </tr>
    </table>
    <br>
    <div align="center">
        <table class="TableList"  width="600"  align="center" >
        <?
        $query = "SELECT * from PROJ_PRIV WHERE PRIV_CODE='APPROVE'";
        $cursor = exequery(TD::conn(), $query);
        $MANAGER_COUNT = 0;
        while ($ROW = mysql_fetch_array($cursor))
        {
            $MANAGER_COUNT++;
            $MANAGER_NAME = $DEPT_NAME = "";
            if ($MANAGER_COUNT == 1)
            {
                ?>
                <tr class="TableHeader">
                    <td nowrap align="center"><?= _("������Ա") ?></td>
                    <td nowrap align="center"><?= _("������") ?></td>
                    <td nowrap align="center"><?= _("����") ?></td>
                </tr>
                <?
            }

            $PRIV_ID = $ROW["PRIV_ID"];
            $PRIV_USER = $ROW["PRIV_USER"];

            $PRIV_USER = explode("|", $PRIV_USER);
            $USER_ID_STR = $PRIV_USER[0];
            $DEPT_ID_STR = $PRIV_USER[1];

            $DEPT_NAME = "";
            if ($DEPT_ID_STR  == "ALL_DEPT")
            {
                $DEPT_NAME = _("ȫ�岿��");
            }
            else
            {
                $DEPT_NAME = td_trim(GetDeptNameById($DEPT_ID_STR));
            }
            $MANAGER_NAME = td_trim(GetUserNameById($USER_ID_STR));

            if ($MANAGER_COUNT % 2 == 1)
            {
                $TableLine = "TableLine1";
            }
            else
            {
                $TableLine = "TableLine2";
            }
            ?>
            <tr class="<?= $TableLine ?>">
                <td align="left" width="50%"><?= $MANAGER_NAME ?></td>
                <td align="left" width="50%"><?= $DEPT_NAME ?></td>
                <td align="left" width="50%" nowrap>
                    <a href="new.php?PRIV_ID=<?= $PRIV_ID ?>"><?= _("�༭") ?></a>&nbsp;&nbsp;<a href="delete.php?PRIV_ID=<?= $PRIV_ID ?>"><?= _("ɾ��") ?></a>    	 
                </td>
            </tr>
            <?
        }
        ?>
        </table>
        
            <?
    if ($MANAGER_COUNT == 0)
    {
        Message("", _("û�м�¼"));
    }
    ?>
    </div>
    
   <br/>
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?= _("����������") ?></span>
            </td>
        </tr>
    </table>
   
   <div  align="center">
              <?
    $query = "SELECT PRIV_USER from PROJ_PRIV WHERE PRIV_CODE='NOAPP'";
    $cursor = exequery(TD::conn(), $query);
    if ($ROW = mysql_fetch_array($cursor))
    {
        $PRIV_USER = $ROW["PRIV_USER"];
        $PRIV_USER = explode("|", $PRIV_USER);
        $DEPT_ID_STR = $PRIV_USER[0];
        $PRIV_ID_STR = $PRIV_USER[1];
        $USER_ID_STR = $PRIV_USER[2];

        $DEPT_NAME_STR = "";
        if ($DEPT_ID_STR  == "ALL_DEPT")
        {
            $DEPT_NAME_STR = _("ȫ�岿��");
        }
        else
        {
            $DEPT_NAME_STR = GetDeptNameById($DEPT_ID_STR);
        }
        $USER_PRIV_STR = GetPrivNameById($PRIV_ID_STR);
        $USER_NAME_STR = GetUserNameById($USER_ID_STR);
    }
    ?>
       <table class="TableList"  width="600"  align="center">
           <?
           if(!empty($DEPT_NAME_STR)||!empty($USER_PRIV_STR)||!empty($USER_NAME_STR))
           {
           ?>
           <tr class="TableHeader">
                    <td nowrap align="center" colspan="2"><?= _("������") ?></td>
                    
            </tr>
              <?
                    }else{
                       Message("", _("û����������¼")); 
                    }
              ?>
            <!--����-->
           <?
           if(!empty($DEPT_NAME_STR))
           {
           ?>
           <tr class="TableLine1">
               <td align="left" width="10%"><?= _("����:") ?></td>
                <td align="left" width="90%"><?= $DEPT_NAME_STR ?></td>
           </tr>
           <?
           }
           ?>
           
           <!--��ɫ-->
           <?
           if(!empty($USER_PRIV_STR))
           {
           ?>
           <tr class="TableLine2">
                <td align="left" width="10%"><?= _("��ɫ:") ?></td>
                <td align="left" width="90%"><?= $USER_PRIV_STR ?></td>
           </tr>
           <?
           }
           ?>
           <!--��Ա-->
           <?
           if(!empty($USER_NAME_STR))
           {
           ?>
           <tr class="TableLine1">
                <td align="left" width="10%"><?= _("��Ա:") ?></td>
                <td align="left" width="90%"><?= $USER_NAME_STR ?></td>
           </tr>
           <?
           }
           ?>
       </table> 
   </div>

</body>
</html>