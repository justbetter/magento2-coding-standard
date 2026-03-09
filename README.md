# Magento 2 Coding Standard

This repository contains the coding standards configurations used for our packages and projects.

## installation

Installation is not strictly necessary, you could simply copy the files you care about and update the paths.
This way you will not get any updates in the future.

Run
```shell-script
composer require --dev "justbetter/magento2-coding-standard"
```

### Editorconfig

The editorconfig is made so your editors are in sync with each other, to prevent different editor from constantly causing changes and conflicts with each other or other tools.

```shell-script
\ln -sf vendor/justbetter/magento2-coding-standard/.editorconfig .editorconfig
```

## PHPStan

First you should install [phpstan-magento](https://github.com/bitexpert/phpstan-magento).

```shell-script
composer require --dev "bitexpert/phpstan-magento"
```

then copy the sample configuration file, this includes larastan with some basic setup from magento2-coding-standard, like the editor url button.

```shell-script
\cp vendor/justbetter/magento2-coding-standard/phpstan.sample.neon phpstan.neon
```

### Optional - Copy workflow

Once you've installed PHPStan you could copy the analyse workflow to automatically run it for PRs

```shell-script
\mkdir -p .github/workflows
\cp vendor/justbetter/magento2-coding-standard/.github/sample-workflows/analyse.yml .github/workflows/analyse.yml
```

## Rector

As a good companion to PHPStan we also have configuration for Rector which can in some cases fix PHPStan issues, and improve the results given by PHPStan

Copy the sample configuration file, this includes Rector with some basic setup from magento2-coding-standard.

```shell-script
\cp vendor/justbetter/magento2-coding-standard/rector.sample.php rector.php
```

Rector is much more stable in it's changes than it ever was, rarely (if at all) making breaking changes.
We still recommend running it manually instead of using workflows.
