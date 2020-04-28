# Request errors serialization bundle
Bundle provides simple serialization for symfony form errors in JSON REST API.

## Installation
1. Install component via composer
```shell script
composer require 4xxi/rest-request-errors
```

## Usage
For Symfony forms:
```php
use Fourxxi\RestRequestError\Exception\FormInvalidRequestException;

if (!$form->isValid()) {
    throw new FormInvalidRequestException($form);
}
```

For custom errors array:
```php
use Fourxxi\RestRequestError\Exception\ArrayInvalidRequestException;

throw new ArrayInvalidRequestException([
    'field1' => 'errorValue',
    'field2' => 'someAnotherError'
]);
```

Examples above produces response:
```json
{
  "errors": [
    
  ],
  "children": {
    "username": {
      "errors": [
        "Some error for form field"
      ],
      "children": []
    }
  }
}
```

If your application doesn't have exception listener, you can use bundle provided exception listener.

To enable it, add configuration yaml into `config/packages/rest_request_error.yaml` with following content:
```yaml
rest_request_error:
  use_exception_listener: true
  ```
