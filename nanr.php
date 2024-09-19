<?php
/*
Plugin Name: No Alt, No Roll
Description: Prevents saving posts and pages if any images are missing alt text.
Version: 1.0
Author: Greg Randall
*/

// Render Gutenberg blocks in the content
function nanr_do_gutenberg( $content ) {
    // Initialize an empty string to hold the rendered output.
    $output_rendered = "";

    // Parse the content into Gutenberg blocks.
    $parsed_blocks = parse_blocks( $content );

    // Check if there are any blocks parsed.
    if ( $parsed_blocks ) {
        // Loop through each block and render it.
        foreach ( $parsed_blocks as $block ) {
            // Render the block and apply 'the_content' filter to the output.
            $output_rendered .= apply_filters( 'the_content', render_block( $block ) );
        }
    }

    // Return the fully rendered content.
    return $output_rendered;
}

function nanr_pretty_list($list) {
    // Check the number of items in the list to determine the formatting.
    if (count($list) > 2) {
        // For lists with more than two items, use commas and 'and' before the last item.
        $lastItem = array_pop($list); // Remove and save the last item.
        $formattedList = implode(", ", $list) . ", and " . $lastItem;
    } elseif (count($list) === 2) {
        // For lists with exactly two items, just use 'and' between them.
        $formattedList = implode(" and ", $list);
    } else {
        // For a single-item list, return the item itself.
        $formattedList = $list[0];
    }

    return $formattedList;
}

function check_image_alt_text( $post_id ) {
    // Prevent function from running on autosave
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }

    // Only run for posts and pages
    $post_type = get_post_type($post_id);
    if ( $post_type != 'post' && $post_type != 'page' ) {
        return;
    }

    // Retrieve the post content
    $post_content = get_post_field('post_content', $post_id);

    // Run the post content through the Gutenberg parser.
    // Some Gutenberg elements don't have the image tags until they are rendered.
    $post_content = nanr_do_gutenberg( $post_content );

    // Render shortcodes in the post content, in case any add images
    $post_content = do_shortcode( $post_content );
    
    // Initialize DOMDocument to parse the post content
    $dom = new DOMDocument();
    // Suppress warnings from malformed HTML
    @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $post_content);
    $xpath = new DOMXPath($dom);
    
    // Query for all <img> tags in the content
    $images = $xpath->query('//img');
    
    // Initialize variables to track images with and without alt text
    $images_without_alt = [];
    $images_missing_alt = 0;
    $images_with_alt = 0;
    
    // Loop through each image to check for alt attribute
    foreach ( $images as $image ) {
        if ( !$image->hasAttribute('alt') || empty($image->getAttribute('alt')) ) {
            // Add images without alt text to the array
            $images_without_alt[] = basename($image->getAttribute('src'));
            $images_missing_alt++;
        } else {
            $images_with_alt++;
        }
    }
    
    // If any image is missing alt text, block the save operation and show an error
    if ( !empty($images_without_alt) ) {
        wp_die('All images must have alt text before saving. Missing alt text in ' . $images_missing_alt . ' of ' . ($images_with_alt + $images_missing_alt) . ' images: ' . nanr_pretty_list( $images_without_alt) );
    }
}

// Attach the function to the save_post action hook
add_action('save_post', 'check_image_alt_text');