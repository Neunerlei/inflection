# Inflection
The package contains yet another inflector but with a twist. The implementation of the
actual inflecting (pluralize and singularize a string) is agnostic to the actual implementation you want to use.
By default the inflection is done by the [symfony/inflector](https://packagist.org/packages/symfony/inflector) but
you can simply switch to any other inflector using adapter classes.

In addition to that the inflector comes with additional string modification capabilities like toDashed, toHuman or toGetter and more.

## Installation
Install this package using composer:

```
composer require neunerlei/inflection
```

## Methods
The inflector can be found at ```Neunerlei\Inflection\Inflector```.

#### toSingular()
Returns the singular form of a word
```php
use Neunerlei\Inflection\Inflector;
Inflector::toSingular("trees"); // "tree"
```

#### toPlural()
Returns the plural form of a word
```php
use Neunerlei\Inflection\Inflector;
Inflector::toPlural("tree"); // "trees"
```

#### toSlug()
Converts a "Given string" to "given-string" or
"another.String-you wouldWant" to "another-string-you-wouldwant".
But in addition to that, it will convert "Annahäuser_Römertopf.jpg" into "annahaeuser-roemertopf-jpg"

NOTE: The implementation is heavily inspired (*cough* mostly stolen *cough*) from  [cakephp's Inflector::slug method](https://book.cakephp.org/4/en/core-libraries/inflector.html). Kudos to them!

```php
use Neunerlei\Inflection\Inflector;
Inflector::toSlug("Given string"); // "given-string"
```

#### toFile()
Similar to toSlug() but is able to detect file extensions, and a path
segment which will both be ignored while converting the file into a sluggified version.

```php
use Neunerlei\Inflection\Inflector;
Inflector::toFile("Given string.jpg"); // "given-string.jpg"

// With and without path handling
Inflector::toFile("/path/with/Given string.jpg", true); 
// "/path/with/given-string.jpg"
Inflector::toFile("/path/with/Given string.jpg"); 
// "path-with-given-string.jpg"
```

#### toArray()
Converts a "Given string" to ["given", "string"] or
"another.String-you wouldWant" to ["another", "string", "you", "would", "want"].

**A thought on intelligent splitting:** The default splitter is rather dumb when it comes to edge cases like IP,
URL, and so on, because it will split them like I, P and U, R, L but stuff like HandMeAMango on the other hand will be correctly splitted like: hand,
me, a, mango. If you set the second parameter to true, those edge cases will be handled more intelligently.
Problems might occur when stuff like "ThisIsFAQandMore" is given, because the camelCase is broken the result will be: "this is fa qand more".

```php
use Neunerlei\Inflection\Inflector;
Inflector::toArray("Given string"); // ["given", "string"];
Inflector::toArray(" Given   string   "); // ["given", "string"];

// Intelligent vs default splitting
// Default:
Inflector::toArray("HelloWORLD"); // ["hello", "w", "o", "r", "l", "d"];
Inflector::toArray("FAQ"); // ["f", "a", "q"];
// Intelligent:
Inflector::toArray("HelloWORLD", TRUE);  // ["hello", "world"]
Inflector::toArray("FAQ", TRUE); // ["faq"];
```

#### toSpacedUpper() | toHuman()
Converts a "Given string" to "Given String" or "another.String-you wouldWant" to "Another String You Would Want".
```php
use Neunerlei\Inflection\Inflector;
Inflector::toSpacedUpper("Given string"); // "Given String"
// Alias: toHuman()
Inflector::toHuman("Given string"); // "Given String"
```

#### toCamelCase()
Converts a "Given string" to "GivenString" or "another.String-you wouldWant" to "AnotherStringYouWouldWant".
```php
use Neunerlei\Inflection\Inflector;
Inflector::toCamelCase("Given string"); // "GivenString"
```

#### toCamelBack()
Converts a "Given string" to "givenString" or "another.String-you wouldWant" to "anotherStringYouWouldWant".
```php
use Neunerlei\Inflection\Inflector;
Inflector::toCamelBack("Given string"); // "givenString"
```

#### toDashed()
Converts a "Given string" to "given-string" or "another.String-you wouldWant" to "another-string-you-would-want".
```php
use Neunerlei\Inflection\Inflector;
Inflector::toDashed("Given string"); // "given-string"
```

#### setInflectorAdapter()
Can be used to inject a custom inflector adapter if you don't want to use the symfony inflector
```php
use Neunerlei\Inflection\Inflector;
Inflector::setInflectorAdapter(new MyInflectorAdapter());
```

#### toUnderscore() | toDatabase()
Converts a "Given string" to "given_string" or "another.String-you wouldWant" to "another_string_you_would_want".
```php
use Neunerlei\Inflection\Inflector;
Inflector::toUnderscore("Given string"); // "given_string"
// Alias: toUnderscore()
Inflector::toDatabase("Given string"); // "given_string"
```

#### toGetter()
Converts a "Given string" to "getGivenString" or "another.String-you wouldWant" to
"getAnotherStringYouWouldWant". It allows you to add a custom prefix other than "get" using the second
parameter. It will also sanitize the incoming string so if "getMyProperty" is given as string you will still end
up with "getMyProperty" instead of "getGetMyProperty" which would not make much sense. The sanitation will
remove the following prefixes "is", "has", "get" and "set".

```php
use Neunerlei\Inflection\Inflector;

// Sanitization in action
Inflector::toGetter("myProperty"); // "getMyProperty"
Inflector::toGetter("hasMyProperty"); // "getMyProperty"
Inflector::toGetter("my-Property"); // "getMyProperty"
Inflector::toGetter("isMyProperty"); // "getMyProperty"
Inflector::toGetter("issetProperty"); // "getIssetProperty" this works, too!

// Disable sanitization
Inflector::toGetter("isMyProperty", null, ["noSanitization"]); // "getIsMyProperty"
Inflector::toGetter("hasMyProperty", null, ["ns"]); // "getHasMyProperty"

// Change the prefix
Inflector::toGetter("myProperty", "is"); // "isMyProperty"
Inflector::toGetter("getMyProperty", "has"); // "hasMyProperty"

// Intelligent splitting works here to
Inflector::toGetter("FAQ", "is", ["intelligentSplitting"]); // "isFaq";
```

#### toSetter()
Converts a "Given string" to "setGivenString" or "another.String-you wouldWant" to "setAnotherStringYouWouldWant".
Sanitizes the input string like toGetter() does and has the same options.
```php
use Neunerlei\Inflection\Inflector;
Inflector::toSetter("Given string"); // "setGivenString"
```

#### toProperty()
This is in general an alias of toCamelBack(); But it will also strip away has/get/is/set prefixes from the given
value before the camel back version is generated. 
```php
use Neunerlei\Inflection\Inflector;
Inflector::toSetter("hasMyProperty"); // "myProperty"
```

#### toComparable()
This method will convert the given string by unifying it. Unify means, it makes it comparable with other
strings, by removing all special characters, converting everything to lowercase, counting all words and the
number of their occurrence (optional) and sorting them alphabetically. This also means, that the text will no
longer make sense for humans, but is easy to use for search and comparison actions.

```php
use Neunerlei\Inflection\Inflector;
Inflector::toComparable("max mustermann"); // "max1 mustermann1"
Inflector::toComparable("Mustermann, Max "); // "max1 mustermann1"
Inflector::toComparable("first name last name"); // "first1 last1 name2"
```

#### toUuid()
Converts any given string into a UUID like: 123e4567-e89b-12d3-a456-426655440000.
This is useful if you want to create, unique id's but want to combine multiple strings with different word order.

Note that "ASDF QWER" will result in the same ID as "QWER ASDF", because
the values will be sorted alphabetically before the id is created.
This makes sorting via first name and last name a lot easier.

```php
use Neunerlei\Inflection\Inflector;
Inflector::toUuid("max mustermann"); // "c47276d9-be31-5329-40d9-25fc290609ec"
Inflector::toUuid("Mustermann, Max "); // "c47276d9-be31-5329-40d9-25fc290609ec"
Inflector::toUuid("first name last name"); // "7f9f995d-6b94-460e-0158-edd97a8b016a"
```

## Changing the Inflector implementation
As stated above you can change the actual implementation of the inflector by providing
a adapter class for the inflector you would like to use the most (pull requests welcome!).

Writing an adapter is easy, create a new class:
```php
namespace YourVendor\YourNamespace;
use Neunerlei\Inflection\Adapter\InflectorAdapterInterface;

class MyInflectorAdapter implements InflectorAdapterInterface{
    public function toSingular(string $pluralWord) : string{
        // Your fancy inflector does it's job here...
    }

    public function toPlural(string $singularWord) : string{
      // Your fancy inflector does it's job here...
    }
}
```

After writing your adapter simply register it like:
```php
namespace YourVendor\YourNamespace;
use Neunerlei\Inflection\Inflector;

// If your adapter does not need any dependencies
Inflector::$inflectorAdapterClass = MyInflectorAdapter::class;

// If you have to instantiate your adapter first
Inflector::setInflectorAdapter(new MyInflectorAdapter());
```

After that the toSingular() and toPlural() methods will be resolved using your own adapter implementation.

## Running tests

- Clone the repository
- Install the dependencies with ```composer install```
- Run the tests with ```composer test```

## Postcardware
You're free to use this package, but if it makes it to your production environment I highly appreciate you sending me a postcard from your hometown, mentioning which of our package(s) you are using.

You can find my address [here](https://www.neunerlei.eu/). 

Thank you :D 
