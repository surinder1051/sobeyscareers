script_full_path=$(dirname "$0")
source $script_full_path/functions.sh

skip_npm_install=$1

log_info "skip_npm_install = $skip_npm_install"

# This script will update frequently updated files
# this script should be run from the theme directory

# Remove old fp-foundation and fp-foundation-theme

file1=`md5 fp-foundation/update-core.sh`

log_info "Updating..."

rm -fr fp-foundation
git clone --quiet git@github.com:FlowPress/fp-foundation.git fp-foundation > /dev/null


file2=`md5 fp-foundation/update-core.sh`

if [ "$file1" != "$file2" ]
then
	log_info "Re-Running update.sh second time with updated script."
	./fp-foundation/update-core.sh
	exit
fi

# Allow for skipping npm install via running update.sh true
if [[ -z $skip_npm_install ]]; then

	log_info "Updating Gulp templates..."
	cp fp-foundation/fp-foundation-theme/.browserslistrc .browserslistrc
	cp fp-foundation/fp-foundation-theme/eslintrc.js eslintrc.js
	cp fp-foundation/fp-foundation-theme/.scss-lint.yml .scss-lint.yml
	cp fp-foundation/fp-foundation-theme/Gulpfile.js Gulpfile.js
	cp fp-foundation/fp-foundation-theme/package.json package.json
	cp -n fp-foundation/templates/sample.env .env
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