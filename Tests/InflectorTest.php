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
 * Last modified: 2020.03.11 at 15:08
 */

declare(strict_types=1);

namespace Neunerlei\Inflection\Tests;


use Neunerlei\Inflection\Adapter\InflectorAdapterInterface;
use Neunerlei\Inflection\Adapter\SymfonyInflectorAdapter;
use Neunerlei\Inflection\Assets\DummyInflectorAdapter;
use Neunerlei\Inflection\Assets\DummyInflectorBridge;
use Neunerlei\Inflection\Inflector;
use PHPUnit\Framework\TestCase;

class InflectorTest extends TestCase
{
    
    public function testAdapterCreation(): void
    {
        // Test default adapter creation and singleton handling
        $adapter = DummyInflectorBridge::getInflectorAdapterInstance();
        $this->assertInstanceOf(InflectorAdapterInterface::class, $adapter);
        $this->assertInstanceOf(SymfonyInflectorAdapter::class, $adapter);
        $this->assertSame($adapter, DummyInflectorBridge::getInflectorAdapterInstance());
        
        // Test if a changed class name forces a recreation of the adapter
        Inflector::$inflectorAdapterClass = DummyInflectorAdapter::class;
        $newAdapter = DummyInflectorBridge::getInflectorAdapterInstance();
        $this->assertInstanceOf(InflectorAdapterInterface::class, $newAdapter);
        $this->assertInstanceOf(DummyInflectorAdapter::class, $newAdapter);
        $this->assertNotSame($adapter, $newAdapter);
        
        // Test if the class can be restored
        Inflector::$inflectorAdapterClass = SymfonyInflectorAdapter::class;
        $adapter = DummyInflectorBridge::getInflectorAdapterInstance();
        $this->assertInstanceOf(InflectorAdapterInterface::class, $adapter);
        $this->assertInstanceOf(SymfonyInflectorAdapter::class, $adapter);
        
        // Test if the adapter can be set by injecting a instance
        $newAdapter = new DummyInflectorAdapter();
        Inflector::setInflectorAdapter($newAdapter);
        $this->assertInstanceOf(InflectorAdapterInterface::class, $newAdapter);
        $this->assertInstanceOf(DummyInflectorAdapter::class, $newAdapter);
        $this->assertNotSame($newAdapter, DummyInflectorBridge::getInflectorAdapterInstance());
        
        // Reset one last time
        Inflector::$inflectorAdapterClass = SymfonyInflectorAdapter::class;
    }
    
    public function _testSymfonyToSingularProvider(): array
    {
        return [
            ['accesses', 'access'],
            ['addresses', 'address'],
            ['agendas', 'agenda'],
            
            // Array results
            ['criteria', 'criterion'],
            ['batches', 'batch'],
            ['appendices', 'appendex'],
        ];
    }
    
    /**
     * @param $plural
     * @param $expect
     *
     * @dataProvider _testSymfonyToSingularProvider
     */
    public function testSymfonyToSingular($plural, $expect): void
    {
        $this->assertEquals($expect, Inflector::toSingular($plural));
    }
    
    public function _testSymfonyToPluralDataProvider(): array
    {
        return [
            ['access', 'accesses'],
            ['address', 'addresses'],
            ['agenda', 'agendas'],
            ['alumnus', 'alumni'],
            ['hippopotamus', 'hippopotami'],
            
            // Array results
            ['matrix', 'matricies'],
            ['person', 'persons'],
            ['thief', 'thiefs'],
        ];
    }
    
    /**
     * @param $singular
     * @param $expect
     *
     * @dataProvider _testSymfonyToPluralDataProvider
     */
    public function testSymfonyToPlural($singular, $expect): void
    {
        $this->assertEquals($expect, Inflector::toPlural($singular));
    }
    
