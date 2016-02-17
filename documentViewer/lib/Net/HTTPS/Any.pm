package Net::HTTPS::Any;

use warnings;
use strict;
use base qw( Exporter );
use vars qw(@EXPORT_OK $ssl_module $skip_NetSSLeay);
use URI::Escape;
use Tie::IxHash;

@EXPORT_OK = qw( https_get https_post );

BEGIN {

    $ssl_module = '';

    eval {
        die if defined($skip_NetSSLeay) && $skip_NetSSLeay;
        require Net::SSLeay;
        Net::SSLeay->VERSION(1.30);

        #import Net::SSLeay
        #  qw(get_https post_https make_form make_headers);
        $ssl_module = 'Net::SSLeay';
    };

    if ($@) {
        eval {
            require LWP::UserAgent;
            require HTTP::Request::Common;
            require Crypt::SSLeay;

            #import HTTP::Request::Common qw(GET POST);
            $ssl_module = 'Crypt::SSLeay';
        };
    }

    unless ($ssl_module) {
        die "One of Net::SSLeay (v1.30 or later)"
          . " or Crypt::SSLeay (+LWP) is required";
    }

}

=head1 NAME

Net::HTTPS::Any - Simple HTTPS class using whichever underlying SSL module is available

=cut

our $VERSION = '0.10';

=head1 SYNOPSIS

  use Net::HTTPS::Any qw(https_get https_post);
  
  ( $page, $response, %reply_headers )
      = https_get(
                   { 'host' => 'www.fortify.net',
                     'port' => 443,
                     'path' => '/sslcheck.html',
                   },
                 );

  ( $page, $response, %reply_headers )
      = https_post(
                    'host' => 'www.google.com',
                    'port' => 443,
                    'path' => '/accounts/ServiceLoginAuth',
                    'args' => { 'field' => 'value' },
                    #'args' => [ 'field'=>'value' ], #order preserved
                  );
  
  #...

=head1 DESCRIPTION

This is a simple wrapper around either of the two available SSL
modules.  It offers a unified API for sending GET and POST requests over HTTPS
and receiving responses.

It depends on Net::SSLeay _or_ ( Crypt::SSLeay and LWP::UserAgent ).

=head1 WHY THIS MODULE

If you just want to write something that speaks HTTPS, you don't need this
module.  Just go ahead and use whichever of the two modules is good for you.
Don't worry about it.

On the other hand, if you are a CPAN author or distribute a Perl application,
especially if you aim to support multiple OSes/disributions, using this module
for speaking HTTPS may make things easier on your users.  It allows your code
to be used with either SSL implementation.

=head1 FUNCTIONS

=head2 https_get HASHREF | FIELD => VALUE, ...

Accepts parameters as either a hashref or a list of fields and values.

Parameters are:

=over 4

=item host

=item port

=item path

=item headers (hashref)

For example: { 'X-Header1' => 'value', ... }

=cut

# =item Content-Type
# 
# Defaults to "application/x-www-form-urlencoded" if not specified.

=item args

CGI arguments, eitehr as a hashref or a listref.  In the latter case, ordering
is preserved (see L<Tie::IxHash> to do so when passing a hashref).

=item debug

Set true to enable debugging.

=back

Returns a list consisting of the page content as a string, the HTTP
response code and message (i.e. "200 OK" or "404 Not Found"), and a list of
key/value pairs representing the HTTP response headers.

=cut

sub https_get {
    my $opts = ref($_[0]) ? shift : { @_ }; #hashref or list

    # accept a hashref or a list (keep it ordered)
    my $post_data = {}; # technically get_data, pedant
    if (      exists($opts->{'args'}) && ref($opts->{'args'}) eq 'HASH'  ) {
        $post_data = $opts->{'args'};
    } elsif ( exists($opts->{'args'}) && ref($opts->{'args'}) eq 'ARRAY' ) {
        tie my %hash, 'Tie::IxHash', @{ $opts->{'args'} };
        $post_data = \%hash;
    }

    $opts->{'port'} ||= 443;
    #$opts->{"Content-Type"} ||= "application/x-www-form-urlencoded";

    ### XXX referer!!!
    my %headers = ();
    if ( ref( $opts->{headers} ) eq "HASH" ) {
        %headers = %{ $opts->{headers} };
    }
    $headers{'Host'} ||= $opts->{'host'};

    my $path = $opts->{'path'};
    if ( keys %$post_data ) {
        $path .= '?'
          . join( ';',
            map { uri_escape($_) . '=' . uri_escape( $post_data->{$_} ) }
              keys %$post_data );
    }

    if ( $ssl_module eq 'Net::SSLeay' ) {

        no warnings 'uninitialized';

        import Net::SSLeay qw(get_https make_headers);
        my $headers = make_headers(%headers);

        $Net::SSLeay::trace = $opts->{'debug'}
          if exists $opts->{'debug'} && $opts->{'debug'};

        my( $res_page, $res_code, @res_headers ) =
          get_https( $opts->{'host'},
                     $opts->{'port'},
                     $path,
                     $headers,
                     #"",
                     #$opts->{"Content-Type"},
                   );

        $res_code =~ /^(HTTP\S+ )?(.*)/ and $res_code = $2;

        return ( $res_page, $res_code, @res_headers );

    } elsif ( $ssl_module eq 'Crypt::SSLeay' ) {

        import HTTP::Request::Common qw(GET);

        my $url = 'https://' . $opts->{'host'};
        $url .= ':' . $opts->{'port'}
          unless $opts->{'port'} == 443;
        $url .= "/$path";

        my $ua = new LWP::UserAgent;
        foreach my $hdr ( keys %headers ) {
            $ua->default_header( $hdr => $headers{$hdr} );
        }
        $ENV{HTTPS_DEBUG} = $opts->{'debug'} if exists $opts->{'debug'};
        my $res = $ua->request( GET($url) );

        my @res_headers = map { $_ => $res->header($_) }
                              $res->header_field_names;

        return ( $res->content, $res->code. ' '. $res->message, @res_headers );

    } else {
        die "unknown SSL module $ssl_module";
    }

}

