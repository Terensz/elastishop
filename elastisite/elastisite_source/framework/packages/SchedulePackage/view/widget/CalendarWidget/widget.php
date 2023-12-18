<style>
.calendarFrame {
	width: auto;
}

.calendarFrame td {
	/* font-family: OpenSans; */
	font-size: 14px;
}

.calendarFrame .currentMonth {
	font-weight:bold;
	text-align:center;
}

.calendarFrame .pager {
	font-weight:bold;
	text-align:center;
}

#Events {
	width: auto;
}

#Events h2 {
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	font-weight:bold;
	margin-top:10px;
}

#Events span {
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
}

<?php
// include('framework/packages/SchedulePackage/view/widget/CalendarWidget/style/calendar.css');
?>
</style>
<?php
(intval($actualMonth) > 0) ? $cMonth = intval($actualMonth) : $cMonth = date("m");
(intval($actualYear) > 0) ? $currentYear = intval($actualYear) : $currentYear = date("Y");

// calculate next and prev month and year used for next / prev month navigation links and store them in respective variables
$prev_year = $currentYear;
$next_year = $currentYear;
$prev_month = intval($cMonth)-1;
$next_month = intval($cMonth)+1;

// if current month is December or January month navigation links have to be updated to point to next / prev years
if ($cMonth == 12 ) {
	$next_month = 1;
	$next_year = $currentYear + 1;
} elseif ($cMonth == 1 ) {
	$prev_month = 12;
	$prev_year = $currentYear - 1;
}

if ($prev_month<10) $prev_month = '0'.$prev_month;
if ($next_month<10) $next_month = '0'.$next_month;

$actualMonthText = ucfirst(strftime("%B", strtotime($actualYear.'-'.$actualMonth.'-'.'1')));
?>

<div class="widgetWrapper">
	<div class="article-title">
	<?php echo trans('already.have.an.appointment'); ?>
	</div>
	<div id="calendarFrame" style="width: 100%; margin: auto;">
	<table width="100%">
		<tr>
			<td class="pager"><a href="javascript:LoadMonth('<?php echo $prev_month; ?>', '<?php echo $prev_year; ?>')">&lt;&lt;</a></td>
			<td colspan="5" class="currentMonth"><?php echo $actualMonthText.', '.$currentYear; ?></td>
			<td class="pager"><a href="javascript:LoadMonth('<?php echo $next_month; ?>', '<?php echo $next_year; ?>')">&gt;&gt;</a></td>
		</tr>
		<tr>
			<td class="CalendarWidget-weekDays">H</td>
			<td class="CalendarWidget-weekDays">K</td>
			<td class="CalendarWidget-weekDays">Sz</td>
			<td class="CalendarWidget-weekDays">Cs</td>
			<td class="CalendarWidget-weekDays">P</td>
			<td class="CalendarWidget-weekDays">Sz</td>
			<td class="CalendarWidget-weekDays">V</td>
		</tr>
	<?php
	$first_day_timestamp = mktime(0, 0, 0, $cMonth, 1, $currentYear);
	$maxday = date("t",$first_day_timestamp); // number of days in current month
	$thismonth = getdate($first_day_timestamp); // find out which day of the week the first date of the month is
	$startday = $thismonth['wday'] - 1; // 0 is for Sunday and as we want week to start on Mon we subtract 1

	for ($i=0; $i<($maxday+$startday); $i++) {

		if (($i % 7) == 0 ) echo "<tr>";

		if ($i < $startday) { echo "<td>&nbsp;</td>"; continue; };

		$current_day = $i - $startday + 1;
		if ($current_day < 10) $current_day = $current_day;

		if (isset($appointments[$currentYear."-".$cMonth."-".$current_day]) && $appointments[$currentYear."-".$cMonth."-".$current_day] != '') {
			$css='CalendarWidget-withevent';
			$click = "onclick=\"LoadEvents('".$currentYear."-".$cMonth."-".$current_day."')\"";
		} else {
			$css='CalendarWidget-noevent';
			$click = '';
		}

		echo "<td class='".$css."'".$click.">". $current_day . "</td>";

		if (($i % 7) == 6 ) echo "</tr>";
	}
	?>
		</table>
	</div>
</div>
<script>
	$('document').ready(function() {
		var containerId = $('#calendarFrame').parent().attr('id');
		// $('#' + containerId).css('min-width', '190px');
		// $('#' + containerId).css('padding-left', '10px');
		// $('#' + containerId).css('padding-right', '14px');
	});
</script>
