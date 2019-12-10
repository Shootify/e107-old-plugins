<?php

require_once("../../class2.php");
if (!defined('e107_INIT')) { exit; }
if (!getperms("P"))
{
    header("location:" . e_BASE . "index.php");
    exit;
}
if (e_LANGUAGE != "English" && file_exists(e_PLUGIN . "e_version/languages/admin/" . e_LANGUAGE . ".php"))
{
    include_once(e_PLUGIN . "e_version/languages/admin/" . e_LANGUAGE . ".php");
}
else
{
    include_once(e_PLUGIN . "e_version/languages/admin/English.php");
}
require_once(e_HANDLER . "userclass_class.php");
require_once(e_ADMIN . "auth.php");

if (e_QUERY == "update")
{
    // Update rest
    $pref['eversion_url'] = $tp->toDB($_POST['eversion_url']);
    $pref['eversion_read'] = $tp->toDB($_POST['eversion_read']);
    $pref['eversion_inmenu'] = $tp->toDB($_POST['eversion_inmenu']);
	$pref['eversion_noplug']= $tp->toDB($_POST['eversion_noplug']);
	$pref['eversion_dformat']= $tp->toDB($_POST['eversion_dformat']);
	$pref['eversion_usedownloads']= $tp->toDB($_POST['eversion_usedownloads']);
	$pref['eversion_useforums']= $tp->toDB($_POST['eversion_useforums']);
	$pref['eversion_usebugs']= $tp->toDB($_POST['eversion_usebugs']);
	save_prefs();
    $evrsn_msgtext = ECLASSF_A14 ;
}

$evrsn_text = "<form id=\"config\" method=\"post\" action=\"" . e_SELF . "?update\">
		<table class=\"fborder\" width='97%'>";
$evrsn_text .= "<tr><td class='fcaption' colspan='2' style='text-align:left'>" . EVERSION_A2 . "</td></tr>";

$evrsn_text .= "<tr><td class='forumheader3' colspan='2' style='text-align:left'><strong>".$tp->toHTML($evrsn_msgtext)."</strong>&nbsp;</td></tr>";

$evrsn_text .= "
<tr>
<td style='width:30%' class='forumheader3'>" . EVERSION_A3 . "</td>
<td style='width:70%' class='forumheader3'>" . r_userclass("eversion_read", $pref['eversion_read'], "off", 'public,guest, nobody, member, admin, classes') . "
</td></tr>";

#$evrsn_text .= "<tr><td class='forumheader3' style='width:30%;'>" . EVERSION_A4 . "</td><td class='forumheader3'>
#<input class='tbox' style='width:90%;' type='text' name='eversion_url' value='" . $tp->toFORM($pref['eversion_url']) . "' /></td></tr>";

$evrsn_text .= "<tr><td class='forumheader3' style='width:30%;'>" . EVERSION_A6 . "</td><td class='forumheader3'>
<input class='tbox' style='width:10%;' type='text' name='eversion_inmenu' value='" . $tp->toFORM($pref['eversion_inmenu']) . "' /></td></tr>";

$evrsn_text .= "<tr><td class='forumheader3' style='width:30%;'>" . EVERSION_A56 . "</td><td class='forumheader3'>
<input class='tbox' style='width:10%;' type='text' name='eversion_noplug' value='" . $tp->toFORM($pref['eversion_noplug']) . "' /></td></tr>";

$evrsn_text .= "<tr><td class='forumheader3' style='width:30%;'>" . EVERSION_A58 . "</td><td class='forumheader3'>
<select class='tbox' name='eversion_dformat']>
<option value='d-m-Y' ".($pref['eversion_dformat']=="d-m-Y"?"selected='selected'":"").">d-m-Y</option>
<option value='m-d-Y'".($pref['eversion_dformat']=="m-d-Y"?"selected='selected'":"").">m-d-Y</option>
<option value='Y-m-d'".($pref['eversion_dformat']=="Y-m-d"?"selected='selected'":"").">Y-m-d</option>
</select></td></tr>";


$evrsn_text .= "<tr><td class='forumheader3' style='width:30%;'>" . EVERSION_A61 . "</td><td class='forumheader3'>
<input class='tbox' type='checkbox' name='eversion_usedownloads' value='1' " . ($pref['eversion_usedownloads']>0?"checked='checked'":"") . "' /></td></tr>";


$evrsn_text .= "<tr><td class='forumheader3' style='width:30%;'>" . EVERSION_A62 . "</td><td class='forumheader3'>
<input class='tbox' type='checkbox' name='eversion_useforums' value='1' " . ($pref['eversion_useforums']>0?"checked='checked'":"") . "' /></td></tr>";

$evrsn_text .= "<tr><td class='forumheader3' style='width:30%;'>" . EVERSION_A63 . "</td><td class='forumheader3'>
<input class='tbox' type='checkbox' name='eversion_usebugs' value='1' " . ($pref['eversion_usebugs']>0?"checked='checked'":"") . "' /></td></tr>";



$evrsn_text .= "<tr><td class='forumheader' colspan='2' style='text-align:left;vertical-align:top;'>
<input class='button' name='savesettings' type='submit' value='" . EVERSION_A5 . "' /></td></tr>";

$evrsn_text .= "</table></form>";
$ns->tablerender(EVERSION_A1, $evrsn_text);
require_once(e_ADMIN . "footer.php");

?>