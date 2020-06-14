Env Validator
=====================

Env Validator is a utility for comparing the **expected** environment variables (usually defined in a `.env.example` file) with the **actual** environment variables (usually loaded from a `.env` file) that are present at runtime.

## Installation

Install via composer:
```shell script
composer require selevia/env-validator
```

## Usage
As your project grows, your infrastructure concerns change, and so does the `.env.example` file. Use the `env-validator` utility to validate that your environment has been configured correctly.

Simply add a script to your `composer.json`:
```json
{
  "scripts": {
    "env-validator": "env-validator"
  } 
}
```

and run it:
```shell script
composer env-validator
```

or, alternatively, run the executable directly:
```shell script
vendor/bin/env-validator
```


## Options 
By default, the utility looks for `.env` and `.env.example` files in the root directory of your project.

If you want to change the default filenames use the following options:
* `--actual` (`-a`) for the actual file.
* `--expected` (`-e`) for the expected file.

Example:
```shell script
vendor/bin/env-validator --actual=.env.dev --expected=.env.template
```

## Output
The very first line will be a summary of each Status type (i.e. Success, Warning, and Error). Below the summary, all the Error and Warning cases will be outputted, if any.

## Statuses
Each environment variable defined *in both files* will be given a Status 


| Type | Output Message | Explanation |
| --- | --- | --- |
| Error | Expected env var ENV_VAR_NAME was completely missing | The variable is missing. This means it was defined in the expected file, but not in the actual one. |
| Warning | Unexpected env var ENV_VAR_NAME encountered | The variable is unexpected. This means it was present in the actual file, but it was not defined in the expected file. |
| Warning | Env var ENV_VAR_NAME was present, but the value was empty | The variable is blank. This means it was defined in the expected file and present in the actual file, but the value was blank.|
| Success| N/A | Everything is okay |