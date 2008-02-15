<?php

require_once('_include.php');

require_once('SimpleSAML/Utilities.php');
require_once('SimpleSAML/Session.php');
require_once('SimpleSAML/XHTML/Template.php');
require_once('SimpleSAML/Metadata/MetaDataStorageHandler.php');


/* Load simpleSAMLphp, configuration */
$config = SimpleSAML_Configuration::getInstance();
$session = SimpleSAML_Session::getInstance(true);

/* Check if valid local session exists.. */
if ($config->getValue('admin.protectindexpage', false)) {
	if (!isset($session) || !$session->isValid('login-admin') ) {
		SimpleSAML_Utilities::redirect('/' . $config->getValue('baseurlpath') . 'auth/login-admin.php',
			array('RelayState' => SimpleSAML_Utilities::selfURL())
		);
	}
}

$warnings = array();

if (SimpleSAML_Utilities::getSelfProtocol() != 'https') {
	$warnings[] = '<strong>You are not using HTTPS</strong> - encrypted communication with the user. Using simpleSAMLphp will works perfectly fine on HTTP for test purposes, but if you will be using simpleSAMLphp in a production environment, you should be running it on HTTPS. [ <a href="http://rnd.feide.no/content/simplesamlphp-maintenance-and-configuration">read more about simpleSAMLphp maintenance</a> ]';
}

	
$links = array();


if ($config->getValue('enable.saml20-sp') === true)
	$links[] = array(
		'href' => 'example-simple/saml2-example.php', 
		'text' => 'SAML 2.0 SP example - test logging in through your IdP');

if ($config->getValue('enable.shib13-sp') === true)
	$links[] = array(
		'href' => 'example-simple/shib13-example.php', 
		'text' => 'Shibboleth 1.3 SP example - test logging in through your Shib IdP');

if ($config->getValue('enable.openid-provider') === true)
	$links[] = array(
		'href' => 'openid/provider/server.php', 
		'text' => 'OpenID Provider site - Alpha version (test code)');

$links[] = array(
	'href' => 'example-simple/hostnames.php', 
	'text' => 'Diagnostics on hostname, port and protocol');



$linksmeta = array();

$linksmeta[] = array(
	'href' => 'admin/metadata.php', 
	'text' => 'Meta data overview for your installation. Diagnose your meta data files.');

if ($config->getValue('enable.saml20-sp') === true)
	$linksmeta[] = array(
		'href' => 'saml2/sp/metadata.php', 
		'text' => 'Hosted SAML 2.0 Service Provider Metadata (automatically generated)');

if ($config->getValue('enable.saml20-idp') === true)
	$linksmeta[] = array(
		'href' => 'saml2/idp/metadata.php', 
		'text' => 'Hosted SAML 2.0 Identity Provider Metadata (automatically generated)');

if ($config->getValue('enable.shib13-sp') === true)
	$linksmeta[] = array(
		'href' => 'shib13/sp/metadata.php', 
		'text' => 'Hosted Shibboleth 1.3 Service Provider Metadata (automatically generated)');

if ($config->getValue('enable.shib13-idp') === true)
	$linksmeta[] = array(
		'href' => 'shib13/idp/metadata.php', 
		'text' => 'Hosted Shibboleth 1.3 Identity Provider Metadata (automatically generated)');
	




$linksdoc = array();

$linksdoc[] = array(
	'href' => 'http://rnd.feide.no/content/installing-simplesamlphp', 
	'text' => 'Installing simpleSAMLphp');

if ($config->getValue('enable.saml20-sp', false ) || $config->getValue('enable.shib13-sp', false))
	$linksdoc[] = array(
		'href' => 'http://rnd.feide.no/content/using-simplesamlphp-service-provider', 
		'text' => 'Using simpleSAMLphp as a Service Provider');

if ($config->getValue('enable.saml20-idp', false ) || $config->getValue('enable.shib13-idp', false))
	$linksdoc[] = array(
		'href' => 'http://rnd.feide.no/content/using-simplesamlphp-identity-provider', 
		'text' => 'Using simpleSAMLphp as an Identity Provider');

if ($config->getValue('enable.shib13-idp', false))
	$linksdoc[] = array(
		'href' => 'http://rnd.feide.no/content/configure-shibboleth-13-sp-work-simplesamlphp-idp', 
		'text' => 'Configure Shibboleth 1.3 SP to work with simpleSAMLphp IdP');

if ($config->getValue('enable.saml20-idp', false ))
	$linksdoc[] = array(
		'href' => 'http://rnd.feide.no/content/simplesamlphp-idp-google-apps-education', 
		'text' => 'simpleSAMLphp as an IdP for Google Apps for Education');

$linksdoc[] = array(
	'href' => 'http://rnd.feide.no/content/simplesamlphp-advanced-features', 
	'text' => 'simpleSAMLphp Advanced Features
');



$linksdoc[] = array(
	'href' => 'http://rnd.feide.no/content/simplesamlphp-maintenance-and-configuration', 
	'text' => 'simpleSAMLphp Maintenance and Configuration');

$enablematrix = array(
	'saml20-sp' => $config->getValue('enable.saml20-sp', false),
	'saml20-idp' => $config->getValue('enable.saml20-idp', false),
	'shib13-sp' => $config->getValue('enable.shib13-sp', false),
	'shib13-idp' => $config->getValue('enable.shib13-idp', false),
);


$t = new SimpleSAML_XHTML_Template($config, 'frontpage.php');
$t->data['header'] = 'simpleSAMLphp installation page';
$t->data['icon'] = 'compass_l.png';
$t->data['warnings'] = $warnings;
$t->data['links'] = $links;
$t->data['links_meta'] = $linksmeta;
$t->data['links_doc'] = $linksdoc;
$t->data['enablematrix'] = $enablematrix;

$t->show();



?>