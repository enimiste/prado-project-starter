<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 28/08/2016
 * Time: 14:14
 */

namespace App\Prado;


trait PageCustomErrorMessagesTrait {

	/**
	 * @return bool
	 */
	public function getHasErrors() {
		return page()->HasErrors;
	}

	/**
	 * @return array
	 */
	public function getErrors() {
		return page()->Errors;
	}

	/**
	 * @return bool
	 */
	public function getHasSuccess() {
		return page()->HasSuccess;
	}

	/**
	 * @return array
	 */
	public function getSuccess() {
		return page()->Success;
	}

	/**
	 * @return bool
	 */
	public function getHasInfos() {
		return page()->HasInfos;
	}

	/**
	 * @return array
	 */
	public function getInfos() {
		return page()->Infos;
	}

	/**
	 * @return bool
	 */
	public function getHasWarnings() {
		return page()->HasWarnings;
	}

	/**
	 * @return array
	 */
	public function getWarnings() {
		return page()->Warnings;
	}
}