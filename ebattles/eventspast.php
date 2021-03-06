<?php
/**
* eventspast.php
*
*/

require_once("../../class2.php");
require_once(e_PLUGIN."ebattles/include/main.php");
require_once(e_PLUGIN."ebattles/include/event.php");
require_once(e_PLUGIN."ebattles/include/paginator.class.php");

/*******************************************************************
********************************************************************/
require_once(HEADERF);
require_once(e_PLUGIN."ebattles/include/ebattles_header.php");

$text .= "
<script type='text/javascript'>
<!--//
function buttonval(v)
{
document.getElementById('sort').value=v;
document.getElementById('submitform').submit();
}
//-->
</script>
";

/**
* Display Past Events
*/
$text .= '<div id="tabs">';
$text .= '<ul>';
$text .= '<li><a href="#tabs-1">'.EB_EVENTP_L2.'</a></li>';
$text .= '</ul>';
$text .= '<div id="tabs-1">';
displayPastEvents();
$text .= '
</div>
</div>
';

$text .= disclaimer();

$ns->tablerender(EB_EVENTP_L1, $text);
require_once(FOOTERF);
exit;

/***************************************************************************************
Functions
***************************************************************************************/
/**
* displayEvents - Displays the events database table in
* a nicely formatted html table.
*/
function displayPastEvents(){
	global $sql;
	global $text;
	global $time;

	$pages = new Paginator;

	$array = array(
	'latest' => array(EB_EVENTS_L4,'EventID'),
	'name'   => array(EB_EVENTS_L5, TBL_EVENTS.'.Name'),
	'game'   => array(EB_EVENTS_L6, TBL_GAMES.'.Name'),
	'type'   => array(EB_EVENTS_L7, TBL_EVENTS.'.Type'),
	'start'  => array(EB_EVENTS_L8, TBL_EVENTS.'.StartDateTime')
	);
	if (!isset($_GET['gameid'])) $_GET['gameid'] = "All";
	$gameid = eb_sanitize($_GET['gameid']);

	if (!isset($_GET['matchtype'])) $_GET['matchtype'] = "All";
	$matchtype = eb_sanitize($_GET['matchtype']);

	if (!isset($_GET['orderby'])) $_GET['orderby'] = 'game';
	$orderby= eb_sanitize($_GET['orderby']);

	$sort = "ASC";
	if(isset($_GET["sort"]) && !empty($_GET["sort"]))
	{
		$sort = ($_GET["sort"]=="ASC") ? "DESC" : "ASC";
	}

	$game_string = ($gameid == "All") ? "" : "   AND (".TBL_EVENTS.".Game = '$gameid')";
	$matchtype_string = ($matchtype == "All") ? "" : "   AND (".TBL_GAMES.".MatchTypes LIKE '%$matchtype%')";

	// Drop down list to select Games to display
	$q_Games = "SELECT DISTINCT ".TBL_GAMES.".*"
	." FROM ".TBL_GAMES.", "
	. TBL_EVENTS
	." WHERE (".TBL_EVENTS.".Game = ".TBL_GAMES.".GameID)"
	.$matchtype_string
	." ORDER BY Name";
	$result_Games = $sql->db_Query($q_Games);
	$numGames = mysql_numrows($result_Games);

	// Drop down list to select Match type to display
	$q_mt = "SELECT ".TBL_GAMES.".*"
	." FROM ".TBL_GAMES.", "
	. TBL_EVENTS
	." WHERE (".TBL_EVENTS.".Game = ".TBL_GAMES.".GameID)"
	.$game_string;
	$result_mt = $sql->db_Query($q_mt);
	$num_mt = mysql_numrows($result_mt);
	$gmatchtypes = '';
	for($i=0; $i<$num_mt; $i++)
	{
		$gmatchtypes  .= ','.mysql_result($result_mt,$i, TBL_GAMES.".MatchTypes");
	}

	$text .= '<form id="submitform" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="get">';
	$text .= '<div>';
	$text .= '<table class="table_left">';
	$text .= '<tr>';
	// Games drop down
	$text .= '<td>'.EB_EVENTS_L9.'<br />';
	$text .= '<select class="tbox" name="gameid" onchange="this.form.submit()">';
	$text .= '<option value="All" '.(($gameid == "All") ? 'selected="selected"' : '').'>'.EB_EVENTS_L10.'</option>';
	for($i=0; $i<$numGames; $i++)
	{
		$gName  = mysql_result($result_Games,$i, TBL_GAMES.".Name");
		$gid  = mysql_result($result_Games,$i, TBL_GAMES.".GameID");
		$text .= '<option value="'.$gid.'" '.(($gameid == $gid) ? 'selected="selected"': '').'>'.htmlspecialchars($gName).'</option>';
	}
	$text .= '</select>';
	$text .= '</td>';
	// Match Types drop down
	$text .= '<td>'.EB_EVENTS_L32.'<br />';
	$text .= '<select class="tbox" name="matchtype" onchange="this.form.submit()">';
	$text .= '<option value="All" '.(($matchtype == "All") ? 'selected="selected"' : '').'>'.EB_EVENTS_L10.'</option>';

	$gmatchtypes  = explode(",", $gmatchtypes);
	$gmatchtypes = array_unique($gmatchtypes);
	sort($gmatchtypes);
	foreach($gmatchtypes as $gmatchtype)
	{
		if ($gmatchtype!='') {
			$text .= '<option value="'.$gmatchtype.'" '.(($gmatchtype == $matchtype) ? 'selected="selected"' : '').'>'.htmlspecialchars($gmatchtype).'</option>';
		}
	}
	$text .= '</select>';
	$text .= '</td>';
	$text .= '</tr>';
	$text .= '</table>';

	$game_string = ($gameid == "All") ? "" : "   AND (".TBL_EVENTS.".Game = '$gameid')";
	$matchtype_string = ($matchtype == "All") ? "" : "   AND (".TBL_EVENTS.".MatchType = '$matchtype')";

	$q = "SELECT count(*) "
	." FROM ".TBL_EVENTS
	." WHERE (".TBL_EVENTS.".Status = 'finished')"
	.$game_string
	.$matchtype_string;
	$result = $sql->db_Query($q);
	$totalItems = mysql_result($result, 0);
	$pages->items_total = $totalItems;
	$pages->mid_range = eb_PAGINATION_MIDRANGE;
	$pages->paginate();

	$orderby_array = $array["$orderby"];
	$q = "SELECT ".TBL_EVENTS.".*, "
	.TBL_GAMES.".*"
	." FROM ".TBL_EVENTS.", "
	.TBL_GAMES
	." WHERE (".TBL_EVENTS.".Status = 'finished')"
	."   AND (".TBL_EVENTS.".Game = ".TBL_GAMES.".GameID)"
	.$game_string
	.$matchtype_string
	." ORDER BY $orderby_array[1] $sort, EventID DESC"
	." $pages->limit";
	$result = $sql->db_Query($q);
	$numEvents = mysql_numrows($result);
	if(!$result || ($numEvents < 0))
	{
		/* Error occurred, return given name by default */
		$text .= EB_EVENTS_L11.'</div>';
		$text .= '</form><br/>';
	} else if($numEvents == 0)
	{
		$text .= EB_EVENTS_L12.'</div>';
		$text .= '</form><br/>';
	}
	else
	{
		// Paginate & Sorting
		$items = '';
		foreach($array as $opt=>$opt_array)	$items .= ($opt == $orderby) ? '<option selected="selected" value="'.$opt.'">'.$opt_array[0].'</option>':'<option value="'.$opt.'">'.$opt_array[0].'</option>';

		// Paginate
		$text .= '<span class="paginate" style="float:left;">'.$pages->display_pages().'</span>';
		$text .= '<span style="float:right">';
		// Sort By
		$text .= EB_PGN_L6;
		$text .= '<select class="tbox" name="orderby" onchange="this.form.submit()">';
		$text .= $items;
		$text .= '</select>';
		// Up/Down arrow
		$text .= '<input type="hidden" id="sort" name="sort" value=""/>';
		if ($sort =="ASC")
		{
			$text .= '<a href="javascript:buttonval(\'ASC\');" title="Ascending"><img src="'.e_PLUGIN.'ebattles/images/sort_asc.gif" alt="Asc" style="vertical-align:middle; border:0"/></a>';
		}
		else
		{
			$text .= '<a href="javascript:buttonval(\'DESC\');" title="Descending"><img src="'.e_PLUGIN.'ebattles/images/sort_desc.gif" alt="Desc" style="vertical-align:middle; border:0"/></a>';

		}

		$text .= '&nbsp;&nbsp;&nbsp;';
		// Go To Page
		$text .= $pages->display_jump_menu();
		$text .= '&nbsp;&nbsp;&nbsp;';
		// Items per page
		$text .= $pages->display_items_per_page();
		$text .= '</span>';
		$text .= '</div>';
		$text .= '</form><br/><br/>';

		/* Display table contents */
		$text .= '<table class="eb_table" style="width:95%"><tbody>';
		$text .= '<tr>
		<th class="eb_th2">'.EB_EVENTS_L13.'</th>
		<th colspan="2" class="eb_th2">'.EB_EVENTS_L14.'</th>
		<th class="eb_th2">'.EB_EVENTS_L15.'</th>
		<th class="eb_th2">'.EB_EVENTS_L16.'</th>
		<th class="eb_th2">'.EB_EVENTS_L17.'</th>
		<th class="eb_th2">'.EB_EVENTS_L18.'</th>
		<th class="eb_th2">'.EB_EVENTS_L19.'</th>
		<th class="eb_th2">'.EB_EVENTS_L34.'</th>
		</tr>';
		for($i=0; $i < $numEvents; $i++)
		{
			$gName  = mysql_result($result,$i, TBL_GAMES.".Name");
			$gIcon  = mysql_result($result,$i, TBL_GAMES.".Icon");
			$event_id  = mysql_result($result,$i, TBL_EVENTS.".EventID");
			$event = new Event($event_id);
			if($event->getField('StartDateTime')!=0)
			{
				$startdatetime_local = $event->getField('StartDateTime') + TIMEOFFSET;
				$date_start = date("d M Y", $startdatetime_local);
			}
			else
			{
				$date_start = "-";
			}
			if($event->getField('EndDateTime')!=0)
			{
				$enddatetime_local = $event->getField('EndDateTime') + TIMEOFFSET;
				$date_end = date("d M Y", $enddatetime_local);
			}
			else
			{
				$date_end = "-";
			}

			/* Nbr players */
			$q_2 = "SELECT COUNT(*) as NbrPlayers"
			." FROM ".TBL_PLAYERS
			." WHERE (Event = '$event_id')";
			$result_2 = $sql->db_Query($q_2);
			$row = mysql_fetch_array($result_2);
			$nbrplayers = $row['NbrPlayers'];

			/* Nbr Teams */
			$q_2 = "SELECT COUNT(*) as NbrTeams"
			." FROM ".TBL_TEAMS
			." WHERE (".TBL_TEAMS.".Event = '$event_id')";
			$result_2 = $sql->db_Query($q_2);
			$row = mysql_fetch_array($result_2);
			$nbrTeams = $row['NbrTeams'];

			/* Nbr matches */
			$q_2 = "SELECT COUNT(DISTINCT ".TBL_MATCHS.".MatchID) as NbrMatches"
			." FROM ".TBL_MATCHS.", "
			.TBL_SCORES
			." WHERE (Event = '$event_id')"
			." AND (".TBL_MATCHS.".Status = 'active')"
			." AND (".TBL_SCORES.".MatchID = ".TBL_MATCHS.".MatchID)";
			$result_2 = $sql->db_Query($q_2);
			$row = mysql_fetch_array($result_2);
			$nbrmatches = $row['NbrMatches'];

			switch($event->getField('Type'))
			{
			case "One Player Ladder":
			case "One Player Tournament":
				$nbrTeamPlayers = $nbrplayers;
				break;
			case "Team Ladder":
				$nbrTeamPlayers = $nbrTeams.'/'.$nbrplayers;
				break;
			case "Clan Ladder":
			case "Clan Tournament":
				$nbrTeamPlayers = $nbrTeams;
				break;
			default:
			}

			$text .= '<tr>
			<td class="eb_td"><a href="'.e_PLUGIN.'ebattles/eventinfo.php?eventid='.$event_id.'">'.$event->getField('Name').'</a></td>
			<td class="eb_td"><img '.getGameIconResize($gIcon).'/></td>
			<td class="eb_td">'.$gName.'</td>
			<td class="eb_td">'.(($event->getField('MatchType')!='') ? $event->getField('MatchType').' - ' : '').$event->eventTypeToString().'</td>
			<td class="eb_td">'.$date_start.'</td>
			<td class="eb_td">'.$date_end.'</td>
			<td class="eb_td">'.$nbrTeamPlayers.'</td>
			<td class="eb_td">'.$nbrmatches.'</td>
			<td class="eb_td">'.$event->eventStatusToString().'</td>
			</tr>';
		}
		$text .= '</tbody></table><br />';

		$text .= '<div>';
		$text .= ebImageLink('back_to_events', '', e_PLUGIN.'ebattles/events.php', '', 'action_back.gif', EB_EVENTP_L3.' '.EB_EVENTP_L4, 'jq-button');
		$text .= '</div>';

	}
}

?>
