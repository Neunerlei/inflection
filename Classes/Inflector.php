<?php
/**
 * Copyright 2020 Martin Neundorfer (Neunerlei)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Last modified: 2020.03.11 at 14:40
 */

declare(strict_types=1);

namespace Neunerlei\Inflection;

use Neunerlei\Inflection\Adapter\InflectorAdapterInterface;
use Neunerlei\Inflection\Adapter\SymfonyInflectorAdapter;

class Inflector
{
    
    /**
     * Default map of accented and special characters to ASCII characters
     *
     * @var array
     */
    protected const TRANSLITERATIONS
        = [
            '/À|Á|Â|Ã|Å|Ǻ|Ā|Ă|Ą|Ǎ/' => 'A',
            '/Æ|Ǽ/' => 'AE',
            '/Ä/' => 'Ae',
            '/Ç|Ć|Ĉ|Ċ|Č/' => 'C',
            '/Ð|Ď|Đ/' => 'D',
            '/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě/' => 'E',
            '/Ĝ|Ğ|Ġ|Ģ|Ґ/' => 'G',
            '/Ĥ|Ħ/' => 'H',
            '/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|І/' => 'I',
            '/Ĳ/' => 'IJ',
            '/Ĵ/' => 'J',
            '/Ķ/' => 'K',
            '/Ĺ|Ļ|Ľ|Ŀ|Ł/' => 'L',
            '/Ñ|Ń|Ņ|Ň/' => 'N',
            '/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ/' => 'O',
            '/Œ/' => 'OE',
            '/Ö/' => 'Oe',
            '/Ŕ|Ŗ|Ř/' => 'R',
            '/Ś|Ŝ|Ş|Ș|Š/' => 'S',
            '/ẞ/' => 'SS',
            '/Ţ|Ț|Ť|Ŧ/' => 'T',
            '/Þ/' => 'TH',
            '/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ/' => 'U',
            '/Ü/' => 'Ue',
            '/Ŵ/' => 'W',
            '/Ý|Ÿ|Ŷ/' => 'Y',
            '/Є/' => 'Ye',
            '/Ї/' => 'Yi',
            '/Ź|Ż|Ž/' => 'Z',
            '/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª/' => 'a',
            '/ä|æ|ǽ/' => 'ae',
            '/ç|ć|ĉ|ċ|č/' => 'c',
            '/ð|ď|đ/' => 'd',
            '/è|é|ê|ë|ē|ĕ|ė|ę|ě/' => 'e',
            '/ƒ/' => 'f',
            '/ĝ|ğ|ġ|ģ|ґ/' => 'g',
            '/ĥ|ħ/' => 'h',
            '/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı|і/' => 'i',
            '/ĳ/' => 'ij',
            '/ĵ/' => 'j',
            '/ķ/' => 'k',
            '/ĺ|ļ|ľ|ŀ|ł/' => 'l',
            '/ñ|ń|ņ|ň|ŉ/' => 'n',
            '/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º/' => 'o',
            '/ö|œ/' => 'oe',
            '/ŕ|ŗ|ř/' => 'r',
            '/ś|ŝ|ş|ș|š|ſ/' => 's',
            '/ß/' => 'ss',
            '/ţ|ț|ť|ŧ/' => 't',
            '/þ/' => 'th',
            '/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ/' => 'u',
            '/ü/' => 'ue',
            '/ŵ/' => 'w',
            '/ý|ÿ|ŷ/' => 'y',
            '/є/' => 'ye',
            '/ї/' => 'yi',
            '/ź|ż|ž/' => 'z',
        ];
    
