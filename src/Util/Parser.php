<?php
declare(strict_types=1);
/*
 * citeproc-php
 *
 * @link        http://github.com/seboettg/citeproc-php for the source repository
 * @copyright   Copyright (c) 2020 Sebastian Böttger.
 * @license     https://opensource.org/licenses/MIT
 */

namespace Seboettg\CiteProc\Util;

use Seboettg\CiteProc\CiteProc;
use Seboettg\CiteProc\Exception\CiteProcException;
use Seboettg\CiteProc\Exception\InvalidStylesheetException;
use Seboettg\CiteProc\Config;
use Seboettg\CiteProc\Locale\Locale;
use Seboettg\CiteProc\Root\Info;
use Seboettg\CiteProc\Root\Root;
use Seboettg\CiteProc\Style\Bibliography;
use Seboettg\CiteProc\Style\Citation;
use Seboettg\CiteProc\Style\Macro;
use Seboettg\CiteProc\Style\Options\GlobalOptions;
use Seboettg\CiteProc\StyleSheet;
use SimpleXMLElement;

class Parser
{
    /**
     * @param SimpleXMLElement $styleSheet
     * @throws CiteProcException
     * @throws InvalidStylesheetException
     */
    public function parseStylesheet(SimpleXMLElement $styleSheet)
    {
        $root = new Root();
        $root->initInheritableNameAttributes($styleSheet);
        CiteProc::getContext()->setRoot($root);
        $globalOptions = new GlobalOptions($styleSheet);
        CiteProc::getContext()->setGlobalOptions($globalOptions);

        foreach ($styleSheet as $node) {
            $name = $node->getName();
            switch ($name) {
                case 'info':
                    CiteProc::getContext()->setInfo(new Info($node));
                    break;
                case 'locale':
                    CiteProc::getContext()->getLocale()->addXml($node);
                    break;
                case 'macro':
                    $macro = new Macro($node, $root);
                    CiteProc::getContext()->addMacro($macro->getName(), $macro);
                    break;
                case 'bibliography':
                    $bibliography = new Bibliography($node, $root);
                    CiteProc::getContext()->setBibliography($bibliography);
                    break;
                case 'citation':
                    $citation = new Citation($node, $root);
                    CiteProc::getContext()->setCitation($citation);
                    break;
            }
        }
    }

    /**
     * @param Config\Locale $locale
     * @throws CiteProcException
     */
    public function parseLocale(Config\Locale $locale)
    {
        CiteProc::getContext()->setLocale(new Locale($locale)); //parse locale
    }
}
