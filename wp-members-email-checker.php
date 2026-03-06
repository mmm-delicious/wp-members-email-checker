<?php
/**
 * Plugin Name: WP-Members Email Validator
 * Description: Validates email address format, blocks institutional domains, and ensures confirmation email matches.
 * Version: 1.1.1
 * Author: MMM Delicious
 * Developer: Mark McDonnell
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * Tested up to: 6.7
 */

defined( 'ABSPATH' ) || exit;

// Auto-updates via GitHub
require_once plugin_dir_path(__FILE__) . 'lib/plugin-update-checker/plugin-update-checker.php';
\YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
    'https://github.com/mmm-delicious/wp-members-email-checker/',
    __FILE__,
    'wp-members-email-checker'
);

// Backward-compatible str_ends_with for PHP < 8
if ( ! function_exists( 'str_ends_with' ) ) {
    function str_ends_with( $haystack, $needle ) {
        $length = strlen( $needle );
        return $length > 0 ? substr( $haystack, -$length ) === $needle : true;
    }
}

add_filter( 'wpmem_register_data', 'wpmem_validate_user_email_fields', 10, 2 );

function wpmem_validate_user_email_fields( $fields, $action ) {
    $errors = new WP_Error();

    $email   = isset( $fields['user_email'] ) ? strtolower( trim( $fields['user_email'] ) ) : '';
    $confirm = isset( $fields['confirm_email'] ) ? strtolower( trim( $fields['confirm_email'] ) ) : '';

    // 1. Valid email format
    if ( ! is_email( $email ) ) {
        $errors->add( 'invalid_email', 'Please enter a valid email address.' );
    }

    // 2. Block institutional domains
    $domain = substr( strrchr( $email, '@' ), 1 );
    $blocked_suffixes = [ '.gov', '.edu', '.org', '.mil', '.int' ];
    foreach ( $blocked_suffixes as $suffix ) {
        if ( str_ends_with( $domain, $suffix ) ) {
            $errors->add( 'blocked_domain', 'Please use a personal email address (not .gov, .edu, or .org).' );
            break;
        }
    }

    // 3. Compare emails
    if ( ! empty( $email ) && ! empty( $confirm ) && $email !== $confirm ) {
        $errors->add( 'email_mismatch', 'Email addresses do not match.' );
    }

    return $errors->has_errors() ? $errors : $fields;
}
