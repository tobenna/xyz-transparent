<?php
/**
 * @file
 * Custom views handler definition.
 *
 * Place this code in
 * /sites/all/[custom_module_name]/includes/views_handler_my_custom_field.inc
 */
 
/**
 * Custom handler class.
 *
 * @ingroup views_field_handlers
 */
class views_handler_amount_paid extends views_handler_field_numeric {
  /**
   * {@inheritdoc}
   *
   * Perform any database or cache data retrieval here. In this example there is
   * none.
   */
  function query() {
    
  }
 
  /**
   * {@inheritdoc}
   *
   * Modify any end user views settings here. Debug $options to view the field
   * settings you can change.
   */
  function option_definition() {
    $options = parent::option_definition();
    return $options;
  }
 
  /**
   * {@inheritdoc}
   *
   * Make changes to the field settings form seen by the end user when adding
   * your field.
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
  }
  
  /**
   * Render callback handler.
   *
   * Return the markup that will appear in the rendered field.
   */
  function render($values) {
    $nid = $values->nid;
    //dpm($valsues);
    $this->options['precision'] = '2';
    $contract_sum = (float) $values->field_field_contract_sum[0]['raw']['value'];
    if($values->node_type == 'project'){
      $percentage = (float) get_percentage_total($nid);
      $value = get_total_sum($nid, $contract_sum, $percentage);
      
    }else{
      $value = 0; 
    }
    if (!empty($this->options['set_precision'])) {
      $value = number_format($value, $this->options['precision'], $this->options['decimal'], $this->options['separator']);
    }
    else {
      $remainder = abs($value) - intval(abs($value));
      $value = $value > 0 ? floor($value) : ceil($value);
      $value = number_format($value, 0, '', $this->options['separator']);
      if ($remainder) {
        // The substr may not be locale safe.
        $value .= $this->options['decimal'] . substr($remainder, 2);
      }
    }

    // Check to see if hiding should happen before adding prefix and suffix.
    if ($this->options['hide_empty'] && empty($value) && ($value !== 0 || $this->options['empty_zero'])) {
      return '';
    }

    // Should we format as a plural.
    if (!empty($this->options['format_plural'])) {
      $value = format_plural($value, $this->options['format_plural_singular'], $this->options['format_plural_plural']);
    }

    return $this->sanitize_value($this->options['prefix'], 'xss')
      . $this->sanitize_value($value)
      . $this->sanitize_value($this->options['suffix'], 'xss');
  }
}

function get_percentage_total($nid){
  $query = new EntityFieldQuery();
     $query->entityCondition('entity_type', 'node')
         ->entityCondition('bundle', 'project_step')
         ->fieldCondition('field_project', 'nid', $nid)
         ->fieldCondition('field_paid', 'value','1')
         ->addMetaData('account', user_load(1)); // Run the query as user 1.
     $result = $query->execute();
     $percentage = 0;
     if (isset($result['node'])) {
        $project_steps = $result['node'];
        // At first we need to get field's id. If you already know field id, you can ommit this step
        // Get all fields attached to a given node type
        $fields = field_info_instances('node', 'project_step');
        // Get id of body field
        $field_id = $fields['field_percentage_of_total']['field_id'];
        field_attach_load('node', $project_steps, FIELD_LOAD_CURRENT, array('field_id' => $field_id));
        
        if(!$project_steps == NULL){
          foreach ($project_steps as $project_step){
            $percentage += (float) $project_step->field_percentage_of_total['und'][0]['value'];
          }
        }
     }
     return $percentage;
}

function get_total_sum( $nid, $contract_sum, $percentage){
  $query = new EntityFieldQuery();
     $query->entityCondition('entity_type', 'node')
         ->entityCondition('bundle', 'retention')
         ->fieldCondition('field_project', 'nid', $nid)
         ->fieldCondition('field_paid', 'value','1')
         ->addMetaData('account', user_load(1)); // Run the query as user 1.
     $result = $query->execute();
     if (isset($result['node'])) {
       //HAS RETENTION PAID.
       $total_paid = (0.95 * ((float) ($percentage/100)) * (float) $contract_sum) + (0.05 * (float)($contract_sum));
     }else{
       $total_paid = (0.95 * ((float) ($percentage/100)) * (float) $contract_sum);
     }
     $total_value = round($total_paid, 2);
     return $total_value;
  
}