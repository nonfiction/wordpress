FROM nonfiction/wordpress-base:v2

# Copy the codebase
COPY ./app /srv/app
COPY ./config /srv/config
COPY ./src /srv/src
COPY ./theme /srv/theme
COPY ./vendor /srv/vendor
COPY ./web /srv/web
COPY ./composer.json /srv/composer.json

# Symlink PageSpeed config
RUN ln -sf /srv/config/pagespeed.conf /etc/apache2/mods-enabled/pagespeed.conf

# Symlink theme folder to where it belongs
RUN ln -sf /srv/theme /srv/web/content/themes/theme

# Assets in the /srv/app/views/img directory are web public at /assets/img
RUN ln -sf /srv/app/views/img /srv/web/assets/img

# Workarounds so resources can still be found when looking in the wrong place
RUN ln -sf /srv/web/content /srv/web/wp-content
RUN ln -sf /srv/web/content /srv/web/app

# Persist uploads in this volume
VOLUME /srv/web/content/uploads

# Give Apache permissions for /content dir
RUN chown -R www-data:www-data /srv/web/content

# This script starts Apache Server
COPY ./run.sh /srv/run.sh
RUN chmod +x /srv/run.sh
CMD ["/srv/run.sh"]
