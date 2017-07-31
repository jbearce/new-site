<?php
/**
 * Please see single-event.php in this directory for detailed instructions on how to use and modify these templates.
 *
 * Override this template in your own theme by creating a file at:
 *
 *     [your-theme]/tribe-events/month/tooltip.php
 * @version 4.4
 */
?>

<script type="text/html" id="tribe_tmpl_tooltip">
	<div id="tribe-events-tooltip-[[=eventId]]" class="tribe-events-tooltip">
		<div class="tribe-events-event-body">
            <h4 class="entry-title summary tribe-events-tooltip_text text _bold _nomargin">[[=raw title]]</h4>

			<div class="tribe-event-duration tribe-events-tooltip_text text _italic _small">
				<abbr class="tribe-events-abbr tribe-event-date-start">[[=dateDisplay]] </abbr>
			</div>
            
            [[ if(excerpt.length || imageTooltipSrc.length) { ]]
			<div class="tribe-event-description tribe-event_user-content user-content">
                [[ if(imageTooltipSrc.length) { ]]
                    <p class="tribe-events-event-thumb">
                        <img src="[[=imageTooltipSrc]]" alt="[[=title]]" />
                    </p>
                [[ } ]]

                [[ if(excerpt.length) { ]]
                    [[=raw excerpt]]
                [[ } ]]
            </div>
			[[ } ]]

			<span class="tribe-events-arrow"></span>
		</div>
	</div>
</script>

<script type="text/html" id="tribe_tmpl_tooltip_featured">
	<div id="tribe-events-tooltip-[[=eventId]]" class="tribe-events-tooltip tribe-event-featured">
        <div class="tribe-events-event-body">

    		<h4 class="entry-title summary tribe-events-tooltip_text text _bold">[[=raw title]]</h4>

			<div class="tribe-event-duration tribe-events-tooltip_text text _italic _small">
				<abbr class="tribe-events-abbr tribe-event-date-start">[[=dateDisplay]] </abbr>
			</div>

			[[ if(excerpt.length || imageTooltipSrc.length) { ]]
			<div class="tribe-event-description tribe-event_user-content user-content">
                [[ if(imageTooltipSrc.length) { ]]
                    <p class="tribe-events-event-thumb">
                        <img src="[[=imageTooltipSrc]]" alt="[[=title]]" />
                    </p>
                [[ } ]]

                [[ if(excerpt.length) { ]]
                    [[=raw excerpt]]
                [[ } ]]
            </div>
			[[ } ]]

			<span class="tribe-events-arrow"></span>
		</div>
	</div>
</script>
