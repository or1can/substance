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

namespace Substance\Themes\HTML;

use Substance\Core\Presentation\AbstractTheme;
use Substance\Core\Presentation\Elements\Actions;
use Substance\Core\Presentation\Elements\Button;
use Substance\Core\Presentation\Elements\Checkbox;
use Substance\Core\Presentation\Elements\Checkboxes;
use Substance\Core\Presentation\Elements\Container;
use Substance\Core\Presentation\Elements\Date;
use Substance\Core\Presentation\Elements\Fieldset;
use Substance\Core\Presentation\Elements\File;
use Substance\Core\Presentation\Elements\Form;
use Substance\Core\Presentation\Elements\Hidden;
use Substance\Core\Presentation\Elements\ImageButton;
use Substance\Core\Presentation\Elements\Item;
use Substance\Core\Presentation\Elements\MachineName;
use Substance\Core\Presentation\Elements\Markup;
use Substance\Core\Presentation\Elements\Password;
use Substance\Core\Presentation\Elements\PasswordConfirm;
use Substance\Core\Presentation\Elements\Radio;
use Substance\Core\Presentation\Elements\Radios;
use Substance\Core\Presentation\Elements\Select;
use Substance\Core\Presentation\Elements\Submit;
use Substance\Core\Presentation\Elements\Table;
use Substance\Core\Presentation\Elements\TableCell;
use Substance\Core\Presentation\Elements\TableRow;
use Substance\Core\Presentation\Elements\TableSelect;
use Substance\Core\Presentation\Elements\TextArea;
use Substance\Core\Presentation\Elements\TextField;
use Substance\Core\Presentation\Elements\Token;
use Substance\Core\Presentation\Elements\Weight;

/**
 * A renderer renders elements.
 */
class HTMLTheme extends AbstractTheme {

  /**
   * @return self
   */
  public static function create() {
    return new HTMLTheme();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderActions()
   */
  public function renderActions( Actions $actions ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderButton()
   */
  public function renderButton( Button $button ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderCheckbox()
   */
  public function renderCheckbox( Checkbox $checkbox ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderCheckboxes()
   */
  public function renderCheckboxes( Checkboxes $checkboxes ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\AbstractTheme::renderContainer()
   */
  public function renderContainer( Container $container ) {
    $output = '<div>';
    foreach ( $container->getElements() as $element ) {
      $output .= $this->render( $element );
    }
    return $output . '</div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderDate()
   */
  public function renderDate( Date $date ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderFieldset()
   */
  public function renderFieldset( Fieldset $fieldset ) {
    $output = '<fieldset>';
    foreach ( $fieldset->getElements() as $element ) {
      $output .= $this->render( $element );
    }
    return $output . '</fieldset>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderFile()
   */
  public function renderFile( File $file ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderForm()
   */
  public function renderForm( Form $form ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderHidden()
   */
  public function renderHidden( Hidden $hidden ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderImageButton()
   */
  public function renderImageButton( ImageButton $image_button ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderItem()
   */
  public function renderItem( Item $item ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderMachineName()
   */
  public function renderMachineName( MachineName $machine_name ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderPassword()
   */
  public function renderPassword( Password $password ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderPasswordConfirm()
   */
  public function renderPasswordConfirm( PasswordConfirm $password_confirm ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderRadio()
   */
  public function renderRadio( Radio $radio ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderRadios()
   */
  public function renderRadios( Radios $radios ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderSelect()
   */
  public function renderSelect( Select $select ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderSubmit()
   */
  public function renderSubmit( Submit $submit ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTable()
   */
  public function renderTable( Table $table ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTableCell()
   */
  public function renderTableCell( TableCell $table_cell ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTableRow()
   */
  public function renderTableRow( TableRow $table_row ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTableSelect()
   */
  public function renderTableSelect( TableSelect $table_select ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTextArea()
   */
  public function renderTextArea( TextArea $textarea ) {
    return '<textarea>' . $textarea->getValue() . '</textarea>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTextField()
   */
  public function renderTextField( TextField $textfield ) {
    return '<div><input type="text" value="' . $textfield->getValue() . '" /></div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderToken()
   */
  public function renderToken( Token $token ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderWeight()
   */
  public function renderWeight( Weight $weight ) {

  }

}
