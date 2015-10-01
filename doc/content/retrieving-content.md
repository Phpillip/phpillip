# Retrieving content

## The content repository

The content repository service is responsible for fetching you content, you'll find it in the Application under the *content_repository* key:

``` php
$app['content_repository'];
```

When parsing a content, the repository returns an associative array with the following keys:

Property      | Presence                 | Description
------------- | ------------------------ | -----------------------------------
slug          | Added if not provided    | Slug, based on the source file name
lastModified  | Added if not provided    | Last modification of the source file
date          | Parsed if provided       | If a `date` property exists, parse it as DateTime
weight        | Parsed if provided       | If a `date` property exists, parse it as DateTime
content       | Added for Markdown files | Content of the Markdown file, converted to HTML
...           | Provided                 | Any other key present in the source file

> Need more/differents properties? You can [add your own](../content/property-handlers)

## Fetching content

### Get a single content

The `getContent` method expect a content type and a content name and return a single content:

``` php
// Get a content matching `my-content.*` contents in 'src/Resources/data/foo':
$app['content_repository']->getContent('foo', 'my-content');

// Result:
[
    'slug'         => 'my-content',
    'lastModified' => DateTime,
    // ... Any other key present in the source file
]
```

### Get all contents

The `getContents` method expect a content type and return all its contents:

``` php
// Get all contents in 'src/Resources/data/foo':
$app['content_repository']->getContents('foo');

// Result:
[
    'my-content'       => ['slug' => 'my-content', 'lastModified' => DateTime, ...],
    'my-other-content' => ['slug' => 'my-other-content', 'lastModified' => DateTime, ...],
    // ...
]
```

In a list, the contents are indexed by default by their source file name (a.k.a _slug_).

### Indexing and ordering contents

Say you have a `post` content that contains a `date` property, you can get all the _posts_ indexed by date:

``` php
// Get all contents in 'src/Resources/data/post' indexed by 'date':
$app['content_repository']->getContents('post', 'date');

// Result:
[
    1441836000 => ['date' => DateTime, 'slug' => 'my-first-post', ...],
    1443474500 => ['date' => DateTime, 'slug' => 'my-second-post', ...],
    // ...
]
```

A third parameter is provided to sort the resulting array:

``` php
// Get older post first ('date' ascending):
$app['content_repository']->getContents('post', 'date', true);

// Get latest post first ('date' descending):
$app['content_repository']->getContents('post', 'date', false);
```
