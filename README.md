# O2System Cache
O2System Cache is an Open Source Cache Management Driver Library. 
Wrappers around some of the most popular cache storage engine. 
All but file-based caching require specific server requirements, and a Fatal Exception will be thrown if server requirements are not met.
O2System Cache is build for working more powerfull with O2System Framework, but also can be used for integrated with others as standalone version with limited features.

### Supported Storage Engines Adapters
| Engine | Support | Tested  | &nbsp; |
| ------------- |:-------------:|:-----:| ----- |
| APC | ```Yes``` | ```Yes``` | http://php.net/apc |
| APCu | ```Yes``` | ```Yes``` | http://php.net/apcu |
| File | ```Yes``` | ```Yes``` | http://php.net/file |
| Memcache | ```Yes``` | ```Yes``` | http://php.net/memcache |
| Memcached | ```Yes``` | ```Yes``` | http://php.net/memcached |
| Redis | ```Yes``` | ```Yes``` | http://redis.io |
| Wincache | ```Yes``` | ```Yes``` | http://php.net/wincache |
| XCache | ```Yes``` | ```Yes``` | https://xcache.lighttpd.net/ |
| Zend OPCache | ```Yes``` | ```Yes``` | http://php.net/opcache |

Installation
------------
The best way to install [O2System Cache](https://packagist.org/packages/o2system/cache) is to use [Composer](http://getcomposer.org)
```
composer require o2system/cache
```

Manual Installation
------------
1. Download the [master zip file](https://github.com/o2system/cache/archive/master.zip).
2. Extract into your project folder.
3. Require the autoload.php file.<br>
```php
require your_project_folder_path/cache/src/autoload.php
```

Usage Example
-------------
```php
use O2System\Cache;
```

Documentation is available on this repository [wiki](https://github.com/o2system/cache/wiki) or visit this repository [github page](https://o2system.github.io/cache).

Ideas and Suggestions
---------------------
Please kindly mail us at [o2system.framework@gmail.com](mailto:o2system.framework@gmail.com).

Bugs and Issues
---------------
Please kindly submit your [issues at Github](http://github.com/o2system/cache/issues) so we can track all the issues along development and send a [pull request](http://github.com/o2system/cache/pulls) to this repository.

System Requirements
-------------------
- PHP 5.6+
- [Composer](http://getcomposer.org)

Credits
-------
* Founder and Lead Projects: [Steeven Andrian Salim](http://steevenz.com)
* Github Pages Designer and Writer: [Teguh Rianto](http://teguhrianto.tk)

Supported By
------------
* [Zend Technologies Ltd.](http://zend.com)
