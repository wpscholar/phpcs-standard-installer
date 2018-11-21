<?php

namespace wpscholar\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Pimple\Container;

/**
 * Class PHPCodeSnifferStandardInstallerPlugin
 *
 * @package wpscholar\Composer
 */
class PHPCodeSnifferStandardInstallerPlugin implements PluginInterface, EventSubscriberInterface {

	/**
	 * The supported package type.
	 *
	 * @var string
	 */
	const PACKAGE_TYPE = 'phpcs-standard';

	/**
	 * A Pimple dependency injection Container instance.
	 *
	 * @var Container
	 */
	public static $container;

	/**
	 * Apply plugin modifications to Composer.
	 *
	 * @param Composer $composer
	 * @param IOInterface $io
	 *
	 * return void
	 */
	public function activate( Composer $composer, IOInterface $io ) {

		$container = new Container( [
			'composer' => $composer,
			'io'       => $io,
		] );

		$config = new PHPCodeSnifferConfig( $container );
		$container['config'] = $config;

		$installer = new PHPCodeSnifferStandardInstaller( $io, $composer, self::PACKAGE_TYPE );
		$installer->injectContainer( $container );

		$composer->getInstallationManager()->addInstaller( $installer );

		self::$container = $container;
	}

	/**
	 * Subscribe to events.
	 *
	 * @return array
	 */
	public static function getSubscribedEvents() {
		return array(
			'post-install-cmd' => 'setup',
			'post-update-cmd'  => 'setup',
		);
	}

	/**
	 * Setup PHP Code Sniffer config.
	 *
	 * @return void
	 */
	public static function setup() {
		self::$container['config']->parseExtraConfig();
	}

}
