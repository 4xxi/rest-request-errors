# Request errors serialization package

TODO: need more documentation

## Usage:

For Symfony forms:
```php
use Fourxxi\RestRequestError\Exception\FormInvalidRequestException;

if (!$form->isValid()) {
    throw new FormInvalidRequestException($form);
}
```

For custom error array:
```php
use Fourxxi\RestRequestError\Exception\ArrayInvalidRequestException;

throw new ArrayInvalidRequestException([
    'field1' => 'errorValue',
    'field2' => 'someAnotherError'
]);
```