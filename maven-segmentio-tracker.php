<?php

/*
  Plugin Name: Maven Segment.io Tracker
  Plugin URI:
  Description:
  Author: Site Mavens
  Version: 0.1
  Author URI:
 */


namespace MavenSegmentIoTracker;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

//WE need to load the library
require_once __DIR__ . '/Analytics.php';

use Maven\Settings\OptionType,
	Maven\Settings\Option;

class Tracker extends \Maven\Tracking\BaseTracker {

	public function __construct( $args = array() ) {
		parent::__construct( 'Segment.io' );
		
		$segmentIoKey = "";
		if ( $args && isset( $args[ 'key' ] ) ) {
			$segmentIoKey = $args[ 'key' ];
		}
		
		$defaultOptions = array(
			new Option(
					"segmentIoKey", "Segment.io Key", $segmentIoKey, '', OptionType::Input
			)
		);

		
		$this->addSettings( $defaultOptions );
		
  //, array("debug" => true,"error_handler" => function ($code, $msg) { var_dump($code); var_dump($msg);die();})
			
	}

	public function addTransaction( \Maven\Tracking\ECommerceTransaction $transaction ) {

		return false;
	}

	public function addEvent( \Maven\Tracking\Event $event ) {

		\Analytics::init( $this->getSetting( 'segmentIoKey') );
		
		$props = $event->getProperties();
		
		
		\Analytics::track( "", $event->getLabel(), $props );
	}

}

$tracker = new Tracker();
\Maven\Core\HookManager::instance()->addFilter('maven/trackers/register', array($tracker,'register'));