    /**
     * A list of filenames we know and recognize as such
     */
    protected const FILE_EXTENSIONS = '|3dm|3ds|3g2|3gp|7z|accdb|ai|aif|apk|app|asf|asp|aspx|avi|bak|bat|bin|bmp|c|cab|cbr|cer|cfg|cfm|cgi|class|com|cpl|cpp|crdownload|crx|cs|csr|css|csv|cue|cur|dat|db|dbf|dds|deb|dem|deskthemepack|dll|dmg|dmp|doc|docx|drv|dtd|dwg|dxf|eps|exe|fla|flv|fnt|fon|gadget|gam|ged|gif|gpx|gz|h|hqx|htm|html|icns|ico|ics|iff|indd|ini|iso|jar|java|jpg|jpeg|js|jsp|json|key|keychain|kml|kmz|lnk|log|lua|m|m3u|m4a|m4v|max|mdb|mdf|mid|mim|mov|mp3|mp4|mpa|mpg|msg|msi|nes|obj|odt|otf|pages|part|pct|pdb|pdf|php|pkg|pl|plugin|png|pps|ppt|pptx|prf|ps|psd|pspimage|py|rar|rm|rom|rpm|rss|rtf|sav|sdf|sh|sitx|sln|sql|srt|svg|swf|swift|sys|tar|tar.gz|tax2016|tex|tga|thm|tif|tiff|tmp|toast|torrent|ttf|txt|typescript|ts|typoscript|uue|vb|vcd|vcf|vcxproj|vob|wav|wma|wmv|wpd|wps|wsf|xcodeproj|xhtml|xlr|xls|xlsx|xml|yuv|zip|zipx|';
    
    /**
     * The class that is used as a default inflector adapter
     *
     * @var string
     */
    public static string $inflectorAdapterClass = SymfonyInflectorAdapter::class;
    
    /**
     * The instance of the inflector adapter after it was instantiated
     *
     * @var InflectorAdapterInterface
     */
    protected static InflectorAdapterInterface $inflectorAdapter;
    
    /**
     * Can be used to inject a custom inflector adapter if you don't want to use the symfony inflector
     *
     * @param InflectorAdapterInterface $adapter
     */
    public static function setInflectorAdapter(InflectorAdapterInterface $adapter): void
    {
        static::$inflectorAdapter = $adapter;
    }
    
    /**
     * Returns the singular form of a word
     *
     * @param   string  $pluralWord  A word in plural form
     *
     * @return string The singular form of the given word
     */
    public static function toSingular(string $pluralWord): string
    {
        return static::getConcreteInflector()->toSingular($pluralWord);
    }
    
    /**
     * Returns the plural form of a word
     *
     * @param   string  $singularWord  A word in singular form
     *
     * @return string The plural form of the given word
     */
    public static function toPlural(string $singularWord): string
    {
        return static::getConcreteInflector()->toPlural($singularWord);
    }
    
    /**
     * Converts a "Given string" to "given-string" or
     * "another.String-you wouldWant" to "another-string-you-wouldwant".
     * But in addition to that, it will convert "Annahäuser_Römertopf.jpg" into "annahaeuser-roemertopf-jpg"
     *
     * NOTE: Yes, this is a shameless copy of
     * http://book.cakephp.org/2.0/en/core-utility-libraries/inflector.html#Inflector::slug
     *
     * @param   string  $string  The string to inflect
     *
     * @return string
     */
    public static function toSlug(string $string): string
    {
        $map = self::TRANSLITERATIONS + [
                "/[^\s\p{Zs}\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu" => ' ',
                "/[\s\p{Zs}]+/mu" => '-',
                sprintf('/^[%s]+|[%s]+$/', "\\-", "\\-") => '',
            ];
        
        return strtolower((string)preg_replace(array_keys($map), $map, $string));
    }
    
    /**
     * Similar to toSlug() but is able to detect file extensions, and a path
     * segment which will both be ignored while converting the file into a sluggified version.
     *
     * @param   string  $string      The string to inflect
     * @param   bool    $expectPath  True to expect an filepath with only the basename to inflect.
     *
     * @return string
     */
    public static function toFile(string $string, bool $expectPath = false): string
    {
        // Handle file extension
        $ext = pathinfo($string, PATHINFO_EXTENSION);
        if (! empty($ext) && stripos(self::FILE_EXTENSIONS, $ext) !== false) {
            $ext = '.' . $ext;
            $string = trim(substr($string, 0, -strlen($ext)));
        } else {
            $ext = '';
        }
        
        // Handle filepath if required
        $path = '';
        if ($expectPath) {
            $string = str_replace(["\\", '/'], DIRECTORY_SEPARATOR, $string);
            $parts = explode(DIRECTORY_SEPARATOR, $string);
            $string = (string)array_pop($parts);
            
            if (! empty($parts)) {
                $parts[] = '';
            }
            
            $path = implode(DIRECTORY_SEPARATOR, $parts);
            $string = basename($string);
        }
        
        return $path . strtolower(static::toSlug($string)) . $ext;
    }
    
