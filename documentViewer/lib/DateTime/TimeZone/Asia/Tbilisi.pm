# This file is auto-generated by the Perl DateTime Suite time zone
# code generator (0.07) This code generator comes with the
# DateTime::TimeZone module distribution in the tools/ directory

#
# Generated from ../DateTime/data/Olson/2010o/asia.  Olson data version 2010o
#
# Do not edit this file directly.
#
package DateTime::TimeZone::Asia::Tbilisi;
BEGIN {
  $DateTime::TimeZone::Asia::Tbilisi::VERSION = '1.26';
}

use strict;

use Class::Singleton;
use DateTime::TimeZone;
use DateTime::TimeZone::OlsonDB;

@DateTime::TimeZone::Asia::Tbilisi::ISA = ( 'Class::Singleton', 'DateTime::TimeZone' );

my $spans =
[
    [
DateTime::TimeZone::NEG_INFINITY,
59295531644,
DateTime::TimeZone::NEG_INFINITY,
59295542400,
10756,
0,
'LMT'
    ],
    [
59295531644,
60694520444,
59295542400,
60694531200,
10756,
0,
'TBMT'
    ],
    [
60694520444,
61730542800,
60694531244,
61730553600,
10800,
0,
'TBIT'
    ],
    [
61730542800,
62490600000,
61730557200,
62490614400,
14400,
0,
'TBIT'
    ],
    [
62490600000,
62506407600,
62490618000,
62506425600,
18000,
1,
'TBIST'
    ],
    [
62506407600,
62522136000,
62506422000,
62522150400,
14400,
0,
'TBIT'
    ],
    [
62522136000,
62537943600,
62522154000,
62537961600,
18000,
1,
'TBIST'
    ],
    [
62537943600,
62553672000,
62537958000,
62553686400,
14400,
0,
'TBIT'
    ],
    [
62553672000,
62569479600,
62553690000,
62569497600,
18000,
1,
'TBIST'
    ],
    [
62569479600,
62585294400,
62569494000,
62585308800,
14400,
0,
'TBIT'
    ],
    [
62585294400,
62601026400,
62585312400,
62601044400,
18000,
1,
'TBIST'
    ],
    [
62601026400,
62616751200,
62601040800,
62616765600,
14400,
0,
'TBIT'
    ],
    [
62616751200,
62632476000,
62616769200,
62632494000,
18000,
1,
'TBIST'
    ],
    [
62632476000,
62648200800,
62632490400,
62648215200,
14400,
0,
'TBIT'
    ],
    [
62648200800,
62663925600,
62648218800,
62663943600,
18000,
1,
'TBIST'
    ],
    [
62663925600,
62679650400,
62663940000,
62679664800,
14400,
0,
'TBIT'
    ],
    [
62679650400,
62695375200,
62679668400,
62695393200,
18000,
1,
'TBIST'
    ],
    [
62695375200,
62711100000,
62695389600,
62711114400,
14400,
0,
'TBIT'
    ],
    [
62711100000,
62726824800,
62711118000,
62726842800,
18000,
1,
'TBIST'
    ],
    [
62726824800,
62742549600,
62726839200,
62742564000,
14400,
0,
'TBIT'
    ],
    [
62742549600,
62758274400,
62742567600,
62758292400,
18000,
1,
'TBIST'
    ],
    [
62758274400,
62773999200,
62758288800,
62774013600,
14400,
0,
'TBIT'
    ],
    [
62773999200,
62790328800,
62774017200,
62790346800,
18000,
1,
'TBIST'
    ],
    [
62790328800,
62806053600,
62790343200,
62806068000,
14400,
0,
'TBIT'
    ],
    [
62806053600,
62806824000,
62806068000,
62806838400,
14400,
1,
'TBIST'
    ],
    [
62806824000,
62821782000,
62806838400,
62821796400,
14400,
1,
'GEST'
    ],
    [
62821782000,
62829896400,
62821792800,
62829907200,
10800,
0,
'GET'
    ],
    [
62829896400,
62837499600,
62829907200,
62837510400,
10800,
0,
'GET'
    ],
    [
62837499600,
62853220800,
62837514000,
62853235200,
14400,
1,
'GEST'
    ],
    [
62853220800,
62868949200,
62853231600,
62868960000,
10800,
0,
'GET'
    ],
    [
62868949200,
62884670400,
62868963600,
62884684800,
14400,
1,
'GEST'
    ],
    [
62884670400,
62900398800,
62884681200,
62900409600,
10800,
0,
'GET'
    ],
    [
62900398800,
62916120000,
62900413200,
62916134400,
14400,
1,
'GEST'
    ],
    [
62916120000,
62931844800,
62916134400,
62931859200,
14400,
0,
'GET'
    ],
    [
62931844800,
62947566000,
62931862800,
62947584000,
18000,
1,
'GEST'
    ],
    [
62947566000,
62963899200,
62947580400,
62963913600,
14400,
0,
'GET'
    ],
    [
62963899200,
62982039600,
62963917200,
62982057600,
18000,
1,
'GEST'
    ],
    [
62982039600,
62995345200,
62982057600,
62995363200,
18000,
1,
'GEST'
    ],
    [
62995345200,
63013489200,
62995363200,
63013507200,
18000,
1,
'GEST'
    ],
    [
63013489200,
63026798400,
63013503600,
63026812800,
14400,
0,
'GET'
    ],
    [
63026798400,
63044938800,
63026816400,
63044956800,
18000,
1,
'GEST'
    ],
    [
63044938800,
63058248000,
63044953200,
63058262400,
14400,
0,
'GET'
    ],
    [
63058248000,
63076993200,
63058266000,
63077011200,
18000,
1,
'GEST'
    ],
    [
63076993200,
63089697600,
63077007600,
63089712000,
14400,
0,
'GET'
    ],
    [
63089697600,
63108442800,
63089715600,
63108460800,
18000,
1,
'GEST'
    ],
    [
63108442800,
63121147200,
63108457200,
63121161600,
14400,
0,
'GET'
    ],
    [
63121147200,
63139892400,
63121165200,
63139910400,
18000,
1,
'GEST'
    ],
    [
63139892400,
63153201600,
63139906800,
63153216000,
14400,
0,
'GET'
    ],
    [
63153201600,
63171342000,
63153219600,
63171360000,
18000,
1,
'GEST'
    ],
    [
63171342000,
63184651200,
63171356400,
63184665600,
14400,
0,
'GET'
    ],
    [
63184651200,
63202791600,
63184669200,
63202809600,
18000,
1,
'GEST'
    ],
    [
63202791600,
63216100800,
63202806000,
63216115200,
14400,
0,
'GET'
    ],
    [
63216100800,
63223959600,
63216118800,
63223977600,
18000,
1,
'GEST'
    ],
    [
63223959600,
63234860400,
63223974000,
63234874800,
14400,
1,
'GEST'
    ],
    [
63234860400,
63247561200,
63234871200,
63247572000,
10800,
0,
'GET'
    ],
    [
63247561200,
DateTime::TimeZone::INFINITY,
63247575600,
DateTime::TimeZone::INFINITY,
14400,
0,
'GET'
    ],
];

sub olson_version { '2010o' }

sub has_dst_changes { 27 }

sub _max_year { 2020 }

sub _new_instance
{
    return shift->_init( @_, spans => $spans );
}



1;

