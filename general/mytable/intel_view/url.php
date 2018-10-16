<?
$COUNT_PUBLIC = 0;
$COUNT_PRIVATE = 0;
$PUBLIC_STR = '';
$PRIVATE_STR = '';
$query = "SELECT * from URL where URL_TYPE='' and (USER='' or USER='".$_SESSION["LOGIN_USER_ID"]."') order by URL_NO";
$cursor= exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor))
{
    $USER        = $ROW1["USER"];
    $URL_DESC    = $ROW1["URL_DESC"];
    $URL         = $ROW1["URL"];
   
    if($USER == '')
    {
        $COUNT_PUBLIC++;
        if($COUNT_PUBLIC == 1)
        {
            $PUBLIC_STR = '<div style="line-height:20px;"><b>'._("公共网址").'</b><ul>';
        }
        $PUBLIC_STR .= '<li><a href="'.$URL.'" target="_blank">'.$URL_DESC.'</a></li>';
    }
    else if($USER == $_SESSION["LOGIN_USER_ID"])
    {
        $COUNT_PRIVATE++;
        if($COUNT_PRIVATE == 1)
        {
            $PRIVATE_STR = '<div style="line-height:20px;margin-top:10px;"><b>'._("个人网址").'</b><ul>';
        }
        $PRIVATE_STR .= '<li><a href="'.$URL.'" target="_blank">'.$URL_DESC.'</a></li>';
    }
}

if($COUNT_PUBLIC > 0)
{
    $PUBLIC_STR .= "</ul></div>";
    echo $PUBLIC_STR;
}
if($COUNT_PRIVATE > 0)
{
    $PRIVATE_STR .= "</ul></div>";
    echo $PRIVATE_STR;
}
?>
