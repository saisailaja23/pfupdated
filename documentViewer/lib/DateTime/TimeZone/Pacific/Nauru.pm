# This file is auto-generated by the Perl DateTime Suite time zone
# code generator (0.07) This code generator comes with the
# DateTime::TimeZone module distribution in the tools/ directory

#
# Generated from ../DateTime/data/Olson/2010o/australasia.  Olson data version 2010o
#
# Do not edit this file directly.
#
package DateTime::TimeZone::Pacific::Nauru;
BEGIN {
  $DateTime::TimeZone::Pacific::Nauru::VERSION = '1.26';
}

use strict;

use Class::Singleton;
use DateTime::TimeZone;
use DateTime::TimeZone::OlsonDB;

@DateTime::TimeZone::Pacific::Nauru::ISA = ( 'Class::Singleton', 'DateTime::TimeZone' );

my $spans =
[
    [
DateTime::TimeZone::NEG_INFINITY,
60590551940,
DateTime::TimeZone::NEG_INFINITY,
60590592000,
40060,
0,
'LMT'
    ],
    [
60590551940,
61258336200,
60590593340,
61258377600,
41400,
0,
'NRT'
    ],
    [
61258336200,
61334722800,
61258368600,
61334755200,
32400,
0,
'JST'
    ],
    [
61334722800,
62430006600,
61334764200,
62430048000,
41400,
0,
'NRT'
    ],
    [
62430006600,
DateTime::TimeZone::INFINITY,
62430049800,
DateTime::TimeZone::INFINITY,
43200,
0,
'NRT'
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

