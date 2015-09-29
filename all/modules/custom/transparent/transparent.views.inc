<?php
/**
 * Implements hook_views_data().
 */
function transparent_views_data() {
 $data = [];
  $data['transparent']['table']['group'] = t('Transparent');
  $data['transparent']['table']['join'] = [
    '#global' => [],
    ];
 
  $data['transparent']['percentage_total'] = [
    'title' => t('Percentage Total'),
    'help' => t('Only use when displaying content of project type'),
    'field' => [
      'handler' => 'views_handler_percentage_completed',
      ],
    ];
  $data['transparent']['amount_paid'] = [
    'title' => t('Total Amount Paid'),
    'help' => t('Only use when displaying content of project type'),
    'field' => [
      'handler' => 'views_handler_amount_paid',
      'click sortable' => TRUE,
      'float' => TRUE,
      ],
    'filter' => [
      'handler' => 'views_handler_filter_numeric',
     ],
    'sort' => [
      'handler' => 'views_handler_sort',
     ],
    ];
 
  return $data;
}