    public function _testToSlugDataProvider(): array
    {
        return [
            ['given-string', 'Given string'],
            ['another-string-you-wouldwant', 'another.String-you wouldWant'],
            ['annahaeuser-roemertopf-jpg', 'Annahäuser_Römertopf.jpg'],
            ['la-langue-francaise-est-un-attribut-de-souverainete-en-france', 'La langue française est un attribut de souveraineté en France'],
            ['exciting-stuff-what-was-that', '!@$#exciting stuff! - what !@-# was that?'],
            ['20-of-profits-went-to-me', '20% of profits went to me!'],
            ['の話が出たので大丈夫かなあと', 'の話が出たので大丈夫かなあと'],
        ];
    }
    
    /**
     * @param $a
     * @param $b
     *
     * @dataProvider _testToSlugDataProvider
     */
    public function testToSlug($a, $b): void
    {
        $this->assertEquals($a, Inflector::toSlug($b));
    }
    
    public function _testToFileDataProvider(): array
    {
        return [
            ['given-string.jpg', 'Given string.jpg'],
            ['another-string-you-wouldwant.tiff', 'another.String-you wouldWant .tiff'],
            ['annahaeuser-roemertopf.jpg', 'Annahäuser_Römertopf.jpg'],
            ['la-langue-francaise-est-un-attribut-de-souverainete-en-france.doc', 'La langue française est un attribut de souveraineté en France.doc'],
            ['/foo/bar/exciting-stuff-what-was-that.json', '/foo/bar/!@$#exciting stuff! - what !@-# was that?.json', true],
            ['/foo/bar20-of-profits-went-to-me.bmp', '/foo/bar20% of profits went to me!.bmp', true],
            ['の話が出-たので大丈夫かな-あと', 'の話が出/たので大丈夫かな.あと'],
            ['/の話が出/たので大丈夫かな-あと', '/の話が出/たので大丈夫かな.あと', true],
        ];
    }
    
    /**
     * @param         $a
     * @param         $b
     * @param   bool  $c
     *
     * @dataProvider _testToFileDataProvider
     */
    public function testToFile($a, $b, bool $c = false): void
    {
        $this->assertEquals($a, Inflector::toFile($b, $c));
    }
    
    public function _testToArrayDataProvider(): array
    {
        return [
            [['given', 'string'], ' Given   string   '],
            [['given', 'string'], 'Given string'],
            [['given', 'string', 'jpg'], 'given-string.jpg'],
            [['given', 'string', 'jpg'], 'Given string.jpg'],
            [['another', 'string', 'you', 'would', 'want'], 'another.String-you wouldWant'],
            [['!@$#exciting', 'stuff!', 'what', '!@', '#', 'was', 'that?'], '!@$#exciting stuff! - what !@-# was that?'],
            [['の話が出たので大丈夫かなあと'], 'の話が出たので大丈夫かなあと'],
            [['hello', 'w', 'o', 'r', 'l', 'd'], 'HelloWORLD'],
            [['f', 'a', 'q'], 'FAQ'],
            [['hello', 'world'], 'HelloWORLD', true],
            [['hello', 'world'], 'Hello WORLD', true],
            [['faq'], 'FAQ', true],
            [['this', 'is', 'faq', 'and', 'more'], 'ThisIsFAQAndMore', true],
            [['this', 'is', 'fa', 'qand', 'more'], 'ThisIsFAQandMore', true] // Broken camel case
        ];
    }
    
    /**
     * @param         $a
     * @param         $b
     * @param   bool  $c
     *
     * @dataProvider _testToArrayDataProvider
     */
    public function testToArray($a, $b, bool $c = false): void
    {
        $this->assertEquals($a, Inflector::toArray($b, $c));
    }
    
    public function _testToSpacedUpperDataProvider(): array
    {
        return [
            ['Given String', 'Given string'],
            ['Given String Jpg', 'given-string.jpg'],
            ['Given String Jpg', 'Given string.jpg'],
            ['Another String You Would Want', 'another.String-you wouldWant'],
            ['の話が出たので大丈夫かなあと', 'の話が出たので大丈夫かなあと'],
            
            ['Hello World', 'HelloWORLD', true],
            ['Hello World', 'Hello WORLD', true],
            ['Faq', 'FAQ', true],
            ['This Is Faq And More', 'ThisIsFAQAndMore', true],
            ['This Is Fa Qand More', 'ThisIsFAQandMore', true] // Broken camel case
        ];
    }
    
