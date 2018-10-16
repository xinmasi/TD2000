<?
$COUNT=0;
$USER_ID_STR = "";

//考勤管理人员 
$query = "select MANAGERS from ATTEND_MANAGER where find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',DEPT_ID_STR) or DEPT_ID_STR='ALL_DEPT'";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
   $MANAGERS.=$ROW["MANAGERS"];

//本部门考勤管理人员
$query = "SELECT USER_ID,USER_NAME from USER,USER_PRIV where DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."' and  find_in_set(USER.USER_ID,'$MANAGERS') and USER.USER_PRIV=USER_PRIV.USER_PRIV order by PRIV_NO,USER_NO,USER_NAME";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];

    if($_SESSION["LOGIN_USER_ID"]==$USER_ID)
       continue;

    $USER_ID_STR .= $USER_ID.",";
    $COUNT++;
?>
 <option value="<?=$USER_ID?>"><?=$USER_NAME?></option>
<?
}

//其他部门考勤管理人员
$query = "SELECT * from USER,USER_PRIV where DEPT_ID!='".$_SESSION["LOGIN_DEPT_ID"]."' and find_in_set(USER.USER_ID,'$MANAGERS') and USER.USER_PRIV=USER_PRIV.USER_PRIV order by PRIV_NO,USER_NO,USER_NAME";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
//    if(find_id($USER_ID_STR,$USER_ID))
//       continue;
    if(find_id($USER_ID_STR,$USER_ID)||$_SESSION["LOGIN_USER_ID"]==$USER_ID )
       continue;

    $USER_ID_STR .= $USER_ID.",";
    $COUNT++;
?>
    <option value="<?=$USER_ID?>"><?=$USER_NAME?></option>

<?
}
//本部门主管
if($COUNT==0)
{
   $query = "SELECT MANAGER from DEPARTMENT where DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'";
   $cursor=exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $DEPT_MANAGER = $ROW["MANAGER"];
   
   $query = "SELECT USER_NAME,USER_ID from USER,USER_PRIV where find_in_set(USER.USER_ID,'$DEPT_MANAGER') and USER.USER_PRIV=USER_PRIV.USER_PRIV order by PRIV_NO,USER_NO,USER_NAME";
   $cursor=exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
       $USER_ID=$ROW["USER_ID"];
       $USER_NAME=$ROW["USER_NAME"];
   
       if($_SESSION["LOGIN_USER_ID"]==$USER_ID || find_id($USER_ID_STR,$USER_ID))
          continue;
   
       $USER_ID_STR .= $USER_ID.",";
       $COUNT++;
?>
       <option value="<?=$USER_ID?>"><?=$USER_NAME?></option>
<?
   }
}
//既没有设置考勤人员，也没有设置部门主管
if($COUNT==0 && $_SESSION["LOGIN_USER_PRIV"]!='1')
{
   $query = "SELECT * from USER_PRIV where USER_PRIV='".$_SESSION["LOGIN_USER_PRIV"]."'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $PRIV_NO=$ROW["PRIV_NO"];

   $query = "SELECT * from USER,USER_PRIV where USER.DEPT_ID!='0' and USER.DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."' and USER.USER_PRIV=USER_PRIV.USER_PRIV and PRIV_NO < '$PRIV_NO' and USER_PRIV.USER_PRIV!='1' order by PRIV_NO,USER_NO,USER_NAME";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID=$ROW["USER_ID"];
      $USER_NAME=$ROW["USER_NAME"];
      if($_SESSION["LOGIN_USER_ID"]==$USER_ID)
         continue;

?>
      <option value="<?=$USER_ID?>"><?=$USER_NAME?></option>
<?
   }
}

if($COUNT==0 && !find_id($USER_ID_STR,"admin"))
{
?>
   <option value="admin"><?=_("系统管理员")?></option>
<?
}
?>