<?php
/*
Plugin Name: All in one Content protection
Plugin URI: https://github.com/Finland93/AllInOneContentProtection
Description: WordPress plugin empowers you to safeguard your website's content effortlessly. With just a click, this plugin adds an extra layer of security and protection to your valuable text, images, and multimedia elements. It prevents unauthorized actions and enhances your website's copyright protection.
Version: 1.0
Author: Finland93
Author URI: https://github.com/Finland93
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if directly accessed
if (!defined('ABSPATH')) {
    exit; 
}


// Add Console warning + prevent right click 
function add_custom_footer_script() {
    echo '<script>
    document.addEventListener(\'contextmenu\', event => event.preventDefault());
    console.log("%cSTOP!", "color: red; font-size: 80px; font-weight:bolder;");
    console.log("%cThis website is protected by copyright laws!", "font-size: 20px; font-weight:bold;");
    console.log("%cCopying is strictly prohibited!", "font-size: 20px; font-weight:bold;");
    </script>';
}
add_action('wp_footer', 'add_custom_footer_script');

// Add CSS protection for selection, drag & drop, prevent printing
function add_custom_head_css() {
    echo '<style>
    @media print {
        html, body {
            display: none; 
        }
    }

    p, h1, h2, h3, h4, h5, h6, img, iframe, br, li, ul, a, div {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    img, picture, a {
        -webkit-user-drag: none;
        -khtml-user-drag: none;
        -moz-user-drag: none;
        -o-user-drag: none;
        user-drag: none;
    }
    </style>';
}
add_action('wp_head', 'add_custom_head_css');
