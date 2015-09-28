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
class views_handler_percentage_completed extends views_handler_field {
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
    //dpm($values);
    $nid = $values->nid;
    if($values->node_type == 'project'){
      return get_percentage_completed($nid);
    }else{
      return "Cannot use for this Content Type :P"; 
    }
  }
}


function get_percentage_completed($nid){
  $query = new EntityFieldQuery();
     $query->entityCondition('entity_type', 'node')
         ->entityCondition('bundle', 'project_step')
         ->fieldCondition('field_project', 'nid', $nid)
         ->fieldCondition('field_paid', 'value','1')
         ->addMetaData('account', user_load(1)); // Run the query as user 1.
     $result = $query->execute();
     $result_keys = [];
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
