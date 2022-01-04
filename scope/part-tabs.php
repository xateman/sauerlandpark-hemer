<?php $tabs = get_sub_field('tabs');
  if($tabs) :
  $i = 1;
  $n = 1;
?>
<div class="tab-wrap">
  <ul class="tab-navigation">
    <?php foreach($tabs as $tab) : ?>
      <li><a href="#tab-<?php echo $i; ?>"><?php echo $tab['tab_title']; ?></a></li>
      <?php $i++; ?>
    <?php endforeach; ?>
  </ul>
  <?php foreach($tabs as $tab) : ?>
    <div id="tab-<?php echo $n; ?>" class="tab-content"><?php echo $tab['tab_text']; ?></div>
    <?php $n++; ?>
  <?php endforeach; ?>
</div>
<?php endif; ?>
