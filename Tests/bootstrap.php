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
 * Last modified: 2020.03.11 at 15:09
 */

declare(strict_types=1);

namespace Neunerlei\Inflection\Assets;

use Neunerlei\Inflection\Adapter\InflectorAdapterInterface;
use Neunerlei\Inflection\Inflector;

class DummyInflectorAdapter implements InflectorAdapterInterface
{
    public function toSingular(string $pluralWord): string
    {
        return "__SINGULAR__";
    }
    
    public function toPlural(string $singularWord): string
    {
        return "__PLURAL__";
    }
}

class DummyInflectorBridge extends Inflector
{
    public static function getInflectorAdapterInstance()
    {
        return Inflector::getConcreteInflector();
    }
}