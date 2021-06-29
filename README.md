# nonfiction Wordpress

Wordpress starter site tuned for Docker Swarm.

## Makefile commands:  

```
up          -- Run development server
composer    -- Install composer packages
npm         -- Install npm packages
webpack     -- Build assets with webpack
context     -- Switch docker context
build       -- Build and push tagged image
artifact    -- Install depedenencies before building
deploy      -- Deploy a tagged image image in the current docker context
public      -- Launch this site on the load balancer
push        -- Push db/files to current docker context
pull        -- Pull db/files from current docker context
shell       -- Launch a bash shell to explore
init        -- Initialize website for development
backup      -- Backups are hourly, but make one right now
```

## Other examples:  

```
 composer require wpackagist-plugin/wp-test-email  -- Install WP plugin
 composer require wpackagist-theme/hueman          -- Install WP theme
 npm install --save-dev jquery                     -- Install npm module
 bin/wp core version                               -- WP-CLI commands
 docker stack rm wordpress                         -- Remove stack
 docker service logs -f wordpress_srv              -- Show logs
```

## Related Repositories

- [nonfiction/wordpress-base](https://github.com/nonfiction/wordpress-base)
- [nonfiction/webpack](https://github.com/nonfiction/webpack)
- [nonfiction/platform](https://github.com/nonfiction/platform)
- [nonfiction/traefik](https://github.com/nonfiction/traefik)
- [nonfiction/workspace](https://github.com/nonfiction/workspace)
