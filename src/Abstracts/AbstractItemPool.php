<?php
/**
 * This file is part of the O2System PHP Framework package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author         Steeve Andrian Salim
 * @copyright      Copyright (c) Steeve Andrian Salim
 */
// ------------------------------------------------------------------------

namespace O2System\Cache\Abstracts;

// ------------------------------------------------------------------------

use O2System\Psr\Cache\CacheItemInterface;
use O2System\Psr\Cache\CacheItemPoolInterface;
use O2System\Spl\Exceptions\Logic\InvalidArgumentException;

/**
 * Class AbstractItemPool
 *
 * @package O2System\Cache\Abstracts
 */
abstract class AbstractItemPool implements CacheItemPoolInterface
{
    /**
     * Deferred Items Storage
     *
     * @var array
     */
    private $storage = [];

    // ------------------------------------------------------------------------

    /**
     * CacheItemPoolInterface::deleteItems
     *
     * Removes multiple items from the pool.
     *
     * @param string[] $keys
     *   An array of keys that should be removed from the pool.
     *
     * @return bool
     * @throws \O2System\Spl\Exceptions\Logic\InvalidArgumentException
     * @throws \O2System\Psr\Cache\InvalidArgumentException
     */
    public function deleteItems( array $keys )
    {
        if ( ! is_array( $keys ) ) {
            throw new InvalidArgumentException( 'CACHE_E_INVALID_ARGUMENT_ARRAY' );
        }

        foreach ( $keys as $key ) {
            if ( $this->deleteItem( $key ) === false ) {
                return false;
                break;
            }
        }

        return true;
    }

    // ------------------------------------------------------------------------

    /**
     * CacheItemPoolInterface::saveDeferred
     *
     * Sets a cache item to be persisted later.
     *
     * @param CacheItemInterface $item
     *   The cache item to save.
     *
     * @return bool
     *   False if the item could not be queued or if a commit was attempted and failed. True otherwise.
     */
    public function saveDeferred( CacheItemInterface $item )
    {
        $this->storage[] = $item;
    }

    // ------------------------------------------------------------------------

    /**
     * CacheItemPoolInterface::commit
     *
     * Persists any deferred cache items.
     *
     * @return bool
     *   True if all not-yet-saved items were successfully saved or there were none. False otherwise.
     */
    public function commit()
    {
        $storage = $this->storage;

        foreach ( $storage as $key => $item ) {
            if ( $this->save( $item ) === true ) {
                unset( $storage[ $key ] );
            }
        }

        if ( count( $storage ) == 0 ) {
            $this->storage = [];

            return true;
        }

        return false;
    }
}