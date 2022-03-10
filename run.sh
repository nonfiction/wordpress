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

# Protect drop-in plugins from being modified
chown root:root /srv/web/content/object-cache.php && chmod -w /srv/web/content/object-cache.php
chown root:root /srv/web/content/advanced-cache.php && chmod -w /srv/web/content/advanced-cache.php
chown root:root /srv/web/content/db.php && chmod -w /srv/web/content/db.php

# Make sure upload/cache/settings directories are writeable
chown -R www-data:www-data /srv/web/content/uploads /srv/web/content/cache /srv/web/content/settings

# Ensure the cache is cleared
rm -rf /srv/web/content/cache/* 
rm -rf /srv/web/content/settings/* 
/usr/local/bin/wp cache flush

# Run Apache in foregrond
apachectl -D FOREGROUND
