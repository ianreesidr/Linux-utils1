#!/bin/sh
#\
exec tclsh "$0" ${1+ "$@"}
#instructions above apply to executing on Linux systems
#do 'dos2unix' if this doesnt work

### read the crashdump file in pilot log
### and print a list of the time and date of each crash
###
### please make this file executable with chmod +x timeCovert.tcl
### please place this file in the root directory of the shark logs
###


proc timeConvert {} {
	set input [eval exec "/bin/grep \"ts ->\" pilot/logs/crashdump"]
	while {[string len $input] > 0} {
		set UnixTime [string range $input 6 15]
		set input [string range $input 23 [string len $input]] 
		puts [clock format $UnixTime -format {%A the %d of %B, %Y %H:%M:%S}]
	}
}

timeConvert
