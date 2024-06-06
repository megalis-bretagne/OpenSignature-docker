#!/bin/bash
#

############################################
#This Bash script facilitates email sending using the curl command. 
#It retrieves configuration settings from the global application configuration file (MailConfig.sh) and sends emails based on provided arguments. The script encodes the subject in base64 due to PHP shellescapecmd requirements.
############################################

##########################################################
# fetch config
##########################################################

# fetch global application config (php !)
#
MYSELF=$0
TOPDIR=$(dirname $(dirname $(dirname ${MYSELF})))
APPCONF=${TOPDIR}/config/MailConfig.sh
MYSLF2=$(basename ${MYSELF})

if [ -s ${APPCONF} ]
then
    source ${APPCONF}
    if [ "${MYSLF2}" = "melsigsnd" ]
    then
        FROM=${MAILSIGNFROM}
    else
        FROM=${MAILFROM}
    fi
else
  echo "Can't find application config file - aborting !"
  exit 3
fi

if [ "${FROM}" = "" -o "${MELSNDMODE}" = "" ]
then
  echo "undefined mail config - aborting !"
  exit 4
fi

##########################################################
# PARSE ARGS  ( should use getopt !! )
##########################################################

MTO=$1
#  subject is base64 encoded !!  because of php shellescapecmd
SUBJ64=$2                   
MFIL=$3

SUBJ=$(echo -n ${SUBJ64} | base64 -d)

MESSAGE="From: ${FROM}
To: ${MTO}
Subject: ${SUBJ}
Reply-To: ${FROM}
MIME-Version: 1.0
Content-Type: text/html; charset=utf-8
Content-Disposition: inline

$(cat ${MFIL})
"

case ${MELSNDMODE} in

"REAL" )
    curl --ssl-reqd --url "${SMTPS}:${SMTPP}" --mail-from "${FROM}" --mail-rcpt "${MTO}" --upload-file - <<EOF 
$MESSAGE
EOF
    ;;
    
"DEBUG" )
    echo "==================================" >> /tmp/melsnd.log
    echo "TO=${MTO} FROM=${FROM} SUB=${SUBJ}" >> /tmp/melsnd.log
    echo "-----------attached file------------" >> /tmp/melsnd.log
    echo ${MESSAGE} >> /tmp/melsnd.log
    ;;

esac
