<?php

function mwm_accessibility_shortcode( $atts ) {
  if(!is_admin()){
    $atts = shortcode_atts(
      array(
        'titulo' => '',
        'texto' => '',
      ),
      $atts,
      'accessibility_font'
    );

    $contenido = mwm_font_resizer_place();
    // Return custom embed code
    return $contenido;
  }

}
add_shortcode( 'accessibility_font', 'mwm_accessibility_shortcode' );
