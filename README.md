# No Alt, No Roll

**Contributors:** Greg Randall  
**Tags:** images, alt text, accessibility, gutenberg  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

No Alt, No Roll prevents saving posts and pages if any images are missing alt text, promoting better accessibility practices.

## Description

No Alt, No Roll is a simple WordPress plugin that helps ensure your posts and pages are accessible. When you try to save a post or page, the plugin checks all images in the content. If any image is missing alt text, it blocks the save operation and notifies you of the images that need attention.

## Installation

1. Upload the `no-alt-no-roll` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

Once activated, the plugin will automatically check for missing alt text whenever you save a post or page. If any images are missing alt text, the save operation will be blocked, and you'll see an error message listing the images that need alt text.

## Frequently Asked Questions

**Q: Will this plugin work with the Gutenberg editor?**

A: Yes, the plugin is designed to work with the Gutenberg editor and parses Gutenberg blocks to find images.

**Q: Will this plugin work with non-Gutenberg editors?**

A: Maybe, but I haven't tested that.

**Q: What types of posts does this plugin check?**

A: The plugin checks both posts and pages.

**Q: Can I bypass the alt text check?**

A: No, the plugin is designed to enforce the presence of alt text on all images to promote accessibility.