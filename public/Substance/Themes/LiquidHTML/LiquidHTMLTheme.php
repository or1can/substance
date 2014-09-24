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

namespace Substance\Themes\LiquidHTML;

use Liquid\Liquid;
use Liquid\Template;
use Substance\Core\Environment\Environment;
use Substance\Core\Presentation\Elements\Page;
use Substance\Themes\HTML\HTMLTheme;

/**
 * A renderer renders elements.
 */
class LiquidHTMLTheme extends HTMLTheme {

  protected $path;

  public function __construct() {
    Liquid::set( 'INCLUDE_ALLOW_EXT', TRUE );
    Liquid::set( 'INCLUDE_PREFIX', '' );
    Liquid::set( 'INCLUDE_SUFFIX', 'tpl' );
    $this->path = __DIR__ . DIRECTORY_SEPARATOR . 'templates';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\AbstractTheme::renderPage()
   */
  public function renderPage( Page $page ) {
    $styles = '';
    foreach ( $page->getStyles() as $style ) {
      $styles .= $style->render( $this );
    }
    $template = new Template();
    $template->parse( file_get_contents( $this->path . DIRECTORY_SEPARATOR . 'page.tpl' ) );
    return $template->render(array(
      'body'=> parent::renderContainer( $page ),
      'style'=> $styles,
    ));
  }

}
