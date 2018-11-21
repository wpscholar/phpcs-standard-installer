<?php

namespace wpscholar\Composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

use Pimple\Container;

/**
 * Class PHPCodeSnifferStandardInstaller
 *
 * @package wpscholar\Composer
 */
class PHPCodeSnifferStandardInstaller extends LibraryInstaller {

	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * Inject the dependency injection container.
	 *
	 * @param Container $container
	 *
	 * @return void
	 */
	public function injectContainer( Container $container ) {
		$this->container = $container;
	}

	/**
	 * Install package.
	 *
	 * @param InstalledRepositoryInterface $repo
	 * @param PackageInterface $package
	 *
	 * @return void
	 */
	public function install( InstalledRepositoryInterface $repo, PackageInterface $package ) {
		parent::install( $repo, $package );
		$this->registerStandard( $package );
	}

	/**
	 * Update package.
	 *
	 * @param InstalledRepositoryInterface $repo
	 * @param PackageInterface $initial
	 * @param PackageInterface $target
	 *
	 * @return void
	 */
	public function update( InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target ) {
		parent::update( $repo, $initial, $target );
		$this->registerStandard( $initial );
	}

	/**
	 * Register a PHP Code Sniffer standard.
	 *
	 * @param PackageInterface $package
	 *
	 * @return void
	 */
	protected function registerStandard( PackageInterface $package ) {
		$config = $this->container['config'];
		$installedPaths = array_filter( explode( ',', $config->configGet( 'installed_paths' ) ) );
		$installedPaths[] = '../../' . $package->getName();
		$config->configSet( 'installed_paths', implode( ',', array_unique( $installedPaths ) ) );
	}

}
