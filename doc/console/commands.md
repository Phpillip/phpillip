# Phpillip's console

Call the Phpillip console with `bin/console` (yes, it's a Symfony console).

Phpillip provides 3 commands:

## Build

Build the static files to `/dist`:

Usage:

      phpillip:build [options] [--] [<host>] [<destination>]

Arguments:

- `host`: What should be used as domain name for absolute url generation?
- `destination`: Full path to destination directory

Options:

- `--no-sitemap`: Don't build the sitemap
- `--no-expose`: Don't expose the public directory after build

# Watch

Have Phpillip watch for any changes in `/src` and rebuild the files automatically:

Usage:

    phpillip:watch [options]

Options:

- `--period=PERIOD`   Set the polling period in seconds [default: 1]

# Serve

You can also live-preview your website without building it by running the Phpillip local PHP server:

Usage:

    phpillip:serve [options] [--] [<address>]

Arguments:

- `address`: address:port [default: "127.0.0.1"]

Options:

- `-p`, `--port=PORT`: Address port number [default: "8080"]

Your website will be available at [http://localhost:8080](http://localhost:8080)
