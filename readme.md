# (simple) Sitemap Plugin

A plugin for [Kirby 3 CMS](http://getkirby.com) that generates an `sitemap.xml` and HTML sitemap.

## Commercial Usage

This plugin is free but if you use it in a commercial project please consider

- [making a donation](https://www.paypal.me/schnti/5) or
- [buying a Kirby license using this affiliate link](https://a.paddle.com/v2/click/1129/48194?link=1170)

## Installation

### Download

[Download the files](https://github.com/schnti/kirby3-sitemap/archive/master.zip) and place them inside `site/plugins/sitemap`.

### Composer

```
composer require schnti/sitemap
```

### Git Submodule
You can add the plugin as a Git submodule.

    $ cd your/project/root
    $ git submodule add https://github.com/schnti/kirby3-sitemap.git site/plugins/sitemap
    $ git submodule update --init --recursive
    $ git commit -am "Add Kirby Sitemap plugin"

Run these commands to update the plugin:

    $ cd your/project/root
    $ git submodule foreach git checkout master
    $ git submodule foreach git pull
    $ git commit -am "Update submodules"
    $ git submodule update --init --recursive
    
## Options

### Config options

You can set the following default options in your `config.php`:

```
'schnti.sitemap.excludeSites      => ['error', 'sitemap', 'thankyou'],       // Array of [page UIDs](https://getkirby.com/docs/reference/objects/page/uid) (Default: ['error', 'sitemap', 'thankyou']`)
'schnti.sitemap.excludeTemplates  => [],                                     // Array of [template names](https://getkirby.com/docs/reference/objects/page/intended-template) (Default: `[]`)
'schnti.sitemap.includeSites      => ['impressum', 'datenschutzerklaerung'], // Array of [page UIDs](https://getkirby.com/docs/reference/objects/page/uid) (Default: `['impressum', 'datenschutzerklaerung']`)
'schnti.sitemap.showUnlistedSites => false,                                  // Boolean (Default: false)
'schnti.sitemap.importantSites    => []                                      //  Array of [page UIDs](https://getkirby.com/docs/reference/objects/page/uid) (Default: `[]`)
```


## How to use it

### sitemap.xml
Visit the sitemap at this url: http://example.com/sitemap.xml.

There is no actual file generated.


### HTML sitemap

Use this simple tag which lets you output an HTML sitemap.

In your text file you can use it as follows:

```
(sitemap:)
```
