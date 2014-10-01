# disqus-cli.php

A lightweight command line client for the Disqus API.

## Installation

This is a Composer package. To install, you will need to have [Composer installed](https://getcomposer.org/doc/00-intro.md).

### As a new project

```
$ composer create-project compwright/disqus-cli /path/to/install/dir
```

Run the script from your project root directory:

```
$ bin/disqus-cli.php
```

### In an existing project

Simply add the `compwright/disqus-cli` package to your `require` section in composer.json and run `composer update`.

Run the script from your project root directory:

```
$ vendor/bin/disqus-cli.php
```

## Configuration

To use this script, you will need to define a `.env` file with the following settings:

* `DISQUS_API_KEY`
* `DISQUS_API_SECRET`
* `DISQUS_ACCESS_TOKEN`

There is a `.env-sample` template for your convenience.

To obtain your Disqus API settings, visit https://disqus.com/api/applications/register/.

## Usage

```
SUMMARY
    disqus-cli.php -- A lightweight command line client for the Disqus API

USAGE
    disqus-cli.php <resource> <verb> [optionlist]

DESCRIPTION
    For a full list of Disqus API resources, verbs, and options, visit https://disqus.com/api/docs

OPTIONS
    --help
        Display this help message
```

Pass options in using the long form, i.e. `--forum=yourforumname`.

### Example

```
$ bin/disqus-cli.php threads create --forum=yourforumname --title="Thread Title"
```
