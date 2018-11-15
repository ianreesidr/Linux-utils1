#!/usr/bin/tclsh
###########################
###Scrape Any  Logs#####
###########################
# execute using this syntax:
# /u/irees/ScrapeLogs.tcl inputfile outputfile.csv
# The purpose of this script is to make ordered tables of interesting data from log file entries.
# The method is to search for specific start and end strings and specify an offset if the search string is adjacent to the interesting string
# Otherwise you must specify 0 for the offset.
# The search is sequential and discards the part of the log entry it has already recognised before searching the remaining part for the next search string.
#
# Specify a list of start and end strings that can be used to chop up barely readable log entries, selecting the data you want
# Search pairs with offsets are specified as the value of the variable SearchPairs in the format:
# {string1 offset1 endstring1 endoffset 1 string2 offset2 endstring2 endoffset2...etc}
# Must be in pairs, there is no error checking for bad syntax.
# If a string is not found  the script jumps to the next pair.
# eg: This entry scrapes the first flow and topo in both directions from the output of grep "conflicting" /usr/mazu/var/log/analyzer.log
# set SearchPairs {"fwd" 0 "\[" -2 "topo" 0 "\)" 0 "rev" 0 "\[" -2 "topo" 0 "\)" 0}
# output: fwd:{ (udp(17): 10.131.98.91:53 -> 10.20.120.71:36664),topo:(10.20.121.110 type:2 priority:2 init:dst (31/30),rev:{ (udp(17): 10.20.120.71:36664 -> 10.131.98.91:53),topo:(10.20.121.110 type:2 priority:2 init:src (30/31)
#
# edit the line below only following rule above. Keep a record of useful SearchPairs in the comments above.
#set SearchPairs {"fwd" 0 "\[" -2 "topo" 0 "\)" 0 "rev" 0 "\[" -2 "topo" 0 "\)" 0}
#set SearchPairs {"\/" -3 "\)" 0}
#set SearchPairs {"fwd" 0 "," -1 "\/" -3 "\)" 0 "rev" 0 "," -1 "\/" -3 "\)" 0}
# restdevicemanager debugging duplicate site names
#set SearchPairs {"group_names \[" 0 "\]" 0 "sites \[" 0 "'\]" 1}
#set SearchPairs {"\(" 1 "\)" -1}
# restdevicemanager print VALUES that have been inserted into shqos tables
#set SearchPairs {"VALUES" 8 "\)" -1}
# restdevicemanager print VALUES from an SQL UPDATE command
set SearchPairs {"=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1 "=" 1 "," -1}
# this routine debugs variable values to the console it is currently disabled
if {0} {
        rename set _set
    proc set {var args} {
       puts [list setting $var $args]
       uplevel _set $var $args
    }
}
#
#
proc getLogEntries {InputFile OutputFile SearchPairs} {
        # read the file to a list
        if {[file exists $InputFile]} {
                if {[catch {open $InputFile r} fileId]} {
                        error $fileId $::errorInfo $::errorCode
                } else {
                        # read the logentries into a list
                        set FileAsList [split [read -nonewline $fileId] \n]
                        close $fileId
                }
        }
        # Scrape strings and format as a comma separated file
        set CommaString {}
        set OutputList {}
        foreach {logEntry} $FileAsList {
                set CommaList {}
                foreach {SearchString SSOffset EndString EsOffset} $SearchPairs {
                        set Begin [string first $SearchString $logEntry]
                        if {$Begin > -1} {
                            #strip off everything before SearchString
                            set logEntry [string range $logEntry [expr {$Begin + $SSOffset}] [string length $logEntry]]
                            set End [string first $EndString $logEntry]
                            set Item "[string range $logEntry  0  [expr {$End + $EsOffset}]]"
                            # trim the logEntry to the character after End + Offset
                            set logEntry [string range $logEntry [expr {$End + $EsOffset}] [string length $logEntry]]
                            lappend CommaList $Item
                        }
                }
                set CommaString [join $CommaList ,]
                lappend OutputList $CommaString
        }

        # write the new values in List1 to File1
        if {[catch {open $OutputFile w} fileId]} {
                error $fileId $::errorInfo $::errorCode
        } else {
                foreach {Item} $OutputList {
                        puts $fileId $Item
                }
        }
        close $fileId
        #
        # output text
        puts "Selected Strings written to file $OutputFile"
}

set InFile "~/InputFile.txt"
set OutFile " ~/OutputFile.csv"
puts [llength $argv]
if {[llength $argv] == 2} {
        set InFile [lindex $argv 0]
        set OutFile [lindex $argv 1]
        #set SearchPairs [lindex $argv 2]
#} elseif {[llength $argv] ==2} {
        # SearchPairs defaults to the value hardcoded above
 #       puts "No SearchPair specified, hardcoded value will be used"
} elseif {[llength $argv] == 1} {
        set InFile [lindex $argv 0]
        puts "No output filename provided. ScrapeLogs.tcl will attempt to write to ~/OutputFile.csv"
} else {
        puts "No input or output filename provided. ScrapeLogs.tcl will attempt to  read from ~/InputFile.txt and write to ~/OutputFile.csv"
}
if {[file exists $InFile]} {
        # call getLogEntries
        if {[catch {getLogEntries $InFile $OutFile $SearchPairs} Result]} {
        error $Result $::errorInfo $::errorCode
        }
}
