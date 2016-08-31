<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 28/08/2016
 * Time: 03:18
 */

/**
 * How to use this trait :
 * - Add it to the page class that manage a datagrid
 * - Implements a OnSortCommand command handler
 * - Call buildSortExp function to get the right sortExpression and the direction of sort to
 * use in yout queries.
 *
 * Class DataGridBidirectionalSortTrait
 * @author  Nouni EL bachir <nouni.elbachir@gmail.com>
 * @package App.Code.Web.UI
 */
trait NDataGridBidirectionalSortTrait {

	/**
	 * direction : desc or asc
	 * @return array formatted as [column_name,direction], can be an empty array
	 */
	public function getLastSortExpression() {
		$last = $this->getViewState( 'last_sort_exp', '' );
		if ( ! empty( $last ) ) {
			return explode( ',', $last );
		} else {
			return [ ];
		}
	}

	/**
	 * @param array $value [col_name, direction]
	 */
	public function setLastSortExpression( array $value ) {
		$this->setViewState( 'last_sort_exp', implode( ',', $value ) );
	}

	/**
	 * Direction : desc or asc
	 *
	 * @param TDataGridSortCommandEventParameter $param
	 *
	 * @return array [sortExp, dir]
	 */
	protected function buildSortExp( $param = null ) {
		if ( $param === null ) {
			return [ ];
		}
		$exp  = $param->getSortExpression();
		$dir  = 'asc';
		$last = $this->getLastSortExpression();
		if ( ! empty( $last ) ) {
			$col = $last[0];
			$dir = $last[1];
			//if is the same column
			if ( $col == $exp ) {
				//inverse the order
				$dir = ( $dir == 'desc' ) ? 'asc' : 'desc';
			}
		}
		$this->setLastSortExpression( [ $exp, $dir ] );

		return [ $exp, $dir ];
	}
}