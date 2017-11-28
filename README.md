[![Build Status](https://travis-ci.org/danielsdeboer/eloquent-search-map.svg?branch=master)](https://travis-ci.org/danielsdeboer/eloquent-search-map)
[![Latest Stable Version](https://poser.pugx.org/aviator/eloquent-search-map/v/stable)](https://packagist.org/packages/aviator/eloquent-search-map)
[![License](https://poser.pugx.org/aviator/eloquent-search-map/license)](https://packagist.org/packages/aviator/eloquent-search-map)

## Overview

Add Eloquent query search constraints effortlessly with this Eloquent macro.

This is especially handy when you find yourself building simple, optional searches often:

```php
$model::when($request->something, function (Builder $query) {
    return $query->where('column', 'like', '%' . $request->something . '%'); 
})->get();
```

With this package you can do this instead:

```php
$model::search(['something', 'otherthing'])->get();
```

You may create an option column map on your model, as well as mapping request properties on the fly.

## Installation

Via Composer:
```
composer require aviator/eloquent-search-map
```

In your `config/app.php` add `Aviator\Search\ServiceProvider::class` to the `providers` array:

```php
'providers' => [
    ...
    Aviator\Search\ServiceProvider::class,
],
```

## Testing

Via Composer:
```
composer test
```

## Usage

### Model Setup

To start, make the model you want to search implement the `Searchable` contract and use the `SearchableTrait`.

```php
class User extends Model implements Searchable
{
    use SearchableTrait;
   
    // ..etc
}
```

Then set a `searches` array property on your model containing your searchable columns. To search in the email column using `$model::search(['email'])`:

```php
protected $searches = [
    'email'
];
```
  
To search in the email column using $model::search(['alias'])

```php
protected $searches = [
    'alias' => 'email'
];
```

### Request Aliases

By default, the search builder assumes the column name or alias matches the request data. So if you call `$model::search('something')`, it will look for `request('something')`.

Of course you can specify the request property name manually:

```php
$model::search('email' => 'user_email')->get();
```

This tells the search builder to look for the `email` request data in `request('user_email')` instead of the default. 

## Other Stuff

### License

This package is licensed with the [MIT License (MIT)](LICENSE).
