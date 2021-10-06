# nonfiction Wordpress

Wordpress starter site tuned for Docker Swarm.

## Makefile commands:  

```
up          -- Start development server
down        -- Stop development server
composer    -- Install composer packages
npm         -- Install npm packages
webpack     -- Build assets with webpack
context     -- Switch docker context
build       -- Build and push tagged image
artifact    -- Install depedenencies before building
deploy      -- Deploy a tagged image to chosen docker context
push        -- Push db/uploads to current docker context
pull        -- Pull db/uploads from current docker context
shell       -- Launch a bash shell to explore
backup      -- Backups are hourly, but make one right now
init        -- Initialize website for development
```

## Other examples:  

```
 composer require wpackagist-plugin/wp-test-email  -- Install WP plugin
 composer require wpackagist-theme/hueman          -- Install WP theme
 npm install --save-dev jquery                     -- Install npm module
 bin/wp core version                               -- WP-CLI commands
 bin/wp replace https://BEFORE https://AFTER       -- WP-CLI search-replace
 docker service logs -f wordpress_srv              -- Show logs
```

## Related Repositories

- [nonfiction/wordpress-base](https://github.com/nonfiction/wordpress-base)
- [nonfiction/webpack](https://github.com/nonfiction/webpack)
- [nonfiction/platform](https://github.com/nonfiction/platform)
- [nonfiction/workspace](https://github.com/nonfiction/workspace)
