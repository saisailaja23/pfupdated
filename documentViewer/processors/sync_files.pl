#!/usr/bin/perl -w
use strict;
use warnings 'all';
#use CGI qw/:standard/;

use lib "/var/www/html/documentViewer/lib/"; # centos test
use lib "/var/www/test/documentViewer/lib/"; # centos live production server - test host
use lib "/var/www/pdfviewer/documentViewer/lib/"; # window - live procution web2 website
use lib "d:/web/localuser/web2solutions/www/poc/documentViewer/lib/"; # window - live procution web2 website




use CGI;
use CGI::Carp 'fatalsToBrowser';
my $cgi = new CGI;

print $cgi->header("application/json");  #




use JSON;
use Data::Dumper;
use WWW::Mechanize;
#use LWP::Simple;

use File::Path qw{mkpath}; # make_path

#use URI::Escape;
#use HTTP::Request::Common;
#use LWP;
#use LWP::UserAgent;



  
sub fail
{
	my($err_msg) = @_;
	my %response = (
		status  => "err",
		response =>  $err_msg,
	);     
	my $json = \%response;
	my $json_text = to_json($json);                           
	print $json_text;
	exit;
}

sub success
{
	my($msg, @files) = @_;
	my %response = ( );
	$response{status} = "success";
	$response{response} = $msg;
	if(@files)
	{
		$response{files} = [@files];
	}
    my $json_text = to_json(\%response);                           
    print $json_text; 		
}





sub read_pdf_storage
{
	my($dir) = @_;
	
	if(-d $dir)
	{
		chdir $dir or &fail("Could not change to the pdf directory. Check the directory structure.");
		
	}else
	{
		#first run - create signature templates
		mkpath($dir) or &fail("Could not create the directory. Check the directory structure.");
		
		chdir $dir or &fail("Could not change to the fonts directory. Check the directory structure.");
	}	

	opendir(DIR, $dir) or &fail("Could not read the pdf directory. Check the directory structure.");
	
	my %pdf_files = ();
	my $pdf_file;
	
	while ($pdf_file = readdir DIR)
	{
		if(-d $pdf_file ne 1)
		{
			$pdf_files{$pdf_file} = "$pdf_file";
		}
	}
	closedir DIR;
	return %pdf_files;
}


my $filesString = $cgi->param("files") || fail("files is missing");
my $pdf_storage = $cgi->param("pdf_storage") || fail("pdf_storage is missing");

my @avaliable_files = ();
my $json = new JSON;
my %files_already_saved = read_pdf_storage($pdf_storage);
my $objJSON = $json->decode($filesString) || fail("Can not decode the json string");

chdir $pdf_storage or &fail("Could not change to the fonts directory. Check the directory structure.");
#use Cwd;

my $mech = WWW::Mechanize->new();

for(@$objJSON)
{
	#print $_->{title}  . '<br>';
	#print $_->{name}  . '<br>';
	#print $_->{web_source_path}  . '<br>';
	#print $_->{read}  . '<br>';
	my $file = $_->{name};
	#print $file . " - ";
	#print $files_already_saved{$file}
	
	if( exists( $files_already_saved{$file} ) )
	{
		#print $file;
		#print ' found it ' . $files_already_saved{$file} . ' - ' . $file;
		push( @avaliable_files , $_ );
		next;
	}
	#print getcwd . '<br>';
	
	#print $_->{web_source_path};
	$mech->get( $_->{web_source_path} ) || die "could not download";
	
	#print $mech->success;
	
	if ($mech->success)
    {
		my $filename = $pdf_storage. '/' . $_->{name};
		$mech->save_content ( $filename );
		push( @avaliable_files , $_ );
		#print $filename . "    File Downloaded!\n\n";
		
    }
    else
    {
		#print "\nDownload Failed!\n";
    }
	
	
	#my $status = getstore($_->{web_source_path}, $_->{name}) || fail("could not download");
	
	#print $_->{web_source_path}, $_->{name} . '<br>';
	 
	#if ( is_success($status) )
	#{
	#  print "file downloaded correctly\n";
	#  push( @avaliable_files , $_ );
	#}
	#else
	#{
	#  print "error downloading file: $status\n";
	#}
}

my %response = ( );
$response{status} = "success";
$response{response} = "Files processed";
if(@avaliable_files)
{
	$response{files} = [@avaliable_files];
}
my $json_text = to_json(\%response);                           
print $json_text;
