# Novalize for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/smousss/laravel-novalize.svg?style=flat-square)](https://packagist.org/packages/smousss/laravel-novalize)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/smousss/laravel-novalize/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/smousss/laravel-novalize/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/smousss/laravel-novalize.svg?style=flat-square)](https://packagist.org/packages/smousss/laravel-novalize)

Smousss can generate Laravel Nova resources in a few seconds using GPT-4 to help you speed up your admin panel's development.

```php
namespace App\Nova;

use …

class Post extends Resource
{
    public static $model = \App\Models\Post::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title', 'slug', 'content', 'description',
    ];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User'),

            Text::make('Title')
                ->rules('required', 'max:255'),

            Slug::make('Slug')
                ->from('Title')
                ->rules('required', 'max:255')
                ->creationRules('unique:posts,slug')
                ->updateRules('unique:posts,slug,{{resourceId}}'),

            Textarea::make('Content')
                ->rules('required'),

            Textarea::make('Description')
                ->rules('required'),

            HasMany::make('Comments'),

            BelongsToMany::make('Tags'),

            HasMany::make('Pins'),
        ];
    }
}
```

## Installation

Install the package via Composer:

```bash
composer require smousss/laravel-novalize
```

Publish the config file:

```bash
php artisan vendor:publish --tag=novalize-config
```

## Usage

First, [generate a secret key](https://smousss.com/dashboard) on smousss.com.

Then, create a Nova resource based on your Post model:

```php
php artisan smousss:novalize App\\Models\\Post
```

Alternatively, create a Nova resource based on multiple models:

```php
php artisan smousss:novalize App\\Models\\Post App\\Models\\Comment
```

## Credit

Novalize for Laravel has been developed by [Benjamin Crozat](https://benjamincrozat.com) for [Smousss](https://smousss.com) ([Twitter](https://twitter.com/benjamincrozat)).

## License

[MIT](LICENSE.md)
