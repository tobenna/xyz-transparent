<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see html.tpl.php
 *
 * @ingroup themeable
 */
?>

  <div class="off-canvas-wrap" data-offcanvas>
		<div class="inner-wrap">
			<aside class="left-off-canvas-menu hide-for-large-up" id="DrawerMenu">

        <a id="exitOut_btn" class="exit-off-canvas"><img src="<?php echo base_path(); ?>sites/all/themes/transparent/assets/img/cancel.svg"></a>
      <?php if ($page['menu_tray']): ?>
          <?php print render($page['menu_tray']); ?>
      <?php endif; ?>
			</aside>
			<header>
				<div class="row hide-for-large-up" id="touch-nav">
					<div id="condensedMobile" class="row">

						<div class="header-menu-icon small-3 columns left-side">
							<a id="mobileMenu_btn" class="left-off-canvas-toggle" href="#"><img src="<?php echo base_path(); ?>sites/all/themes/transparent/assets/img/menuBars_mobile.svg"></a>
						</div>
						<div class="small-6 columns text-center" id="logo-touch">
							<a title="Transparent" href="/"><img src="<?php echo base_path(); ?>sites/all/themes/transparent/assets/img/logo-mobile.svg"></a>
						</div>
						<div class="header-cart-icon small-3 columns right-side">
						</div>
          </div>

				</div>
				<div class="show-for-large-up" id="desktop-nav">
					<div class="row">
						<div class="logo-div">
							<a title="Transparent" href="<?php echo base_path();?>"><img src="<?php echo base_path(); ?>sites/all/themes/transparent/assets/img/logo-dark.svg"></a>
						</div>
						<nav id="primary-nav">
							<?php if ($page['top_menu1']): ?>
                 <?php print render($page['top_menu1']); ?>
               <?php endif; ?>
							<div id="account-nav">
								<?php if ($page['top_menu2']): ?>
                 <?php print render($page['top_menu2']); ?>
               <?php endif; ?>
							</div>
						</nav>
					</div>
				</div>

			</header>
        <div class="title-div">
          <div class="row small-12 large-10 medium-11">
            <?php print render($title_prefix); ?>
            <?php if ($title): ?><h3 class="title" id="page-title"><?php print $title; ?></h3><?php endif; ?>
            <?php print render($title_suffix); ?>
          </div>
        </div>
        
			<!-- Main Page Content-->
			<div class="content row small-12 large-10 medium-11">
				<div class="small-12">
            <?php print $messages; ?>
				</div>
				<!-- MAIN Content Block -->
				<div class="small-12 medium-12 large-8 columns">
            <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>
            <?php print render($page['help']); ?>
            <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
            <?php print render($page['content']); ?>
            <?php print $feed_icons; ?>
				</div>
				<!-- END Content Block -->
				<!-- Side Bar Second -->
        <?php $rid = array_search('Head Accounts', user_roles());
            //dpm($rid);
            //global $user;
            //dpm($user->roles);
            //dpm(user_roles());?>
				<aside id="sidebar" class="small-12 medium-12 large-4 columns">
        <?php if ($page['sidebar']): ?>
          <?php print render($page['sidebar']); ?>
        <?php endif; ?>
				</aside>
        
				<!-- END Side Bar Second -->
			</div>
      <div class="row large-10">
        <?php if ($page['content_bottom']): ?>
          <?php print render($page['content_bottom']); ?>
        <?php endif; ?>
      </div>
			<!-- End Main -->
		</div>
	</div>
