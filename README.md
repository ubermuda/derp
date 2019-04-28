# Derp

An experiment on exploring an application's required files.

This will parse your code (using `nikic/php-parser`) and try to detect every PHP file that is needed to make your app run.

**Please note**: this is only a POC and there ARE bugs (I don't know what bugs, but it's 100% guaranteed that they exist).

## Is this useful?

I don't really know, you tell me. It started as an experiment to reduce AWS Lambda packages size. Since size (kinda) is a factor, I wanted to see how far we could go in reducing an app's footprint.  

Right now, using this to package itself, here are the resulting packages sizes:

```console
9.9M    derp
1.7M    derp-packaged
```

And number of files:

```console
2182    derp
347     derp-packaged
```

But honestly, the most useful feature right now is the `why` command that will tell you why a file is required.

## Usage

Clone this repository somewhere:

```console
git clone https://github.com/ubermuda/derp.git
```

Create a `manifest.json` file in your app's root directory. This file must contain a valid JSON valid object with the following keys:

- `include` (mandatory), the files that the tool should parse (generally, your app's `index.php`). Supports globing (through `fname`).
- `autoload` (mandatory), your autoloading strategy (right now, only `composer` is supported)

So for example, if your app's entry point is `index.php`, your manifest will look something like this:

```json
{
    "include": [
        "index.php"
    ],

    "autoload": "composer"
}
```

You can also exclude classes with the `exclude-class` setting (like for `include`, globing is supported). It is useful when a class cannot be autoload because a dependency is missing (happens for example if your app has non-mandatory features based on what's installed and what's not). See this repository's `manifest.json` for an example.

Then, run the tool:

```console
$ php bin/derp ../my-app/manifest.json print
```

This will list the files required to run your application.

Having just that raw list is not really useful, so Derp comes with some other commands:

- `why`, shows you why a certain file is required
- `package`, packages your application with only the required files

Run `php bin/derp` for more information.
