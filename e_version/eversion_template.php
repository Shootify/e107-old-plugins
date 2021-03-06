<?php
if (!defined("USER_WIDTH"))
{
    define("USER_WIDTH", "width:100%");
}
// *
// * Eversion list
// *
if (!isset($EVRSN_LIST_PRE))
{
    // Start of joke list
    // This is sent first
    $EVRSN_LIST_PRE = "
	<div class='fborder' style='" . USER_WIDTH . "'>";
}
// *
if (!isset($EVRSN_LIST_HEAD))
{
    // The main heading for the jokes list
    // displayed second
    $EVRSN_LIST_HEAD = "
	<table style='" . USER_WIDTH . "'>
		<tr>
			<td class='fcaption' colspan='6'>" . EVERSION_17 . "</td>
		</tr>
		<tr>
			<td class='fcaption' colspan='6'>&nbsp;</td>
		</tr>
		<tr>
			<td class='fcaption' colspan='6'><div style='text-align:center;'><img src='images/logo.png' style='border:0;' alt='' title = '' /></div></td>
		</tr>
		<tr>
			<td class='forumheader2' style='width:5%;'>&nbsp;</td>
			<td class='forumheader2' style='width:25%;'>" . EVERSION_5 . "</td>
			<td class='forumheader2' style='width:15%;'>" . EVERSION_6 . "</td>
			<td class='forumheader2' style='width:20%;'>" . EVERSION_11 . "</td>
			<td class='forumheader2' style='width:25%;'>" . EVERSION_10 . "</td>
			<td class='forumheader2' style='width:10%;'>" . EVERSION_14 . "</td>
		</tr>";
}

// *
if (!isset($EVRSN_LIST_LIST))
{
    // The list of jokes number of jokes is set in admin config
    $EVRSN_LIST_LIST = "
	<tr>
		<td class='forumheader3' style='width:5%;'>{EVRSN_ICON}</td>
		<td class='forumheader3' style='width:25%;'>{EVRSN_PLUGTITLE}</td>
		<td class='forumheader3' style='width:15%;'>{EVRSN_VERSION}</td>
		<td class='forumheader3' style='width:20%;'>{EVRSN_AUTHOR}</td>
		<td class='forumheader3' style='width:25%;'>{EVRSN_DATE=short}</td>
		<td class='forumheader3' style='width:10%;text-align:center;'>{EVRSN_DLOAD}</td>
	</tr>";
}
// *
if (!isset($EVRSN_LIST_FOOTER))
{
    // Last section of the joke list
    $EVRSN_LIST_FOOTER .= "
	<tr>
		<td class='fcaption' colspan='6'>{EVRSN_NP}&nbsp;</td>
	</tr>
	<tr>
    	<td class='fcaption' colspan='6' ><div style='text-align:center;'>{EVRSN_RSS}</div></td>
    </tr>
	</table>";
}
// *
if (!isset($EVRSN_LIST_POST))
{
    // Start of joke list
    // This is sent first
    $EVRSN_LIST_POST = "
	</div>";
}
// *
if (!isset($EVRSN_LIST_NOEVRSN))
{
    // No jokes to display
    $EVRSN_LIST_NOEVRSN .= "
	<tr>
		<td class='forumheader3' colspan='5'>None</td>
	</tr>";
}


// * Display Joke
// *
if (!isset($EVRSN_SHOW_PRE))
{
    $EVRSN_SHOW_PRE = "<div class='fborder' style='" . USER_WIDTH . "'>";
}
// *
// *
if (!isset($EVRSN_SHOW_HEADER))
{
    $EVRSN_SHOW_HEADER = "
	<table class='fborder'  style='" . USER_WIDTH . "'>
	<tr>
		<td class='fcaption' colspan='2'>" . EVERSION_16 . "</td>
	</tr>
	<tr>
		<td class='forumheader3' colspan='2'>{BACK_BUTTON}&nbsp;&nbsp;{UP_BUTTON}</td>
	</tr>
	<tr>
		<td class='fcaption' colspan='6'><div style='text-align:center;'><img src='images/logo.png' style='border:0;' alt='' title = '' /></div></td>
	</tr>";
}
if (!isset($EVRSN_SHOW_DETAIL))
{
    $EVRSN_SHOW_DETAIL .= "

	<tr>
		<td class='forumheader3' style='width:20%'>" . EVERSION_6 . "</td>
		<td class='forumheader3' style='width:80%'>{EVRSN_TITLE}</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:20%'>" . EVERSION_5 . "</td>
		<td class='forumheader3' style='width:80%'>{EVRSN_PNAME}</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:20%'>" . EVERSION_7 . "</td>
		<td class='forumheader3' style='width:80%'>{EVRSN_VNUMBER}</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:20%'>" . EVERSION_10 . "</td>
		<td class='forumheader3' style='width:80%'>{EVRSN_VDATE=long}</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:20%'>" . EVERSION_11 . "</td>
		<td class='forumheader3' style='width:80%'>{EVRSN_AUTH}</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:20%'>" . EVERSION_12 . "</td>
		<td class='forumheader3' style='width:80%'>{EVRSN_REVISIONS}</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:20%'>" . EVERSION_13 . "</td>
		<td class='forumheader3' style='width:80%'>{EVRSN_COMMENTS}</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:20%'>" . EVERSION_14 . "</td>
		<td class='forumheader3' style='width:80%'>{EVRSN_DLPATH}</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:20%'>" . EVERSION_19 . "</td>
		<td class='forumheader3' style='width:80%'>{EVRSN_SUPPORT}</td>
	</tr>
	<tr>
		<td class='forumheader3' style='width:20%'>" . EVERSION_28 . "</td>
		<td class='forumheader3' style='width:80%'>{EVRSN_BUGS}</td>
	</tr>
	";
}
// *
if (!isset($EVRSN_SHOW_FOOTER))
{
 $EVRSN_SHOW_FOOTER = "
 	<tr>
    	<td class='fcaption' colspan='2' ><div style='text-align:center;'>{EVRSN_RSS}</div></td>
    </tr>
	</table>";
}
// *
if (!isset($EVRSN_SHOW_POST))
{
    $EVRSN_SHOW_POST = "</div>";
}
?>