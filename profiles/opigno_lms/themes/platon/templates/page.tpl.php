<header id="site-header">
  <div class="row">
    <div class="col col-1-out-of-2 col-1-out-of-4 col-1-out-of-6">
      <?php if (!empty($logo)): ?>
        <a href="<?php print $front_page; ?>" id="logo"><img src="<?php print $logo; ?>" alt="Opigno"></a>
      <?php endif; ?>
    </div>

    <div class="col col-1-out-of-2 col-3-out-of-4 col-5-out-of-6">
      <?php if (!empty($search_form)): ?>
        <div id="header-search">
          <?php print render($search_form); ?>
        </div>
      <?php endif; ?>

      <div id="user-account-information">
        <div id="user-account-information-picture">
          <a href="<?php print url('user'); ?>">
            <img src="<?php print $base_path . $directory; ?>/img/anonymous-account.png">
          </a>
        </div>

        <div id="user-account-information-name">
          <?php print t("welcome @user", array('@user' => $logged_in ? $user->name : t("guest"))); ?>

          <div id="user-account-information-links">
            <?php if ($logged_in): ?>
              <?php print l(t("my account"), 'user'); ?> | <?php print l(t("logout"), 'user/logout'); ?>
            <?php else: ?>
              <?php if ($can_register): ?>
                <?php print l(t("register"), 'user/register'); ?> |
              <?php endif; ?>
              <?php print l(t("login"), 'user/login'); ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<div id="site-content">
  <div class="row">
    <div id="first-sidebar" class="col col-2-out-of-2 col-1-out-of-4 col-1-out-of-6">
      <?php if (!empty($main_navigation) && ($logged_in || theme_get_setting('platon_menu_show_for_anonymous')) && theme_get_setting('toggle_main_menu')): ?>
        <?php print $main_navigation; ?>
      <?php endif; ?>

      <?php print render($page['sidebar_first']); ?>
    </div>

    <div id="second-sidebar" class="col col-2-out-of-2 col-3-out-of-4 col-5-out-of-6">
      <?php if (!empty($page['help'])): ?>
        <div id="help">
          <?php print render($page['help']); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($og_context_parent_link) && empty($is_og_node)): ?>
        <div id="back-to-parent-link">
          <?php print $og_context_parent_link; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($title)): ?>
        <div id="title-wrapper">
          <?php print render($title_prefix); ?>
          <h1><?php print $title; ?></h1>
          <?php print render($title_suffix); ?>

          <?php if (!empty($og_context_navigation)): ?>
            <div id="og-context-navigation">
              <?php print $og_context_navigation; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($messages)): ?>
        <div id="messages">
          <?php print render($messages); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($tabs['#primary']) && empty($hide_tabs)): ?>
        <div id="tabs">
          <?php print render($tabs); ?>
        </div>
      <?php endif; ?>

      <?php if ($action_links): ?>
        <ul class="action-links">
          <?php print render($action_links); ?>
        </ul>
      <?php endif; ?>

      <div id="content">
        <?php print render($page['content']); ?>
        <?php print render($page['content_bottom']); ?>
      </div>
    </div>
  </div>
</div>

<?php if (!empty($page['footer'])): ?>
  <footer id="site-footer">
    <div class="row">
      <div class="col span_6">
        <?php print render($page['footer']); ?>
      </div>
    </div>
  </footer>
<?php endif; ?>
