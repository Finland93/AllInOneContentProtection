# All in one Content Protection WordPress Plugin

The All in one Content Protection WordPress plugin provides robust content protection features to safeguard your website's intellectual property. It prevents unauthorized access, copying, and distribution of your valuable content, ensuring your hard work remains secure and protected.

## Features

- **Automatic Content Protection:** Automatically add custom JavaScript to disable right-click and prevent content copying.
  
- **Print Protection:** Implement print protection by hiding content when users try to print your web pages.

- **Selective Element Protection:** Prevent text and media elements from being selected or dragged, enhancing your website's security against content theft.

- **User-Friendly:** Easy-to-use just activate this plugin and it will add CSS + javascript automaticly to header and footer on frontend.

- **Compatibility:** Compatible with most WordPress themes, ensuring seamless integration and consistent protection across your entire website.

## Installation

1. **Download:** [Download the plugin zip file](https://github.com/Finland93/AllInOneContentProtection/releases/tag/WordPressPlugin) from the latest release.

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

If u want to use this on other websites you can do it like this, add header style above ending </head> element and add footer script above ending </body> tag

1. **Header style:**
```html
<style>
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
</style>
html```
3. **Footer script:**
```html
<script>
    document.addEventListener('contextmenu', event => event.preventDefault());
    console.log("%cSTOP!", "color: red; font-size: 80px; font-weight:bolder;");
    console.log("%cThis website is protected by copyright laws!", "font-size: 20px; font-weight:bold;");
    console.log("%cCopying is strictly prohibited!", "font-size: 20px; font-weight:bold;");
 </script>
html```
## License

This project is licensed under the [GNU General Public License v2.0 (GPL-2.0)](LICENSE).

---

**Note:** Customize the installation and usage instructions based on the specific functionalities and settings of your plugin. Make sure to provide clear and concise instructions to help users understand how to install and use the plugin effectively.
