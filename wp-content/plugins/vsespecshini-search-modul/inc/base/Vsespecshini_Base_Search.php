<?php


/**
 * Класс для работы с поиском и атрибутами товаров.
 */
class Vsespecshini_Base_Search
{
    protected $wpdb;
    protected $attribute_names = [];
    protected $table_name;

    /**
     * Конструктор класса. Инициализирует глобальные переменные и хуки.
     */
    public function __construct()
    {
        $this->wpdb = $GLOBALS['wpdb'];
        $this->table_name = $this->wpdb->prefix . "attribute_count";
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Обновляет таблицу с атрибутами.
     */
    public static function update_attribute_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "attribute_count";
        $attribute_names = ['pa_tyre_width', 'pa_tyre_profile', 'pa_tyre_rim', 'pa_load_index', 'pa_speed_index'];

        foreach ($attribute_names as $attribute_name) {
            $results = $wpdb->get_results("
            SELECT meta_value, COUNT(meta_value) as count
            FROM {$wpdb->postmeta}
            WHERE meta_key = 'attribute_" . $attribute_name . "'
            GROUP BY meta_value
        ");

            foreach ($results as $row) {
                $wpdb->replace(
                    $table_name,
                    [
                        'attribute_name' => $attribute_name,
                        'attribute_value' => $row->meta_value,
                        'product_count' => $row->count,
                    ],
                    [
                        '%s',
                        '%s',
                        '%d',
                    ]
                );
            }
        }
    }

    /**
     * Создает таблицу для хранения данных о атрибутах, если она еще не существует.
     */
    public function create_table_if_not_exists()
    {
        if ($this->wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") != $this->table_name) {
            $sql = "CREATE TABLE {$this->table_name} (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        attribute_name varchar(255) NOT NULL,
        attribute_value varchar(255) NOT NULL,
        product_count mediumint(9) NOT NULL,
        UNIQUE KEY id (id)
    );";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    /**
     * Регистрирует и подключает скрипты и стили.
     */
    public function enqueue_assets()
    {
        if (is_front_page()) {
            $plugin_dir = plugin_dir_url(__FILE__);

            $styles_file = 'vsespecshini-styles.css';
            $scripts_file = 'vsespecshini-scripts.js';

            $styles_version = filemtime(plugin_dir_path(__FILE__) . $styles_file);
            $scripts_version = filemtime(plugin_dir_path(__FILE__) . $scripts_file);

            wp_enqueue_style(
                'vsespecshini-styles',
                $plugin_dir . $styles_file,
                [],
                $styles_version
            );

            wp_enqueue_script(
                'vsespecshini-scripts',
                $plugin_dir . $scripts_file,
                ['jquery'],
                $scripts_version,
                true
            );
        }
    }


    public function create_disk_table_if_not_exists() {
        $disk_table_name = $this->wpdb->prefix . "disk_attribute_count";
        if ($this->wpdb->get_var("SHOW TABLES LIKE '{$disk_table_name}'") != $disk_table_name) {
            $sql = "CREATE TABLE {$disk_table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            attribute_name varchar(255) NOT NULL,
            attribute_value varchar(255) NOT NULL,
            product_count mediumint(9) NOT NULL,
            UNIQUE KEY id (id)
        );";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public static function update_disk_attribute_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . "disk_attribute_count";
        $attribute_names = ['pa_disk_width', 'pa_disk_diameter', 'pa_disk_offset', 'pa_disk_hub_diameter'];

        foreach ($attribute_names as $attribute_name) {
            $results = $wpdb->get_results("
        SELECT meta_value, COUNT(meta_value) as count
        FROM {$wpdb->postmeta}
        WHERE meta_key = 'attribute_" . $attribute_name . "'
        GROUP BY meta_value
    ");

            foreach ($results as $row) {
                $wpdb->replace(
                    $table_name,
                    [
                        'attribute_name' => $attribute_name,
                        'attribute_value' => $row->meta_value,
                        'product_count' => $row->count,
                    ],
                    [
                        '%s',
                        '%s',
                        '%d',
                    ]
                );
            }
        }
    }

}

// Регистрируем периодическое событие, если оно еще не зарегистрировано
if (!wp_next_scheduled('update_attribute_table_hook')) {
    wp_schedule_event(time(), 'daily', 'update_attribute_table_hook');
}

// Подключаем метод класса к хуку
add_action('update_attribute_table_hook', ['Vsespecshini_Base_Search', 'update_attribute_table']);

// Регистрируем периодическое событие, если оно еще не зарегистрировано
if (!wp_next_scheduled('update_disk_attribute_table_hook')) {
    wp_schedule_event(time(), 'daily', 'update_disk_attribute_table_hook');
}

// Подключаем метод класса к хуку
// Подключаем метод класса к хуку
add_action('update_disk_attribute_table_hook', ['Vsespecshini_Base_Search', 'update_disk_attribute_table']);

Vsespecshini_Base_Search::update_disk_attribute_table();