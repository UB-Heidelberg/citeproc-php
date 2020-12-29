<?php
/*
 * citeproc-php
 *
 * @link        http://github.com/seboettg/citeproc-php for the source repository
 * @copyright   Copyright (c) 2016 Sebastian Böttger.
 * @license     https://opensource.org/licenses/MIT
 */

namespace Seboettg\CiteProc\Style\Sort;

use Seboettg\CiteProc\CiteProc;
use Seboettg\CiteProc\Util\Variables;
use SimpleXMLElement;

/**
 * Class Key
 *
 * The cs:sort element must contain one or more cs:key child elements. The sort key, set as an attribute on cs:key, must
 * be a variable (see Appendix IV - Variables) or macro name. For each cs:key element, the sort direction can be set to
 * either “ascending” (default) or “descending” with the sort attribute. The attributes names-min, names-use-first, and
 * names-use-last may be used to override the values of the corresponding et-al-min/et-al-subsequent-min,
 * et-al-use-first/et-al-subsequent-use-first and et-al-use-last attributes, and affect all names generated via macros
 * called by cs:key.
 *
 * @package Seboettg\CiteProc\Style\Sort
 *
 * @author Sebastian Böttger <seboettg@gmail.com>
 */
class Key implements SortKey
{
    /**
     * variable name or macro
     * @var string
     */
    private $variable;

    /**
     * the sort direction can be set to either “ascending” (default) or “descending” with the sort attribute
     * @var string
     */
    private $sort = "ascending";

    /**
     * macro name
     * @var string
     */
    private $macro;

    /**
     * only relevant for date ranges
     * @var int
     */
    private $rangePart = 1;

    public static function factory(SimpleXMLElement $node)
    {
        $variable = $macro = null;
        $sort = "ascending";
        /** @var SimpleXMLElement $attribute */
        foreach ($node->attributes() as $attribute) {
            $name = $attribute->getName();
            if ($name === "variable") {
                $variable = (string) $attribute;
            }
            if ($name === "sort") {
                $sort = (string) $attribute;
            }
            if ($name === "macro") {
                $variable = "macro";
                $macro = (string) $attribute;
            }
        }
        return new self($variable, $macro, $sort);
    }

    /**
     * Key constructor.
     * The cs:sort element must contain one or more cs:key child elements. The sort key, set as an attribute on cs:key,
     * must be a variable (see Appendix IV - Variables) or macro name. For each cs:key element, the sort direction can
     * be set to either “ascending” (default) or “descending” with the sort attribute.
     *
     * TODO: The attributes names-min, names-use-first, and names-use-last may be used to override the values of the
     * corresponding et-al-min/et-al-subsequent-min, et-al-use-first/et-al-subsequent-use-first and et-al-use-last
     * attributes, and affect all names generated via macros called by cs:key.
     *
     * @param string|null $variable
     * @param string|null $macro
     * @param string|null $sort
     */
    public function __construct(?string $variable, ?string $macro, ?string $sort)
    {
        $this->variable = $variable;
        $this->macro = $macro;
        $this->sort = $sort;
    }

    /**
     * @return string
     */
    public function getVariable()
    {
        return $this->variable;
    }

    /**
     * @return string (ascending|descending)
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @return string
     */
    public function getMacro()
    {
        return $this->macro;
    }

    /**
     * @return bool
     */
    public function isNameVariable()
    {
        return Variables::isNameVariable($this->variable);
    }

    /**
     * @return bool
     */
    public function isNumberVariable()
    {
        return Variables::isNumberVariable($this->variable);
    }

    /**
     * @return bool
     */
    public function isDateVariable()
    {
        return Variables::isDateVariable($this->variable);
    }

    /**
     * @return bool
     */
    public function isMacro()
    {
        return $this->variable === "macro" && !empty(CiteProc::getContext()->getMacro($this->macro));
    }

    /**
     * @param $rangePart
     */
    public function setRangePart($rangePart)
    {
        $this->rangePart = $rangePart;
    }

    /**
     * @return int
     */
    public function getRangePart()
    {
        return $this->rangePart;
    }
}
