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

# Symlink theme folder to where it belongs
rm -rf /srv/web/content/themes/theme && ln -sf /srv/theme /srv/web/content/themes/theme

# Make sure upload directory is writeable
chown -R www-data:www-data /srv/web/content/uploads

# Assets in the /srv/app/layouts/img directory are web public at /assets/img
rm -f /srv/web/assets/img && ln -sf /srv/app/layouts/img /srv/web/assets/img

# Run Apache in foregrond
apachectl -D FOREGROUND