    /**
     * Converts a "Given string" to ["given", "string"] or
     * "another.String-you wouldWant" to ["another", "string", "you", "would", "want"].
     *
     * @param   string  $string                The string to inflect
     * @param   bool    $intelligentSplitting  The default splitter is rather dumb when it comes to edge cases like IP,
     *                                         URL, and so on, because it will split them like I, P and U, R, L but stuff
     *                                         like HandMeAMango on the other hand will be correctly splitted like: hand,
     *                                         me, a, mango. If you set this to true, those edge cases will be handled.
     *                                         Problems might occur when stuff like "ThisIsFAQandMore" is given, because
     *                                         the camelCase is broken the result will be: "this is fa qand more".
     *
     * @return array
     */
    public static function toArray(string $string, bool $intelligentSplitting = false): array
    {
        // Build pattern
        $separatorPattern = '[\-]+|[_]+|[.]+|[\s]+';
        $pattern = '/(?=[A-Z])|' . $separatorPattern . '/';
        
        // Handle intelligent splitting
        if ($intelligentSplitting) {
            // Precompile
            // This ensures that words like "FOO_BAR" or "foo-BAR" and alike will be split intelligently into
            // ["foo", "bar"]
            $string = preg_replace_callback(
                '/(^|' . $separatorPattern . ')([A-Z]+)(' . $separatorPattern . '|$)/',
                static function ($v) {
                    return $v[1] . strtolower($v[2]) . $v[3];
                }, $string);
            
            // This replaces everything that's in upper case with itself but with a space in front.
            // If there is more than a single char, like in ThisIsAGreatWord it will strip the
            // G (in Great) off and push it into the next word. The result will be: this is a great word.
            // Words like FAQ will be kept together if given alone.
            // Problems might occur when stuff like "ThisIsFAQandMore" is given,
            // because the camelCase is broken. The result will be: "this is fa qand more".
            $offset = 0;
            $stringLength = strlen($string);
            $string = preg_replace_callback("/([A-Z]+)|(?P<DOT>[\s\S])/",
                static function ($v) use (&$offset, $stringLength) {
                    $a = $v[0];
                    
                    // We need this workaround, as there is no other way of determining the offset in a php regex...
                    $wordLength = strlen($a);
                    $offset += $wordLength;
                    
                    if (isset($v['DOT'])) {
                        return $a;
                    }
                    
                    $nextWord = '';
                    if ($wordLength > 1 && $offset !== $stringLength) {
                        $nextWord = ' ' . substr($a, -1);
                        $a = substr($a, 0, -1);
                    }
                    
                    return strtolower(' ' . $a . $nextWord);
                }, $string);
        }
        
        // Do the split
        $parts = preg_split($pattern, $string, -1, PREG_SPLIT_NO_EMPTY);
        
        return array_map('strtolower', $parts);
    }
    
    /**
     * Converts a "Given string" to "Given String" or
     * "another.String-you wouldWant" to "Another String You Would Want".
     *
     * @param   string  $string                The string to inflect
     * @param   bool    $intelligentSplitting  -> See Inflector::toArray() $intelligentSplitting for details
     *
     * @return string
     * @see Inflector::toArray
     */
    public static function toSpacedUpper(string $string, bool $intelligentSplitting = false): string
    {
        return implode(' ', array_map('ucfirst', static::toArray($string, $intelligentSplitting)));
    }
    
    /**
     * Alias of toSpacedUpper
     *
     * @param   string  $string                The string to inflect
     * @param   bool    $intelligentSplitting  -> See Inflector::toArray() $intelligentSplitting for details
     *
     * @return string
     * @see Inflector::toArray
     */
    public static function toHuman(string $string, bool $intelligentSplitting = false): string
    {
        return static::toSpacedUpper($string, $intelligentSplitting);
    }
    
    /**
     * Converts a "Given string" to "GivenString" or
     * "another.String-you wouldWant" to "AnotherStringYouWouldWant".
     *
     * @param   string  $string                The string to inflect
     * @param   bool    $intelligentSplitting  -> See Inflector::toArray() $intelligentSplitting for details
     *
     * @return string
     * @see Inflector::toArray
     */
    public static function toCamelCase(string $string, bool $intelligentSplitting = false): string
    {
        return implode(array_map('ucfirst', static::toArray($string, $intelligentSplitting)));
    }
    
