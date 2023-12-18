<?php
###########################################################
/*
GuestBook Script
Copyright (C) 2012 StivaSoft ltd. All rights Reserved.


This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see http://www.gnu.org/licenses/gpl-3.0.html.

For further information visit:
http://www.phpjabbers.com/
info@phpjabbers.com

Version:  1.0
Released: 2013-09-07
*/
###########################################################

error_reporting(0);
include("config.php");

$sql = "SELECT * FROM ".$SETTINGS["data_table"]." WHERE `event_date` = '".mysql_real_escape_string($_REQUEST["date"])."'";
$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
while ($row = mysql_fetch_assoc($sql_result)) {
	echo "<h2>".stripcslashes($row["title"])."</h2>";
	echo "<span>".stripcslashes($row["description"])."</span>";
	
}
?>