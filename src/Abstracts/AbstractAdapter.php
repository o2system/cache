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

use O2System\Cache\Exception;
use O2System\Cache\Registries\Config;
use O2System\Psr\Cache\CacheItemPoolAdapterInterface;

/**
 * Class AbstractAdapter
 *
 * @package O2System\Cache\Abstracts
 */
abstract class AbstractAdapter extends AbstractItemPool implements CacheItemPoolAdapterInterface
{
    /**
     * Adapter Platform Name
     *
     * @var string
     */
    protected $platform;

    /**
     * Adapter Config
     *
     * @var array
     */
    protected $config = [ ];

    /**
     * Adapter Prefix Key
     *
     * @var string
     */
    protected $prefixKey;

    // ------------------------------------------------------------------------

    /**
     * AbstractAdapter::__construct
     *
     * @param \O2System\Cache\Registries\Config|NULL $config
     *
     * @return AbstractAdapter
     * @throws Exception
     */
    public function __construct ( Config $config = null )
    {
        if ( isset( $config ) ) {
            if ( $this->isSupported() ) {
                $this->connect( $config->getArrayCopy() );

                if ( $config->offsetExists( 'prefixKey' ) ) {
                    $this->setPrefixKey( $config->prefixKey );
                }
            } else {
                throw new Exception( 'E_MESSAGE_CACHE_UNSUPPORTED_ADAPTER', 0, [ $this->platform ] );
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * AbstractAdapter::setPrefixKey
     *
     * Sets item prefix key.
     *
     * @param $prefixKey
     */
    public function setPrefixKey ( $prefixKey )
    {
        $this->prefixKey = rtrim( $prefixKey, ':' ) . ':';
    }

    // ------------------------------------------------------------------------

    /**
     * AbstractAdapter::getPlatform
     *
     * Gets item pool adapter platform name.
     *
     * @return string
     */
    public function getPlatform ()
    {
        return $this->platform;
    }
}