    /**
     * @param         $a
     * @param         $b
     * @param   bool  $c
     *
     * @dataProvider _testToSpacedUpperDataProvider
     */
    public function testToSpacedUpper($a, $b, bool $c = false): void
    {
        $this->assertEquals($a, Inflector::toSpacedUpper($b, $c));
        $this->assertEquals($a, Inflector::toHuman($b, $c));
    }
    
    public function _testToCamelCaseDataProvider(): array
    {
        return [
            ['GivenString', 'Given string'],
            ['GivenStringJpg', 'given-string.jpg'],
            ['GivenStringJpg', 'Given string.jpg'],
            ['AnotherStringYouWouldWant', 'another.String-you wouldWant'],
            ['の話が出たので大丈夫かなあと', 'の話が出たので大丈夫かなあと'],
            
            ['HelloWorld', 'HelloWORLD', true],
            ['HelloWorld', 'Hello WORLD', true],
            ['Faq', 'FAQ', true],
            ['ThisIsFaqAndMore', 'ThisIsFAQAndMore', true],
            ['ThisIsFaQandMore', 'ThisIsFAQandMore', true] // Broken camel case
        ];
    }
    
    /**
     * @param         $a
     * @param         $b
     * @param   bool  $c
     *
     * @dataProvider _testToCamelCaseDataProvider
     */
    public function testToCamelCase($a, $b, bool $c = false): void
    {
        $this->assertEquals($a, Inflector::toCamelCase($b, $c));
    }
    
    public function _testToCamelBackDataProvider(): array
    {
        return [
            ['givenString', 'Given string'],
            ['givenStringJpg', 'given-string.jpg'],
            ['givenStringJpg', 'Given string.jpg'],
            ['anotherStringYouWouldWant', 'another.String-you wouldWant'],
            ['の話が出たので大丈夫かなあと', 'の話が出たので大丈夫かなあと'],
            
            ['helloWorld', 'HelloWORLD', true],
            ['helloWorld', 'Hello WORLD', true],
            ['faq', 'FAQ', true],
            ['thisIsFaqAndMore', 'ThisIsFAQAndMore', true],
            ['thisIsFaQandMore', 'ThisIsFAQandMore', true] // Broken camel case
        ];
    }
    
    /**
     * @param         $a
     * @param         $b
     * @param   bool  $c
     *
     * @dataProvider _testToCamelBackDataProvider
     */
    public function testToCamelBack($a, $b, bool $c = false): void
    {
        $this->assertEquals($a, Inflector::toCamelBack($b, $c));
    }
    
    public function _testToDashedDataProvider(): array
    {
        return [
            ['given-string', 'Given string'],
            ['given-string-jpg', 'given-string.jpg'],
            ['given-string-jpg', 'Given string.jpg'],
            ['another-string-you-would-want', 'another.String-you wouldWant'],
            ['の話が出たので大丈夫かなあと', 'の話が出たので大丈夫かなあと'],
            
            ['hello-world', 'HelloWORLD', true],
            ['hello-world', 'Hello WORLD', true],
            ['faq', 'FAQ', true],
            ['this-is-faq-and-more', 'ThisIsFAQAndMore', true],
            ['this-is-fa-qand-more', 'ThisIsFAQandMore', true] // Broken camel case
        ];
    }
    
    /**
     * @param         $a
     * @param         $b
     * @param   bool  $c
     *
     * @dataProvider _testToDashedDataProvider
     */
    public function testToDashed($a, $b, bool $c = false): void
    {
        $this->assertEquals($a, Inflector::toDashed($b, $c));
    }
    
