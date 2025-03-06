RED="\033[0;31m"
REDBOLD="\033[1;31m"
GREEN="\033[0;32m"
GREENBOLD="\033[1;32m"
YELLOW="\033[0;33m"
YELLOWBOLD="\033[1;33m"
BLUE="\033[0;34m"
BLUEBOLD="\033[1;34m"
PURPLE="\033[0;35m"
PURPLEBOLD="\033[1;35m"
CYAN="\033[0;36m"
CYANBOLD="\033[1;36m"
WHITE="\033[0;37m"
WHITEBOLD="\033[1;37m"
NC='\033[0m' # No Color

log_info(){
	DATE_WITH_TIME=`date +"%T"`

	if [[ $2 ]]; then
		printf "${CYAN} $1 ${CYANBOLD} $2 ${NC}\n"
	else
		printf "${CYAN}${1}${NC}\n"
	fi
}

log_warn(){
	msg="${YELLOWBOLD}${1}${NC}"
	log "$msg"
}

log_error(){
	# msg="${REDBOLD}${1}${NC}"
	if [[ $2 ]]; then
		msg="${RED}${1}${NC}${REDBOLD}${2}${NC}"
	else
		msg="${RED}${1}${NC}"
	fi
	log "$msg"
}

log_success(){
	if [[ $2 ]]; then
		msg="${GREEN}${1}${NC}${GREENBOLD}${2}${NC}"
	else
		msg="${GREEN}${1}${NC}"
	fi
	log "$msg"
}