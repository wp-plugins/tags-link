<?php
/**
 * Plugin Name: Tags Link
 * Plugin URI: http://uxtheme.net
 * Description: Find tags from single content and add link to tags.
 * Version: 1.0.0
 * Author: UX-Theme
 * Author URI: http://uxtheme.net
 * License: GPLv2 or later
 * Text Domain: ux-tags-link
 */
if (! defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}

/**
 * Main Tags Link Class
 *
 * @class UXTagsLink
 *
 * @version 1.0.0
 */
if (! class_exists("UXTagsLink")) {
    
    class UXTagsLink{
        
        public static function instance()
        {
            static $instance = null;
        
            if (null === $instance) {
                
                $instance = new UXTagsLink();
                
                $instance->setup_actions();
            }
            
            return $instance;
        }
        
        private function setup_actions(){
            
            add_filter( 'the_content', array($this ,'tagscontent') );
        }
        
        public static function tagscontent($content){
            
            // not single.
            if(!is_single()) return $content;
            
            // get tags.
            $tags = get_the_tags();
            
            // null.
            if(!$tags) return $content;
            
            foreach ($tags as $tag){
                
                $content = str_replace($tag->name, '<a href="'.esc_url(get_tag_link($tag->term_id)).'" target="_blank">'.$tag->name.'</a>', $content);
            }
            
            return $content;
        }
    }
    
    function uxtagslink()
    {
        return UXTagsLink::instance();
    }
    
    if (defined('UXTAGSLINK_LATE_LOAD')) {
        add_action('plugins_loaded', 'uxtagslink', (int) UXTAGSLINK_LATE_LOAD);
    } else {
        uxtagslink();
    }
}