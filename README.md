# Extended Front Page - WordPress Plugin

**Set any WordPress post type as your front page - not just pages!**

A lightweight WordPress plugin that extends the native front page functionality to support custom post types, posts, and any content type as your website's homepage.

## 🚀 Features

- **Universal Post Type Support**: Set any post type as front page (posts, pages, custom post types)
- **Native WordPress Integration**: Seamlessly extends existing Reading Settings
- **Zero Configuration**: Works immediately after activation
- **SEO Friendly**: Maintains proper canonical URLs and meta information
- **Performance Optimized**: Lightweight with minimal overhead
- **Developer Friendly**: Includes helper functions for theme development
- **Admin Visual Indicators**: Shows "Front Page" status in post lists

## 📋 Table of Contents

- [Installation](#installation)
- [Quick Start](#quick-start)
- [How It Works](#how-it-works)
- [Use Cases](#use-cases)
- [Developer Documentation](#developer-documentation)
- [Frequently Asked Questions](#frequently-asked-questions)
- [Contributing](#contributing)
- [Support](#support)

## 🔧 Installation

### Method 1: Manual Installation

1. Download the plugin files
2. Upload the `extended-front-page` folder to `/wp-content/plugins/`
3. Activate the plugin through the WordPress admin panel
4. Go to **Settings → Reading** to configure

### Method 2: WordPress Admin

1. Navigate to **Plugins → Add New**
2. Search for "Extended Front Page"
3. Install and activate the plugin
4. Configure in **Settings → Reading**

### Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Any WordPress theme

## ⚡ Quick Start

1. **Activate the plugin**
2. **Go to Settings → Reading**
3. **Select "A static page"** under "Your homepage displays"
4. **Choose any content** from the enhanced dropdown (now includes all post types)
5. **Save changes**

That's it! Your chosen content is now your front page.

## 🛠️ How It Works

### Core Functionality

The plugin hooks into WordPress's native front page system using three key mechanisms:

#### 1. Dropdown Enhancement

```php
add_filter('get_pages', array($this, 'extend_front_page_dropdown'), 10, 2);
```

- Extends WordPress's front page dropdown to include all public post types
- Maintains native WordPress interface and workflow
- Adds post type labels for clarity (e.g., "My Product (Product)")

#### 2. Query Modification

```php
add_action('pre_get_posts', array($this, 'handle_front_page_query'));
```

- Modifies the main query on the front page
- Ensures proper post type and ID are loaded
- Maintains singular page context for SEO

#### 3. Admin Integration

```php
add_filter('display_post_states', array($this, 'add_front_page_state'), 10, 2);
```

- Shows "Front Page" indicator in admin post lists
- Provides visual confirmation of front page status

### Technical Implementation

The plugin leverages WordPress's existing `show_on_front` and `page_on_front` options, ensuring:

- **Backward compatibility** with existing installations
- **Standard WordPress behavior** for all front page functionality
- **Proper template hierarchy** usage
- **SEO-friendly URLs** and canonical tags

## 🎯 Use Cases

### E-commerce Stores

Set a featured product as your homepage to showcase your best-selling item.

### Portfolio Websites

Display your latest project or featured work directly on the front page.

### News/Blog Sites

Feature breaking news or important announcements as your homepage.

### Business Websites

Showcase a specific service or company announcement prominently.

### Event Websites

Promote your main event or latest happening as the front page.

### Real Estate

Feature a premium property listing as your homepage.

## 👨‍💻 Developer Documentation

### Helper Functions

The plugin provides utility functions for theme developers:

#### `is_extended_front_page($post_id)`

Check if a specific post is set as the front page.

```php
if (is_extended_front_page()) {
    // Custom front page logic
    echo "This is our custom front page!";
}
```

#### `get_extended_front_page_id()`

Get the ID of the current front page post.

```php
$front_page_id = get_extended_front_page_id();
if ($front_page_id) {
    $front_page_post = get_post($front_page_id);
    echo "Front page: " . $front_page_post->post_title;
}
```

### Template Hierarchy

The plugin respects WordPress's standard template hierarchy:

- **Custom Post Type**: `single-{post_type}.php` → `single.php` → `index.php`
- **Regular Post**: `single.php` → `index.php`
- **Page**: `page.php` → `index.php`

### Hooks and Filters

#### Actions

- `extended_front_page_loaded` - Fired when front page query is modified
- `extended_front_page_admin_notice` - Customize admin notices

#### Filters

- `extended_front_page_post_types` - Modify which post types are included
- `extended_front_page_query_args` - Customize query arguments

### Example: Custom Template for Front Page Posts

```php
// In your theme's functions.php
add_filter('template_include', 'custom_front_page_template');

function custom_front_page_template($template) {
    if (is_front_page() && !is_page()) {
        $custom_template = locate_template('front-page-post.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    return $template;
}
```

## ❓ Frequently Asked Questions

### Will this affect my SEO?

No, the plugin maintains proper canonical URLs, meta information, and follows WordPress best practices for SEO.

### Does it work with caching plugins?

Yes, the plugin is compatible with popular caching solutions like WP Rocket, W3 Total Cache, and WP Super Cache.

### Can I use it with page builders?

Absolutely! Works with Elementor, Gutenberg, Divi, Beaver Builder, and other page builders.

### Will it slow down my website?

No, the plugin adds minimal overhead and only runs when necessary.

### What happens if I deactivate the plugin?

Your front page will revert to showing your latest posts (WordPress default) or the last page you had set.

### Does it work with multisite?

Yes, the plugin works on WordPress multisite installations.

## 🔄 Version History

### 1.0.0

- Initial release
- Native WordPress integration
- Support for all public post types
- Admin visual indicators
- Helper functions for developers

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guidelines](CONTRIBUTING.md) for details.

### Development Setup

1. Clone the repository
2. Install WordPress development environment
3. Activate the plugin in your test environment
4. Make your changes and test thoroughly
5. Submit a pull request

## 📞 Support

- **Documentation**: [Plugin Documentation](https://github.com/stirtingale/wordpress-extended-front-page-options)
- **Issues**: [GitHub Issues](https://github.com/stirtingale/wordpress-extended-front-page-options/issues)
- **Professional Support**: [Contact Stirtingale](https://stirtingale.com)

## 📄 License

This plugin is licensed under the GPL v2 or later.

```
Copyright (C) 2025 Thomas James Hole, Stirtingale

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

## 👨‍💻 Author

**Thomas James Hole**  
[Stirtingale](https://stirtingale.com)

---

### Keywords

wordpress plugin, front page, homepage, custom post types, cms, wordpress development, post types, static page, wordpress customization, seo friendly

### Tags

`wordpress` `plugin` `front-page` `custom-post-types` `homepage` `cms` `seo` `development`
