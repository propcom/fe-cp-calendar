
<?
	// Make sure links for the nav and calendar links mathc what your page name is called i.e index.php

?>


<section class="diary__wrapper">

	<?
		if(isset($_GET['year']) && isset($_GET['month']) && checkdate($_GET['month'], 1, $_GET['year'])) {
			$startOfMonth = mktime(0, 0, 0, $_GET['month'], 1, $_GET['year']);
		} else {
			$startOfMonth = mktime(0, 0, 0, date('n'), 1);
		}

		$nextMonth = strtotime('+1 month', $startOfMonth);
		$prevMonth = strtotime('-1 month', $startOfMonth);

		$siteId = 1275;
		require_once '/var/www/shared/calendarincludes/calendar.php';

		$eventsData = getEventsData(array('format'=>'byDate'));
		$eventTitles = array();
	?>


	<nav class="date__select">

		<a href="index.php?month=<?= date('n', $prevMonth) ?>&year=<?= date('Y', $prevMonth) ?>"><</a>
		<span class="month"><?= date('F', $startOfMonth) ?></span>
		<a href="index.php?month=<?= date('n', $nextMonth) ?>&year=<?= date('Y', $nextMonth) ?>">></a>

	</nav>

	<article id="date__calendar">
			<div class="row day__option">
				<? for($i=1; $i<=7; $i++): ?>
					<div class="cell<? if($i == date('N')): ?> day-select<? endif; ?><? if($i == 7): ?> end<? endif; ?>"><?= substr(date('D', ($i+3) * 86400), 0, 3) ?></div>
				<? endfor; ?>
			</div>

			<?
				$daysOfLastMonth = date('N', $startOfMonth) - 1;
				$currentDate = $startOfMonth;
			?>

			<? for($i = 0; $i < 6; $i++): ?>
				<div class="date__option row"<? if($i == 5): ?> id="foot-row"<? endif; ?>>
				<? for($j = 0; $j < 7; $j++): ?>
					<?
						$class = 'cell';

						if($j == 6) {
							$class .= ' end';
						}

						if($daysOfLastMonth) {
							// End of last month
							$date = strtotime("-{$daysOfLastMonth} days", $startOfMonth);
							$class .= ' inactive';
							$daysOfLastMonth--;
						} else {
							// Current month
							$date = $currentDate;
							$currentDate = strtotime('+1 day', $currentDate);

							// Start of next month
							if(date('n', $date) != date('n', $startOfMonth)) {
								$class .= ' inactive';
							}
						}
					?>
					<div class="<?= $class ?>">
						<span class="number"><?= date('j', $date) ?></span>
						<div class="event">
							<? if (isset($eventsData['events'][$date])): ?>
								<? $event = reset($eventsData['events'][$date]) ?>
									<?
										$active_class = '';
										If (isset($_GET['day'])) {
											if (date('j', $date) == $_GET['day']) {
												$active_class = 'active';
											}
										}
									?>

									<a
										href="index.php?year=<?= date('Y', $date) ?>&month=<?= date('n', $date) ?>&day=<?= date('d', $date) ?>"
										class="<?= $active_class ?>"
									>
										<?= date('j', $date) ?>
									</a>


							<? endif ?>
						</div>
					</div>
				<? endfor ?>
				</div>

			<? endfor; ?>


		<? if (isset($_GET['year']) && isset($_GET['month']) && isset($_GET['day'])): ?>

			<? $selectdate = mktime(0, 0, 0, $_GET['month'], $_GET['day'], $_GET['year']) ?>



			<div class="event__container">

				<? IF (isset($eventsData['events'][$selectdate])):

					// Main Event Container

					?>

					<? foreach ($eventsData['events'][$selectdate] as $event): ?>

						<? if ($event['hasImage']): ?>
							<div id="iframe-image">
								<img src="<?= $event['imageUrl'] ?>" alt="" style="max-width:360px;max-height:310px;" />
							</div>
						<? endif ?>

						<h2><?= $event['event_name'] ?></h2>
						<p><?= $event['event_desc'] ?></p>

					<? endforeach ?>

				<? ENDIF ?>

			<? endif ?>

		</div>

	</article>

</section>