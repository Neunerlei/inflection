# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

## [2.0.0](https://github.com/Neunerlei/inflection/compare/v1.3.1...v2.0.0) (2023-01-02)


### ⚠ BREAKING CHANGES

* PHP 8.1 is now required, also updated the symfony
inflector version

### Features

* update to php 8.1 ([41df0b8](https://github.com/Neunerlei/inflection/commit/41df0b886fafe3013535fab982f3a5a69c08f8ec))


### Bug Fixes

* ensure compatibility with php 8.0 ([89cc657](https://github.com/Neunerlei/inflection/commit/89cc6578faff3cceb71e78e4ff94ae697665e497))
* ensure test compatibility with php 8.0 ([4822212](https://github.com/Neunerlei/inflection/commit/48222128a6233b5c17981a756d6e39ba6f57b923))

### [1.3.1](https://github.com/Neunerlei/inflection/compare/v1.3.0...v1.3.1) (2022-08-26)


### Bug Fixes

* reliably inflect strings like "FOO_BAR" or "HELLO-world" with the intelligentSplitting option ([63a5cc9](https://github.com/Neunerlei/inflection/commit/63a5cc9c2be54b738e62fcabf38f0bba892f655a))

## [1.3.0](https://github.com/Neunerlei/inflection/compare/v1.2.0...v1.3.0) (2021-11-10)


### Features

* replace symfony/inflector with symfony/string component ([cf6a6e6](https://github.com/Neunerlei/inflection/commit/cf6a6e63bc525274091e17c49435015400023468))


### Bug Fixes

* **SymfonyInflectorAdapter:** always expect an array from EnglishInflector ([737ddab](https://github.com/Neunerlei/inflection/commit/737ddabe33104043926a4a993e4db3e7fc39af4c))

## [1.2.0](https://github.com/Neunerlei/inflection/compare/v1.1.0...v1.2.0) (2020-03-11)


### Features

* remove dependency on neunerlei/options that was actually not required at all ([0efbcd6](https://github.com/Neunerlei/inflection/commit/0efbcd6242304762ab1840b2841d8a0c8392b9d4))

## 1.1.0 (2020-03-11)


### Features

* initial commit ([f7d9934](https://github.com/Neunerlei/inflection/commit/f7d9934561bd9f82c353319c28afdf69ee82111f))
