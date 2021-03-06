<?php

/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct URL of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type; for example, "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type; for example, story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode; for example, "full", "teaser".
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined; for example, $node->body becomes $body. When needing to
 * access a field's raw values, developers/themers are strongly encouraged to
 * use these variables. Otherwise they will have to explicitly specify the
 * desired field language; for example, $node->body['en'], thus overriding any
 * language negotiation rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup themeable
 */
?>


<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php print $user_picture; ?>

  <?php print render($title_prefix); ?>
  <?php if (!$page): ?>
    <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <div class="content"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
    ?>
      <table class='project-table small-12'>
          <tbody>
            <tr>
							<th>TITLE</th>
							<td><?php echo $node->field_title['und'][0]['safe_value']; ?></td>
						</tr>
            <tr>
							<th>SITE ADDRESS</th>
							<td><?php echo $node->field_address_of_project['und'][0]['safe_value']; ?></td>
						</tr>
            <tr>
							<th>LOCAL GOVERNMENT</th>
							 <td><a href="<?php echo base_path() . drupal_get_path_alias('node/' . $node->field_local_government['und'][0]['nid'])?>"><?php echo $node->field_local_government['und'][0]['node']->title; ?></a></td>
						</tr>
            <tr>
							<th>CONTRACTOR</th>
              <td><a href="<?php echo base_path() . drupal_get_path_alias('node/' . $node->field_contractor['und'][0]['nid'])?>"><?php echo $node->field_contractor['und'][0]['node']->title; ?></a></td>
						</tr>
            <tr>
							<th>PERCENTAGE COMPLETION</th>
              <td><?php $percentage = $node->percentage_complete;
                        echo $percentage;?> %
              </td>
						</tr>
            <tr>
							<th>CONTRACT SUM</th>
              <td>&#x20A6; <?php 
              $contract_sum = $node->field_contract_sum['und'][0]['value'];
              echo 
              number_format($contract_sum, 2, '.', ','); ?></td>
						</tr>
            <?php 
              $without_retention = ((((100-$percentage)/100)*0.95) * (float) $contract_sum);
              $retention = ((0.05) * (float) $node->field_contract_sum['und'][0]['value']);
              if(!$node->retention_paid == 1){
                $amount_due = $without_retention + $retention;
              }else{
                $amount_due = $without_retention;
              }
              
              ?>
            <tr>
							<th>AMOUNT PAID</th>
              <td >&#x20A6; <?php echo number_format(($contract_sum-$amount_due), 2, '.', ',') ?></td>
						</tr>
            <tr>
							<th>AMOUNT DUE (INCL. RETENTION)</th>
              <td>&#x20A6; <?php 
                              echo number_format($amount_due, 2, '.', ','); ?> 
                <?php if($amount_due < 0){
                  echo '<span class="overpaid">(OVERPAID)<span>';
                }
?></td>
						</tr>
            <tr>
							<th>STATUS</th>
              <td id="STATUS"><?php echo $node->status_message; ?></td>
						</tr>
          </tbody>
      </table>
      <?php if (isset($node->field_award_certificate['und']['0']['uri'])): ?>
      <?php 
        $path_uri = $node->field_award_certificate['und']['0']['uri'];
         $path_to_file = file_create_url($path_uri); ?>
         <a target="_blank" href="<?php echo $path_to_file ?>"> 
         <div class="download-link"><img src="<?php echo base_path()?>sites/all/themes/transparent/assets/img/download.png">
          &nbsp; Download Award Certificate </div></a>
      <?php endif; ?>
  </div>

  <?php 
    print render($content['links']);
  ?>

</div>
