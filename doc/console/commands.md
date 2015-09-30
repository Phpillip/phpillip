# Phpillip's console

> Phpillip uses Symfony console. So, in order to get the full list of available commands, just call `bin/console`.

Phpillip provides 3 commands:

## Build

Build the static files to `/dist`:

__Usage:__

    phpillip:build [options] [--] [<host>] [<destination>]

__Arguments:__

- `host`: What should be used as domain name for absolute url generation?
- `destination`: Full path to destination directory

__Options:__

- `--no-sitemap`: Don't build the sitemap
- `--no-expose`: Don't expose the public directory after build

Example:

    bin/console phpillip:build my-domain.com

## Watch

Have Phpillip watch for any changes in `/src` and rebuild the files automatically:

__Usage:__

    phpillip:watch [options]

__Options:__

- `--period=PERIOD`: Set the polling period in seconds (default: 1)

Example:

    bin/console phpillip:watch

## Serve

You can also live-preview your website without building it by launching the Phpillip local PHP server:

__Usage:__

    phpillip:serve [options] [--] [<address>]

__Arguments:__

- `address`: address:port (default: "127.0.0.1")

__Options:__

- `-p`, `--port=PORT`: Address port number (default: "8080")

Example:

    bin/console phpillip:serve

Your website will be available at [http://localhost:8080](http://localhost:8080)

__Note:__

It's a Symfony console so you can use shortcuts ;)

    # Build
    bin/console p:b

    # Watch
    bin/console p:w

    # Serve
    bin/console p:s

