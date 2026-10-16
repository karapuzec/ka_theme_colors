Ka Theme Colors for OpenCart 4 (requires Ka Extensions 5.1.1.x)

Changes the base colors of the default storefront theme: primary color (buttons, links, alerts),
top bar, header, menu, cart button, footer and prices.
Since 1.1.0.0 the settings page also has 'Custom CSS' and 'Custom Javascript' tabs for your own code.

Install: upload ka_theme_colors.ocmod.zip on Extensions > Installer, then go to Extensions > Ka Extensions
and press Install next to 'Ka Theme Colors'. Press Edit to open the color settings.

How it works: on save the module generates a CSS block from the settings and stores it in the OpenCart cache.
The module does not use OpenCart events, it works through kamod (Ka Extensions):
- kamod/catalog/controller/common/header.php overrides the common/header controller and passes the block
  from the cache (regenerated from the database if the cache is empty) to the template as ka_theme_colors_style;
- kamod/catalog/view/template/common/header.twig.xml (kapatch) prints it as <style id="ka-theme-colors">
  right before </head>, after stylesheet.css.
Only the default theme is supported.

Custom CSS is printed as <style id="ka-theme-custom-css"> right after the colors block (same kamod header patch).
Custom Javascript is printed as <script id="ka-theme-custom-js"> before </body>, after jQuery and Bootstrap:
kamod/catalog/controller/common/footer.php overrides the common/footer controller and
kamod/catalog/view/template/common/footer.twig.xml (kapatch) prints the code. Empty fields print nothing.
The code is not escaped (only store admins can edit it); a closing </style> or </script> inside the code
is written as <\/style> / <\/script> so it cannot close the block early.