    public function _testToUnderscoreDataProvider(): array
    {
        return [
            ['given_string', 'Given string'],
            ['given_string_jpg', 'given-string.jpg'],
            ['given_string_jpg', 'Given string.jpg'],
            ['another_string_you_would_want', 'another.String-you wouldWant'],
            ['の話が出たので大丈夫かなあと', 'の話が出たので大丈夫かなあと'],
            
            ['hello_world', 'HelloWORLD', true],
            ['hello_world', 'Hello WORLD', true],
            ['faq', 'FAQ', true],
            ['this_is_faq_and_more', 'ThisIsFAQAndMore', true],
            ['this_is_fa_qand_more', 'ThisIsFAQandMore', true] // Broken camel case
        ];
    }
    
    /**
     * @param         $a
     * @param         $b
     * @param   bool  $c
     *
     * @dataProvider _testToUnderscoreDataProvider
     */
    public function testToUnderscore($a, $b, bool $c = false): void
    {
        $this->assertEquals($a, Inflector::toUnderscore($b, $c));
        $this->assertEquals($a, Inflector::toDatabase($b, $c));
    }
    
    public function _testToGetterDataProvider(): array
    {
        return [
            ['getMyProperty', 'myProperty'],
            ['getMyProperty', 'hasMyProperty'],
            ['getMyProperty', ' hasMyProperty '],
            ['getMyProperty', ' has.MyProperty '],
            ['getMyProperty', ' has myProperty '],
            ['getMyProperty', 'isMyProperty'],
            ['getMyProperty', 'setMyProperty'],
            ['getIssetProperty', 'issetProperty'],
            ['isMyProperty', 'myProperty', 'is'],
            
            ['getGetMyProperty', 'get my property', 'get', ['noSanitizing']],
            ['hasFaq', 'FAQ', 'has', ['intelligentSplitting']],
            ['hasFaq', 'FAQ', 'has', ['is']],
            ['hasFAQ', 'FAQ', 'has'],
            ['FAQ', 'FAQ', ''],
        ];
    }
    
    /**
     * @param           $a
     * @param           $b
     * @param   string  $c
     * @param   array   $d
     *
     * @dataProvider _testToGetterDataProvider
     */
    public function testToGetter($a, $b, string $c = 'get', array $d = []): void
    {
        $this->assertEquals($a, Inflector::toGetter($b, $c, $d));
    }
    
    public function _testToSetterDataProvider(): array
    {
        return [
            ['setMyProperty', 'myProperty'],
            ['setMyProperty', 'hasMyProperty'],
            ['setMyProperty', ' hasMyProperty '],
            ['setMyProperty', ' has.MyProperty '],
            ['setMyProperty', ' has myProperty '],
            ['setMyProperty', 'isMyProperty'],
            ['setMyProperty', 'setMyProperty'],
            ['setIssetProperty', 'issetProperty'],
            
            ['setMyProperty', 'get my property'],
            ['setGetMyProperty', 'get my property', ['noSanitizing']],
            ['setGetMyProperty', 'get my property', ['ns']],
            ['setFaq', 'FAQ', ['intelligentSplitting']],
            ['setFaq', 'FAQ', ['is']],
            ['setFAQ', 'FAQ'],
        ];
    }
    
    /**
     * @param          $a
     * @param          $b
     * @param   array  $c
     *
     * @dataProvider _testToSetterDataProvider
     */
    public function testToSetter($a, $b, array $c = []): void
    {
        $this->assertEquals($a, Inflector::toSetter($b, $c));
    }
    
    public function _testToPropertyDataProvider(): array
    {
        return [
            ['myProperty', 'myProperty'],
            ['myProperty', 'hasMyProperty'],
            ['myProperty', ' hasMyProperty '],
            ['myProperty', ' has.MyProperty '],
            ['myProperty', ' has myProperty '],
            ['myProperty', 'isMyProperty'],
            ['myProperty', 'setMyProperty'],
            ['issetProperty', 'issetProperty'],
            
            ['myProperty', 'get my property'],
            ['getMyProperty', 'get my property', ['noSanitizing']],
            ['getMyProperty', 'get my property', ['ns']],
            ['faq', 'FAQ', ['intelligentSplitting']],
            ['faq', 'FAQ', ['is']],
            ['fAQ', 'FAQ'],
        ];
    }
    
