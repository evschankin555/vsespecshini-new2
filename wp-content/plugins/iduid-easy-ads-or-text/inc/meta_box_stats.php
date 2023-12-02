<?php if ( ! defined( 'ABSPATH' )) {die();} ?>

<label>
    <select name="period" id="period">
        <option <?php selected( $this->period, "daily" ); ?> value="daily"><?php _e( 'Daily', $this->varPref ); ?></option>
        <option <?php selected( $this->period, "weekly" ); ?> <?php if ( empty ( $this->period ) ) {echo "selected"; } ?> value="weekly"><?php _e( 'Weekly', $this->varPref ); ?></option>
        <option <?php selected( $this->period, "monthly" ); ?> value="monthly"><?php _e( 'Monthly', $this->varPref ); ?></option>
    </select>
</label>

<span id="metricaloading"></span>
<canvas id="metrica-graph" style="width:100%; height: 400px;"></canvas>
<div id="metrica-graph-warning"></div>

<div id="metrica-widget-data">
    <table width="100%">
        <tr>
            <td width="20%">
                <b><?php _e( 'Visits', $this->varPref ); ?>:</b>
            </td>
            <td width="20%">
                <?php
                if ( ! empty( $total )) {
                    echo $total;
                } else {
                     _e( 'None', $this->varPref );
                }
                ?>
            </td>
        </tr>

    </table>

</div>

<style>
    .metrica-inside .closed{
        display: none;
    }
</style>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		<?php if( ! is_array( $statical_data ) || empty( $statical_data ) ) { ?>
		$('#metrica-graph').hide();
		$('#metrica-graph-warning').html("<p><?php _e('Sorry, couldn\'t draw graph for selected period, please try different time period.',$this->varPref);?></p>");
		<?php } else { ?>

		window.chartColors = {red: 'rgb(255, 99, 132)', orange: 'rgb(255, 159, 64)', yellow: 'rgb(255, 205, 86)', green: 'rgb(75, 192, 192)', blue: 'rgb(54, 162, 235)', purple: 'rgb(153, 102, 255)', grey: 'rgb(201, 203, 207)'};

		var data = {
			labels: [
				<?php
				$date_format = ( $this->period != "monthly" ? 'D' : 'd M' );
				foreach(  $statical_data as $date => $stats_item ){
					echo "'" .date_i18n($date_format, strtotime( $date ) ). "',";
				}
				?>
			],

			datasets: [
				{
					label: "<?php echo __('Visits',$this->varPref);?>",
					backgroundColor: window.chartColors.blue,
					borderColor: window.chartColors.blue,
					fill: false,
					data: [
						<?php foreach( $statical_data as $item){
						echo $item["visits"].",";
					};?>
					]
				}
			]
		};

		var context = document.querySelector('#metrica-graph').getContext('2d');

		new Chart(context, {
			type: '<?php echo( $this->period == "daily" ? 'bar' : 'line' );?>',
			data   : data,
			options: {
				responsive: true,
				title     : {
					display: true,
					text   : ''
				},
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true
						}
					}]
				},
				<?php if('daily' !== $this->period):?>
				tooltips: {
					mode: 'index'
				}
				<?php endif;?>
			}
		});



		<?php } ?>

		$(document).on("change", "#period", function () {
			jQuery.ajax({
				type : 'post',
				url  : 'admin-ajax.php',
				cache: false,
				data : {
					action     : '<?php echo $this->varPref?>_actions',
					period     : $(this).val(),
					_ajax_nonce: '<?php echo wp_create_nonce($this->varPref.'_query_nonce');?>'
				},
				beforeSend: function () {
					jQuery("#metricaloading").html('<img src="<?php echo admin_url("images/wpspin_light.gif")?>" />').show();
				},
				success: function (html) {
					jQuery("#metricaloading").hide();
					jQuery('#yandex_metrica_widget .inside').html(html);
					return true;
				}
			});
		});

    });

</script>