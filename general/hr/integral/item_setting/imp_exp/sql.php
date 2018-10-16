<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
ob_implicit_flush();
echo str_repeat(' ', 512); // flush the cache of ie
function split_sql_file($sql, $delimiter = ';')
{
        $sql               = trim($sql);
        $char              = '';
        $last_char         = '';
        $ret               = array();
        $string_start      = '';
        $in_string         = FALSE;
        $escaped_backslash = FALSE;

        for ($i = 0; $i < strlen($sql); ++$i) {
            $char = $sql[$i];

            // if delimiter found, add the parsed part to the returned array
            if ($char == $delimiter && !$in_string) {
                $ret[]     = substr($sql, 0, $i);
                $sql       = substr($sql, $i + 1);
                $i         = 0;
                $last_char = '';
            }

            if ($in_string) {
                // We are in a string, first check for escaped backslashes
                if ($char == '\\') {
                    if ($last_char != '\\') {
                        $escaped_backslash = FALSE;
                    } else {
                        $escaped_backslash = !$escaped_backslash;
                    }
                }
                // then check for not escaped end of strings except for
                // backquotes than cannot be escaped
                if (($char == $string_start)
                    && ($char == '`' || !(($last_char == '\\') && !$escaped_backslash))) {
                    $in_string    = FALSE;
                    $string_start = '';
                }
            } else {
                // we are not in a string, check for start of strings
                if (($char == '"') || ($char == '\'') || ($char == '`')) {
                    $in_string    = TRUE;
                    $string_start = $char;
                }
            }
            $last_char = $char;
        } // end for

        // add any rest to the returned array
        if (!empty($sql)) {
            $ret[] = $sql;
        }
        return $ret;
} // end of the 'split_sql_file()' function

$HTML_PAGE_TITLE = _("积分项恢复");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/system.gif" align="absmiddle"><span class="big3"> <?=_("积分项恢复")?></span><br>
    </td>
  </tr>
</table>

<br>

<?
$tables = @fread($fp = td_fopen($sql_file, 'r'), filesize($sql_file));
@fclose($fp);

if (!$tables)
{
  Message("",_("对不起，无法读取积分项恢复导入文件 $sql_file 。"));
  exit;
}

if (stristr($tables,"CREATE TABLE `address`")||stristr($tables,"CREATE TABLE `user`"))
{
  Message(_("提示"),_("您要导入的可能不是积分项文件。"));
  Button_Back();
  exit;
}

$querys = split_sql_file($tables);

$ERROR_COUNT=0;
foreach ( $querys as $query )
{

	preg_match('/CREATE TABLE `([a-z0-9_]+)`/i', $query, $tmp);

	$tableName = $tmp[1];

	if(exequery(TD::conn(),$query))
	{

	   if($tableName)
	      Message("",_("成功建立表格 $tableName"));

	}
	else
	{
	   Message(_("积分项恢复出现错误"),_("错误原因：") . mysql_error()._("<br><br>错误语句：$query"));
	   $ERROR_COUNT++;
	   //Button_Back();
	   //exit;
	}
}

$STR=_("积分项恢复完成！");

if($ERROR_COUNT>0)
   $STR.="<br>".sprintf(_("共发生%d次错误！"),$ERROR_COUNT);
else
   $STR.="<br>"._("全部积分项恢复成功！");
Message("",$STR);
Button_Back();
?>
