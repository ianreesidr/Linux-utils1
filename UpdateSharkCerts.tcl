#!/bin/sh
#\
exec tclsh "$0" ${1+ "$@"}
#instructions above apply to executing on Linux systems
#do 'dos2unix' if this doesnt work
#
# script to get the list of netsharks connected to netprofiler and update the mnmp certificate
# to be used to update all sharks on a network following the expiry of certificate after Sept 29 2016
#
# relies on php script to connect to shark using REST API
# TACACS login and password are assumed to be authorised for all devices
# parameters #####
# parameter 1: username
# parameter 2: password
# to be run on the NetProfiler 
#
# Procedure to write certificate to shark
proc writeSharkCert {ip_address username password} {
		set php_script "/usr/local/bin/php shark_cert_update.php"
		set php_script [concat $php_script  " $ip_address $username $password"]
        if { [catch {eval exec $php_script } php_output] } {
           puts "$::errorCode"
        }
        return $php_output
}