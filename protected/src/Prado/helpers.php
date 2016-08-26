<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 25/08/2016
 * Time: 14:07
 */

use App\Exception\AppException;

if ( ! function_exists( 'app' ) ) {

	/**
	 * @return TApplication
	 */
	function app() {
		return Prado::getApplication();
	}
}

if ( ! function_exists( 'request' ) ) {
	/**
	 * @return THttpRequest
	 */
	function request() {
		return app()->getRequest();
	}
}

if ( ! function_exists( 'module' ) ) {
	/**
	 * @param string $id
	 *
	 * @return IModule
	 */
	function module( $id ) {
		return app()->getModule( $id );
	}
}

if ( ! function_exists( 'response' ) ) {
	/**
	 * @return THttpResponse
	 */
	function response() {
		return app()->getResponse();
	}
}

if ( ! function_exists( 'redirect_page' ) ) {
	/**
	 * @param string $page
	 * @param array  $param
	 */
	function redirect_page( $page, array $param = [ ] ) {
		return redirect_url( page_url( $page, $param ) );
	}
}

if ( ! function_exists( 'redirect_url' ) ) {
	/**
	 * @param string $url
	 */
	function redirect_url( $url ) {
		return response()->redirect( $url );
	}
}
if ( ! function_exists( 'running_service' ) ) {
	/**
	 * @return IService
	 */
	function running_service() {
		return app()->getService();
	}
}

if ( ! function_exists( 'page_service' ) ) {
	/**
	 * @return TPageService
	 * @throws AppException
	 */
	function page_service() {
		if ( app()->getPageServiceID() == running_service()->getID() ) {
			return running_service();
		} else {
			throw new AppException( 500, 'Page Service not found' );
		}
	}
}

if ( ! function_exists( 'page_url' ) ) {
	/**
	 * @param string $page
	 * @param array  $param
	 *
	 * @return string
	 */
	function page_url( $page, array $param = [ ] ) {
		return page_service()->constructUrl( $page, $param );
	}
}

if ( ! function_exists( 'input' ) ) {

	/**
	 * @param string $name
	 * @param string $default
	 *
	 * @return string
	 */
	function input( $name, $default = null ) {

		if ( request()->contains( $name ) ) {
			return request()->itemAt( $name );
		} else {
			return $default;
		}
	}
}

if ( ! function_exists( 'is_guest' ) ) {

	/**
	 * @return bool
	 */
	function is_guest() {
		return user()->getIsGuest();
	}
}

if ( ! function_exists( 'is_admin' ) ) {

	/**
	 * @return bool
	 */
	function is_admin() {
		return user()->getIsAdmin();
	}
}

if ( ! function_exists( 'is_super_admin' ) ) {

	/**
	 * @return bool
	 */
	function is_super_admin() {
		return user()->getIsSuperAdmin();
	}
}

if ( ! function_exists( 'user' ) ) {

	/**
	 * @return IUser
	 */
	function user() {
		return app()->getUser();
	}
}

if ( ! function_exists( 'session' ) ) {

	/**
	 * @return THttpSession
	 */
	function session() {
		return app()->getSession();
	}
}

if ( ! function_exists( 'localize' ) ) {

	/**
	 * @param string $text
	 * @param array  $parameters
	 * @param string $catalogue
	 * @param string $charset
	 *
	 * @return string
	 */
	function localize( $text, $parameters = array(), $catalogue = null, $charset = null ) {
		return Prado::localize( $text, $parameters, $catalogue, $charset );
	}
}

if ( ! function_exists( 'plog' ) ) {

	/**
	 * @param string $msg
	 * @param int    $level
	 * @param string $category
	 * @param string $ctl
	 */
	function plog( $msg, $level = TLogger::INFO, $category = 'Uncategorized', $ctl = null ) {
		Prado::log( $msg, $level, $category, $ctl );
	}
}

if ( ! function_exists( 'ptrace' ) ) {

	/**
	 * @param string $msg
	 * @param string $category
	 * @param string $ctl
	 */
	function ptrace( $msg, $category = 'Uncategorized', $ctl = null ) {
		Prado::trace( $msg, $category, $ctl );
	}
}

if ( ! function_exists( 'page' ) ) {

	/**
	 * @return TPage
	 * @throws THttpException
	 */
	function page() {
		return page_service()->getRequestedPage();
	}
}

if ( ! function_exists( 'param' ) ) {

	/**
	 * @param string $id
	 * @param string $default
	 *
	 * @return string
	 */
	function param( $id, $default = null ) {
		if ( app()->getParameters()->contains( $id ) ) {
			return app()->getParameters()->itemAt( $id );
		} else {
			return $default;
		}
	}
}

if ( ! function_exists( 'auth' ) ) {

	/**
	 * @return TAuthManager
	 */
	function auth() {
		return module( 'auth' );
	}
}

if ( ! function_exists( 'db_conn' ) ) {

	/**
	 * @param string $conn connection name in the database.xml config
	 *
	 * @return TDbConnection
	 * @throws AppException
	 */
	function db_conn( $conn ) {
		$db_conn = module( $conn );
		if ( ! $db_conn instanceof TDbConnection ) {
			throw new AppException( 500, 'Database Connection not found' );
		}

		return $db_conn;
	}
}

if ( ! function_exists( 'site_url' ) ) {

	/**
	 * @return string
	 */
	function site_url( $uri ) {
		return rtrim( param( 'base_url', request()->getApplicationUrl() ), '/' ) . '/' . $uri;
	}
}