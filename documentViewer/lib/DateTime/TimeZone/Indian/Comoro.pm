# This file is auto-generated by the Perl DateTime Suite time zone
# code generator (0.07) This code generator comes with the
# DateTime::TimeZone module distribution in the tools/ directory

#
# Generated from ../DateTime/data/Olson/2010o/africa.  Olson data version 2010o
#
# Do not edit this file directly.
#
package DateTime::TimeZone::Indian::Comoro;
BEGIN {
  $DateTime::TimeZone::Indian::Comoro::VERSION = '1.26';
}

use strict;

use Class::Singleton;
use DateTime::TimeZone;
use DateTime::TimeZone::OlsonDB;

@DateTime::TimeZone::Indian::Comoro::ISA = ( 'Class::Singleton', 'DateTime::TimeZone' );

my $spans =
[
    [
DateTime::TimeZone::NEG_INFINITY,
60289391216,
DateTime::TimeZone::NEG_INFINITY,
60289401600,
10384,
0,
'LMT'
    ],
    [
60289391216,
DateTime::TimeZone::INFINITY,
60289402016,
DateTime::TimeZone::INFINITY,
10800,
0,
'EAT'
    ],
];

sub olson_version { '2010o' }

sub has_dst_changes { 0 }

sub _max_year { 2020 }

sub _new_instance
{
    return shift->_init( @_, spans => $spans );
}



1;

