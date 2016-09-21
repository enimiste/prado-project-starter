<?php

/**
 * Class NParentSubChildTrait
 *
 * - pid : record id
 * - parent_pid : parent record id
 * - order : element order integer
 * - You should add foreign key to parent_pid column and add this code to your ActiveRecord class
 * <code>
 * public static $RELATIONS = array(
 * 'parent' => [ self::BELONGS_TO, 'YourActiveRecord', 'parent_pid' ],
 * 'childs' => [ self::HAS_MANY, 'YourActiveRecord', 'parent_pid' ],
 * );
 * </code>
 *
 * @property array               childs
 * @property mixed|TActiveRecord parent
 *
 * @method TActiveRecord ::finder()
 * @method TDbConnection ::getActiveDbConnection()
 * @method bool save() Save the model into database
 */
trait NParentSubChildTrait {

	public $pid;
	public $order;
	public $parent_pid = null;

	/**
	 * Return false if this element is a root element
	 *
	 * @return bool
	 */
	public function getIsChild() {
		return $this->parent_pid !== null;
	}

	/**
	 * @param int    $except pid of the element to exclude
	 *
	 * @param string $select
	 *
	 * @return array
	 */
	public static function parentPages( $except = null, $select = '*' ) {
		$criteria                    = new TActiveRecordCriteria();
		$criteria->OrdersBy['order'] = 'asc';
		$criteria->Select            = $select;

		if ( ! is_null( $except ) ) {
			$criteria->Condition          = ' pid <> :pid AND parent_pid IS NULL';
			$criteria->Parameters[':pid'] = $except;
		} else {
			$criteria->Condition = 'parent_pid IS NULL';
		}

		return self::finder()->findAll( $criteria );
	}

	/**
	 * Returns the last element order
	 * Null if no element exists
	 *
	 * @return int|null
	 */
	public static function getLastOrder() {
		$criteria                    = new TActiveRecordCriteria();
		$criteria->OrdersBy['order'] = 'desc';

		$rec = self::finder()->find( $criteria );
		if ( $rec === null ) {
			$lastOrder = - 1;
		} else {
			$lastOrder = $rec->order;
		}

		return $lastOrder;
	}

	/**
	 * @return TActiveRecord|NParentSubChildTrait
	 */
	public static function finderWithParent() {
		return self::finder()->withParent();
	}

	/**
	 * @return TActiveRecord|NParentSubChildTrait
	 */
	public static function finderWithChilds() {
		return self::finder()->withChilds();
	}

	/**
	 * @return bool
	 */
	public function getHasChilds() {
		return count( $this->childs );
	}

	/**
	 * @return bool
	 */
	public function getIsParent() {
		return $this->getHasChilds();
	}

	/**
	 * Return true if this element has the lower order
	 *
	 * @return bool
	 */
	public function getIsFirstInOrder() {
		$criteria                    = new TActiveRecordCriteria();
		$criteria->OrdersBy['order'] = 'asc';

		$rec = self::finder()->find( $criteria );

		return $rec->pid === $this->pid;
	}

	/**
	 * Return true if this element has the greater order
	 *
	 * @return bool
	 */
	public function getIsLastInOrder() {
		$criteria                    = new TActiveRecordCriteria();
		$criteria->OrdersBy['order'] = 'desc';

		$rec = self::finder()->find( $criteria );

		return $rec->pid === $this->pid;
	}

	/**
	 * @param string $msg
	 *
	 * @return NAppException or sub classes
	 */
	protected static function newException( $msg ) {
		return new NAppException( 400, $msg );
	}