=head2 https_post HASHREF | FIELD => VALUE, ...

Accepts parameters as either a hashref or a list of fields and values.

Parameters are:

=over 4

=item host

=item port

=item path

=item headers (hashref)

For example: { 'X-Header1' => 'value', ... }

=item Content-Type

Defaults to "application/x-www-form-urlencoded" if not specified.

=item args

CGI arguments, eitehr as a hashref or a listref.  In the latter case, ordering
is preserved (see L<Tie::IxHash> to do so when passing a hashref).

=item content

Raw content (overrides args).  A simple scalar containing the raw content.

=item debug

Set true to enable debugging in the underlying SSL module.

=back

Returns a list consisting of the page content as a string, the HTTP
response code and message (i.e. "200 OK" or "404 Not Found"), and a list of
key/value pairs representing the HTTP response headers.

=cut

sub https_post {
    my $opts = ref($_[0]) ? shift : { @_ }; #hashref or list

    # accept a hashref or a list (keep it ordered).  or a scalar of content.
    my $post_data = '';
    if (      exists($opts->{'args'}) && ref($opts->{'args'}) eq 'HASH'  ) {
        $post_data = $opts->{'args'};
    } elsif ( exists($opts->{'args'}) && ref($opts->{'args'}) eq 'ARRAY' ) {
        tie my %hash, 'Tie::IxHash', @{ $opts->{'args'} };
        $post_data = \%hash;
    }
    if ( exists $opts->{'content'} ) {
        $post_data = $opts->{'content'};
    }

    $opts->{'port'} ||= 443;
    $opts->{"Content-Type"} ||= "application/x-www-form-urlencoded";

    ### XXX referer!!!
    my %headers;
    if ( ref( $opts->{headers} ) eq "HASH" ) {
        %headers = %{ $opts->{headers} };
    }
    $headers{'Host'} ||= $opts->{'host'};

    if ( $ssl_module eq 'Net::SSLeay' ) {
        
        no warnings 'uninitialized';

        import Net::SSLeay qw(post_https make_headers make_form);
        my $headers = make_headers(%headers);

        $Net::SSLeay::trace = $opts->{'debug'}
          if exists $opts->{'debug'} && $opts->{'debug'};

        my $raw_data = ref($post_data) ? make_form(%$post_data) : $post_data;

        $Net::SSLeay::trace = $opts->{'debug'}
          if exists $opts->{'debug'} && $opts->{'debug'};

        my( $res_page, $res_code, @res_headers ) =
          post_https( $opts->{'host'},
                      $opts->{'port'},
                      $opts->{'path'},
                      $headers,
                      $raw_data,
                      $opts->{"Content-Type"},
                    );

        $res_code =~ /^(HTTP\S+ )?(.*)/ and $res_code = $2;

        return ( $res_page, $res_code, @res_headers );

    } elsif ( $ssl_module eq 'Crypt::SSLeay' ) {

        import HTTP::Request::Common qw(POST);

        my $url = 'https://' . $opts->{'host'};
        $url .= ':' . $opts->{'port'}
          unless $opts->{'port'} == 443;
        $url .= $opts->{'path'};

        my $ua = new LWP::UserAgent;
        foreach my $hdr ( keys %headers ) {
            $ua->default_header( $hdr => $headers{$hdr} );
        }

        $ENV{HTTPS_DEBUG} = $opts->{'debug'} if exists $opts->{'debug'};

        my $res;
        if ( ref($post_data) ) {
            $res = $ua->request( POST( $url, [%$post_data] ) );
        }
        else {
            my $req = new HTTP::Request( 'POST' => $url );
            $req->content_type( $opts->{"Content-Type"} );
            $req->content($post_data);
            $res = $ua->request($req);
        }

        my @res_headers = map { $_ => $res->header($_) }
                              $res->header_field_names;

        return ( $res->content, $res->code. ' '. $res->message, @res_headers );

    } else {
        die "unknown SSL module $ssl_module";
    }

}

=head1 AUTHOR

Ivan Kohler, C<< <ivan-net-https-any at freeside.biz> >>

=head1 BUGS

Please report any bugs or feature requests to C<bug-net-https-any at rt.cpan.org>, or through
the web interface at L<http://rt.cpan.org/NoAuth/ReportBug.html?Queue=Net-HTTPS-Any>.  I will be notified, and then you'll
automatically be notified of progress on your bug as I make changes.

=head1 SUPPORT

You can find documentation for this module with the perldoc command.

    perldoc Net::HTTPS::Any

You can also look for information at:

=over 4

=item * RT: CPAN's request tracker

L<http://rt.cpan.org/NoAuth/Bugs.html?Dist=Net-HTTPS-Any>

=item * AnnoCPAN: Annotated CPAN documentation

L<http://annocpan.org/dist/Net-HTTPS-Any>

=item * CPAN Ratings

L<http://cpanratings.perl.org/d/Net-HTTPS-Any>

=item * Search CPAN

L<http://search.cpan.org/dist/Net-HTTPS-Any>

=back

=head1 COPYRIGHT & LICENSE

Copyright 2008-2010 Freeside Internet Services, Inc. (http://freeside.biz/)
All rights reserved.

This program is free software; you can redistribute it and/or modify it
under the same terms as Perl itself.

=cut

1;
