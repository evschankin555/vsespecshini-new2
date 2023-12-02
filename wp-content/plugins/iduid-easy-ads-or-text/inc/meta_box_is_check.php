<?php wp_nonce_field($this->varPref."_meta_box_content", $this->varPref.'_query_nonce'); ?>

<ul>
<?php foreach ( $this->metaBoxCheckPage as $key => $val ) { ?>
	<li><input type="checkbox" name="<?php echo $this->varPref; ?>_tocheckpage[]" value="<?php echo $key; ?>" <?php if(is_array($value) AND in_array($key, $value)){?>checked<?php }?>/><?php echo $val; ?></li>
<?php } ?>
</ul>
