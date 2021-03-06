
CONTENTS OF THIS FILE
---------------------

 * Installing Kalatheme
 * Installing Bootstrap
 * Creating a Subtheme
 * Key Features

INSTALLING KALATHEME
--------------------
Kalatheme has certain requirements in order to work correctly. The minimal set
of requirements are

 * Libraries 2.1+
 * Panels 3.x
 * JQuery Update 2.x (with JQuery version set to 1.7+)
 * A Bootstrap 3.0+ library (read below in Installing Bootstrap)

The easiest way to ensure these dependencies are met, and for a richer experience
in general is by downloading and starting with the Panopoly distribution.
http://drupal.org/project/panopoly

Then install Kalatheme like any other theme
http://drupal.org/documentation/install/modules-themes

INSTALLING BOOTSTRAP
--------------------
Kalatheme doesn't do much without a Bootstrap Library so you are going to need
to shop around for one.

 * To get the standard Bootstrap library, or to customize that library:
 http://getbootstrap.com/

 * If you are looking for a free and pre-made custom version of Bootstrap:
 http://bootswatch.com/

 * If you don't mind paying for a little extra:
 http://wrapbootstrap.com/

 * You can also Google for other sources if you are feeling adventerous.
 http://www.google.com/

Kalatheme uses the Libraries API so in order to get Bootstrap working you need
to put your Bootstrap files in sites/all/libraries/CURRENT-THEME_bootstrap. For
example, if you have a Kalatheme subtheme enabled called mytheme, you'd put
Bootstrap's files in sites/all/libraries/mytheme_bootstrap. If you have Kalatheme
set as your default theme, you'd use sites/all/libraries/kalatheme_bootstrap.
This is so you can have differently customized installations of Bootstrap for
different themes.

Custom Bootstrap libraries can use a non-standard files scheme so you need to make sure that
your bootstrap directory looks like the following folders and files.

  /CURRENT-THEME_bootstrap
  /CURRENT-THEME_bootstrap/css
  /CURRENT-THEME_bootstrap/css/bootstrap.css
  /CURRENT-THEME_bootstrap/css/bootstrap.min.css
  /CURRENT-THEME_bootstrap/fonts/
  /CURRENT-THEME_bootstrap/js/
  /CURRENT-THEME_bootstrap/js/bootstrap.js
  /CURRENT-THEME_bootstrap/js/bootstrap.min.js

IMPORTANT: The only actual requirement here is that either css/bootstrap.css or
css/bootstrap.min.css exist and that they both have some sort of version information
at the top like this:

  /*!
   * Bootstrap v3.0.0
   *
   * Copyright 2013 Twitter, Inc
   * Licensed under the Apache License v2.0
   * http://www.apache.org/licenses/LICENSE-2.0

Most themes have this by default and you can use the above as a basis. It is also worth
noting that while you only need boostrap.(min).css for this to "work" you will likely be
disappointed if you don't have the JS and font files as well.

If you have more files than what is listed above we recommend putting these files
in a KalaSUBtheme.

You also do not need to have the minified files to get this to work but they are highly
recommended for better performance.

CREATING A SUBTHEME
-------------------
Kalatheme is meant to be the base theme that is used to build more powerful
subthemes. Subthemes inherit almost all of the propoerties of their base theme
so you can reduce theme clutter and start on the 10th floor. Here is some
documentation on creating a basic subtheme
http://drupal.org/node/225125

BONUS FEATURES
------------
 * Settings
 On the settings page for Kalatheme you can configure how you want the style
 plugin to work.

 * Style Plugin
 When you choose to "Customize this page" using the Panels In-Place Editor you
 gain access to a bunch of customization tools provided by Kalatheme. Select the
 paintbrush on the panels pane or region you want to edit, choose "Kalacustomize"
 and hit next.

 * Views Grid
 Any view that is made with the grid display and that has an amount of columns
 that can evenly divide 12 will be automatically responsive.

 * Responsive Menu
 The "main-menu" menu will automatically dropdown for subitems. It will also
 automatically "responsify" on tablet and phone.
