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
        <div class="tribe-events-event-header">
            <h4 class="entry-title summary">[[=raw title]]</h4>
        </div>

		<div class="tribe-events-event-body">
            <ul class="tribe-events-meta-list _nomargin">
                <li class="tribe-events-meta-list-item">
                    <div class="tribe-event-duration">
                        <i class="tribe-events-meta-list-icon far fa-fw fa-clock"></i>
                        <abbr class="tribe-events-abbr tribe-event-date-time">[[=raw date]][[ if(typeof time === 'string' && time.length) { ]] <span class="tribe-events-meta-list-small">[[=raw time]]</span>[[ } ]]</abbr>
                    </div>
                </li>
                [[ if(typeof venue === 'string' && venue.length){ ]]
                <li class="tribe-events-meta-list-item">
                    <div class="tribe-event-location">
                        <i class="tribe-events-meta-list-icon fas fa-fw fa-map-marker-alt"></i>
                        <abbr class="tribe-events-abbr tribe-event-venue">[[=venue]][[ if(typeof address === 'string' && address.length) { ]] <span class="tribe-events-meta-list-small">[[=address]]</span>[[ } ]]</abbr>
                    </div>
                </li>
                [[ } ]]
                [[ if(typeof categories === 'string' && categories.length){ ]]
                <li class="tribe-events-meta-list-item">
                    <div class="tribe-event-terms">
                        <i class="tribe-events-meta-list-icon far fa-fw fa-calendar"></i>
                        <abbr class="tribe-events-abbr tribe-event-categories">[[=categories]]</abbr>
                    </div>
                </li>
                [[ } ]]
            </ul>
		</div>
	</div>
</script>

<script type="text/html" id="tribe_tmpl_tooltip_featured">
	<div id="tribe-events-tooltip-[[=eventId]]" class="tribe-events-tooltip tribe-event-featured">
        <div class="tribe-events-event-header">
            <h4 class="entry-title summary">[[=raw title]]</h4>
        </div>

		<div class="tribe-events-event-body">
            <ul class="tribe-events-meta-list _nomargin">
                <li class="tribe-event-meta-list-item">
                    <div class="tribe-event-duration">
                        <i class="tribe-events-meta-list-icon far fa-fw fa-clock"></i>
                        <abbr class="tribe-events-abbr tribe-event-date-time">[[=raw date]][[ if(typeof time === 'string' && time.length) { ]] <span class="tribe-events-meta-list-small">[[=raw time]]</span>[[ } ]]</abbr>
                    </div>
                </li>
                [[ if(typeof venue === 'string' && venue.length){ ]]
                <li class="tribe-events-meta-list-item">
                    <div class="tribe-event-location">
                        <i class="tribe-events-meta-list-icon fas fa-fw fa-map-marker-alt"></i>
                        <abbr class="tribe-events-abbr tribe-event-venue">[[=venue]][[ if(typeof address === 'string' && address.length) { ]] <span class="tribe-events-meta-list-small">[[=address]]</span>[[ } ]]</abbr>
                    </div>
                </li>
                [[ } ]]
                [[ if(typeof categories === 'string' && categories.length){ ]]
                <li class="tribe-events-meta-list-item">
                    <div class="tribe-event-terms">
                        <i class="tribe-events-meta-list-icon far fa-fw fa-calendar"></i>
                        <abbr class="tribe-events-abbr tribe-event-categories">[[=categories]]</abbr>
                    </div>
                </li>
                [[ } ]]
            </ul>
		</div>
	</div>
</script>
