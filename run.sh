#!/bin/bash

# Environment variables expected:
export HOST
export WP_ENV
export DB_HOST
export DB_USER
export DB_PASSWORD
export DB_NAME
export SMTP_HOST
export SMTP_PORT
export SMTP_USER
export SMTP_PASSWORD

# Update msmtp config for transactional email
esh /etc/msmtprc.esh > /etc/msmtprc

# Symlink theme folder to where it belongs
rm -rf /srv/web/content/themes/theme && ln -sf /srv/theme /srv/web/content/themes/theme

# Assets in the /srv/app/views/img directory are web public at /assets/img
rm -rf /srv/web/assets/img && ln -sf /srv/app/views/img /srv/web/assets/img

# Workarounds so resources can still be found when looking in the wrong place
rm -rf /srv/web/wp-content && ln -sf /srv/web/content /srv/web/wp-content

# Protect drop-in plugins from being modified
chown root:root /srv/web/content/object-cache.php && chmod -w /srv/web/content/object-cache.php
chown root:root /srv/web/content/advanced-cache.php && chmod -w /srv/web/content/advanced-cache.php
chown root:root /srv/web/content/db.php && chmod -w /srv/web/content/db.php

# Ensure the cache is cleared
rm -rf /srv/web/content/cache/* 
rm -rf /srv/web/content/settings/* 
/usr/local/bin/wp cache flush

# Make sure upload/cache/settings directories are writeable
chown -R www-data:www-data /srv/web/content/uploads /srv/web/content/cache /srv/web/content/settings &

# Run Apache in foregrond
apachectl -D FOREGROUND
