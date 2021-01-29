# Sogltest

Test Laravel application with controller that sends requests to fake SendMessage API endpoint.

## Run tests

```sh
$ php artisan test
```

## Operating the API

Make POST request to `/api/send` with such body:
```json
{
    "to": [1, 2, 3],
    "message": "Hello world!"
}
```

That shall make 3 separate requests on server side, resulting in same message sent to all recipients.

## What's modified?

Compare the initial commit with the latest test commit:
```sh
$ git diff 891eb3d...1c1abff
```
