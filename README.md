# Derp

An experiment on exploring an application's required files.

This will parse your code (using `nikic/php-parser`) and try to detect every PHP file that is needed to make your app run.

**Please note**: this is only a POC and there ARE bugs (I don't know what bugs, but it's 100% guaranteed that they exist).

## Is this useful?

I don't really know, you tell me. It started as an experiment to reduce AWS Lambda packages size. Since size (kinda) is a factor, I wanted to see how far we could go in reducing an app's footprint.  

Right now, using this to package itself, here are the resulting packages sizes:

```console
3.3M    Derp
937K    Derp-packaged
724K    Derp.zip
208K    Derp-packaged.zip
```

(Not sure if the zip files size is relevant, including it just in case)

And number of files:

```console
696     Derp
218     Derp-packaged
```

(Pretty sure number of files is totally irrelevant :p)

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

You can also exclude files with the `exclude` setting (like for `include`, globing is supported).

Then, run the tool:

```console
$ php bin/derp ../my-app/manifest.json print
```

This will list the files required to run your application.

Having just that raw list is not really useful, so Derp comes with some other commands:

- `why`, shows you why a certain file is required
- `package`, packages your application with only the required files

Run `php bin/derp` for more information.
