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
use Socket;

#parametre du serveur a configurer et tester
my $SERVER1 ;
my $USER1 ;
my $PASSWORD1 ;

GetOptions(
        "s=s"     => \$SERVER1,
        "u=s"     => \$USER1,
        "p=s"     => \$PASSWORD1,
);

$ENV{PERL_LWP_SSL_VERIFY_HOSTNAME}=0;

#creation de session pour le boitier
my $session1 = Infoblox::Session->new("master" => $SERVER1, "username" => $USER1, "password" => $PASSWORD1);

    #recuperation des status d'ouverture de la session	
    my $result1 = $session1->status_code();
    my $response1 = $session1->status_detail();

	my $res = "$response1 ($result1)"; 
	
	#si le code est 1009, la creation de la session a echoué car le serveur est passif
	if($result1 == 1009)
	{	
		#on recupere l'adresse IP dans le detail du status 
		if( $res =~ m/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/ ){
		print "CHANGE\n";
		my $iaddr = inet_aton($1);
		my $name  = gethostbyaddr($iaddr, AF_INET); #resolution dns inverse
		print "$name \n";
		}
	}
	#creation de session reussie
	elsif($result1==0)
	{
		print "OK \n";
	}
	#echec de creation de session
	else
	{
		print "PB \n";
	}

exit;
