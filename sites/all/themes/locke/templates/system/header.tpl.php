<?php
/**
 * @file header.tpl.php
 *
 * This is not a "real" template file that interacts with the PHPtemplate 
 * engine, but rather an include file called from page.tpl.php
 *
 * The purpose of this file is to create an easy way for themers to customize
 * their header without editing the entire page.tpl.php file
**/
?>

<header id="header" class="header" role="header">
  <div class="container">
  
    <?php if ($secondary_menu): ?>
      <div class="secondary menu">
        <?php print theme('links', array('links' => $secondary_menu, 'attributes' => array('id' => 'secondary', 'class' => array('links', 'clearfix', 'secondary-menu')))); ?>
      </div>
    <?php endif; ?>
  
    <?php if ($page['header']): ?>
      <div id="header-region">
        <?php print render($page['header']); ?>
      </div>
    <?php endif; ?>
    
    <div id="navigation" class="navbar">
      <div class=""> <!-- Remove .navbar-inner to remove bootstrap style. -->
        <div class="container-fluid clearfix">
          <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
          <a class="btn btn-navbar btn-navbar-menu" data-toggle="collapse" data-target=".nav-menu-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <!-- .btn-navbar-search for collapsed search form -->
          <?php if ($search_form): ?>
            <a class="btn btn-navbar btn-navbar-search" data-toggle="collapse" data-target=".nav-search-collapse">
              <span class="icon-search"></span>
            </a>
          <?php endif; ?>
          
          <?php if ($site_name || $site_slogan || $logo): ?>
            <div id="name-and-slogan" class="clearfix pull-left brand">
            
              <?php
              /**
               * Recent SEO recommendations suggest "hiding" site names through a
               * negative text-indent is bad practice. Instead, logos should be wrapped
               * with the desired header tags and the alt text should be the site name.
               * Google and screenreaders are both engineered to read the alt text of
               * images, and the headers which wrap those images will give the 
               * alt text the prominence desired.
               * 
               * @see http://luigimontanez.com/2010/stop-using-text-indent-css-trick/
              **/ ?>
              <?php if ($logo && $site_name): ?>
                <?php if ($title): /* if the page title is set */ ?>
                  <div id="site-name-and-logo" class="site-name-image-wrapper">
                    <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
                      <img src="<?php print $logo; ?>" alt="<?php print $site_name; ?>" />
                    </a>
                  </div>
                <?php else: /* Use h1 when the content title is empty */ ?>
                  <h1 id="site-name-and-logo" class="site-name-image-wrapper">
                    <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
                      <img src="<?php print $logo; ?>" alt="<?php print $site_name; ?>" />
                    </a>
                  </h1>
                <?php endif; ?>
              <?php else: ?>
                <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
                  <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>"/>
                </a>
              <?php endif; ?>
            
              <?php if (!$logo && $site_name): ?>
                <?php if ($title): ?>
                  <div id="site-name">
                    <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><?php print $site_name; ?></a>
                  </div>
                <?php else: /* Use h1 when the content title is empty */ ?>
                  <h1 id="site-name">
                    <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><?php print $site_name; ?></a>
                  </h1>
                <?php endif; ?>
              <?php endif; ?>
            
            </div>
          <?php endif; ?>


          <div class="nav-collapse nav-menu-collapse">
            <div class="inner">
              <?php if ($main_menu): ?>
                <nav id="main-menu" class="main-menu pull-left" role="navigation">
                  <?php print render($main_menu); ?>
                </nav> <!-- /#main-menu -->
              <?php endif; ?>
            </div>
          </div>

          <div class="nav-collapse nav-search-collapse">
            <div class="inner">
              <?php if ($search_form): ?>
                <?php print $search_form; ?>
              <?php endif; ?>
            </div>
          </div>

      </div>
    </div> 
  </div> <!-- /#navigation -->
</header>