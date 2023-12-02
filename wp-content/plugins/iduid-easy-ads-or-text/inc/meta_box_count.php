<?php wp_nonce_field($this->varPref."_meta_box_content", $this->varPref.'_query_nonce'); ?>

<ul>
	<li><label>Active:</label> <input type="checkbox" name="<?php echo $this->varPref; ?>_countactive" value="" <?php echo ($Data['countactive'] == 'true'?'checked':'');?>/></li>
	<li><label>Views:</label> <input type="number" name="<?php echo $this->varPref; ?>_countviews" value="<?php echo $Data['countviews'];?>"/></li>
</ul>
