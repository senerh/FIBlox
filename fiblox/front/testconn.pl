#!/usr/bin/perl
use FindBin qw($Bin $Script);
my ($BASE,$NAME)=($Bin,$Script) ;

BEGIN
{
	push (@INC, "lib");
	push (@INC, "$FindBin::Bin");
	push (@INC, "$FindBin::Bin/lib");
};

# unbuffer output
$| = 1 ;

use Infoblox ;
use Getopt::Long;
use strict;

my $SERVER ;
my $USER ;
my $PASSWORD ;

GetOptions(
	"s=s"     => \$SERVER,
	"u=s"     => \$USER,
	"p=s"     => \$PASSWORD,
);

$ENV{PERL_LWP_SSL_VERIFY_HOSTNAME}=0;

my $session = Infoblox::Session->new("master" => $SERVER, "username" => $USER, "password" => $PASSWORD);

if ($session->status_code() eq 0)
{
	print "OK";
}
else
{
	print "PB";
}
exit;
