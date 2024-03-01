# WordPress Plugins Upgrade Notice

A small PHP script that built for WordPress plugins to display an upgrade message right under the plugin update area and also you can display admin notice to notify your users related to how important is your plugin update. The best part is you don't need to edit plugin files when you want to display the upgrade message.

### Why we use?
Sometimes we need to display an upgrade meessage to our customers or users that the plugin you are using has a major changes and you need to take backup before the update or you need to update following plugins in order to work the plugin and something else. 

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
