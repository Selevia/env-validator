Selevia Env Validator
=====================

Selevia Env Validator is meant to check your `.env` file validity based on `.env.example` in order to avoid any unexpected behaviour.

## Installation

Install via composer:
```
composer require selevia/env-validator
```

## Usage
Simply run following command:
```
vendor/bin/env-validator
```
Command will use `.env` and `.env.example` files in the root directory of your project.

If You want to change default filenames use following options:
* `--actual` (`-a`) for the actual file.
* `--expected` (`-e`) for the expected file.

For example:
```
vendor/bin/env-validator --actual=.env.dev --expected=.env.template
```

## Output
You will get a summary with the number of variables Success, Warning and Error.
Below you will see separate messages for each variable with the Error or Warning status.

Messages can be:
* `Expected env var MISSING_ENV_KEY was completely missing`. The key is present in the expected file, but not in the actual one.
* `Unexpected env var UNNECESSARY_ENV_KEY encountered`. The key is present in the actual file, but not in the expected one.
* `Env var ENV_KEY_WITHOUT_VALUE was present, but the value was empty`. The expected key was present in the actual file, but the value is missing.