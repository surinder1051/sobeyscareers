script_full_path=$(dirname "$0")
template_path=$(dirname "$1")
source $script_full_path/functions.sh

theme_dir="$(dirname "$PWD")"
theme_path="/wp-content/themes/"$(basename "$theme_dir")

# This file should be run from the theme root folder

# Need to clone this as non submodule because WPE doesn't support private submodules
# rm -fr fp-foundation && git clone git@github.com:FlowPress/fp-foundation.git fp-foundation && rm -fr fp-foundation/.git

log_info "Creating Theme Structure..."
# Init Structure
log_info "Creating Theme Structure..."
# Init Structure
mkdir -p assets
mkdir -p assets/scss
mkdir -p assets/scss/pages
mkdir -p assets/js
mkdir -p components
mkdir -p dist
mkdir -p classes

#auto create the _branding.scss file that will be used to override bootstrap and set theme configurations
echo "\$base-path: "$theme_path
echo "\$base-path: \"$theme_path\";\r\$asset-path: \"$theme_path/assets/\";\r\$base-font-size: 16;" > $theme_dir"/assets/scss/includes/_branding.scss"

log_info "Copying gulp config..."
# Only want to do this on init, if we don't need custom paths then defaults will come from GulpFile.js
cp -rfn fp-foundation/fp-foundation-theme/Gulpfile.json Gulpfile.json
cp -rfn fp-foundation/fp-foundation-theme/package.json package.json
cp -rfn fp-foundation/fp-foundation-theme/package-lock.json package-lock.json

log_info "Running Update..."
./fp-foundation/update.sh

log_warn "Make sure theme/node_modules is ignored in Git"