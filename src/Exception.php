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

use O2System\Psr\Cache\CacheException;
use O2System\Spl\Exceptions\RuntimeException;

/**
 * Class Exception
 * @package O2System\Cache
 */
class Exception extends RuntimeException implements CacheException
{

}