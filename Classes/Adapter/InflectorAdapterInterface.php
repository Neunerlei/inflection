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
 * Last modified: 2020.03.11 at 14:39
 */

declare(strict_types=1);

namespace Neunerlei\Inflection\Adapter;


interface InflectorAdapterInterface
{
    
    /**
     * Returns the singular form of a word
     *
     * @param   string  $pluralWord  A word in plural form
     *
     * @return string The singular form of the given word
     */
    public function toSingular(string $pluralWord): string;
    
    /**
     * Returns the plural form of a word
     *
     * @param   string  $singularWord  A word in singular form
     *
     * @return string The plural form of the given word
     */
    public function toPlural(string $singularWord): string;
}