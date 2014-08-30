<?php

namespace Liquid\Tag;

use Liquid\Liquid;
use Liquid\Context;
use Liquid\LiquidException;
use Liquid\BlankFileSystem;
use Liquid\Regexp;

/**
 * Used to decrement a counter into a template
 *
 * Example:
 *
 *     {% decrement value %}
 *
 * @author Viorel Dram
 */
class TagDecrement extends AbstractTag
{
	/**
	 * Name of the variable to decrement
	 *
	 * @var int
	 */
	private $toDecrement;

	/**
	 * Constructor
	 *
	 * @param string $markup
	 * @param array $tokens
	 * @param BlankFileSystem $fileSystem
	 *
	 * @throws \Liquid\LiquidException
	 */
	public function __construct($markup, array $tokens, $fileSystem) {
		$syntax = new Regexp("/(" . Liquid::LIQUID_ALLOWED_VARIABLE_CHARS . "+)/");

		if ($syntax->match($markup)) {
			$this->toDecrement = $syntax->matches[0];
		} else {
			throw new LiquidException("Syntax Error in 'decrement' - Valid syntax: decrement [var]");
		}
	}

	/**
	 * Renders the tag
	 *
	 * @param Context $context
	 *
	 * @return string|void
	 */
	public function render(Context $context) {
		// if the value is not set in the environment check to see if it
		// exists in the context, and if not set it to 0
		if (!isset($context->environments[0][$this->toDecrement])) {
			// check for a context value
			$fromContext = $context->get($this->toDecrement);

			// we already have a value in the context
			$context->environments[0][$this->toDecrement] = (null !== $fromContext) ? $fromContext : 0;
		}

		// decrement the environment value
		$context->environments[0][$this->toDecrement]--;

		return '';
	}
}
