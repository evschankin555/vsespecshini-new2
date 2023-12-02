<?php wp_nonce_field($this->varPref."_meta_box_content", $this->varPref.'_query_nonce'); ?>

<ul>
<?php foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { ?>
	<li><input type="checkbox" name="<?php echo $this->varPref; ?>_toside[]" value="<?php echo ucwords( $sidebar['id'] ); ?>" <?php if(is_array($value) AND in_array(ucwords($sidebar['id']), $value)){?>checked<?php }?>/><?php echo ucwords( $sidebar['name'] ); ?></li>
<?php } ?>
</ul>