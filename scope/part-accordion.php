<?php $accordion = get_sub_field('accordion');
  if($accordion) :
    $acc_id = 'acc_' . $uniqid;
?>
  <div id="<?php echo $acc_id; ?>" class="accordion-wrap">
    <?php foreach($accordion as $item) : ?>
      <div class="accordion-title">
        <span class="label"><?php echo $item['accordion_title']; ?></span>
      </div>
      <div class="accordion-content">
        <?php if ($item['accordion_image']) { ?>
          <div class="accordion-header">
            <?php
              $acc_header_id = $acc_id.'_slider';
              $options = [
                'navigation' => true,
                'size'       => 'wide'
              ];
              create_slider( $acc_header_id, $item['accordion_image'], $options );
            ?>
          </div>
        <?php } ?>
        <div class="accordion-text">
          <?php echo $item['accordion_text']; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
