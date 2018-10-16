<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("设置项目审批权限");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?= _("设置审批人员") ?> </span><br>
            </td>
        </tr>
    </table>

    <div align="center">
        <input type="button"  value="<?= _("设置审批人员") ?>" class="BigButton" onClick="location = 'new.php';" title="<?= _("设置审批人员") ?>">&nbsp;&nbsp;
        <input type="button"  value="<?= _("设置免审批范围") ?>" class="BigButton" onClick="location = 'exempt.php';" title="<?= _("设置免审批范围") ?>">
    </div>
    <br>
    <table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
        <tr>
            <td background="<?= MYOA_STATIC_SERVER ?>/static/images/dian1.gif" width="100%"></td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?= _("管理审批规则") ?></span>
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
                    <td nowrap align="center"><?= _("审批人员") ?></td>
                    <td nowrap align="center"><?= _("管理部门") ?></td>
                    <td nowrap align="center"><?= _("操作") ?></td>
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
                $DEPT_NAME = _("全体部门");
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
                    <a href="new.php?PRIV_ID=<?= $PRIV_ID ?>"><?= _("编辑") ?></a>&nbsp;&nbsp;<a href="delete.php?PRIV_ID=<?= $PRIV_ID ?>"><?= _("删除") ?></a>    	 
                </td>
            </tr>
            <?
        }
        ?>
        </table>
        
            <?
    if ($MANAGER_COUNT == 0)
    {
        Message("", _("没有记录"));
    }
    ?>
    </div>
    
   <br/>
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?= _("免审批规则") ?></span>
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
            $DEPT_NAME_STR = _("全体部门");
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
                    <td nowrap align="center" colspan="2"><?= _("免审批") ?></td>
                    
            </tr>
              <?
                    }else{
                       Message("", _("没有免审批记录")); 
                    }
              ?>
            <!--部门-->
           <?
           if(!empty($DEPT_NAME_STR))
           {
           ?>
           <tr class="TableLine1">
               <td align="left" width="10%"><?= _("部门:") ?></td>
                <td align="left" width="90%"><?= $DEPT_NAME_STR ?></td>
           </tr>
           <?
           }
           ?>
           
           <!--角色-->
           <?
           if(!empty($USER_PRIV_STR))
           {
           ?>
           <tr class="TableLine2">
                <td align="left" width="10%"><?= _("角色:") ?></td>
                <td align="left" width="90%"><?= $USER_PRIV_STR ?></td>
           </tr>
           <?
           }
           ?>
           <!--人员-->
           <?
           if(!empty($USER_NAME_STR))
           {
           ?>
           <tr class="TableLine1">
                <td align="left" width="10%"><?= _("人员:") ?></td>
                <td align="left" width="90%"><?= $USER_NAME_STR ?></td>
           </tr>
           <?
           }
           ?>
       </table> 
   </div>

</body>
</html>