	/**
	 * Re-order the elements to change the order of the given element to the new order
	 *
	 * @param int|TActiveRecord|NParentSubChildTrait $elem_id
	 * @param int                                    $newOrder
	 *
	 * @return TActiveRecord|NParentSubChildTrait
	 * @throws NAppException or subclasses
	 */
	public static function reOrderPage( $elem_id, $newOrder ) {
		if ( $elem_id instanceof TActiveRecord ) {
			$elem_id = $elem_id->pid;
		}
		/** @var NParentSubChildTrait|TActiveRecord $elem */
		$elem = self::finder()->findByPk( $elem_id );
		if ( $elem === null || ! $elem instanceof TActiveRecord ) {
			throw self::newException( 'Element not found' );
		}

		/* Check if another element has the same newOrder */
		/** @var NParentSubChildTrait $elemWithSameOrder */
		$elemWithSameOrder = self::finder()->find( '`order` = :order', [ ':order' => $newOrder ] );

		if ( $elemWithSameOrder === null || ! $elemWithSameOrder instanceof TActiveRecord ) {
			throw self::newException( 'No element was found that had the same order' );
		}

		$con = self::getActiveDbConnection();
		$con->setActive( true );
		$tx = $con->beginTransaction();
		/**
		 * En : normal element
		 * EwcNp : element with child and without parent
		 * EwcAp : element with child and with parent
		 * Ec : Element child
		 */
		try {
			if ( self::isNormalElem( $elem ) && self::isNormalElem( $elemWithSameOrder ) ) {
				self::processCaseEnEn( $elem, $elemWithSameOrder );
			} else if ( self::isNormalElem( $elem ) && self::isElemC( $elemWithSameOrder ) ) {
				$elemWithSameOrder = self::finderWithParent()->findByPk( $elemWithSameOrder->pid );
				self::processCaseEnEc( $elem, $elemWithSameOrder );
			} else if ( self::isNormalElem( $elem ) && self::isElemWcNp( $elemWithSameOrder ) ) {
				$elemWithSameOrder = self::finderWithChilds()->findByPk( $elemWithSameOrder->pid );
				self::processCaseEnEwcNp( $elem, $elemWithSameOrder );
			} else if ( self::isElemC( $elem ) && self::isElemC( $elemWithSameOrder ) ) {
				self::processCaseEcEc( $elem, $elemWithSameOrder );
			} else if ( self::isElemWcNp( $elem ) && self::isNormalElem( $elemWithSameOrder ) ) {
				$elem = self::finderWithChilds()->findByPk( $elem->pid );
				self::processCaseEwcNpEn( $elem, $elemWithSameOrder );
			} else if ( self::isElemWcNp( $elem ) && self::isElemC( $elemWithSameOrder ) ) {
				$elem              = self::finderWithChilds()->findByPk( $elem->pid );
				$elemWithSameOrder = self::finderWithParent()->findByPk( $elemWithSameOrder->pid );
				self::processCaseEwcNpEc( $elem, $elemWithSameOrder );
			} else {
				throw self::newException( 'Operation not supported' );
			}

			$tx->commit();

			return $elem;
		} catch ( \Exception $e ) {
			$tx->rollback();
			throw self::newException( $e->getMessage() );
		}
	}

	/**
	 * @param TActiveRecord|NParentSubChildTrait $elemEn  should implements NOrderableParentRecordInterface
	 * @param TActiveRecord|NParentSubChildTrait $elemEn2 should implements NOrderableParentRecordInterface
	 *
	 * @throws NAppException
	 */
	protected static function processCaseEnEn( $elemEn, $elemEn2 ) {
		$o              = $elemEn2->order;
		$elemEn2->order = $elemEn->order;
		if ( ! $elemEn2->save() ) {
			throw self::newException( 'Error will saving element ' . $elemEn2->pid );
		}
		$elemEn->order = $o;
		if ( ! $elemEn->save() ) {
			throw self::newException( 'Error will saving element ' . $elemEn->pid );
		}
	}

	/**
	 * @param TActiveRecord|NParentSubChildTrait $elemEc  should implements NOrderableParentRecordInterface
	 * @param TActiveRecord|NParentSubChildTrait $elemEc2 should implements NOrderableParentRecordInterface
	 *
	 * @throws NAppException
	 */
	protected static function processCaseEcEc( $elemEc, $elemEc2 ) {
		if ( $elemEc->parent_pid !== $elemEc2->parent_pid ) {
			throw self::newException( 'Operation not supported. Childs should be from the same parent' );
		}
		$o              = $elemEc2->order;
		$elemEc2->order = $elemEc->order;
		if ( ! $elemEc2->save() ) {
			throw self::newException( 'Error will saving element ' . $elemEc2->pid );
		}
		$elemEc->order = $o;
		if ( ! $elemEc->save() ) {
			throw self::newException( 'Error will saving element ' . $elemEc->pid );
		}
	}

	/**
	 * @param TActiveRecord|NParentSubChildTrait $elemEwcNp should implements NOrderableParentRecordInterface
	 * @param TActiveRecord|NParentSubChildTrait $elemEn    should implements NOrderableParentRecordInterface
	 *
	 * @throws NAppException
	 */
	protected static function processCaseEwcNpEn( $elemEwcNp, $elemEn ) {
		self::processCaseEnEwcNp( $elemEn, $elemEwcNp );
	}

	/**
	 * @param TActiveRecord|NParentSubChildTrait $elemEn   should implements NOrderableParentRecordInterface
	 * @param TActiveRecord|NParentSubChildTrait $elemWcNp should implements NOrderableParentRecordInterface
	 *
	 * @throws NAppException
	 */
	protected static function processCaseEnEwcNp( $elemEn, $elemWcNp ) {
		$childs = $elemWcNp->childs;
		$elemWcNp->order -= 1;
		if ( ! $elemWcNp->save() ) {
			throw self::newException( 'Error will saving element ' . $elemWcNp->pid );
		}
		foreach ( $childs as $child ) {
			$child->order -= 1;
			if ( ! $child->save() ) {
				throw self::newException( 'Error will saving child element ' . $child->pid );
			}
		}
		$elemEn->order += count( $childs ) + 1;
		if ( ! $elemEn->save() ) {
			throw self::newException( 'Error will saving element ' . $elemEn->pid );
		}
	}

