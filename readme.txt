Ka Theme Colors for OpenCart 4 (requires Ka Extensions 5.1.1.x)

Changes the base colors of the default storefront theme: primary color (buttons, links, alerts),
top bar, header, menu, cart button, footer and prices.

Install: upload ka_theme_colors.ocmod.zip on Extensions > Installer, then go to Extensions > Ka Extensions
and press Install next to 'Ka Theme Colors'. Press Edit to open the color settings.

How it works: on save the module generates a CSS block from the settings and stores it in the OpenCart cache.
The module does not use OpenCart events, it works through kamod (Ka Extensions):
- kamod/catalog/controller/common/header.php overrides the common/header controller and passes the block
  from the cache (regenerated from the database if the cache is empty) to the template as ka_theme_colors_style;
- kamod/catalog/view/template/common/header.twig.xml (kapatch) prints it as <style id="ka-theme-colors">
  right before </head>, after stylesheet.css.
Only the default theme is supported.
