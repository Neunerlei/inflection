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

namespace Neunerlei\Inflection\Adapter;


use Symfony\Component\Inflector\Inflector;

class SymfonyInflectorAdapter implements InflectorAdapterInterface {
	
	/**
	 * @inheritDoc
	 */
	public function toSingular(string $pluralWord): string {
		$result = Inflector::singularize($pluralWord);
		if (is_array($result)) return reset($result);
		return (string)$result;
	}
	
	/**
	 * @inheritDoc
	 */
	public function toPlural(string $singularWord): string {
		$result = Inflector::pluralize($singularWord);
		if (is_array($result)) return reset($result);
		return (string)$result;
	}
	
}