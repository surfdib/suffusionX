<?php
/**
 * Core header file for Suffusion
 *
 * Cleaned version - May 2026
 *
 * @package Suffusion
 * @subpackage Templates
 */
global $suffusion_unified_options, $suffusion_interactive_text_fields, $suffusion_translatable_fields;

if (function_exists('icl_t')) {
    foreach ($suffusion_unified_options as $id => $value) {
        if (in_array($id, $suffusion_translatable_fields) && isset($suffusion_interactive_text_fields[$id])) {
            $value = wpml_t('suffusion-interactive', $suffusion_interactive_text_fields[$id]."|".$id, $value);
        }
        global $$id;
        $$id = $value;
        $suffusion_unified_options[$id] = $value;
    }
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" data-source="rankingcoach">
    
    <?php
    suffusion_document_header();
    
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php suffusion_before_page(); ?>
        <?php suffusion_before_begin_wrapper(); ?>
        <div id="wrapper" class="fix">
        <?php suffusion_after_begin_wrapper(); ?>
            <div id="container" class="fix">
                <?php suffusion_after_begin_container(); ?>
