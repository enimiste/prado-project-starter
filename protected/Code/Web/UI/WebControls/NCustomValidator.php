<?php

using( 'System.Web.UI.WebControls.TCustomValidator' );

/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 05/09/2016
 * Time: 03:11
 */
class NCustomValidator extends TCustomValidator {

	/**
	 * This method is invoked when the server side validation happens.
	 * It will raise the <b>OnServerValidate</b> event.
	 * The method also allows derived classes to handle the event without attaching a delegate.
	 * <b>Note</b> The derived classes should call parent implementation
	 * to ensure the <b>OnServerValidate</b> event is raised.
	 *
	 * @param string the value to be validated
	 *
	 * @return boolean whether the value is valid
	 */
	public function onServerValidate( $value ) {
		$param = new NServerValidateEventParameter( $value, true, $this->Var );
		$this->raiseEvent( 'OnServerValidate', $this, $param );

		return $param->getIsValid();
	}

	/**
	 * @return mixed
	 */
	public function getVar() {
		return $this->getViewState( 'var', null );
	}

	/**
	 * @param mixed $value
	 */
	public function setVar( $value ) {
		$this->setViewState( 'var', $value );
	}
}

/**
 * NServerValidateEventParameter class
 *
 * NServerValidateEventParameter encapsulates the parameter data for
 * <b>OnServerValidate</b> event of TCustomValidator components.
 *
 * @author  NOUNI EL BACHIR <nouni.elbachir@gmail.com>
 * @package App.Code.Web.UI.WebControls
 */
class NServerValidateEventParameter extends TServerValidateEventParameter {
	/** @var  mixed */
	protected $var;

	/**
	 * NServerValidateEventParameter constructor.
	 *
	 * @param $value
	 * @param $isValid
	 */
	public function __construct( $value, $isValid, $var ) {
		parent::__construct( $value, $isValid );

		$this->var = $var;
	}

	/**
	 * @return mixed
	 */
	public function getVar() {
		return $this->var;
	}

	/**
	 * @param mixed $var
	 */
	public function setVar( $var ) {
		$this->var = $var;
	}
}