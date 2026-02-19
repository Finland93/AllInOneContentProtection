# All in one Content Protection WordPress Plugin

The All in one Content Protection WordPress plugin provides robust content protection features to safeguard your website's intellectual property. It prevents unauthorized access, copying, and distribution of your valuable content, ensuring your hard work remains secure and protected.

## Features

- **Automatic Content Protection:** Automatically add custom JavaScript to disable right-click and prevent content copying.
  
- **Print Protection:** Implement print protection by hiding content when users try to print your web pages.

- **Selective Element Protection:** Prevent text and media elements from being selected or dragged, enhancing your website's security against content theft.

- **User-Friendly:** Easy-to-use just activate this plugin and it will add CSS + javascript automaticly to header and footer on frontend.

- **Compatibility:** Compatible with most WordPress themes, ensuring seamless integration and consistent protection across your entire website.

## Installation

1. **Download:** [Download the plugin zip file](https://github.com/Finland93/AllInOneContentProtection/releases/tag/WP-Plugin) from the latest release.

2. **WordPress Dashboard:**
    - Go to your WordPress Admin Dashboard.
    - Navigate to Plugins -> Add New.
    - Click on "Upload Plugin" and select the downloaded zip file.
    - Activate the plugin.

3. **Manual Installation:**
    - Extract the downloaded zip file.
    - Upload the extracted folder to the `wp-content/plugins/` directory of your WordPress installation.
    - Activate the plugin from the WordPress Plugins menu.

## Usage

Once the plugin is activated, your content will be automatically protected. There are no additional configuration steps required. Customize the protection features according to your preferences in the plugin settings page if needed.

## Manual usage

If u want to use this on other websites you can do it like this, add header style above ending &lt;/head&gt; element and add footer script above ending &lt;/body&gt; tag

1. **Header style:**
```
<style>
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
        img {
            pointer-events: none;
            user-select: none;
            -webkit-user-drag: none;
        }
        
        body {
            user-select: none;
            -webkit-user-select: none;
        }
</style>
```
3. **Footer script:**
```
<script>
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
    </script>
```
## License

This project is licensed under the [GNU General Public License v2.0 (GPL-2.0)](LICENSE).
