# WordPress Plugins Upgrade Notice

A small PHP script that built for WordPress plugins to display an upgrade message right under the plugin update area and also you can display an admin notice to notify your users related to how important is your plugin update. The best part is you don't need to edit plugin files when you want to display the upgrade message.

- **Upgrade Notice**

![image](https://github.com/MuhammadRehman/wordpress-plugin-upgrade-notice/assets/9959730/521db365-e12f-4b5a-8c68-877f7e72d04d)


- **Admin Notice**

![image](https://github.com/MuhammadRehman/wordpress-plugin-upgrade-notice/assets/9959730/9e08d9d0-25db-4b2c-9bfd-3178286ae5e7)


### Why do we use?
Sometimes we need to display an upgrade message to our customers or users that the plugin you are using has major changes and you need to take a backup before the update or you need to update the following plugins in order to work the plugin and something else. 

### How It's Working?
This package connects with the trunk folder of your SVN repository and it fetches the upgrade notice and the admin notice directly from there. So if you want to display the message you just need to edit your readme.txt from your trunk SVN repository.

### Installation
Here are some steps to install the package
1. Download this package and extract it within your plugin's root directory.
2. Open the updage.php file and change the variables with your plugin data.

```php
// plugin slug should be copied from wordpress plugin URL eg. https://wordpress.org/plugins/wp-hooks-finder/
public $slug = 'wp-hooks-finder';
public $folder_name = 'wp-hooks-finder';
public $file_name = 'wp-hooks-finder.php';
```

### Install via Composer

```cmd
composer require mrehman/wp-plugin-upgrade-notice
```
### Configuration

You need to write the exact format for the upgrade message and admin notice. Below are examples of how to write it in your plugin's readme.txt file in the WordPress SVN directory.

Example
```
== Upgrade Notice ==

= 3.0 =
*Important:* This is a major update. Please remember to backup your data before proceeding with the update.

== Admin Notice ==

= 3.0 =
*Important:* This plugin releases database related fixes please take backup before update the plugin, for more details [click here](https://muhammadrehman.com)
```
