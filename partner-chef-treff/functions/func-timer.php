<?php
/**
 * 1. Додаємо власний інтервал у WP-Cron (щохвилини).
 */
add_filter('cron_schedules', 'my_custom_cron_schedules');
function my_custom_cron_schedules($schedules) {
	if (!isset($schedules['minute'])) {
		$schedules['minute'] = array(
			'interval' => 60,
			'display'  => __('Once Every Minute')
		);
	}
	return $schedules;
}

/**
 * 2. Плануємо подію (якщо ще не заплановано).
 */
add_action('wp', 'schedule_event_datetime_check');
function schedule_event_datetime_check() {
	if (!wp_next_scheduled('check_event_datetime')) {
		wp_schedule_event(time(), 'minute', 'check_event_datetime');
	}
}

/**
 * 3. Логіка перевірки дати й виконання дій (зняття галочки).
 *
 *    - Якщо дата з ACF настала, ставимо 'af_edit_order_enable_edit_btn' => 'no'
 *      (щоб відключити редагування у плагіні Addify).
 *    - Одночасно ставимо 'adfyedit_order_edit_button_field_0' => '0'
 *      (щоб зняти ваш кастомний чекбокс).
 */
add_action('check_event_datetime', 'run_function_on_event_datetime');
function run_function_on_event_datetime() {

	// Зчитуємо дату з ACF (поле event_datetime в Options)
	$event_datetime = get_field('event_datetime', 'options');
	if ($event_datetime) {
		$event_timestamp   = strtotime($event_datetime);
		$current_timestamp = time();
		$last_executed_event = get_option('last_executed_event_datetime', '');

		// Якщо час настав і ще не виконувалося для цієї дати
		if ($current_timestamp >= $event_timestamp && $last_executed_event !== $event_datetime) {

			// 1) Вимкнути глобальний чекбокс плагіна Addify
			$af_settings = get_option('af_edit_order_general_settings', array());
			$af_settings['af_edit_order_enable_edit_btn'] = 'no';
			update_option('af_edit_order_general_settings', $af_settings);

			// 2) Вимкнути ваш кастомний чекбокс (поле "adfyedit_order_edit_button_field_0")
			//    Припустимо, "0" означає знято, а "1"/"edit" означає ввімкнено
			update_option('adfyedit_order_edit_button_field_0', '0');

			// Запам'ятовуємо, що для цієї дати вже виконано
			update_option('last_executed_event_datetime', $event_datetime);
		}
	}
}

/**
 * 4. JS у адмінці — «насильно» зняти обидва чекбокси,
 *    якщо в базі вже "no"/"0".
 */
add_action('admin_print_footer_scripts', 'add_checkbox_js', 99);
function add_checkbox_js() {
	if (is_admin()) :
		?>
      <script>
          document.addEventListener('DOMContentLoaded', function() {

              // Перший чекбокс (Addify)
              var checkboxNameAddify = 'af_edit_order_enable_edit_btn';

              // Другий чекбокс (кастомний)
              var checkboxNameCustom = 'adfyedit_order_edit_button_field_0';

              // 3-секундна затримка
              setTimeout(function(){

                  // 1) Перевіряємо Addify-поле
                  fetch(ajaxurl + '?action=get_checkbox_status')
                      .then(response => response.json())
                      .then(data => {
                          if (data.success && data.data.value !== 'yes') {
                              var checkboxAddify = document.querySelector('input[name="' + checkboxNameAddify + '"]');
                              if (checkboxAddify) {
                                  checkboxAddify.checked = false;
                                  checkboxAddify.removeAttribute('checked');
                              }
                          }
                      });

                  // 2) Перевіряємо ваше кастомне поле
                  fetch(ajaxurl + '?action=get_checkbox_status_custom')
                      .then(response => response.json())
                      .then(data => {
                          if (data.success && data.data.value === '0') {
                              var checkboxCustom = document.querySelector('input[name="' + checkboxNameCustom + '"]');
                              if (checkboxCustom) {
                                  checkboxCustom.checked = false;
                                  checkboxCustom.removeAttribute('checked');
                              }
                          }
                      });

              }, 3000);
          });
      </script>
	<?php
	endif;
}

/**
 * 5a. Обробник AJAX для «Addify» поля 'af_edit_order_enable_edit_btn'
 */
add_action('wp_ajax_get_checkbox_status', 'get_checkbox_status');
function get_checkbox_status() {
	$af_settings = get_option('af_edit_order_general_settings', array());
	$current_value = isset($af_settings['af_edit_order_enable_edit_btn'])
		? $af_settings['af_edit_order_enable_edit_btn']
		: 'no';

	wp_send_json_success(array('value' => $current_value));
}

/**
 * 5b. Обробник AJAX для вашого поля "adfyedit_order_edit_button_field_0"
 *     Якщо там '0', значить "вимкнено".
 *     Якщо там '1' або 'edit', значить "увімкнено".
 */
add_action('wp_ajax_get_checkbox_status_custom', 'get_checkbox_status_custom');
function get_checkbox_status_custom() {
	$value = get_option('adfyedit_order_edit_button_field_0', '0');
	wp_send_json_success(array('value' => $value));
}
