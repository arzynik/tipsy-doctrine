## Tipsy Doctrine

A doctrine adapter for Tipsy


#### Installation

1. `vendor/bin/doctrine orm:schema-tool:update --force --dump-sql`


#### Usage
Usage is similar to using `Tipsy\Resource`.

1. Create a doctrine model, for example in `model/Product.php`
2. Specify the path 

  ```php
  Tipsy\Tipsy::config([doctrine => [model => 'models']]);
  ```
3. Add a database config
  
  ```php
  Tipsy\Tipsy::config([db => [url => 'mysql://user:pass@host/dbname']]);
  ```
4. Set the Resource

  ```php
  Tipsy::service('Product', '\Tipsy\Doctrine\Resource\Product');
  ```
5. Use DI to access the object

  ```php
  Tipsy\Tipsy::router()->home(function($Product) {
    $p = $Product->create([
      name => 'test'
    ]);
  });

See [Tipsy Doctrine Example](https://github.com/arzynik/tipsy-example-doctrine) for a more detailed example.


#### Info
See [Tipsy Documentation](https://github.com/arzynik/tipsy/wiki) for more information on Tipsy.

See [Getting Started](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/tutorials/getting-started.html) for more info on Doctrine.
