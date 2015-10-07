# Sitemap

Phpillip automatically generates an XML sitemap of your website.
The sitemap contains every URL called by the _build_ command.

## Set last modified

When registering a url in the sitemap, the build command looks for a _Last-Modified_ header in the _Response_, if it exists it will be used as `<lastmod>` tag for this entry in the sitemap.

So if you want to set a last modified tag for a custom controller, just set the _Last-Modified_ header in your response.

__Tip:__ If your routes are [declared as _content_ routes](../feature/helpers.md), Phpillip automatically set that header based on last change of your content files.

## Hide a route from sitemap

By default, all routes are registered in the sitemap.
To hide a route from the sitemap, use the method `hideFromSitemap`;

``` php
$app->get('blog/feed.rss')->hideFromSitemap();
```

## Disable Sitemap

To disable the _sitemap_ feature completely, set it to `false` in the configuration:

``` yaml
# src/Resources/config/config.yml
sitemap: false
```
