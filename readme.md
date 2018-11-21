# PHP Code Sniffer Standards Installer

A Composer plugin that properly registers PHP Code Sniffer standards. Visit the [PHP Code Sniffer coding standard tutorial](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Coding-Standard-Tutorial) to find out more about creating custom coding standards/rulesets.

## Create an Installable Coding Standard

1. Create a repository for hosting your PHP Code Sniffer coding standard.
2. Set the type as `phpcs-standards` in your composer.json file. 
3. Require PHP Code Sniffer and this installer as dependencies in your composer.json file:

```json
    "type": "phpcs-standards",
    "require": {
        "squizlabs/php_codesniffer": "^2.9.2",
        "wpscholar/phpcs-standards-installer": "^1.0"
    }
```

## Usage

- Require any Composer package of type `phpcs-standards`.
- Optionally add any PHP Code Sniffer config options to the `extra` section in your composer.json file

```json
    "require": {
      "wpscholar/phpcs-standards-wpscholar": "^1.0"
    },
    "extra": {
        "phpcs-config": {
            "default_standard": "WPScholar",
            "testVersion": "5.4-"
        }
    }
```

- Run `composer install`!

## IDE Integration
Some IDE integrations of PHPCS  will fail to register your ruleset since it doesn't live in your project root. In order to rectify this, place phpcs.xml at your project root:

```xml
<?xml version="1.0"?>
<ruleset name="Project Rules">
	<rule ref="WPScholar" />
</ruleset>
``` 

