<?php
/**
 * Please see single-event.php in this directory for detailed instructions on how to use and modify these templates.
 *
 * Override this template in your own theme by creating a file at:
 *
 *     [your-theme]/tribe-events/month/mobile.php
 *
 * @version  4.3.5
 */
?>

<script type="text/html" id="tribe_tmpl_month_mobile_day_header">
	<div class="tribe-mobile-day" data-day="[[=date]]">[[ if(has_events) { ]]
		<h3 class="tribe-mobile-day-heading title --h2">[[=i18n.for_date]] <span>[[=raw date_name]]<\/span><\/h3>[[ } ]]
	<\/div>
</script>

<script type="text/html" id="tribe_tmpl_month_mobile">
	<div class="tribe-events-mobile tribe-clearfix tribe-events-mobile-event-[[=eventId]][[ if(categoryClasses.length) { ]] [[= categoryClasses]][[ } ]]">
		<h4 class="summary title --h6 __nomargin">
			<a class="url title__link link" href="[[=permalink]]" title="[[=title]]" rel="bookmark">[[=raw title]]<\/a>
		<\/h4>

		<div class="tribe-events-event-body">
			<div class="tribe-events-event-schedule-details">
                <p class="tribe-events__text text __nomargin">
                    <span class="tribe-event-date-start">[[=dateDisplay]] <\/span>
                <\/p>
			<\/div>

            <p class="tribe-events__text text">
	            <a href="[[=permalink]]" class="tribe-events-read-more text__link link" rel="bookmark">[[=i18n.find_out_more]]<\/a>
            <\/p>
		<\/div>
	<\/div>
</script>
