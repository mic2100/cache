# Caching Library

PSR-16 compatible caching library

Welcome to the store, to enable the store to supply data we need to configure it with supplier. See the insturctions below:

```php

//Create the supplier, the supplier must implement the `Mic2100\Cache\Suppliers\SupplierInterface` interface.
$suppier = new Mic2100\Cache\Suppliers\TestDummy;

//Add the supplier to the store
$store = new Mic2100\Cache\Store($suppier);

//Adding data to the supplier
$store->set('key1', 'Some test data');

//Getting data from the supplier
$store->get('key1'); //returns: Some test data

//Removing the data from the supplier
$store->delete('key1');
```

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