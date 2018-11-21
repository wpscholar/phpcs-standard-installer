<?php

namespace wpscholar\Composer;

use Pimple\Container;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Class PHPCodeSnifferConfig
 *
 * @package wpscholar\Composer
 */
class PHPCodeSnifferConfig {

	/**
	 * PHPCodeSnifferConfig constructor.
	 *
	 * @param Container $container
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
		$processBuilder = new ProcessBuilder();
		$processBuilder->setPrefix( $container['composer']->getConfig()->get( 'bin-dir' ) . DIRECTORY_SEPARATOR . 'phpcs' );
		$container['processBuilder'] = $processBuilder;
	}

	/**
	 * Parse and set extra PHP Code Sniffer config.
	 */
	public function parseExtraConfig() {
		$name = 'phpcs-config';
		$extra = $this->container['composer']->getPackage()->getExtra();
		if ( isset( $extra[ $name ] ) && is_array( $extra[ $name ] ) ) {
			foreach ( $extra[ $name ] as $key => $value ) {
				$this->configSet( $key, $value );
			}
		}
	}

	/**
	 * Get a config value.
	 *
	 * @param string $key
	 *
	 * @return string|null
	 */
	public function configGet( $key ) {
		$value = null;
		try {
			$output = $this->container['processBuilder']
				->setArguments( array( '--config-show' ) )
				->getProcess()
				->mustRun()
				->getOutput();
			$lines = array_filter( explode( PHP_EOL, $output ) );
			foreach ( $lines as $line ) {
				if ( 0 === strpos( $line, $key ) ) {
					$value = trim( str_replace( "{$key}:", '', $line ) );
					break;
				}
			}
		} catch ( ProcessFailedException $exception ) {
			$this->container['io']->writeError( $exception->getMessage() );
		}

		return $value;
	}

	/**
	 * Set a config value.
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function configSet( $key, $value ) {
		try {
			$this->container['processBuilder']
				->setArguments( array( '--config-set', $key, $value ) )
				->getProcess()
				->mustRun()
				->getOutput();
			$this->container['io']->write( sprintf( 'PHP Code Sniffer config <info>%s</info> <comment>set to</comment> <info>%s</info>', $key, $value ) );
		} catch ( ProcessFailedException $exception ) {
			$this->container['io']->writeError( $exception->getMessage() );
		}
	}

}
