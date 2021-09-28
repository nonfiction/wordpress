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

# Symlink PageSpeed config
ln -sf /srv/config/pagespeed.conf /etc/apache2/mods-enabled/pagespeed.conf

# Protect drop-in plugins from being modified
chown root:root /srv/web/content/object-cache.php
chown root:root /srv/web/content/advanced-cache.php
chown root:root /srv/web/content/db.php

# Ensure the cache is cleared
/usr/local/bin/wp cache flush

# Symlink theme folder to where it belongs
rm -rf /srv/web/content/themes/theme && ln -sf /srv/theme /srv/web/content/themes/theme

# Make sure upload/cache/settings directories are writeable
chown -R www-data:www-data /srv/web/content/uploads /srv/web/content/cache /srv/web/content/settings

# Assets in the /srv/app/views/img directory are web public at /assets/img
rm -f /srv/web/assets/img && ln -sf /srv/app/views/img /srv/web/assets/img

# Run Apache in foregrond
apachectl -D FOREGROUND
