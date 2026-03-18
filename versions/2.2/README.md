# tva-duplicate-pro

A WordPress plugin for duplicating posts, pages, and custom post types with their meta data.

## Features

- One-click duplication of posts, pages, and WooCommerce products
- Preserves all meta data during duplication
- Special handling for WooCommerce products (images, gallery, SKU)
- Automatically creates a draft copy for review

## Installation

1. Upload the plugin files to `/wp-content/plugins/tva-duplicate-pro` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the 'Duplicate with tva.sg' link under each post/page

## Development

### Building the Plugin

To create a distribution zip file:

```bash
./build.sh
```

This will:
1. Create a clean build with only the necessary files
2. Generate a versioned zip file in the `dist` directory
3. Save a copy of the zip to the `archive/{version}` directory

### Repository Structure

- `tva-duplicate-pro.php`: Main plugin file
- `includes/`: PHP classes and functions
- `assets/`: CSS, JS, and images
- `archive/`: Archived releases
- `dist/`: Distribution files

## Version History

### 2.1 (Current)
- Initial tracked version
- Added WooCommerce product support
- Improved meta data handling