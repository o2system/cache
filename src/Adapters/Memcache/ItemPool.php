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

namespace O2System\Cache\Adapters\Memcache;

// ------------------------------------------------------------------------

use O2System\Cache\Item;
use O2System\Kernel\Spl\Exceptions\Logic\InvalidArgumentException;
use O2System\Psr\Cache\CacheItemInterface;
use O2System\Psr\Cache\CacheItemPoolInterface;

/**
 * Class ItemPool
 *
 * @package O2System\Cache\Adapters\File
 */
class ItemPool extends Adapter implements CacheItemPoolInterface
{
    /**
     * ItemPool::getItems
     *
     * Returns a traversable set of cache items.
     *
     * @param string[] $keys
     *   An indexed array of keys of items to retrieve.
     *
     * @throws InvalidArgumentException
     *   If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return array|\Traversable
     *   A traversable collection of Cache Items keyed by the cache keys of
     *   each item. A Cache item will be returned for each key, even if that
     *   key is not found. However, if no keys are specified then an empty
     *   traversable MUST be returned instead.
     */
    public function getItems ( array $keys = [ ] )
    {
        if ( ! is_array( $keys ) ) {
            throw new InvalidArgumentException( 'E_HEADER_INVALIDARGUMENTEXCEPTION' );
        }

        $items = [ ];

        if ( empty( $keys ) ) {
            $allItems = $this->memcache->getExtendedStats( 'items' );

            foreach ( $allItems as $server => $metadata ) {
                foreach ( $metadata[ 'items' ] AS $itemKey => $itemMetadata ) {
                    $cacheDump = $this->memcache->getExtendedStats( 'cachedump', (int) $itemKey );

                    foreach ( $cacheDump[ $server ] AS $cacheDumpItemKey => $cacheDumpItemMetadata ) {
                        $items[] = $this->getItem( str_replace( $this->prefixKey, '', $cacheDumpItemKey ) );
                    }
                }
            }
        } elseif ( count( $keys ) ) {
            foreach ( $keys as $key ) {
                $items[] = $this->getItem( $key );
            }
        }

        return $items;
    }

    // ------------------------------------------------------------------------

    /**
     * ItemPool::getKey
     *
     * Returns a Cache Item representing the specified key.
     *
     * This method must always return a CacheItemInterface object, even in case of
     * a cache miss. It MUST NOT return null.
     *
     * @param string $key
     *   The key for which to return the corresponding Cache Item.
     *
     * @throws InvalidArgumentException
     *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return CacheItemInterface
     *   The corresponding Cache Item.
     */
    public function getItem ( $key )
    {
        if ( ! is_string( $key ) ) {
            throw new InvalidArgumentException( 'E_HEADER_INVALIDARGUMENTEXCEPTION' );
        }

        $metadata = $this->memcache->get( $this->prefixKey . $key );
        $metadata = isset( $metadata[ 0 ] ) ? $metadata[ 0 ] : $metadata;

        return new Item( $key, $metadata );
    }

    // ------------------------------------------------------------------------

    /**
     * ItemPool::hasItem
     *
     * Confirms if the cache contains specified cache item.
     *
     * Note: This method MAY avoid retrieving the cached value for performance reasons.
     * This could result in a race condition with CacheItemInterface::get(). To avoid
     * such situation use CacheItemInterface::isHit() instead.
     *
     * @param string $key
     *   The key for which to check existence.
     *
     * @throws InvalidArgumentException
     *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return bool
     *   True if item exists in the cache, false otherwise.
     */
    public function hasItem ( $key )
    {
        if ( ! is_string( $key ) ) {
            throw new InvalidArgumentException( 'E_HEADER_INVALIDARGUMENTEXCEPTION' );
        }

        return (bool) $this->memcache->get( $this->prefixKey . $key );
    }

    // ------------------------------------------------------------------------

    /**
     * ItemPool::clear
     *
     * Deletes all items in the pool.
     *
     * @return bool
     *   True if the pool was successfully cleared. False if there was an error.
     */
    public function clear ()
    {
        return $this->memcache->flush();
    }

    // ------------------------------------------------------------------------

    /**
     * ItemPool::deleteItem
     *
     * Removes the item from the pool.
     *
     * @param string $key
     *   The key to delete.
     *
     * @throws InvalidArgumentException
     *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return bool
     *   True if the item was successfully removed. False if there was an error.
     */
    public function deleteItem ( $key )
    {
        if ( ! is_string( $key ) ) {
            throw new InvalidArgumentException( 'E_HEADER_INVALIDARGUMENTEXCEPTION' );
        }

        return $this->memcache->delete( $this->prefixKey . $key );
    }

    // ------------------------------------------------------------------------

    /**
     * ItemPool::save
     *
     * Persists a cache item immediately.
     *
     * @param CacheItemInterface $item
     *   The cache item to save.
     *
     * @return bool
     *   True if the item was successfully persisted. False if there was an error.
     */
    public function save ( CacheItemInterface $item )
    {
        $metadata = $item->getMetadata();
        $metadata[ 'data' ] = $item->get();

        if ( extension_loaded( 'zlib' ) ) {
            return $this->memcache->set(
                $this->prefixKey . $item->getKey(),
                $metadata,
                MEMCACHE_COMPRESSED,
                $metadata[ 'ttl' ]
            );
        } else {
            return $this->memcache->set( $this->prefixKey . $item->getKey(), $metadata, 0, $metadata[ 'ttl' ] );
        }
    }
}