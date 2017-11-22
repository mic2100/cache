# Caching Library

[![Build Status](https://travis-ci.org/mic2100/cache.png?branch=master)](https://travis-ci.org/mic2100/cache)

PSR-16 compatible caching library

Instructions

```php

//Instantiate the Configuration class
$config = new \Mic2100\Cache\Configuration;

//Instantiate the SupplierInterface class (ArraySupplier or others)
$supplier = new \Mic2100\Cache\Suppliers\ArraySupplier;

//Add the supplier to the Configuration class
$config->setSupplier($supplier);

//Instantiate the Store and pass it the config
$store = new Store($config);

//Adding data to the store
$store->set('key1', 'Some test data');

//Getting data from the store
$store->get('key1'); //returns: Some test data

//Removing the data from the store
$store->delete('key1');

//Removing all the data from the store
$store->clear();

//Check if the store has the data
if ($store->has('key')) {
    //the key exists in the store
}
```
                    _________________________
                   ||                       ||
                   ||      Store Front      ||
   ________________||_______________________||_____________
  |_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_||
  |_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|| /|
  |_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_||/||
  |_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|||/|
  |_|_|_|_|_|_|_|_|_|     _      _     |_|_|_|_|_|_|_|_|_|_|/||
  |_|               |    (_)    (_)    |                 |_|/||
  |_|  Suppliers:   |__________________|   Suppliers:    |_||/|
  |_|               |_|      ||      |_|                 |_|/||
  |_|   Memcache    |_|      ||      |_|      File       |_||/|
  |_|               |_|      ||      |_|                 |_|/||
  |_|     APC       |_|     [||]     |_|      Redis      |_||/|
  |_|               |_|      ||      |_|                 |_|/||
  |_|_______________|_|      ||      |_|_________________|_||/|
  |_|_|_|_|_|_|_|_|_|_|______||______|_|_|_|_|_|_|_|_|_|_|_|/||
__|_|_|_|_|_|_|_|_|_|_|______||______|_|_|_|_|_|_|_|_|_|_|_||/________
 /     /     /     /     /     /     /     /     /     /     /     /
/_____/_____/_____/_____/_____/_____/_____/_____/_____/_____/_____/
____________________________________________________________jro___
```