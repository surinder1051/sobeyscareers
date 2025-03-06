script_full_path=$(dirname "$0")
source $script_full_path/functions.sh

skip_npm_install=$1

log_info "skip_npm_install = $skip_npm_install"

#used to create default helper variables in the default branding file
theme_dir="$(dirname "$PWD")"
theme_path="/wp-content/themes/"$(basename "$theme_dir")

# This script will update frequently updated files
# this script should be run from the theme directory

# Remove old fp-foundation and fp-foudnation-theme

file1=`md5 fp-foundation/update.sh`

log_info "Updating..."

rm -fr fp-foundation
git clone --quiet git@github.com:FlowPress/fp-foundation.git fp-foundation > /dev/null


file2=`md5 fp-foundation/update.sh`

if [ "$file1" != "$file2" ]
then
	log_info "Re-Running update.sh second time with updated script."
	./fp-foundation/update.sh
	exit
fi

# Moving .git folder into git_disabled so we can quickly re-enable git if we need to push changes back up to foundation repo
# .git_disabled dir should be ignored in project or global git ignore files
mv fp-foundation/.git fp-foundation/.git_disabled
cd fp-foundation
git clone --quiet git@github.com:FlowPress/fp-foundation-theme.git fp-foundation-theme > /dev/null
cd ..

#Create a default branding sass file if it doesn't exist
log_info "..."
log_info "$theme_dir/assets/scss/includes/_branding.scss"
if [ ! -f $theme_dir/assets/scss/includes/_branding.scss ];
then
	log_info "Creating default branding sass file..."
	echo "\$base-path: "$theme_path
	pwd
	echo "\$base-path: \"$theme_path\";\r\$asset-path: \"$theme_path/assets/\";\r\$base-font-size: 16;" > "$theme_dir/assets/scss/includes_branding.scss"
fi

log_info "Creating Theme Structure..."
# Init Structure
mkdir -p assets
mkdir -p assets/scss
mkdir -p assets/scss/includes
mkdir -p assets/scss/includes-foundation
mkdir -p assets/scss/pages
mkdir -p assets/scss/templates
mkdir -p assets/scss/campaigns
mkdir -p assets/js
mkdir -p components
mkdir -p dist
mkdir -p classes

log_info "Updating Gulp templates..."
cp fp-foundation/fp-foundation-theme/.browserslistrc .browserslistrc
cp fp-foundation/fp-foundation-theme/eslintrc.js eslintrc.js
cp fp-foundation/fp-foundation-theme/.scss-lint.yml .scss-lint.yml
cp fp-foundation/fp-foundation-theme/Gulpfile.js Gulpfile.js
cp fp-foundation/fp-foundation-theme/package.json package.json
cp -n fp-foundation/templates/sample.env .env

log_info "Updating Theme Structure..."
cp fp-foundation/fp-foundation-theme/assets/scss/includes-foundation/_component.scss assets/scss/includes-foundation/_component.scss
cp fp-foundation/fp-foundation-theme/assets/scss/includes-foundation/_mixins.scss assets/scss/includes-foundation/_mixins.scss

cp -n fp-foundation/fp-foundation-theme/assets/scss/includes/_bootstrap.scss assets/scss/includes/_bootstrap.scss
cp -n fp-foundation/fp-foundation-theme/assets/scss/includes/_branding.scss assets/scss/includes/_branding.scss
cp -n fp-foundation/fp-foundation-theme/assets/scss/includes/_fonts.scss assets/scss/includes/_fonts.scss
cp -n fp-foundation/fp-foundation-theme/assets/scss/includes/_forms.scss assets/scss/includes/_forms.scss
cp -n fp-foundation/fp-foundation-theme/assets/scss/includes/_gutenberg.scss assets/scss/includes/_gutenberg.scss
cp -n fp-foundation/fp-foundation-theme/assets/scss/includes/_icons.scss assets/scss/includes/_icons.scss
cp -n fp-foundation/fp-foundation-theme/assets/scss/includes/_templates.scss assets/scss/includes/_templates.scss

# Allow for skipping npm install via running update.sh true
if [[ -z $skip_npm_install ]]; then
	log_info "NPM Install"
	npm install

	log_info "NPM Audit Fix"
	npm audit fix
else
	log_info "Skipping NPM install"
fi

log_info "Cleaning up..."
rm -fr fp-foundation/fp-foundation-theme/

log_info "Done."
