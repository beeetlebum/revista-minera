<?php
defined( 'ABSPATH' ) || die();
?>
 <div class="xpro-weather-widget"><?php
    if ( isset( $data['error'] ) ) {
        echo '<div class="weather-error">' . esc_html( $data['error'] ) . '</div>';
        return;
    }
    $forecast_items = array();
    if ( isset( $data['forecastHours'] ) && is_array( $data['forecastHours'] ) ) {
        $forecast_items = $data['forecastHours'];
    }
    if ( empty( $forecast_items ) ) {
        echo '<div class="weather-error">No forecast data available</div>';
        return;
    }
    $max_hours = ! empty( $settings['forecast_hours'] ) ? intval( $settings['forecast_hours'] ) : 24;
    $forecast_items = array_slice( $forecast_items, 0, $max_hours );
    
    $current = $forecast_items[0];
    $temp_unit = ! empty( $settings['temperature_unit'] ) ? $settings['temperature_unit'] : 'C';
    $layout = isset( $settings['weather_layout'] ) ? $settings['weather_layout'] : 'layout-1';
    // Render selected layout
    switch ( $layout ) {
        case 'layout-2':
            $this->render_layout_2( $current, $forecast_items, $settings, $temp_unit );
            break;
        case 'layout-3':
            $this->render_layout_3( $current, $forecast_items, $settings, $temp_unit );
            break;
        case 'layout-4':
            $this->render_layout_4( $current, $forecast_items, $settings, $temp_unit );
            break;
        case 'layout-5':
            $this->render_layout_5( $current, $forecast_items, $settings, $temp_unit );
            break;
        default:
            $this->render_layout_1( $current, $forecast_items, $settings, $temp_unit );
            break;
    }
    ?>
   
    </div>