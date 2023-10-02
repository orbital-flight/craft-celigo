# Release Notes for Celigo

## 1.1.1 - 2023.10.02
### Fixed 
* Fixed a bug where the controller could not recognize a section handle

## 1.1.0 - 2023.10.01
### Added
* You can now call your APIs with a controller (via a regular `<form>` html form or an AJAX request). Head to the [documentation](https://github.com/orbital-flight/craft-celigo#readme) to learn about it!
* You can choose whether to enable the controller for anonymous requests in the plugin settings 
* Added `errorBody` for debugging, which features the `message` and `code` properties

### Changed
* Updated the documentation troubleshooting section
* The documentation button now properly targets the #readme anchor (settings page)
* `response.debugError` is now formatted with the `status`, `reasonPhrase`, `body`, `headers` and `metadata` properties

## 1.0.0 - 2023.09.20
* Initial release
