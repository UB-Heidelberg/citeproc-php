<?php
/*
 * This file is a part of HDS (HeBIS Discovery System). HDS is an 
 * extension of the open source library search engine VuFind, that 
 * allows users to search and browse beyond resources. More 
 * Information about VuFind you will find on http://www.vufind.org
 * 
 * Copyright (C) 2016 
 * HeBIS Verbundzentrale des HeBIS-Verbundes 
 * Goethe-Universität Frankfurt / Goethe University of Frankfurt
 * http://www.hebis.de
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace Seboettg\CiteProc\Rendering;


use Seboettg\CiteProc\CiteProc;
use Seboettg\CiteProc\Context;
use Seboettg\CiteProc\Locale\Locale;
use Seboettg\CiteProc\Rendering\Name\Names;

class NamesTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $context = new Context();
        $context->setLocale(new Locale("de-DE"));
        CiteProc::setContext($context);
    }

    public function testRenderSingleEditor()
    {
        $data = "{\"author\": [{\"dropping-particle\": \"\", \"family\": \"Einstein\", \"given\": \"Albert\", \"non-dropping-particle\": \"\", \"static-ordering\": false}],\"editor\": [{\"dropping-particle\": \"de\", \"family\": \"Doe\", \"given\": \"John\", \"non-dropping-particle\": \"la\", \"static-ordering\": false}], \"id\": \"ITEM-1\", \"title\": \"His Anonymous Life\", \"type\": \"book\"}";

        $xml = "<names variable=\"editor\" delimiter=\", \"><name and=\"symbol\" initialize-with=\". \" delimiter=\", \"/><label form=\"short\" prefix=\", \" text-case=\"title\"/></names>";

        $names = new Names(new \SimpleXMLElement($xml));
        $ret = $names->render(json_decode($data));
        $this->assertEquals("John la Doe, Hrsg.", $ret);

    }

    public function testRenderSingleAuthorAndSingleEditor()
    {
        $data = "{\"author\": [{\"dropping-particle\": \"\", \"family\": \"Einstein\", \"given\": \"Albert\", \"non-dropping-particle\": \"\", \"static-ordering\": false}],\"editor\": [{\"dropping-particle\": \"de\", \"family\": \"Doe\", \"given\": \"John\", \"non-dropping-particle\": \"la\", \"static-ordering\": false}], \"id\": \"ITEM-1\", \"title\": \"His Anonymous Life\", \"type\": \"book\"}";

        $xml = "<group>
                    <names variable=\"author\" delimiter=\";\" suffix=\" in: \">
                        <name form=\"long\" name-as-sort-order=\"all\" sort-separator=\", \"/>
                    </names>
                    <text variable=\"title\" text-case=\"title\" suffix=\", \"/>
                    <names variable=\"editor\" delimiter=\", \" prefix=\"(\" suffix=\")\">
                        <name and=\"symbol\" initialize-with=\". \" delimiter=\", \"/>
                        <label form=\"short\" prefix=\", \" text-case=\"title\"/>
                    </names>
                </group>";

        $names = new Group(new \SimpleXMLElement($xml));
        $ret = $names->render(json_decode($data));
        $this->assertEquals("Einstein, Albert in: His Anonymous Life, (John la Doe, Hrsg.)", $ret);

    }

    public function testRenderMultipleAuthors()
    {
        $data = "{\"author\": [{\"dropping-particle\": \"de\", \"family\": \"Doe\", \"given\": \"John\", \"non-dropping-particle\": \"la\", \"static-ordering\": false}, {\"dropping-particle\": \"\", \"family\": \"Doe\", \"given\": \"Jane\", \"non-dropping-particle\": \"\", \"static-ordering\": false}], \"id\": \"ITEM-1\", \"title\": \"Her Anonymous Life\", \"type\": \"book\"}";

        $xml = "<names variable=\"author\" delimiter=\", \" prefix=\" (\" suffix=\")\"><name and=\"symbol\" initialize-with=\". \" delimiter=\", \"/><label form=\"short\" prefix=\", \" text-case=\"title\"/></names>";

        $names = new Names(new \SimpleXMLElement($xml));

        $ret = $names->render(json_decode($data));
        $x = "foo";
    }
}