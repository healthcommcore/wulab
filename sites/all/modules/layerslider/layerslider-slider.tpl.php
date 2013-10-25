<div id="layerslider-<?php print $id; ?>" class="layerslider" style="width: <?php print $width; ?>px;height: <?php print $height; ?>px">
	<?php foreach ($layers as $layer): ?>
		<div class="ls-layer" rel="<?php print $layer['rel']; ?>">
			<?php foreach ($layer['sublayers'] as $sublayer) : ?>
				<?php print render($sublayer); ?>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</div>