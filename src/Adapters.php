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

namespace O2System\Cache;

// ------------------------------------------------------------------------

use O2System\Cache\Abstracts\AbstractItemPool;
use O2System\Psr\Cache\CacheItemInterface;
use O2System\Psr\Cache\CacheItemPoolInterface;
use O2System\Psr\Cache\InvalidArgumentException;
use O2System\Psr\Patterns\AbstractObjectRegistryPattern;

/**
 * Class Adapters
 *
 * @package O2System\Cache
 */
class Adapters extends AbstractObjectRegistryPattern implements CacheItemPoolInterface
{
    /**
     * Adapters::__construct
     *
     * @param Registries\Config $config
     *
     * @return Adapters
     */
    public function __construct(Registries\Config $config)
    {
        if ($config->offsetExists('default')) {
            foreach ($config as $poolOffset => $poolConfig) {
                $this->createItemPool($poolOffset, $poolConfig);
            }
        } elseif ($config->offsetExists('adapter')) {
            $this->createItemPool('default', $config);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Adapters::createItemPool
     *
     * Create Item Pool
     *
     * @param string $poolOffset
     * @param Registries\Config $poolConfig
     */
    public function createItemPool($poolOffset, Registries\Config $poolConfig)
    {
        $adapterClassName = '\O2System\Cache\Adapters\\' . ucfirst($poolConfig->adapter) . '\ItemPool';

        if (class_exists($adapterClassName)) {
            $adapter = new $adapterClassName($poolConfig);

            $this->register($adapter, $poolOffset);
        }
    }

    // ------------------------------------------------------------------------

    public function &getItemPool($poolOffset)
    {
        return $this->getObject($poolOffset);
    }

    public function hasItemPool($poolOffset)
    {
        return $this->__isset($poolOffset);
    }

    /**
     * Adapters::isValid
     *
     * Determine if value is meet requirement.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid($value)
    {
        if ($value instanceof AbstractItemPool) {
            return true;
        }

        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Adapters::getKey
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
    public function getItem($key)
    {
        return $this->default->getItem($key);
    }

    // ------------------------------------------------------------------------

    /**
     * Adapters::getItems
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
    public function getItems(array $keys = [])
    {
        return $this->default->getItems($keys);
    }

    // ------------------------------------------------------------------------

    /**
     * Adapters::hasItem
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
    public function hasItem($key)
    {
        return $this->default->hasItem($key);
    }

    // ------------------------------------------------------------------------

    /**
     * Adapters::clear
     *
     * Deletes all items in the pool.
     *
     * @return bool
     *   True if the pool was successfully cleared. False if there was an error.
     */
    public function clear()
    {
        return $this->default->clear();
    }

    // ------------------------------------------------------------------------

    /**
     * Adapters::deleteItem
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
    public function deleteItem($key)
    {
        return $this->default->deleteItem($key);
    }

    // ------------------------------------------------------------------------

    /**
     * Adapters::deleteItems
     *
     * Removes multiple items from the pool.
     *
     * @param string[] $keys
     *   An array of keys that should be removed from the pool.
     *
     * @throws InvalidArgumentException
     *   If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return bool
     *   True if the items were successfully removed. False if there was an error.
     */
    public function deleteItems(array $keys = [])
    {
        return $this->default->deleteItems($keys);
    }

    // ------------------------------------------------------------------------

    /**
     * Adapters::save
     *
     * Persists a cache item immediately.
     *
     * @param CacheItemInterface $item
     *   The cache item to save.
     *
     * @return bool
     *   True if the item was successfully persisted. False if there was an error.
     */
    public function save(CacheItemInterface $item)
    {
        return $this->default->save($item);
    }

    // ------------------------------------------------------------------------

    /**
     * Adapters::saveDeferred
     *
     * Sets a cache item to be persisted later.
     *
     * @param CacheItemInterface $item
     *   The cache item to save.
     *
     * @return bool
     *   False if the item could not be queued or if a commit was attempted and failed. True otherwise.
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        return $this->default->saveDeferred($item);
    }

    // ------------------------------------------------------------------------

    /**
     * Adapters::commit
     *
     * Persists any deferred cache items.
     *
     * @return bool
     *   True if all not-yet-saved items were successfully saved or there were none. False otherwise.
     */
    public function commit()
    {
        return $this->default->commit();
    }
}