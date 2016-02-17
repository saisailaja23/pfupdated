# This file is auto-generated by the Perl DateTime Suite time zone
# code generator (0.07) This code generator comes with the
# DateTime::TimeZone module distribution in the tools/ directory

#
# Generated from ../DateTime/data/Olson/2010o/northamerica.  Olson data version 2010o
#
# Do not edit this file directly.
#
package DateTime::TimeZone::America::Atikokan;
BEGIN {
  $DateTime::TimeZone::America::Atikokan::VERSION = '1.26';
}

use strict;

use Class::Singleton;
use DateTime::TimeZone;
use DateTime::TimeZone::OlsonDB;

@DateTime::TimeZone::America::Atikokan::ISA = ( 'Class::Singleton', 'DateTime::TimeZone' );

my $spans =
[
    [
DateTime::TimeZone::NEG_INFINITY,
59768949988,
DateTime::TimeZone::NEG_INFINITY,
59768928000,
-21988,
0,
'LMT'
    ],
    [
59768949988,
60503616000,
59768928388,
60503594400,
-21600,
0,
'CST'
    ],
    [
60503616000,
60520892400,
60503598000,
60520874400,
-18000,
1,
'CDT'
    ],
    [
60520892400,
61212434400,
60520870800,
61212412800,
-21600,
0,
'CST'
    ],
    [
61212434400,
61255468800,
61212416400,
61255450800,
-18000,
1,
'CDT'
    ],
    [
61255468800,
61366287600,
61255450800,
61366269600,
-18000,
1,
'CWT'
    ],
    [
61366287600,
61370290800,
61366269600,
61370272800,
-18000,
1,
'CPT'
    ],
    [
61370290800,
DateTime::TimeZone::INFINITY,
61370272800,
DateTime::TimeZone::INFINITY,
-18000,
0,
'EST'
    ],
];

sub olson_version { '2010o' }

sub has_dst_changes { 4 }

sub _max_year { 2020 }

sub _new_instance
{
    return shift->_init( @_, spans => $spans );
}



1;