    /**
     * Converts a "Given string" to "givenString" or
     * "another.String-you wouldWant" to "anotherStringYouWouldWant".
     *
     * @param   string  $string                The string to inflect
     * @param   bool    $intelligentSplitting  -> See Inflector::toArray() $intelligentSplitting for details
     *
     * @return string
     * @see Inflector::toArray
     */
    public static function toCamelBack(string $string, bool $intelligentSplitting = false): string
    {
        return lcfirst(static::toCamelCase($string, $intelligentSplitting));
    }
    
    /**
     * Converts a "Given string" to "given-string" or
     * "another.String-you wouldWant" to "another-string-you-would-want".
     *
     * @param   string  $string                The string to inflect
     * @param   bool    $intelligentSplitting  -> See Inflector::toArray() $intelligentSplitting for details
     *
     * @return string
     * @see Inflector::toArray
     */
    public static function toDashed(string $string, bool $intelligentSplitting = false): string
    {
        return implode('-', static::toArray($string, $intelligentSplitting));
    }
    
    /**
     * Converts a "Given string" to "given_string" or
     * "another.String-you wouldWant" to "another_string_you_would_want".
     *
     * @param   string  $string                The string to inflect
     * @param   bool    $intelligentSplitting  -> See Inflector::toArray() $intelligentSplitting for details
     *
     * @return string
     * @see Inflector::toArray
     */
    public static function toUnderscore(string $string, bool $intelligentSplitting = false): string
    {
        return implode('_', static::toArray($string, $intelligentSplitting));
    }
    
    /**
     * Alias of toUnderscore();
     *
     * @param   string  $string                The string to inflect
     * @param   bool    $intelligentSplitting  -> See Inflector::toArray() $intelligentSplitting for details
     *
     * @return string
     * @see Inflector::toArray
     */
    public static function toDatabase(string $string, bool $intelligentSplitting = false): string
    {
        return static::toUnderscore($string, $intelligentSplitting);
    }
    
    /**
     * Converts a "Given string" to "getGivenString" or "another.String-you wouldWant" to
     * "getAnotherStringYouWouldWant". It allows you to add a custom prefix other than "get" using the second
     * parameter. It will also sanitize the incoming string so if "getMyProperty" is given as string you will still end
     * up with "getMyProperty" instead of "getGetMyProperty" which would not make much sense. The sanitation will
     * remove the following prefixes "is", "has", "get" and "set".
     *
     * @param   string       $string   The string to inflect
     * @param   string|null  $prefix   By default "get", could be "is" or "has" if it is required
     * @param   array        $options  Additional processing options
     *                                 - noSanitizing|ns (bool) FALSE: If TRUE the incoming string will not be sanitized
     *                                 - intelligentSplitting|is (bool) FALSE: See Inflector::toArray()
     *                                 $intelligentSplitting for details
     *
     * @return string
     */
    public static function toGetter(string $string, ?string $prefix = null, array $options = []): string
    {
        // Only apply if the variable does not contain the prefix already
        $cc = static::toCamelCase(...static::applyToGetterSetterPropOptions($string, $options));
        
        return (is_null($prefix) ? 'get' : $prefix) . $cc;
    }
    
    /**
     * Converts a "Given string" to "setGivenString" or
     * "another.String-you wouldWant" to "setAnotherStringYouWouldWant".
     *
     * Sanitizes the input string like toGetter() does.
     *
     * @param   string  $string   The string to inflect
     * @param   array   $options  Additional processing options
     *                            - noSanitizing|ns (bool) FALSE: If TRUE the incoming string will not be sanitized
     *                            - intelligentSplitting|is (bool) FALSE: See Inflector::toArray() $intelligentSplitting for
     *                            details
     *
     * @return string
     */
    public static function toSetter(string $string, array $options = []): string
    {
        return 'set' . static::toCamelCase(...static::applyToGetterSetterPropOptions($string, $options));
    }
    
    
    /**
     * This is in general an alias of toCamelBack(); But it will also strip away has/get/is/set prefixes from the given
     * value before the camel back version is generated.
     *
     * @param   string  $string   The string to inflect
     * @param   array   $options  Additional processing options
     *                            - noSanitizing|ns (bool) FALSE: If TRUE the incoming string will not be sanitized
     *                            - intelligentSplitting|is (bool) FALSE: See Inflector::toArray() $intelligentSplitting
     *                            for details
     *
     * @return string
     */
    public static function toProperty(string $string, array $options = []): string
    {
        return static::toCamelBack(...static::applyToGetterSetterPropOptions($string, $options));
    }
    
