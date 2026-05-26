# Dimhouse WordPress Theme

This theme converts the static Dimhouse HTML clone into a maintainable WordPress theme with ACF Pro.

## Setup

1. Copy `dimhouse-theme` into `wp-content/themes/`.
2. Activate the theme.
3. Install and activate ACF Pro.
4. Set a page as the front page.
5. Fill the Dimhouse Options page for favicon, logo, social links, header info, and footer info.
6. Fill the Home Sections flexible content on the front page.

## Structure

- `header.php`
- `footer.php`
- `functions.php`
- `front-page.php`
- `page.php`
- `single.php`
- `index.php`
- `404.php`
- `template-parts/sections/*`
- `assets/css/theme.css`
- `assets/js/main.js`

## Section Mapping

- Top social bar -> `header.php` + theme options social repeater
- Hero banner/video -> `template-parts/sections/hero.php`
- Workflow / booking -> `template-parts/sections/process.php`
- Menu grid -> `template-parts/sections/menu_grid.php`
- Estimate tabs -> `template-parts/sections/estimate.php`
- About / company content -> `template-parts/sections/about.php`
- Testimonials -> `template-parts/sections/testimonials.php`
- FAQs -> `template-parts/sections/faq.php`
- Contact/form -> `template-parts/sections/contact.php`

## Dynamic ACF Areas

- Favicon, logo, footer logo
- Social links
- Header info and CTA
- Footer columns and footer text
- Hero video, image, buttons, banners
- Process steps
- Menu cards
- Estimate tabs and their rich content
- About section content and image
- Testimonials repeater
- FAQ repeater
- Contact items and shortcode

## Notes

- Class names and section IDs are kept close to the clone for frontend parity.
- Legacy asset paths resolve through `dimhouse_asset_uri()`.
- Large rich content blocks from the clone can be pasted into ACF WYSIWYG fields.
