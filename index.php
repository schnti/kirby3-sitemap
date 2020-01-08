<?php

function recursiveNavigation($type, $subpages = null)
{

	$sitemap = [];

	if ($subpages == null) {
		// alles Seiten auslesen
		$subpages = site()->pages();
	}

	foreach ($subpages AS $p) {

		// display options
		$isOnSitesWhitelist = in_array($p->id(), option('schnti.sitemap.' . $type . '.sites.whitelist'));
		$isPrioSite = in_array($p->id(), option('schnti.sitemap.' . $type . '.sites.prio'));
		$isOnTemplateWhitelist = in_array($p->intendedTemplate(), option('schnti.sitemap.' . $type . '.templates.whitelist'));

		// prüfen ob die Seite Untersieten hat die keine Modul Seite sind
		$hasChildren = $p->children()->filterBy('uid', '!=', 'modules')->count() > 0;

		// wenn eine Unterseite existiert und diese die uid "modules" hat und wiederum eigene Unterseiten hat, dann ist es eine OnePager Seite
		$isOnePager = $p->children()->first() && $p->children()->first()->uid() == 'modules' && $p->children()->first()->hasChildren();

		// bestimmte Seiten nicht anzeigen
		if (in_array($p->id(), option('schnti.sitemap.' . $type . '.sites.blacklist')))
			continue;

		// bestimmte Templates nicht anzeigen
		if (in_array($p->intendedTemplate(), option('schnti.sitemap.' . $type . '.templates.blacklist')))
			continue;

		// ungelistet seiten ausblenden, außer ungelistete Seiten Config is taktiv oder die Seite/Themplate ist auf der Whitelist
		if ($p->isUnlisted() && !option('schnti.sitemap.' . $type . '.showUnlistedSites') && !$isOnSitesWhitelist && !$isOnTemplateWhitelist)
			continue;

		// OnePager Module ausblenden, außer OnePager Module Config ist aktiv oder die Seite/Themplate ist auf der Whitelist
		if (Str::startsWith($p->intendedTemplate(), 'module.') && !option('schnti.sitemap.' . $type . '.showOnePagerModules') && !$isOnSitesWhitelist && !$isOnTemplateWhitelist)
			continue;

		$thisPage = [
			'id'       => $p->id(),
			'uid'      => $p->uid(),
			'modified' => $p->modified(),
			//			'uri'              => $p->uri(), // debug
			//			'status'           => $p->status(),  // debug
			//			'intendedTemplate' => $p->intendedTemplate(), // debug
			'title'    => $p->title()->value(),
			'url'      => $p->url()
		];

		$children = [];

		if ($hasChildren) {

			// default wenn Unterseiten
			$children = $p->children();

		} else if ($isOnePager) {

			// wenn Modul Seite, dann Module anzeigen
			$children = $p->children()->first()->children();
		}

		if (count($children)) {

			$subs = recursiveNavigation($type, $children);

			if (count($subs) > 0) {
				$thisPage['children'] = $subs;
			} else {

				// wenn show Tags Config aktiviert ist
				if (option('schnti.sitemap.' . $type . '.showTags')) {

					// auslesen von möglichen tags auf den Unterseiten
					$tags = $p->children()->listed()->pluck('tags', ',', true);

					if (count($tags) > 0) {
						foreach ($tags as $tag) {
							$thisPage['children'][] = [
								'id'    => urlencode($tag),
								'uid'   => urlencode($tag),
								//							'uri'              => '', // debug
								//							'status'           => '',  // debug
								//							'intendedTemplate' => '', // debug
								'title' => $tag,
								'url'   => url($p->url(), ['params' => ['tag' => urlencode($tag)]])
							];
						}
					}
				}
			}
		}

		if ($isPrioSite)
			array_unshift($sitemap, $thisPage);
		else {
			array_push($sitemap, $thisPage);
		}

	}

	return $sitemap;
}

function htmlHelper($pages)
{
	$html = '<ul>';

	foreach ($pages AS $p) {

		$html .= '<li>';

		$html .= '<a href="' . $p['url'] . '">' . $p['title'] . '</a>';

		// debug
		//		$html .= $p['status'] . ' - ' . $p['id'] . ' - ' . $p['intendedTemplate']; // debug

		if (isset($p['children'])) {
			$html .= htmlHelper($p['children']);
		}

		$html .= '</li>';
	}

	$html .= '</ul>';

	return $html;
}

function xmlHelper($pages)
{

	$xml = '';

	foreach ($pages as $p) {

		$xml .= '<url>';
		$xml .= '<loc>' . html($p['url']) . '</loc>';
		if (isset($p['modified']))
			$xml .= '<lastmod>' . date('c', $p['modified']) . '</lastmod>';
		$xml .= '</url>';

		if (isset($p['children'])) {
			$xml .= xmlHelper($p['children']);
		}

	}

	return $xml;
}

Kirby::plugin('schnti/sitemap', [
	'options' => [
		'html.sites.blacklist'     => ['error', 'sitemap', 'thankyou'],
		'html.sites.whitelist'     => ['home', 'impressum', 'datenschutzerklaerung'],
		'html.sites.prio'          => [],
		'html.templates.blacklist' => [],
		'html.templates.whitelist' => [],
		'html.showUnlistedSites'   => false,
		'html.showOnePagerModules' => false,
		'html.showTags'            => false,

		'xml.sites.blacklist'      => ['error', 'sitemap', 'thankyou'],
		'xml.sites.whitelist'      => ['home', 'impressum', 'datenschutzerklaerung'],
		'xml.sites.prio'           => [],
		'xml.templates.blacklist'  => [],
		'xml.templates.whitelist'  => [],
		'xml.showUnlistedSites'    => false,
		'xml.showOnePagerModules'  => false,
		'xml.showTags'             => false,

		'json.sites.blacklist'     => ['error', 'sitemap', 'thankyou'],
		'json.sites.whitelist'     => ['home', 'impressum', 'datenschutzerklaerung'],
		'json.sites.prio'          => [],
		'json.templates.blacklist' => [],
		'json.templates.whitelist' => [],
		'json.showUnlistedSites'   => false,
		'json.showOnePagerModules' => false,
		'json.showTags'            => false
	],
	'tags'    => [
		'sitemap' => [
			'html' => function () {
				return htmlHelper(recursiveNavigation('html'));
			}
		]
	],
	'routes'  => [
		[
			'pattern' => 'sitemap.xml',
			'method'  => 'GET',
			'action'  => function () {

				$xml = '<?xml version="1.0" encoding="UTF-8"?>';
				$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
				$xml .= xmlHelper(recursiveNavigation('xml'));
				$xml .= '</urlset>';

				return kirby()
					->response()
					->type('xml')
					->body($xml);

			}
		],
		[
			'pattern' => 'sitemap.json',
			'method'  => 'GET',
			'action'  => function () {

				$json['pages'] = recursiveNavigation('json');

				return kirby()
					->response()
					->type('json')
					->body(json_encode($json));

			}
		]
	]
]);