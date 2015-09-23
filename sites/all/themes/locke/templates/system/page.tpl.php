<?php
/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see html.tpl.php
 */
?>

<!-- ______________________ HEADER _______________________ -->

<?php include('header.tpl.php'); ?>

<main id="main-wrapper" role="main">
  <div id="main" class="main <?php print (!$is_panel) ? 'container' : ''; ?>">
    <?php if ($breadcrumb): ?>
      <div id="breadcrumb" class="visible-desktop">
        <div class="container">
          <?php print $breadcrumb; ?>
        </div>
      </div>
    <?php endif; ?>
    <?php if ($messages): ?>
      <div id="messages">
        <div class="container">
          <?php print $messages; ?>
        </div>
      </div>
    <?php endif; ?>
    <div id="content">
      <a id="main-content"></a>
      <div id="page-header">
          <div class="container">
            <?php if ($title): ?>
              <div class="page-header">
                <h1 class="title"><?php print $title; ?></h1>
              </div>
            <?php endif; ?>
            <?php if ($tabs): ?>
              <div class="tabs">
                <?php print render($tabs); ?>
              </div>
            <?php endif; ?>
            <?php if ($action_links): ?>
              <ul class="nav nav-pills action-links">
                <?php print render($action_links); ?>
              </ul>
            <?php endif; ?>
          </div>
      </div>
      <?php print render($page['content']); ?>
    </div>
  </div>
</main> <!-- /#main-wrapper -->

<!-- ______________________ FOOTER _______________________ -->

<?php include('footer.tpl.php'); ?>
