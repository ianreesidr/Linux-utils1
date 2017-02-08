#!/bin/sh
#\
exec tclsh "$0" ${1+ "$@"}
#instructions above apply to executing on Linux systems
#do 'dos2unix' if this doesnt work
##################################################
# procNetstat.tcl
# prints the output of /proc/{pid}/netstat
# in 2 nice columns for easy reading
##################################################
# pass the pid as a parameter
##################################################
if {0} {
        rename set _set
    proc set {var args} {
       puts [list setting $var $args]
       uplevel _set $var $args
    }
}


proc procNetStat {Input} {
        set TcpExtInstance [string first "TcpExt" $Input]
        set Index1 [expr ($TcpExtInstance +7)]
        set Input [string range $Input $Index1 [string length $Input]]
        set TcpExtInstance [string first "TcpExt" $Input]
        set NamesList [string range $Input 1 [expr ($TcpExtInstance -1)]]

        set IpExtInstance [string first "IpExt" $Input]
        set Index1 [expr ($TcpExtInstance +7)]
        set Index2 [expr ($IpExtInstance -1)]
        set ValuesList [string range $Input $Index1 $Index2]

        foreach Name $NamesList Value $ValuesList {
                puts "$Name $Value"
        }
}



set pid [lindex $argv 0]
if { [catch {eval exec "/bin/cat /proc/$pid/net/netstat" } Data] } {
           puts "$::errorCode"
   }
procNetStat $Data
