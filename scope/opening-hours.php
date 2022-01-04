<h2><?php _e('Aktuelle Öffnungszeiten', LOCAL); ?></h2>

<?php
$OHs = get_opening_hours('all');
$label = (object) [
  'date' => __('Datum', LOCAL),
  'time' => __('Zeitraum', LOCAL),
];
if ( is_array( $OHs) ) {

  ?>
  <div class="timetable">
    <div class="table-head date">
      <b><?php echo $label->date; ?></b>
    </div>
    <div class="table-head time">
      <b><?php echo $label->time; ?></b>
    </div>
  <?php

  foreach ( $OHs as $OH ) {

    $start_date = new DateTime($OH->dates['start']);
    $end_date   = new DateTime($OH->dates['end']);

    if ( $OH->message != '' ) {
      $times = $OH->message;
    } else {
      $times = $OH->times['start'] . ' Uhr - ' . $OH->times['end'] . ' Uhr';
    }

    $dates = $start_date->format('d.m.Y') . ' - ' . $end_date->format('d.m.Y');
    echo '<div class="table-cell date" data-label="'.$label->date.'">'.$dates.'</div>';
    echo '<div class="table-cell time" data-label="'.$label->time.'">'.$times.'</div>';

  }

  ?>
  </div>
  <?php

} else if ( is_object($OHs ) ) {
  $start_date = new DateTime($OHs->dates['start']);
  $end_date   = new DateTime($OHs->dates['end']);
  ?>
  <p>Der Sauerlandpark ist vom <b><?php echo $start_date->format('d.m.Y'); ?></b> bis zum <b><?php echo $end_date->format('d.m.Y'); ?></b> von <b><?php echo $OHs->times['start']; ?></b> - <b><?php echo $OHs->times['end']; ?></b> Uhr geöffnet.</p>
  <?php
}