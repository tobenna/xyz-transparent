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
<?php 
function convert_number_to_words($number) {
    
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
    
    if (!is_numeric($number)) {
        return false;
    }
    
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
    
    $string = $fraction = null;
    
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
    
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
    
    return $string;
}
?>
<div>
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
      dpm($node);
    ?>
      <table class='project-table'>
          <tbody>
            <tr>
							<th>TITLE</th>
							<td><?php echo $node->field_project['und'][0]['node']->field_title['und'][0]['safe_value']; ?></td>
						</tr>
            <tr>
							<th>SITE LOCATION</th>
							<td><?php echo $node->field_project['und'][0]['node']->field_address_of_project['und'][0]['safe_value']; ?></td>
						</tr>
            <tr>
							<th>LOT NUMBER</th>
							<td><a href="<?php echo base_path() . drupal_get_path_alias('node/' . $node->field_project['und'][0]['nid']);?>"><?php echo $node->field_project['und'][0]['node']->title; ?></a></td>
						</tr>
            <tr>
							<th>CONTRACTOR</th>
							<td><a href="<?php 
              $contractor = node_load($node->field_project['und'][0]['node']->field_contractor['und'][0]['nid']);
              echo base_path() . drupal_get_path_alias('node/' . $node->field_project['und'][0]['node']->field_contractor['und'][0]['nid']);?>"><?php echo $contractor->title; ?></a></td>
						</tr>
            <tr>
							<th>CONTRACT SUM</th>
              <td>&#x20A6; <span class="table-number"><?php echo number_format($node->contract_sum, 2, '.', ','); ?></span></td>
						</tr>
            <tr>
							<th>RETENTION</th>
              <td>&#x20A6; <span class="table-number">&dash;<?php echo number_format((string) (0.05 * (float) $node->contract_sum), 2, '.', ','); ?></span></td>
						</tr>
            <tr>
							<th>PREVIOUS PAYMENTS</th>
              <?php 
                /**
                  * Helper function to get percentage of previous payments
                  */
                 function get_completeion_status($node){
                   $query = new EntityFieldQuery();
                      $query->entityCondition('entity_type', 'node')
                          ->entityCondition('bundle', 'project_step')
                          ->fieldCondition('field_project', 'nid', $node->nid)
                          ->fieldCondition('field_paid', 'value','1')
                          ->addMetaData('account', user_load(1)); // Run the query as user 1.
                      $result = $query->execute();
                      $result_keys = [];
                      $percentage = 0;
                      if(!$result == NULL){
                        $result_keys = array_keys($result['node']);
                        $nodes = node_load_multiple($result_keys);
                        foreach ($nodes as $step) {
                          $percentage += (float) $step->field_percentage_of_total['und'][0]['value'];

                        }
                      }
                      return $percentage;
                 }
              
              $previous_payments = (0.01 *((float) (get_completeion_status($node->field_project['und'][0]['node'])))) * (float) (($node->contract_sum) * 0.95);
              
              ?>
              
              <td>&#x20A6; <span class="table-number"><?php echo number_format((string) ($previous_payments), 2, '.', ','); ?></span></td>

						</tr>
            <tr class="ammount-due">
							<th>AMOUNT DUE (<?php (float) $percentage_total = $node->field_percentage_of_total['und'][0]['value'];
                echo $percentage_total;?>%)</th>
              <td>&#x20A6; <span class="table-number"><?php 
                $amount_due = ($percentage_total/100) * 0.95* (float)($node->contract_sum);
              echo number_format((string) ($amount_due), 2, '.', ','); ?></span></td>
						</tr>
            <tr>
							<th>IN WORDS</th>
              <td class="in-words"><?php echo convert_number_to_words($amount_due); ?> NAIRA</td>
						</tr>
          </tbody>
      </table>
  </div>

  <?php print render($content['links']); ?>

</div>
