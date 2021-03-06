<?php
/*
+ -----------------------------------------------------------------+
| e107: Clan Members Basic 1.0                                     |
| =============================                                    |
|                                                                  |
| Copyright (c) 2011 Untergang                                     |
| http://www.udesigns.be/                                          |
|                                                                  |
| This file may not be redistributed in whole or significant part. |
+------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

// Plugin info -------------------------------------------------------------------------------------------------------
$eplug_name = 'Clan Members Basic';
$eplug_version = '1.0';
$eplug_author = 'Untergang';
$eplug_url = 'http://www.udesigns.be';
$eplug_email = '';
$eplug_description = 'This plugin shows the members of your clan per game/team';
$eplug_compatible = 'e107v0.7+';
$eplug_readme = 'e_help.php';

// Name of the plugin's folder -------------------------------------------------------------------------------------
$eplug_folder = "clanmembers";

// Name of menu item for plugin ----------------------------------------------------------------------------------
$eplug_menu_name = "clanmembers";

// Name of the admin configuration file --------------------------------------------------------------------------
$eplug_conffile = "admin.php";

// Icon image and caption text ------------------------------------------------------------------------------------
$eplug_icon = $eplug_folder."/images/clanmembers_32.png";
$eplug_icon_small = $eplug_folder."/images/clanmembers_16.png";
$eplug_caption = 'Configure Clan Members';

// List of preferences -----------------------------------------------------------------------------------------------
$eplug_prefs = array(

 );

// List of table names -----------------------------------------------------------------------------------------------
$eplug_table_names = array("clan_members_config",
	  "clan_members_gallery",
	  "clan_members_gamelink",
	  "clan_members_info",
	  "clan_members_teamlink");	

// List of sql requests to create tables -----------------------------------------------------------------------------

$eplug_tables = array("CREATE TABLE IF NOT EXISTS ".MPREFIX."clan_members_config (
	  version varchar(10) NOT NULL,
	  gamesorteams varchar(5) NOT NULL DEFAULT 'Games',
	  cmtitle varchar(100) NOT NULL DEFAULT 'Clan Members',
	  show_opened tinyint(1) NOT NULL DEFAULT '1',
	  show_gname tinyint(1) NOT NULL DEFAULT '0',
	  rank_per_game tinyint(1) NOT NULL DEFAULT '0',
	  memberorder varchar(50) NOT NULL DEFAULT 'Activity|DESC-Username|ASC-Rank|ASC',
	  style tinyint(1) NOT NULL DEFAULT '1',
	  allowchangeinfo tinyint(1) NOT NULL DEFAULT '1',
	  allowupimage tinyint(1) NOT NULL DEFAULT '1',
	  maxwidth int(3) NOT NULL DEFAULT '100',
	  maxheight int(3) NOT NULL DEFAULT '150',
	  titlealign varchar(10) NOT NULL DEFAULT 'center',
	  padding int(2) NOT NULL DEFAULT '0',
	  maxfilesize int(5) NOT NULL DEFAULT '0',
	  membersperrow int(2) NOT NULL DEFAULT '2',
	  joinformat varchar(25) NOT NULL DEFAULT 'j M Y',
	  birthformat varchar(25) NOT NULL DEFAULT 'j M Y',
	  enableprofile tinyint(1) NOT NULL DEFAULT '1',
	  enablehardware tinyint(1) NOT NULL DEFAULT '1',
	  enablegallery tinyint(1) NOT NULL DEFAULT '1',
	  showawards tinyint(1) NOT NULL DEFAULT '1',
	  maximages int(5) NOT NULL DEFAULT '6',
	  galfilesize int(10) NOT NULL DEFAULT '512',
	  thumbwidth int(4) NOT NULL DEFAULT '200',
	  showuserimage tinyint(1) NOT NULL DEFAULT '1',
	  profileimgwidth int(3) NOT NULL DEFAULT '200',
	  profileimgheight int(3) NOT NULL DEFAULT '200',
	  listwidth varchar(4) NOT NULL DEFAULT '500',
	  profiletoguests tinyint(1) NOT NULL DEFAULT '1',
	  changeatdot TINYINT(1) NOT NULL DEFAULT '1',
	  guestviewcontactinfo TINYINT(1) NOT NULL DEFAULT '0',
	  showview TINYINT (1) NOT NULL DEFAULT '0',
	  showcontactlist TINYINT (1) NOT NULL DEFAULT '0',
	  profilealign varchar(10) NOT NULL DEFAULT 'left',
	  leftsidewidth int(3) NOT NULL DEFAULT '150',
	  lastclean int(15) NOT NULL DEFAULT '0',
	  listorder text,
	  profileorder text)",
	  "INSERT INTO ".MPREFIX."clan_members_config (version, listorder, profileorder) VALUES ('1.0', 'a:2:{s:4:\"show\";a:10:{i:0;s:8:\"Username\";i:1;s:8:\"Realname\";i:2;s:6:\"Gender\";i:3;s:8:\"Birthday\";i:4;s:3:\"Age\";i:5;s:7:\"Country\";i:6;s:8:\"Location\";i:7;s:10:\"User Image\";i:8;s:5:\"Xfire\";i:9;s:9:\"Join Date\";}s:4:\"hide\";a:6:{i:0;s:4:\"Rank\";i:1;s:10:\"Rank Image\";i:2;s:8:\"Steam ID\";i:3;s:5:\"Games\";i:4;s:5:\"Teams\";i:5;s:8:\"Activity\";}}',
	  'a:2:{s:4:\"show\";a:15:{i:0;s:8:\"Username\";i:1;s:10:\"Rank Image\";i:2;s:4:\"Rank\";i:3;s:8:\"Realname\";i:4;s:6:\"Gender\";i:5;s:3:\"Age\";i:6;s:8:\"Birthday\";i:7;s:7:\"Country\";i:8;s:8:\"Location\";i:9;s:9:\"Join Date\";i:10;s:5:\"Xfire\";i:11;s:8:\"Steam ID\";i:12;s:5:\"Games\";i:13;s:5:\"Teams\";i:14;s:8:\"Activity\";}s:4:\"hide\";a:2:{i:0;s:11:\"Wars Played\";i:1;s:8:\"Last War\";}}')",
	  "CREATE TABLE IF NOT EXISTS ".MPREFIX."clan_members_info (
	  userid int(11) NOT NULL,
	  rank varchar(100) DEFAULT NULL,
	  birthday bigint NOT NULL DEFAULT '1',
	  gender varchar(6) DEFAULT NULL,
	  xfire varchar(100) DEFAULT NULL,
	  steam varchar(50) DEFAULT NULL,
	  rankorder int(2) NOT NULL DEFAULT '0',
	  avatar varchar(100) DEFAULT NULL,
	  active int(1) NOT NULL DEFAULT '1',
	  realname varchar(100) DEFAULT NULL,
	  location varchar(100) DEFAULT NULL,
	  country varchar(50) NOT NULL DEFAULT 'Unknown',
	  joindate bigint NOT NULL DEFAULT '1',
	  manufacturer varchar(50) DEFAULT NULL,
	  cpu varchar(50) DEFAULT NULL,
	  memory varchar(50) DEFAULT NULL,
	  hdd varchar(50) DEFAULT NULL,
	  vga varchar(50) DEFAULT NULL,
	  monitor varchar(50) DEFAULT NULL,
	  sound varchar(50) DEFAULT NULL,
	  speakers varchar(50) DEFAULT NULL,
	  keyboard varchar(50) DEFAULT NULL,
	  mouse varchar(50) DEFAULT NULL,
	  surface varchar(50) DEFAULT NULL,
	  os varchar(50) DEFAULT NULL,
	  mainboard varchar(50) DEFAULT NULL,
	  pccase varchar(50) DEFAULT NULL,
	  PRIMARY KEY (userid))",
	  "CREATE TABLE IF NOT EXISTS ".MPREFIX."clan_members_gamelink (
	  id int(11) NOT NULL AUTO_INCREMENT,
	  gid int(11) NOT NULL DEFAULT '0',
	  userid int(11) NOT NULL DEFAULT '0',
	  rank int(1) NOT NULL DEFAULT '0',
	  PRIMARY KEY (id))",
	  "CREATE TABLE IF NOT EXISTS ".MPREFIX."clan_members_teamlink (
		id int( 11 ) NOT NULL AUTO_INCREMENT ,
		tid int( 11 ) NOT NULL DEFAULT '0',
		userid int(11) NOT NULL DEFAULT '0',
		rank int( 1 ) NOT NULL DEFAULT '0',
		PRIMARY KEY ( id ))",
	  "CREATE TABLE IF NOT EXISTS ".MPREFIX."clan_games (
	  gid int(11) NOT NULL AUTO_INCREMENT,
	  abbr VARCHAR(20) NULL,
	  gname varchar(50) NOT NULL,
	  banner varchar(50) NULL,
	  icon varchar(50) NULL,
	  inmembers tinyint(1) NOT NULL DEFAULT '1',
	  inwars tinyint(1) NOT NULL DEFAULT '1',
	  position int(3) NOT NULL,
	  PRIMARY KEY (gid))",
	  "CREATE TABLE IF NOT EXISTS ".MPREFIX."clan_teams (
	  tid int(11) NOT NULL AUTO_INCREMENT,
	  team_tag varchar(25) NOT NULL,
	  team_name varchar(50) NOT NULL,
	  team_country varchar(30) NOT NULL DEFAULT 'Unknown',
	  banner varchar(50) NULL,
	  icon varchar(50) NULL,
	  inmembers tinyint(1) NOT NULL DEFAULT '1',
	  inwars tinyint(1) NOT NULL DEFAULT '1',
	  position int(3) NOT NULL,
	  PRIMARY KEY (tid))");	
	
// Create a link in main menu (yes=TRUE, no=FALSE) -------------------------------------------------------------
$eplug_link = TRUE;
$eplug_link_name = "Clan Members";
$eplug_link_url = e_PLUGIN.'clanmembers/clanmembers.php';

// Text to display after plugin successfully installed ------------------------------------------------------------------
$eplug_done = 'Your clan members list is now installed';

?>