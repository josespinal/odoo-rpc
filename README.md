# Access Odoo via JSON-RPC and XML-RPC

> **Note**: This package is a fork of [obuchmann/odoo-jsonrpc](https://github.com/obuchmann/odoo-jsonrpc) with several improvements:
> - Added XML-RPC support alongside JSON-RPC
> - Enhanced Laravel integration with proper service provider
> - Added Laravel Collection support for search results
> - Improved type safety and error handling
> - Added convenient methods like `first()` for better developer experience
> - Better PHP 8.0+ support

Connect to odoo via the json-rpc or xml-rpc api. If you are in a laravel project, this package registers a provider. But laravel is not required for this package.

## Installation

You can install the package via composer:

```bash
composer require josespinal/odoo-rpc
```

The service provider will automatically register itself if you are in a laravel project.

You can publish the config file with:
```bash
php artisan vendor:publish --provider="JoseSpinal\OdooRpc\OdooServiceProvider" --tag="config"
```

## Usage

### Protocol Selection

This package supports both JSON-RPC and XML-RPC protocols. You can specify which protocol to use in your configuration:

```php
// In your .env file
ODOO_PROTOCOL=json-rpc  # or xml-rpc

// Or in your code
$config = new Config(
    database: 'odoo',
    host: 'http://localhost:8069',
    username: 'admin',
    password: 'password',
    protocol: 'xml-rpc'  // or 'json-rpc'
);
```

### Basic Usage

```php
use JoseSpinal\OdooRpc\Odoo;
use JoseSpinal\OdooRpc\Odoo\Request\Arguments\Domain;

$this->host = 'http://localhost:8069';
$this->username = 'admin';
$this->password = 'password';
$this->database = 'odoo';

// Connect to Odoo
$odoo = new Odoo(new Odoo\Config($database, $host, $username, $password));
$odoo->connect();


// Check Access rights (bool)
$check = $odoo->checkAccessRights('res.partner', 'read');

// Check Access rights in model syntax

$check = $odoo->model('res.partner')
            ->can('read');
            
// Use Domain for Search
$isCompanyDomain = (new Domain())->where('is_company', '=', true);
$companyIds = $odoo->search('res.partner', $isCompanyDomain);

// read ids
$companies = $odoo->read('res.partner', $companyIds);

// search_read with model Syntax
$companies = $odoo->model('res.partner')
            ->where('is_company', '=', true)
            ->get();
            
// search_read with single item
$company = $odoo->model('res.partner')
            ->where('is_company', '=', true)
            ->where('name', '=', 'My Company')
            ->first();
            
// create with model syntax
$partner = $odoo->model('res.partner')
            ->create([
                'name' => 'My Company',
                'is_company' => true
            ]);
            
// update with model syntax
$partner = $odoo->model('res.partner')
            ->where('name', '=', 'My Company')
            ->update([
                'name' => 'My New Company'
            ]);
// direct update by id            
$myCompanyId = 1;
$partner = $odoo->updateById('res.partner', $myCompanyId, [
    'name' => 'My New Company'
]);

// delete by id
$odoo->deleteById('res.partner', $myCompanyId);


```


### Laravel Usage

```php

class Controller{

    public function index(\JoseSpinal\OdooRpc\Odoo $odoo){
        // Find Model by Id
        $product = $odoo->find('product.template', 1);
        
        // Update Model by ID
        $this->odoo->updateById('product.product', $product->id, [
            'name' => $name,
        ]);
        
        // Create returning ID
        $id = $this->odoo
            ->create('res.partner', [
                'name' => 'Bobby Brown'
            ]);
        
        // Search for Models with or
        $partners = $this->odoo->model('res.partner')
            ->where('name', '=', 'Bobby Brown')
            ->orWhere('name', '=', 'Gregor Green')
            ->limit(5)
            ->orderBy('id', 'desc')
            ->get();
        
        // Update by Query
        $updateResponse = $this->odoo
            ->model('res.partner')
            ->where('name', '=', 'Bobby Brown')
            ->update([
                'name' => 'Dagobert Duck'
            ]);
    }
}
```

### Laravel Models

Laravel Models are implemented with Attributes

```php
#[Model('res.partner')]
use JoseSpinal\OdooRpc\Odoo\OdooModel;

class Partner extends OdooModel
{
    #[Field]
    public string $name;

    #[Field('email')]
    public ?string $email;
}


class Controller{

    public function index(){
        // Find Model by Id
        $partner = Partner::find(1);
        
        // Search Model
        $partner = Partner::query()
            ->where('name', '=', 'Azure Interior')
            ->first();
        
        // Update Model
        $partner->name = "Dagobert Duck";
        $partner->save();
        
        // Create returning ID
        $partner = new Partner();
        $partner->name = 'Tester';
        $partner->save();               
    }
}
```

### Casts

You can define a cast for your models. This is useful if you want to convert odoo fields to a specific type. There are some predefined casts for date and datetime fields.

Casts are global and can be registered in the Odoo class.

```php

// The basic datetime cast
\JoseSpinal\OdooRpc\Odoo::registerCast(new Odoo\Casts\DateTimeCast());

// a datetime cast that respects the timezone
\JoseSpinal\OdooRpc\Odoo::registerCast(new Odoo\Casts\DateTimeCast('Europe/Berlin'));


// you can write custom casts by extending the Obuchmann\OdooJsonRpc\Odoo\Casts\Cast class
// example DateTimeCast

class DateTimeCast extends Cast
{

    public function getType(): string
    {
        return \DateTime::class;
    }

    public function cast($raw)
    {
        if($raw){
            try {
                return new \DateTime($raw);
            } catch (\Exception) {} // If no valid Date return null
        }
        return null;
    }

    public function uncast($value)
    {
        if($value instanceof \DateTime){
            return $value->format('Y-m-d H:i:s');
        }
    }
} 



```


For more examples take a look at the tests directory.


## Tests

```bash
composer test
```
