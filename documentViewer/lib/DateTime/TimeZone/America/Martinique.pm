# This file is auto-generated by the Perl DateTime Suite time zone
# code generator (0.07) This code generator comes with the
# DateTime::TimeZone module distribution in the tools/ directory

#
# Generated from ../DateTime/data/Olson/2010o/northamerica.  Olson data version 2010o
#
# Do not edit this file directly.
#
package DateTime::TimeZone::America::Martinique;
BEGIN {
  $DateTime::TimeZone::America::Martinique::VERSION = '1.26';
}

use strict;

use Class::Singleton;
use DateTime::TimeZone;
use DateTime::TimeZone::OlsonDB;

@DateTime::TimeZone::America::Martinique::ISA = ( 'Class::Singleton', 'DateTime::TimeZone' );

my $spans =
[
    [
DateTime::TimeZone::NEG_INFINITY,
59611176260,
DateTime::TimeZone::NEG_INFINITY,
59611161600,
-14660,
0,
'LMT'
    ],
    [
59611176260,
60284145860,
59611161600,
60284131200,
-14660,
0,
'FFMT'
    ],
    [
60284145860,
62459524800,
60284131460,
62459510400,
-14400,
0,
'AST'
    ],
    [
62459524800,
62474641200,
62459514000,
62474630400,
-10800,
1,
'ADT'
    ],
    [
62474641200,
DateTime::TimeZone::INFINITY,
62474626800,
DateTime::TimeZone::INFINITY,
-14400,
0,
'AST'
    ],
];

sub olson_version { '2010o' }

sub has_dst_changes { 1 }

sub _max_year { 2020 }

sub _new_instance
{
    return shift->_init( @_, spans => $spans );
}



1;

