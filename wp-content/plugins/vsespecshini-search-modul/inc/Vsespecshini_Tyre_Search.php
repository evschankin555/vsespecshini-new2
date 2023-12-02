<?php
class Vsespecshini_Tyre_Search extends Vsespecshini_Base_Search {
    public function __construct() {
        parent::__construct();

        $this->setAttributes([
            'pa_tyre_width' => 'Ширина',
            'pa_tyre_profile' => 'Профиль',
            'pa_tyre_rim' => 'Диаметр',
          //  'pa_load_index' => 'Индекс нагрузки',
         //   'pa_speed_index' => 'Индекс скорости'
        ]);

        add_shortcode('vsespecshini_search_tyre', [$this, 'search_tyre_shortcode']);
        add_action('update_attribute_table_hook', [$this, 'update_attribute_table']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function setAttributes($attributes) {
        $this->attribute_names = array_keys($attributes);
        $this->attribute_readable_names = $attributes;
    }

    public function prepare_data() {
        global $wpdb;
        $table_name = $wpdb->prefix . "attribute_count";
        $attribute_options = [];

        foreach ($this->attribute_names as $attribute_name) {
            $attribute_values = $wpdb->get_results("
                SELECT attribute_value, product_count
                FROM {$table_name}
                WHERE attribute_name = '$attribute_name'
                ORDER BY product_count DESC
            ");
            $attribute_options[$attribute_name] = $attribute_values;
        }

        return [
            'is_logged_in' => is_user_logged_in() ? 'true' : 'false',
            'attribute_options' => $attribute_options,
            'attribute_readable_names' => $this->attribute_readable_names
        ];
    }

    private function render_attribute_options($attribute_options, $attribute_readable_names) {
        foreach ($this->attribute_names as $attribute_name) {
            echo '<div class="form-group">';
            echo '<label>'. $attribute_readable_names[$attribute_name] .'</label>';
            echo '<select name="'. $attribute_name .'" class="form-control custom-control-select">';
            echo '<option value="">Неважно</option>';
            foreach ($attribute_options[$attribute_name] as $option) {
                echo '<option value="'. $option->attribute_value .'">'. $option->attribute_value .'</option>';
            }
            echo '</select></div>';
        }
    }

    public function render($data) {
        ob_start();
        $is_logged_in = $data['is_logged_in'];
        $attribute_options = $data['attribute_options'];
        $attribute_readable_names = $data['attribute_readable_names'];

        ?>
        <div class="home-search-layout inner" style="display: none" data-loggedin="<?php echo $is_logged_in; ?>">
            <div class="card card-home-search card-home-search-tyre">
                <div class="card-home-search-tabs">
                    <span class="title">Подбор шин</span>
                </div>
                <div class="panel panel-by-size show" data-panel="by-size">
                    <form action="/filter/tyre/" method="post">
                        <div class="search-tyre-size">
                            <?php $this->render_attribute_options($attribute_options, $attribute_readable_names); ?>
                        </div>
                        <div class="form-submit">
                            <button type="submit" class="btn btn-filter button">Подобрать шины</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function search_tyre_shortcode() {
        $data = $this->prepare_data();
        return $this->render($data);
    }
}

$tyre_search = new Vsespecshini_Tyre_Search();
$tyre_search->create_table_if_not_exists();
