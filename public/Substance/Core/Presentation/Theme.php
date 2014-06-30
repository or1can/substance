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

use Substance\Core\Alert\Alert;
use Substance\Core\Presentation\Elements\TextField;
use Substance\Core\Presentation\Elements\Container;

/**
 * A theme is responsible for rendering elements with a particular appearance.
 */
interface Theme {

  /**
   * Returns a new instance of this Theme.
   *
   * @return self
   */
  public static function create();

  /**
   * Renders the specified Element.
   *
   * This method allows the Theme control over how each Element is rendered.
   * The default themes ask each Element how to render itself, and each Element
   * calls back to a Theme method for that specific type of Element.
   *
   * A Theme could take control over this process by examining the Element and
   * choosing different Theme methods, e.g.
   *
   * switch ( get_class( $element ) ) {
   *   case 'Substance\Core\Presentation\Elements\TextField':
   *     // Theme a TextField as a Container.
   *     return $this->renderContainer( $element );
   *     break;
   *   default:
   *     // Theme other Elements using their default behaviour.
   *     return $element->render( $this );
   *     break;
   * }
   *
   * @return Element the Element to render.
   * @return string the rendered Element.
   */
  public function render( Element $element );

  /**
   * Render the specified Presentable object.
   *
   * @param Presentable $presentable the Presentable to render.
   * @return string the rendered Presentable.
   */
  public function renderPresentable( Presentable $presentable );

  /**
   * Render the specified Container object.
   *
   * @param Container $container the Container to render.
   * @return string the rendered Container.
   */
  public function renderContainer( Container $container );

    /**
   * Render the specified TextField object.
   *
   * @param TextField $textfield the TextField to render.
   * @return string the rendered TextField.
   */
  public function renderTextField( TextField $textfield );

}
