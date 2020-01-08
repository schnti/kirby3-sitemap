# (simple) Sitemap Plugin

A plugin for [Kirby 3 CMS](http://getkirby.com) that generates an `sitemap.xml` and HTML sitemap.

> :warning: Warning: there are config breaking changes between version 1. * and 2. *

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
'schnti.sitemap.xml.sites.blacklist'      => ['error', 'sitemap', 'thankyou'],
'schnti.sitemap.xml.sites.whitelist'      => ['home', 'impressum', 'datenschutzerklaerung'],
'schnti.sitemap.xml.sites.prio'           => [],
'schnti.sitemap.xml.templates.blacklist'  => [],
'schnti.sitemap.xml.templates.whitelist'  => [],
'schnti.sitemap.xml.showUnlistedSites'    => false,
'schnti.sitemap.xml.showOnePagerModules'  => false,
'schnti.sitemap.xml.showTags'             => false,

'schnti.sitemap.html.sites.blacklist'     => ['error', 'sitemap', 'thankyou'],
'schnti.sitemap.html.sites.whitelist'     => ['home', 'impressum', 'datenschutzerklaerung'],
'schnti.sitemap.html.sites.prio'          => [],
'schnti.sitemap.html.templates.blacklist' => [],
'schnti.sitemap.html.templates.whitelist' => [],
'schnti.sitemap.html.showUnlistedSites'   => false,
'schnti.sitemap.html.showOnePagerModules' => false,
'schnti.sitemap.html.showTags'            => false,

'schnti.sitemap.json.sites.blacklist'     => ['error', 'sitemap', 'thankyou'],
'schnti.sitemap.json.sites.whitelist'     => ['home', 'impressum', 'datenschutzerklaerung'],
'schnti.sitemap.json.sites.prio'          => [],
'schnti.sitemap.json.templates.blacklist' => [],
'schnti.sitemap.json.templates.whitelist' => [],
'schnti.sitemap.json.showUnlistedSites'   => false,
'schnti.sitemap.json.showOnePagerModules' => false,
'schnti.sitemap.json.showTags'            => false

```

### .sites.blacklist
An array of [Kirby page IDs](https://getkirby.com/docs/reference/objects/page/id) who are excluded from the sitemap.

### .sites.whitelist
An array of [Kirby page IDs](https://getkirby.com/docs/reference/objects/page/id) who are included if their status is unlisted.
 
### .sites.whitelist

### .templates.blacklist
An array of [intended template names](https://getkirby.com/docs/reference/objects/page/intended-template) whose pages are excluded from the sitemap.

### .templates.whitelist
An array of [intended template names](https://getkirby.com/docs/reference/objects/page/intended-template) whose pages are included if their status is unlisted.

### .showUnlistedSites
If `true`, all unlisted sites are also included.

### .showOnePagerModules
If you are using the [Kirby Modules from Thomas Günther](https://github.com/medienbaecker/kirby-modules) this plugin hides automatically the "modules" page from the sitemap.

If `showOnePagerModules` is `true` the OnePager Modules are shown as anker navigation links.

### .showTags (for experts)
If `true`, for example blog categories or tags are listed as sub page navigation links.

Internal the `$page->children()->listed()->pluck('tags', ',', true)` and `url($p->url(), ['params' => ['tag' => urlencode($tag)]])` function is used.  

## How to use it

### sitemap.xml (for search engines)
Visit the sitemap as XML: `http://example.com/sitemap.xml`

There is no actual file generated.


### sitemap.json (for tools)
Visit the sitemap as JSON: `http://example.com/sitemap.json`

There is no actual file generated.

### HTML sitemap (for humans)

Use this simple tag which lets you output an HTML sitemap.

In your text file you can use it as follows:

```
(sitemap: )
```
