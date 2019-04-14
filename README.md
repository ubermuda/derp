# Lambda Packager

An experiment on reducing the size of AWS Lambda packages.

This will parse your code (using `nikic/php-parser`) and try to detect every PHP file that is needed to make your lambda run.

**Please note**: that this is only a POC and that there ARE bugs (I don't know what bugs, but it's 100% guaranteed that they exist).

## Is this useful?

I don't really know, you tell me.

Right now, using this to package itself, here are the results:

```console
3.3M    LambdaPackager
937K    LambdaPackager-packaged
724K    LambdaPackager.zip
208K    LambdaPackager-packaged.zip
```

(Not sure if the zip files size is relevant, including it just in case)

## Usage

Clone this repository somewhere:

```console
git clone https://github.com/ubermuda/lambda-packager.git
```

Create a `manifest.json` file in your lambda's root directory. This file must contain a valid JSON valid object with the following keys:

- `include`, the files that the tool should parse (generally, your lambda's `index.php`)
- `autoload`, your autoloading strategy (right now, only `composer` is supported)

So for example, if your lambda entry point is `index.php`, your manifest will look something like this:

```json
{
    "include": [
        "index.php"
    ],

    "autoload": "composer"
}
```

Then, run the tool:

```console
$ php bin/package ../my-lambda/manifest.json
```

By default, the tool will package your lambda in a `build/` directory located in your project's root, but you can pass a custom build directory to the script:

```console
$ php bin/package ../my-lambda/manifest.json /tmp/build
```
