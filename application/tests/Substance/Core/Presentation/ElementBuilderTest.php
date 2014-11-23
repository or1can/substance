<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2014 Kevin Rogers
 *
 * Substance is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Substance is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Substance.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Substance\Core\Presentation;

use Substance\Core\Presentation\Elements\TableCell;
use Substance\Themes\Text\TextTheme;
use Substance\Themes\HTML\HTMLTheme;

class ElementBuilderTest extends \PHPUnit_Framework_TestCase {

  protected $html_theme;

  protected $text_theme;

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    $this->html_theme = new HTMLTheme();
    $this->text_theme = new TextTheme();
  }

  /**
   * Test building a table cell.
   */
  public function testBuildTableCell() {
    $tablecell = TableCell::build(array(
      '#type' => 'Substance\Core\Presentation\Elements\TableCell',
      '#elements' => 'stuff',
    ));

    // Check the element types.
    $this->assertInstanceOf( 'Substance\Core\Presentation\Elements\TableCell', $tablecell );
    $this->assertInstanceOf( 'Substance\Core\Presentation\ElementAttributes', $tablecell->getAttributes() );
    $elements = $tablecell->getElements();
    $this->assertCount( 1, $elements );
    $element = array_shift( $elements );
    $this->assertInstanceOf( 'Substance\Core\Presentation\Elements\Markup', $element );
    // Render the table cell in a text theme and check the output.
    $rendered = $tablecell->render( $this->text_theme );
    $this->assertEquals( 'stuff', $rendered );
    // Render the table cell in an HTML theme and check the output.
    $rendered = $tablecell->render( $this->html_theme );
    $this->assertEquals( '<td>stuff</td>', $rendered );
  }

  /**
   * Test building a table row.
   */
  public function testBuildTableRow() {
    $tablerow = ElementBuilder::build(array(
      '#type' => 'Substance\Core\Presentation\Elements\TableRow',
      '#row' => array( 'stuff', 'morestuff' ),
    ));

    // Check the element types.
    $this->assertInstanceOf( 'Substance\Core\Presentation\Elements\TableRow', $tablerow );
    $this->assertInstanceOf( 'Substance\Core\Presentation\ElementAttributes', $tablerow->getAttributes() );
    $elements = $tablerow->getElements();
    $this->assertCount( 2, $elements );
    $cell = array_shift( $elements );
    $this->assertInstanceOf( 'Substance\Core\Presentation\Elements\TableCell', $cell );
    $cell = array_shift( $elements );
    $this->assertInstanceOf( 'Substance\Core\Presentation\Elements\TableCell', $cell );
    // Render the table cell in a text theme and check the output.
    $rendered = $tablerow->render( $this->text_theme );
    $this->assertEquals( 'stuff : morestuff', $rendered );
    // Render the table cell in an HTML theme and check the output.
    $rendered = $tablerow->render( $this->html_theme );
    $this->assertEquals( '<tr><td>stuff</td><td>morestuff</td></tr>', $rendered );
  }

  /**
   * Test building a table.
   */
  public function testBuildTable() {
    $table = ElementBuilder::build(array(
      '#type' => 'Substance\Core\Presentation\Elements\Table',
      '#rows' => array(
        array( 'stuff', 'morestuff' ),
        array( 'stuff2', 'morestuff2' )
      ),
    ));

    // Check the element types.
    $this->assertInstanceOf( 'Substance\Core\Presentation\Elements\Table', $table );
    $this->assertInstanceOf( 'Substance\Core\Presentation\ElementAttributes', $table->getAttributes() );
    $elements = $table->getElements();
    $this->assertCount( 2, $elements );
    $element = array_shift( $elements );
    $this->assertInstanceOf( 'Substance\Core\Presentation\Elements\TableRow', $element );
    $element = array_shift( $elements );
    $this->assertInstanceOf( 'Substance\Core\Presentation\Elements\TableRow', $element );
    // Render the table cell in a text theme and check the output.
    $rendered = $table->render( $this->text_theme );
    $this->assertEquals( "\nstuff : morestuff\nstuff2 : morestuff2\n", $rendered );
    // Render the table cell in an HTML theme and check the output.
    $rendered = $table->render( $this->html_theme );
    $this->assertEquals( '<table><tr><td>stuff</td><td>morestuff</td></tr><tr><td>stuff2</td><td>morestuff2</td></tr></table>', $rendered );
  }

}