    /**
     * @param          $a
     * @param          $b
     * @param   array  $c
     *
     * @dataProvider _testToPropertyDataProvider
     */
    public function testToProperty($a, $b, array $c = []): void
    {
        $this->assertEquals($a, Inflector::toProperty($b, $c));
    }
    
    public function _testToComparableDataProvider(): array
    {
        return [
            ['first1 last1 name2', 'first name last name '],
            ['first last name', 'first name last name ', false],
            ['max1 mustermann1', 'max mustermann'],
            ['max1 mustermann1', 'Mustermann, Max '],
            ['max mustermann', 'max mustermann', false],
            [
                'adipiscing1 amet1 consectetur1 do1 dolor1 eiusmod1 elit1 incididunt1 ipsum1 labore1 lorem1 sed1 sit1 tempor1 ut1',
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore',
            ],
            [
                'adipiscing amet consectetur do dolor eiusmod elit incididunt ipsum labore lorem sed sit tempor ut',
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore',
                false,
            ],
            [
                'blindtexte1 den1 der1 die1 fern1 hinten1 hinter1 konsonantien1 laender1 leben1 und1 vokalien1 weit1 wortbergen1',
                'Weit hinten, hinter den Wortbergen, fern der Länder Vokalien und Konsonantien leben die Blindtexte',
            ],
            [
                'blindtexte1 den1 der1 die1 fern1 hinten1 hinter1 konsonantien1 laender1 leben1 und1 vokalien1 weit1 wortbergen1',
                'Fern, weit hinten, hinter den Wortbergen, der Länder Vokalien und Konsonantien leben die Blindtexte',
            ],
            [
                'blindtexte1 den2 der1 die1 fern1 hinten1 hinter2 konsonantien1 laender1 leben1 und1 vokalien1 weit1 wortbergen1',
                'Weit hinten, hinter den Konsonantien leben die Blindtexte. Hinter den Wortbergen, fern der Länder Vokalien und ',
            ],
        ];
    }
    
    /**
     * @param         $a
     * @param         $b
     * @param   bool  $c
     *
     * @dataProvider _testToComparableDataProvider
     */
    public function testToComparable($a, $b, bool $c = true): void
    {
        $this->assertEquals($a, Inflector::toComparable($b, $c));
    }
    
    
    public function _testToUuidDataProvider(): array
    {
        return [
            ['7f9f995d-6b94-460e-0158-edd97a8b016a', 'first name last name '],
            ['7f9f995d-6b94-460e-0158-edd97a8b016a', 'first name last name '],
            ['c47276d9-be31-5329-40d9-25fc290609ec', 'max mustermann'],
            ['c47276d9-be31-5329-40d9-25fc290609ec', 'mustermann, max '],
            ['c47276d9-be31-5329-40d9-25fc290609ec', 'max mustermann'],
            [
                '6140505b-14bf-c388-c3e5-c400ce11b26c',
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore',
            ],
            [
                '6140505b-14bf-c388-c3e5-c400ce11b26c',
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore',
            ],
            [
                '867e6598-22ac-b7a0-91a9-d1a67e21b251',
                'Weit hinten, hinter den Wortbergen, fern der Länder Vokalien und Konsonantien leben die Blindtexte',
            ],
            [
                '867e6598-22ac-b7a0-91a9-d1a67e21b251',
                'Fern, weit hinten, hinter den Wortbergen, der Länder Vokalien und Konsonantien leben die Blindtexte',
            ],
            [
                'd0a3d3f6-8134-825f-1c60-eac5b7f737c1',
                'Weit hinten, hinter den Konsonantien leben die Blindtexte. Hinter den Wortbergen, fern der Länder Vokalien und ',
            ],
        ];
    }
    
    /**
     * @param         $a
     * @param         $b
     *
     * @dataProvider _testToUuidDataProvider
     */
    public function testToUuid($a, $b): void
    {
        $this->assertEquals($a, Inflector::toUuid($b));
    }
}