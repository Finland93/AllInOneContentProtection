<?php
/*
Plugin Name: All in one Content protection
Plugin URI: https://github.com/Finland93/AllInOneContentProtection
Description: WordPress plugin empowers you to safeguard your website's content effortlessly. With just a click, this plugin adds an extra layer of security and protection to your valuable text, images, and multimedia elements. It prevents unauthorized actions and enhances your website's copyright protection.
Version: 1.1
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
    document.addEventListener("DOMContentLoaded", () => {
    document.addEventListener("contextmenu", (e) => e.preventDefault());
    document.addEventListener("selectstart", (e) => e.preventDefault());
    document.addEventListener("dragstart", (e) => e.preventDefault());
    document.addEventListener("copy", (e) => e.preventDefault());
    document.addEventListener("cut", (e) => e.preventDefault());
    document.addEventListener("keydown", (e) => {
        if (e.ctrlKey || e.metaKey) {
            const t = e.key.toLowerCase();
            ["c", "a", "u", "s", "p"].includes(t) && e.preventDefault();
        }
        ("F12" === e.key || e.ctrlKey && e.shiftKey && ["i", "j", "c"].includes(e.key.toLowerCase())) && e.preventDefault();
    });
    window.addEventListener("beforeprint", () => {
        document.body.style.visibility = "hidden";
        document.body.style.height = "0";
        document.body.style.overflow = "hidden";
    });
    window.addEventListener("afterprint", () => {
            document.body.style.visibility = "";
            document.body.style.height = "";
            document.body.style.overflow = "";
        });
    });
    console.log("%c⚠️ STOP!", "color: red; font-size: 80px; font-weight: 900;");
    console.log( "%cThis browser console is intended for developers only.","font-size: 20px; font-weight: bold;");
    console.log("%cIf someone told you to copy or paste code here, it could be a scam.", "font-size: 18px;");
    console.log("%cDo not continue unless you fully understand what you are doing.","font-size: 18px; font-weight: bold;");
    </script>';
}
add_action('wp_footer', 'add_custom_footer_script');

// Add CSS protection for selection, drag & drop, prevent printing
function add_custom_head_css() {
    echo '<style>
        a, article, body, div, h1, h2, h3, h4, h5, h6, li, p, section, span {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
        
        [draggable], a, img {
            user-drag: none;
            -webkit-user-drag: none;
        }
        
        @media print {
            *, body, html {
                visibility: hidden !important;
                display: none !important;
                height: 0 !important;
                width: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>';
}
add_action('wp_head', 'add_custom_head_css');