    /**
     * This method will convert the given string by unifying it. Unify means, it makes it comparable with other
     * strings, by removing all special characters, converting everything to lowercase, counting all words and the
     * number of their occurrence (optional) and sorting them alphabetically. This also means, that the text will no
     * longer make sense for humans, but is easy to use for search and comparison actions.
     *
     * @param   string  $string                     The string to unify
     * @param   bool    $appendNumberOfOccurrences  By default words have the number of their occurrence added to the
     *                                              result. For example "the white fox and the hen" => "and1 fox1 hen1 the2
     *                                              white1" If you don"t want that quantification you can disable it using
     *                                              this option. The result would then be: "and fox hen the white"
     *
     *
     * @return string
     */
    public static function toComparable(string $string, bool $appendNumberOfOccurrences = true): string
    {
        $parts = array_count_values(static::toArray(static::toFile($string)));
        
        if ($appendNumberOfOccurrences) {
            array_walk($parts, static function (&$v, $k) {
                $v = $k . $v;
            });
        } else {
            $parts = array_keys($parts);
        }
        
        sort($parts);
        
        return implode(' ', $parts);
    }
    
    /**
     * Converts any given string into a UUID like: 123e4567-e89b-12d3-a456-426655440000.
     * This is useful if you want to create, unique id's but want to combine multiple strings with different word order.
     *
     * Note that "ASDF QWER" will result in the same ID as "QWER ASDF", because
     * the values will be sorted alphabetically before the id is created.
     * This makes sorting via first name and last name a lot easier.
     *
     * @param   string  $string  The string to convert to uuid
     *
     * @return string
     */
    public static function toUuid(string $string): string
    {
        $string = md5(static::toComparable($string));
        
        return substr($string, 0, 8) . '-' .
               substr($string, 8, 4) . '-' .
               substr($string, 12, 4) . '-' .
               substr($string, 16, 4) . '-' .
               substr($string, 20);
    }
    
    /**
     * Internal helper to apply the recurring options for toGetter(), toSetter() and toProperty()
     *
     * @param   string  $string
     * @param   array   $options
     *
     * @return array
     */
    protected static function applyToGetterSetterPropOptions(string $string, array $options): array
    {
        return [
            isset($options['noSanitizing'])
            || in_array('noSanitizing', $options, true)
            || in_array('ns', $options, true) ? $string : static::sanitizeGetterAndSetterPrefix($string),
            
            isset($options['intelligentSplitting'])
            || in_array('intelligentSplitting', $options, true)
            || in_array('is', $options, true),
        ];
    }
    
    /**
     * Helper to remove words like set / get / is or has from the beginning of the string
     * to sanitize optional "setters", "getters" and "properties"
     *
     * @param   string  $string  The string to inflect
     *
     * @return string
     */
    protected static function sanitizeGetterAndSetterPrefix(string $string): string
    {
        return (string)preg_replace('/^(set|get|is|has)([^a-zöäüß])/u', '$2', trim($string));
    }
    
    /**
     * Internal method to get or retrieve the correct, inflector adapter instance.
     * This allows us to use every inflector on the market if the correct adapter exists for it.
     * It also allows lacy loading, so that the inflector is only instantiated when there are really values to
     * singularize and pluralize.
     *
     * @return InflectorAdapterInterface
     * @throws InvalidInflectorAdapterException
     */
    protected static function getConcreteInflector(): InflectorAdapterInterface
    {
        if (isset(static::$inflectorAdapter)
            && get_class(static::$inflectorAdapter) === static::$inflectorAdapterClass) {
            return static::$inflectorAdapter;
        }
        
        if (! class_exists(static::$inflectorAdapterClass)) {
            throw new InvalidInflectorAdapterException(
                'The inflector adapter class: ' . static::$inflectorAdapterClass . ' does not exist!');
        }
        
        $adapter = new static::$inflectorAdapterClass();
        if (! $adapter instanceof InflectorAdapterInterface) {
            throw new InvalidInflectorAdapterException(
                'The inflector adapter class: ' . static::$inflectorAdapterClass .
                ' does not implement the required interface: ' . InflectorAdapterInterface::class);
        }
        
        return static::$inflectorAdapter = $adapter;
    }
}