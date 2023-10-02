# Celigo

Use this plugin to connect Craft to any custom Celigo « My API » endpoint using a controller or the Twig variable.

## Setting up your endpoints

You will first need to configure your custom My API celigo endpoints (and their scripts/configurations) [on Celigo](https://docs.celigo.com/hc/en-us/sections/360009934891-Build-your-API).

Once you have your API ID and token, head to the plugin settings and add them to the table.

You will need to choose a handle to refer to each API in your code.

As an extra safety measure, you can use environment variables for your credentials.

## Basic usage

Once your endpoints are configured, you can build your requests by calling the appropriate controller or directly from your twig templates using the plugin's variable.

You will need the HTTP method which you want to call your API\* with, the custom endpoint you created on the previous step, and optionally the parameters you want to pass to the API.

\*_Available HTTP methods: GET - POST - PUT - UPDATE - DELETE - PATCH_

### Twig variable:

```twig
{% set response = craft.celigo.get('my-custom-handle') %}
```

```twig
{% set response = craft.celigo.post('my-custom-handle', {
    param1: "value1",
    param2: "value2",
    param3: [
        subParam1: "value3",
        subParam2: "value4"
    ]
}) %}
```

Your API’s response will be converted from JSON to a twig object.

Let's say your API returns this JSON response:

```json
{
    "weather": {
        "id": 420,
        "main": "Rain",
        "description": "moderate rain"
    }
}
```

You'll be able to output `moderate rain` using `response.weather.description`

### Controller:

You can also call your APIs using the plugin's controllers by calling `celigo/call/[HTTP_METHOD]`.

The required parameter `handle` is a string calling your API handle.

If you are calling the controller via an HTML form, the response will be sent as a twig object `'response'` working similarly to the twig variable described above.

Use the `params` parameter as needed to pass additional parameters as an array.

Finally, you can also pass a `redirect` parameter with the template (handle or path) you want the response to be available in (this param is optional. Without it, the controller will load the response in the template from which the request has been made).

#### Example:

```twig
<form method="post">
    {{ csrfInput }}
    {{ actionInput('celigo/call/get') }}
    {{ redirectInput('my-template') }}
    {{ hiddenInput('handle', 'my-handle') }}
    {{ hiddenInput('params[user][id]', '42') }}
    {{ hiddenInput('params[user][email]', 'email@example.com') }}
    {{ hiddenInput('params[groups][0][id]', '528491') }}
</form>
```

The above example will make a `GET` request to the `my-handle` API, passing the following parameters:

```json
{
    "user": {
        "id": "42",
        "email": "email@example.com"
    },
    "groups": [
        {
            "id": "528491"
        }
    ]
}
```

#### AJAX:

If you are calling the controller with an AJAX request, the response will be provided as JSON. Make sure to follow the [usual Craft convention to call controllers with AJAX](https://craftcms.com/docs/4.x/dev/controller-actions.html#ajax), such as accepting JSON and be properly formatted with a CSRF token.

```javascript
const postData = {
    handle: "my-handle",
    params: {
        "user": {
            "id": "42",
            "email": "email@example.com"
        },
        "groups": [
            {
                "id": "528491"
            }
        ]
    }
};

getSessionInfo().then((session) => {
    fetch("/actions/celigo/call/get", {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            "X-CSRF-Token": session.csrfTokenValue,
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(postData),
    })
    .then((response) => response.json())
    .then((result) => console.log(result));
});
```

## Troubleshooting

You can listen for errors by checking the `error` property `{{ response.error }}`, which provides a friendly, comprehensive explanation of what's going on.

For error 4XX and 5XX, two dumpables objects are generated to help you troubleshoot ;

-   The `errorBody` property will contain the main error message and code ;

```twig
{{ response.errorBody.message ?? "" }}
{{ response.errorBody.code ?? "" }}
```

-   The `debugError` property will contain the full API’s response data as an object you can dump:

```twig
{% if response.error is defined %}
    {{ dump(response.errorDebug) }}
{% endif %}
```

For early development, you can just dump any of your response with:

```twig
{% if response is defined %}
    {{ dump(response) }}
{% endif %}
```

_AJAX troubleshooting basically works the same but with a JSON response._

## Disclaimer

This plugin mostly acts like an API bridge between your Craft project and any existing Celigo MyAPI endpoints. However, and because Celigo’s solution is very complete, sensible data can easily be obtained and dealed with. Keep in mind that you are responsible for your script's behaviours and for the usage or display of the data you can manipulate with this plugin.

Orbital-flight is not linked to Celigo ;-)

## Requirements

This plugin requires Craft CMS 4.3.5 or later, and PHP 8.0.2 or later.

## Installation

You can install this plugin from the Plugin Store or with Composer.

#### From the Plugin Store

Go to the Plugin Store in your project’s Control Panel and search for “Celigo”. Then press “Install”.

#### With Composer

Open your terminal and run the following commands:

```bash
# go to the project directory
cd /path/to/my-project.test

# tell Composer to load the plugin
composer require orbital-flight/craft-celigo

# tell Craft to install the plugin
./craft plugin/install celigo
```
