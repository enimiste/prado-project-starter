<?php

using( 'System.Data.ActiveRecord.TActiveRecord' );

/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 01/09/2016
 * Time: 01:44
 */
abstract class NActiveRecord extends TActiveRecord {

	public $created_at;
	public $created_by;
	public $updated_at;
	public $updated_by;

	/**
	 * @param TActiveRecordChangeEventParameter $param
	 */
	public function onInsert( $param ) {
		parent::onInsert( $param );

		$this->created_at = mysql_timestamp( time() );
		$this->created_by = user()->getName();
	}

	/**
	 * @param TActiveRecordChangeEventParameter $param
	 */
	public function onUpdate( $param ) {
		parent::onUpdate( $param );

		if ( $this->canHandleOnUpdate() ) {
			$this->updated_at = mysql_timestamp( time() );
			$this->updated_by = user()->getName();
		}
	}

	/**
	 * @return bool
	 */
	protected abstract function canHandleOnUpdate();
}