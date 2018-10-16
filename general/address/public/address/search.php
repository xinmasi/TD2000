<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("通讯簿");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script><script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<body class="bodycolor">
<div class="PageHeader"></div>
<table class="TableTop" width="650">
   <tr>
      <td class="left"></td>
      <td class="center subject">
         <?=_("联系人查询")?>
      </td>
      <td class="right"></td>
   </tr>
</table>
<table class="TableBlock no-top-border" width="650">
  <form action="search_submit.php" name="form1" method="post">
    <tr>
    <td nowrap class="TableData"><?=_("分组：")?></td>
    <td nowrap class="TableData">
        <select name="GROUP_ID" class="BigSelect">
<?
      $query = "SELECT * from ADDRESS_GROUP where USER_ID='' order by ORDER_NO asc,GROUP_NAME asc";
      $cursor= exequery(TD::conn(),$query);
?>
          <option value="ALL_DEPT" selected><?=_("所有")?></option>
          <option value="0"><?=_("默认")?></option>
<?
      while($ROW=mysql_fetch_array($cursor))
      {
         $GROUP_ID=$ROW["GROUP_ID"];
         $GROUP_NAME=$ROW["GROUP_NAME"];
         $PRIV_DEPT=$ROW["PRIV_DEPT"];
         $PRIV_ROLE=$ROW["PRIV_ROLE"];
         $PRIV_USER=$ROW["PRIV_USER"];
      	 if($PRIV_DEPT!="ALL_DEPT")
      	 {
      	    if(!find_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID"]) && !find_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV"]) && !find_id($PRIV_USER,$_SESSION["LOGIN_USER_ID"]) && !check_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV_OTHER"],true)!="" && !check_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID_OTHER"],true)!="")
      	    {
      	       continue;
      	    }
         }
?>
          <option value="<?=$GROUP_ID?>"><?=$GROUP_NAME?></option>
<?
      }
?>
        </select>
    </td>
   </tr>
    <tr>
      <td nowrap class="TableData"><?=_("姓名：")?></td>
      <td class="TableData"><input type="text" name="PSN_NAME" size="10" class="BigInput"></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("性别：")?></td>
      <td class="TableData">
        <select name="SEX" class="BigSelect">
          <option value="ALL"><?=_("所有")?></option>
          <option value="0"><?=_("男")?></option>
          <option value="1"><?=_("女")?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("生日：")?></td>
      <td class="TableData">
      	<?=_("从")?>
      	<input type="text" name="B_BIRTHDAY" size="10" class="BigInput" onClick="WdatePicker()">
     
        <?=_("至")?>
        <input type="text" name="E_BIRTHDAY" size="10" class="BigInput" onClick="WdatePicker()">
   
     </td>
    </tr>    
    <tr>
      <td nowrap class="TableData"> <?=_("昵称：")?></td>
      <td class="TableData">
        <input type="text" name="NICK_NAME" size="25" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("单位名称：")?></td>
      <td class="TableData">
        <input type="text" name="DEPT_NAME" size="25" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("工作电话：")?></td>
      <td class="TableData">
        <input type="text" name="TEL_NO_DEPT" size="25" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("单位地址：")?></td>
      <td class="TableData">
        <input type="text" name="ADD_DEPT" size="25" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("家庭电话：")?></td>
      <td class="TableData">
        <input type="text" name="TEL_NO_HOME" size="25" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("家庭住址：")?></td>
      <td class="TableData">
        <input type="text" name="ADD_HOME" size="25" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("手机：")?></td>
      <td class="TableData">
        <input type="text" name="MOBIL_NO" size="25" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("备注：")?></td>
      <td class="TableData">
        <input type="text" name="NOTES" size="25" class="BigInput">
      </td>
    <tr>
      <td class="TableData" colspan="2">
        <?=get_field_table(get_field_html("ADDRESS",""))?>
      </td>
    </tr>
    </tr>
      <td nowrap class="TableControl" colspan="2" align="center">
          <input type="submit" value="<?=_("查询")?>" class="BigButton" title="<?=_("进行查询")?>" name="button">
      </td>
    </tr>
    </form>
  </table>