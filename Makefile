.PHONY: deploy

all:
	@echo -e "Make commands:"
	@echo -e "\tup \t\t -- Start development server"
	@echo -e "\tdown \t\t -- Stop development server"
	@echo -e "\tcomposer \t -- Install composer packages"
	@echo -e "\tnpm \t\t -- Install npm packages"
	@echo -e "\twebpack \t -- Build assets with webpack"
	@echo -e "\tcontext \t -- Switch docker context"
	@echo -e "\tbuild \t\t -- Build and push tagged image"
	@echo -e "\tartifact \t -- Install depedenencies before building"
	@echo -e "\tdeploy \t\t -- Deploy a tagged image in the current docker context"
	@echo -e "\tpublic \t\t -- Launch this site on the load balancer"
	@echo -e "\tpush \t\t -- Push db/files to current docker context"
	@echo -e "\tpull \t\t -- Pull db/files from current docker context"
	@echo -e "\tshell \t\t -- Launch a bash shell to explore"
	@echo -e "\tbackup \t\t -- Backups are hourly, but make one right now"
	@echo -e "\tinit \t\t -- Initialize website for development"
	@echo -e ""
	@echo -e "Other examples:"
	@echo -e "\tcomposer require wpackagist-plugin/wp-test-email\t -- Install WP plugin"
	@echo -e "\tcomposer require wpackagist-theme/hueman\t\t -- Install WP theme"
	@echo -e "\tnpm install --save-dev jquery\t\t\t\t -- Install npm module"
	@echo -e "\tbin/wp core version\t\t\t\t\t -- WP-CLI commands"
	@echo -e "\tdocker service logs -f $(shell bin/get app)_srv\t\t\t -- Show logs"

# Install composer packages
composer:		; composer update && composer dump-autoload -o

# Install npm packages
npm:			; npm update --save-dev

# Build assets with webpack
webpack: 		; webpack --progress

# Run development server
up:			; @bin/run up
down:			; docker -c default stack rm $(shell bin/get app)

# Switch docker context
context: 		; @bin/run context

# Build and push tagged docker image
build: 			; @bin/run build

# Deploy a tagged image to the current docker context
deploy:			; @bin/run deploy

# Install dependencies before building
artifact: webpack composer build;

# Launch this site on the load balancer
public:			; @bin/run public

# Push/pull data to/from current docker context
push_db:		; @bin/run push_db
pull_db:		; @bin/run pull_db
push_files: 		; @bin/run push_files
pull_files: 		; @bin/run pull_files

push: push_files push_db 
pull: pull_files pull_db 

# Launch a bash shell to exlore
shell:			; docker exec -it $(shell bin/get container_id) bash
oneoff:			; docker run -it --rm -v $(shell bin/get uploads):/srv/web/content/uploads $(shell bin/get image):latest bash
login:			; @bin/get login

# Initialize website
init_db:		; @bin/run init_db
init_wp: 		; @bin/run init_wp
wait:			; sleep 15
init: init_db npm composer up wait init_wp 

# Backups are hourly, but make one right now
backup: 		; ssh root@host.$(shell bin/get swarm) 'su -c "rsnapshot hourly" - work'