	/**
	 * @param TActiveRecord|NParentSubChildTrait $elemWcNp should implements NOrderableParentRecordInterface
	 * @param TActiveRecord|NParentSubChildTrait $elemC    should implements NOrderableParentRecordInterface
	 *
	 * @throws NAppException
	 */
	protected static function processCaseEwcNpEc( $elemWcNp, $elemC ) {
		$elemWcNp2 = $elemC->parent;
		if ( $elemWcNp->pid == $elemWcNp2->pid ) {
			throw self::newException( 'You try to order a parent element against its own child' );
		}
		/* This code suppose that $elemWcNo->order > $elemWcNp2->order */
		$elemWcNp->order = $elemWcNp2->order;
		if ( ! $elemWcNp->save() ) {
			throw self::newException( 'Error will saving parent element ' . $elemWcNp->pid );
		}
		$childs = $elemWcNp->childs;
		$i      = $elemWcNp->order + 1;
		foreach ( $childs as $child ) {
			$child->order = $i;
			if ( ! $child->save() ) {
				throw self::newException( 'Error will saving child element ' . $child->pid );
			}
			$i ++;
		}
		$elemWcNp2->order = $i ++;
		if ( ! $elemWcNp2->save() ) {
			throw self::newException( 'Error will saving parent element ' . $elemWcNp2->pid );
		}
		$childs2 = $elemWcNp2->childs;
		foreach ( $childs2 as $child ) {
			$child->order = $i;
			if ( ! $child->save() ) {
				throw self::newException( 'Error will saving child element ' . $child->pid );
			}
			$i ++;
		}
	}

	/**
	 * @param TActiveRecord|NParentSubChildTrait $elemEn should implements NOrderableParentRecordInterface
	 * @param TActiveRecord|NParentSubChildTrait $elemEc should implements NOrderableParentRecordInterface
	 *
	 * @throws NAppException
	 */
	protected static function processCaseEnEc( $elemEn, $elemEc ) {
		$parent        = $elemEc->parent;
		$elemEn->order = $parent->order;
		if ( ! $elemEn->save() ) {
			throw self::newException( 'Error will saving element ' . $elemEn->pid );
		}
		$parent->order += 1;
		if ( ! $parent->save() ) {
			throw self::newException( 'Error will saving parent element ' . $parent->pid );
		}
		$childs = self::finder()->findAll( 'parent_pid = :pid', [ ':pid' => $parent->pid ] );
		foreach ( $childs as $child ) {
			$child->order += 1;
			if ( ! $child->save() ) {
				throw self::newException( 'Error will saving child element ' . $child->pid );
			}
		}

	}

	/**
	 * Normal element : has no parent and no childs
	 *
	 * @param TActiveRecord|NParentSubChildTrait $elem
	 *
	 * @return bool
	 */
	protected static function isNormalElem( $elem ) {
		return $elem->parent_pid === null &&
		       self::finder()->count( 'parent_pid = :parent_pid', [ ':parent_pid' => $elem->pid ] ) <= 0;
	}

	/**
	 * Is element has childs and no parent
	 *
	 * @param TActiveRecord|NParentSubChildTrait $elem
	 *
	 * @return bool
	 */
	protected static function isElemWcNp( $elem ) {
		return $elem->parent_pid === null &&
		       self::finder()->count( 'parent_pid = :parent_pid', [ ':parent_pid' => $elem->pid ] ) > 0;
	}

	/**
	 * Is element has childs and parent
	 *
	 * @param TActiveRecord|NParentSubChildTrait $elem
	 *
	 * @return bool
	 */
	protected static function isElemWcAp( $elem ) {
		return $elem->parent_pid !== null &&
		       self::finder()->count( 'parent_pid = :parent_pid', [ ':parent_pid' => $elem->pid ] ) > 0;
	}

	/**
	 * Is element only a child and not a parent
	 *
	 * @param TActiveRecord|NParentSubChildTrait $elem
	 *
	 * @return bool
	 */
	protected static function isElemC( $elem ) {
		return $elem->parent_pid !== null &&
		       self::finder()->count( 'parent_pid = :parent_pid', [ ':parent_pid' => $elem->pid ] ) <= 0;
	}

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
	public static function reOrderAllElems( $useTx = true ) {
		$criteria                    = new TActiveRecordCriteria();
		$criteria->OrdersBy['order'] = 'asc';
		$criteria->Condition         = 'parent_pid IS NULL';
		$parents                     = self::finderWithChilds()->findAll( $criteria );
		$order                       = 1;
		if ( $useTx ) {
			/** @var TDbConnection $conn */
			$conn = self::getActiveDbConnection();
			$conn->setActive( true );
			$tx = $conn->beginTransaction();
		}
		try {
			/** @var self $parent */
			foreach ( $parents as $parent ) {
				$parent->order = $order ++;
				/** @var self $child */
				foreach ( $parent->childs as $child ) {
					$child->order = $order ++;
					$child->save();
				}
				$parent->save();
			}
			if ( $useTx ) {
				$tx->commit();
			}
		} catch ( \Exception $e ) {
			if ( $useTx ) {
				try {
					$tx->rollback();
				} catch ( TDbException $dbe ) {
					$e = $dbe;
				}
			}
			throw new NPageException( $e->getMessage() );
		}
	}
}
