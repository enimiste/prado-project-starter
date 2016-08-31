<?php

using( 'System.Exceptions.TErrorHandler' );
using( 'App.Code.Exception.NAppException' );

/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 24/08/2016
 * Time: 16:09
 */
class NAppErrorHandler extends TErrorHandler {
	/**
	 * @param $statusCode
	 * @param $exception
	 *
	 * @return string
	 */
	protected function getErrorTemplate( $statusCode, $exception ) {
		if ( $exception instanceof NAppException ) {
			return <<<EOF
			<html><head><title>App error</title></head><body>
			<div style="color: red;"> %%ErrorMessage%% </div>
			</body></html>
EOF;
		}

		return parent::getErrorTemplate( $statusCode, $exception );

	}


	/**
	 * @param $statusCode
	 * @param $exception
	 */
	protected function handleExternalError( $statusCode, $exception ) {
		if ( $exception instanceof NAppException ) {
			plog( $exception->getErrorMessage(), \TLogger::ERROR, 'NAppException' );
		}
		parent::handleExternalError( $statusCode, $exception );
	}

}