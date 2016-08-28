<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 25/08/2016
 * Time: 17:38
 */

namespace App\Prado;


abstract class MasterPage extends TemplateControl {


	/**
	 * @return array of array ['danger'=>[],'info'=>[],'success'=>[],'warning'=>[]]
	 */
	public function getFlashMessages() {
		if ( ! session()->contains( '_flash' ) ) {
			return [ ];
		}
		$flashs             = session()['_flash'];
		session()['_flash'] = [ ];

		return $flashs;
	}

	/**
	 * @return bool
	 */
	public function getHasFlashMessages() {
		return ! empty( session()['_flash'] );
	}
}