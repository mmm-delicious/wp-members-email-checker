# WP-Members Email Validator

A WordPress plugin that adds email validation to WP-Members registration forms.

## Features

- Validates email address format using WordPress's built-in `is_email()` check
- Blocks institutional email domains (`.gov`, `.edu`, `.org`, `.mil`, `.int`) — enforces personal email only
- Confirms that the email and confirmation email fields match
- PHP 7.4 compatible (polyfills `str_ends_with` for PHP < 8)

## Requirements

- WordPress 5.0+
- PHP 7.4+
- WP-Members plugin

## Installation

1. Upload the `wp-members-email-checker` folder to `/wp-content/plugins/`
2. Activate the plugin through the Plugins menu in WordPress
3. No configuration needed — validation runs automatically on WP-Members registration forms

## How It Works

Hooks into the `wpmem_register_data` filter. If any validation fails, returns a `WP_Error` to WP-Members which displays the error to the user and blocks registration.

## Changelog

### 1.1
- Added ABSPATH security guard
- Standardized plugin header format

### 1.0
- Initial release
