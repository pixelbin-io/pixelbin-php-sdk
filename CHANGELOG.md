# Pixelbin Backend SDK CHANGELOG

## 0.0.2

- Fixed issue with autoload behaviour `PixelbinConfig`.
- Added `createSignedUrlV2Async` and `createSignedUrlV2` for signed multipart upload

## V0.0.4

### Additions

- Added the `integrationPlatform` option to the `PixelbinConfig` constructor. This option allows users to prepend a custom value to the `user-agent` header string on all calls made using this SDK.

### Documentation

- Updated documentation
- Added missing imports in example scripts.
- Fixed invalid php example code.

### Minor Fixes

- Fixed issue where errors were being printed to console and not thrown out.
- Fixed issue where entire request and response body would be printed when making successful function calls.
