<?php
  $span = 'span' . floor((12 / count($rows[0])));
?>
<?php if (!empty($title)) : ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>
<div class="<?php print $class; ?>"<?php print $attributes; ?>>
  <?php foreach ($rows as $row_number => $columns): ?>
    <div <?php if ($row_classes[$row_number]) { print 'class="' . $row_classes[$row_number] .' row"';  } ?>>
      <?php foreach ($columns as $column_number => $item): ?>
        <div <?php if ($column_classes[$row_number][$column_number]) { print 'class="' . $column_classes[$row_number][$column_number] . ' ' . $span . '"';  } ?>>
          <?php print $item; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
</div>