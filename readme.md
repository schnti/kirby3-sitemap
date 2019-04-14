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

## Config options

You can set the following default options in your `config.php`:

```
'schnti.sitemap.excludeSites         => ['error', 'sitemap', 'thankyou'],
'schnti.sitemap.excludeTemplates     => [],
'schnti.sitemap.includeSites         => ['home', 'impressum', 'datenschutzerklaerung'],
'schnti.sitemap.showUnlistedSites    => false,
'schnti.sitemap.showOnePagerModules' => false
```

### excludeSites
An array of [Kirby page UIDs](https://getkirby.com/docs/reference/objects/page/uid) who are excluded from the sitemap.

Default: `['error', 'sitemap', 'thankyou']`
 
### excludeTemplates

An array of [intended template names](https://getkirby.com/docs/reference/objects/page/intended-template) whose pages are excluded from the sitemap.

Default: `[]`

### includeSites

An array of [Kirby page UIDs](https://getkirby.com/docs/reference/objects/page/uid) who are included if their status is unlisted.

Default: `['home', 'impressum', 'datenschutzerklaerung']`

### showUnlistedSites

If `true`, all unlisted sites are also included.

Default: `false`

### showOnePagerModules

If `false`, all [intended template names](https://getkirby.com/docs/reference/objects/page/intended-template) starting with `module.` are excluded from the sitemap.

Default: `false`



## How to use it

### sitemap.xml (for search engines)
Visit the sitemap as XML at this url: `http://example.com/sitemap.xml`

There is no actual file generated.


### sitemap.json (for tools)
Visit the sitemap as JSON at this url: `http://example.com/sitemap.json`

There is no actual file generated.

### HTML sitemap

Use this simple tag which lets you output an HTML sitemap.

In your text file you can use it as follows:

```
(sitemap:)
```
