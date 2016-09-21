<?php

/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 06/09/2016
 * Time: 23:09
 */
interface NOrderableParentRecordInterface {

	/**
	 * Return false if this element is a root page
	 *
	 * @return bool
	 */
	function getIsChild();

	/**
	 * @param int    $except pid of the element to exclude
	 *
	 * @param string $select
	 *
	 * @return array
	 */
	static function parentPages( $except = null, $select = '*' );

	/**
	 * Returns the last element order
	 * Null if no page exists
	 *
	 * @return int|null
	 */
	static function getLastOrder();

	/**
	 * Re-order the elements to change the order of the given element to the new order
	 *
	 * @param int|TActiveRecord $pid
	 * @param int               $newOrder
	 *
	 * @return bool
	 * @throws NPageException
	 * @throws TActiveRecordException
	 */
	static function reOrderPage( $pid, $newOrder );

	/**
	 * @return TActiveRecord
	 */
	static function finderWithParent();

	/**
	 * @return TActiveRecord
	 */
	static function finderWithChilds();

	/**
	 * @return bool
	 */
	function getHasChilds();

	/**
	 * Return true if this element has the lower order
	 *
	 * @return bool
	 */
	function getIsFirstInOrder();

	/**
	 * Return true if this element has the greater order
	 *
	 * @return bool
	 */
	function getIsLastInOrder();
	
	/**
	 * Re-order all elements.
	 *
	 * Rules :
	 * - Sub elements will follow there parent on order.
	 * - Next parent element gets the last element last child order + 1.
	 *
	 * @param bool $useTx if false this method will not run into a DB transaction
	 *
	 * @throws NPageException
	 */
	static function reOrderAllElems( $useTx = true );
}
