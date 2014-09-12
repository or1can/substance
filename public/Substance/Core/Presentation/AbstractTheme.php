<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2005 - 2014 Kevin Rogers
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

use Substance\Core\Presentation\Elements\Actions;
use Substance\Core\Presentation\Elements\Container;
use Substance\Core\Presentation\Elements\Markup;
use Substance\Core\Presentation\Elements\Page;
use Substance\Core\Presentation\Elements\Token;
use Substance\Core\Presentation\Elements\Weight;

/**
 * Abstract implementation of Theme, to simplify Theme developement.
 */
abstract class AbstractTheme implements Theme {

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::render()
   */
  public function render( Element $element ) {
    return $element->render( $this );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderActions()
   */
  public function renderActions( Actions $actions ) {
    // Actions are just a special kind of container, so render it the same.
    return $this->renderContainer( $actions );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderContainer()
   */
  public function renderContainer( Container $container ) {
    $output = '';
    foreach ( $container->getElements() as $element ) {
      $output .= $this->render( $element );
    }
    return $output;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderMarkup()
   */
  public function renderMarkup( Markup $markup ) {
    return $markup->getMarkup();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderPage()
   */
  public function renderPage( Page $page ) {
    // Pages are just a special kind of container, so render it the same.
    return $this->renderContainer( $page );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderPresentable()
   */
  public function renderPresentable( Presentable $presentable ) {
    return $this->render( $presentable->present() );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderToken()
   */
  public function renderToken( Token $token ) {
    // A Token is just a special kind of hidden field, so render it the same.
    return $this->renderHidden( $token );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderWeight()
   */
  public function renderWeight( Weight $weight ) {
    // A Weight is just a special kind of select field, so render it the same.
    return $this->renderSelect( $weight );
  }

}
