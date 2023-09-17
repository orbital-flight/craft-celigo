# Celigo

Use this plugin to connect Craft to any custom Celigo « My API » endpoint using the twig variable.

## Setting up your endpoints

You will first need to configure your custom My API celigo endpoints (and their scripts/configurations) [on Celigo](https://docs.celigo.com/hc/en-us/sections/360009934891-Build-your-API).

Once you have your API ID and token, head to the plugin settings and add them to the table. 

You will need to choose a handle to refer to each API in your code. 

As an extra safety measure, you can use environment variables for your credentials.

## Basic usage

Once your endpoints are configured, you can build your requests directly from your twig templates using the plugin's variable.

You will need the HTTP method with which you want to call your API*, the custom endpoint you created in the previous step, and optionally the parameters you want to pass to the API.

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

**Available HTTP methods: GET - POST - PUT - UPDATE - DELETE - PATCH*

## Troubleshooting
You can listen for errors by checking the `error` property `{{ response.error }}`.
For error 4XX and 5XX, the `debugError` property will contain the full API’s response data as an object you can dump:
```twig
{% if response.error is defined %}
    {{ dump(response.debugError) }}
{% endif %}
```

For early development, you can just dump any of your responses with:
```twig
{{ dump(response) ?? "" }}
```

## Disclaimer
This plugin mostly acts like an API bridge between your Craft project and any existing Celigo MyAPI endpoints. However, and because Celigo’s solution is very complete, sensible data can easily be obtained and dealed with. Keep in mind that you are responsible for your scripts behaviours and for the usage or display of the data you can manipulate with this plugin. 

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
