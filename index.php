<?php

function check($p)
{
	$excludeSites = option('schnti.sitemap.excludeSites');
	$excludeTemplates = option('schnti.sitemap.excludeTemplates');
	$includeSites = option('schnti.sitemap.includeSites');
	$showInvisibleSites = option('schnti.sitemap.showUnlistedSites');

	// invisible or include
	if ($showInvisibleSites == true || ($p->isListed() && ($p->depth() == 1 || $p->parent()->isListed())) || in_array($p->uri(), $includeSites)) {
		// excluded site or template
		if (!in_array($p->uri(), $excludeSites) && !in_array($p->intendedTemplate(), $excludeTemplates)) {
			return true;
		}
	}
	return false;
}

function recursiveNavigationJson($subpages = null)
{
	$array = array();

	if ($subpages == null) {
		$subpages = site()->pages();
	}

	foreach ($subpages AS $p) :

		if (!check($p))
			continue;

		$sub = null;

		if ($p->hasChildren()) {
			$result = recursiveNavigationJson($p->children());

			if ($result && $result['pages']) {
				$sub = $result['pages'];
			}
		}

		// Response
		$array['pages'][] = [
			'url'   => $p->url(),
			'title' => $p->title()->value(),
			'id'    => str_replace('/', '', $p->id()),
			'pages' => $sub
		];

	endforeach;

	return $array;
}

function recursiveNavigation($subpages = null)
{

	if ($subpages == null) {
		$subpages = site()->pages();
	}

	$sitemap = '<ul>';

	foreach ($subpages AS $p) {

		if (!check($p))
			continue;

		$sitemap .= '<li>';

		$sitemap .= '<a href="' . $p->url() . '">' . $p->title() . '</a>';

		if ($p->hasChildren()) {
			$sitemap .= recursiveNavigation($p->children());
		}

		$sitemap .= '</li>';
	}
	$sitemap .= '</ul>';

	return $sitemap;
}

Kirby::plugin('schnti/sitemap', [
	'options' => [
		'excludeSites'      => ['error', 'sitemap', 'thankyou'],
		'excludeTemplates'  => [],
		'includeSites'      => ['impressum', 'datenschutzerklaerung'],
		'showUnlistedSites' => false
	],
	'tags'    => [
		'sitemap' => [
			'html' => function ($tag) {
				return recursiveNavigation();
			}
		]
	],
	'routes'  => [
		[
			'pattern' => 'sitemap.xml',
			'method'  => 'GET',
			'action'  => function () {

				$sitemap = '<?xml version="1.0" encoding="utf-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

				// get all pages
				$pages = site()->pages()->index();

				foreach ($pages as $p) {

					if (!check($p))
						continue;

					$sitemap .= '<url><loc>' . html($p->url());
					$sitemap .= '</loc><lastmod>' . date('c', $p->modified()) . '</lastmod></url>';

				}

				$sitemap .= '</urlset>';

				return new Response($sitemap, 'xml');

			}
		],
		[
			'pattern' => 'sitemap.json',
			'method'  => 'GET',
			'action'  => function () {

				return recursiveNavigationJson();

			}
		]
	]
]);