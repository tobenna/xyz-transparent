<?php
/**
 * Implements hook_views_data().
 */
function transparent_views_data() {
 $data = [];
  $data['transparent']['table']['group'] = t('Transparent');
  $data['transparent']['table']['join'] = array(
    // Exist in all views.
    '#global' => array(),
  );
 
  $data['transparent']['percentage_total'] = array(
    'title' => t('Percentage Total'),
    'help' => t('Only use when displaying content of project type'),
    'field' => array(
      'handler' => 'views_handler_percentage_completed',
    ),
  );
 
  return $data;
}
