#!/bin/sh
#\
exec tclsh "$0" ${1+ "$@"}
#instructions above apply to executing on Linux systems
#do 'dos2unix' if this doesnt work
##################################################
# mdSort.tcl Script to sort the output of cat /proc/mdstat
# Can make it easier to see which disk is missing
#################################################
# comments ian.rees@riverbed.com
#################################################
# save this script to /home/mazu on a cascade device
# make executable with chmod +x mdSort.tcl
# run ./mdSort.tcl file_containing_output_of_proc/mdstat
# defaults to reading /proc/mdstat
# writes sorted output in to
# /home/mazu/mdStatSort.txt
################################################
#
proc mdStatSort {Input} {
        set Output ""
        while {[string first "md" $Input] > -1} {
                set OutputLine ""
                set mdX [string first "md" $Input]
                set mdLineEnd [string first "\n" $Input $mdX]
                set mdLine [string range $Input $mdX $mdLineEnd]
                set mdNum [string trim [lindex $mdLine 0] "md"]
                set mdLine [lrange $mdLine 4 [llength $mdLine]]
                set mdLine [lsort $mdLine]
                set Input [string range $Input [expr ($mdLineEnd + 1)] [string length $Input]]
                set OutputLine [join "md $mdNum $mdLine" " "]
                lappend Output $OutputLine
        }
        set Output [lsort -integer -index 1 $Output]
        set OutputFile "/home/mazu/mdStatSort.txt"
        if {[catch {open $OutputFile w} fileId]} {
                error $fileId $::errorInfo $::errorCode
        } else {
                foreach {Item} $Output {
                        puts $fileId $Item
                }
        }
        close $fileId
        puts "file $OutputFile has been created"
}
if {[llength $argv] == 0} {
        catch {eval exec "/bin/cat /proc/mdstat"} MdStat
} else {
        set LogFile [lindex $argv 0]
        # read from a text file
        if {[catch {open $LogFile r} fileId]} {
                 error $fileId $::errorInfo $::errorCode
        } else {
                 set MdStat [read  $fileId]
                 close $fileId
        }
}
puts "we read this: $MdStat"
mdStatSort $MdStat

##################################################
