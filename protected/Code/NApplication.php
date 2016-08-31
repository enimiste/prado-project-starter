<?php
using( 'System.TApplication' );

/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 25/08/2016
 * Time: 15:03
 */
class NApplication extends TApplication {
	public function __construct( $basePath = 'protected', $cacheConfig = true, $configType = self::CONFIG_TYPE_XML ) {
		parent::__construct( $basePath, $cacheConfig, $configType );

		Prado::setPathOfAlias( 'App', $this->getBasePath() );
	}

}