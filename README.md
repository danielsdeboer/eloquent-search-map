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

By default, the search builder assumes the column name or alias matches the request data. So if you call `$model::search(['something'])`, it will look for `request('something')`.

Of course you can specify the request property name manually:

```php
$model::search(['email' => 'user_email'])->get();
```

This tells the search builder to look for the `email` request data in `request('user_email')` instead of the default.

### Custom Requests

If you need to pass a custom request into the macro, use the second parameter, which accepts an object extending `Illuminate\Http\Request`:

```php
$model::search(['term'], $request)->get();
``` 

Of course, this is completely optional. If a request isn't provided, it will be retrieved from the container.

### Related Models

If you want to query related models, you can! Use dot notation:

```php
protected $searches = [
    'relation.column'
];
``` 

This will look for a relation method called `company()` and add a `whereHas` constraint to the query. For instance:

```php
$users = User::search('company.city')->get();
```

This will look on the `User` model for a relation `company()` and search in the `city` attribute of that model.

By default we assume that the request will have the same property, snake cased. For the above query constraint the search builder will look for `request('company_city')`.

This can also be mapped:

```php
$users = User::search(['company.city' => 'city'])->get();
```

The search builder will now look for `request('city')` instead.

## Other Stuff

### License

This package is licensed with the [MIT License (MIT)](LICENSE).
