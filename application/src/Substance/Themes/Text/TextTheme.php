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

namespace Substance\Themes\Text;

use Substance\Core\Presentation\AbstractTheme;
use Substance\Core\Presentation\Elements\Actions;
use Substance\Core\Presentation\Elements\Button;
use Substance\Core\Presentation\Elements\Checkbox;
use Substance\Core\Presentation\Elements\Checkboxes;
use Substance\Core\Presentation\Elements\Container;
use Substance\Core\Presentation\Elements\Date;
use Substance\Core\Presentation\Elements\Fieldset;
use Substance\Core\Presentation\Elements\File;
use Substance\Core\Presentation\Elements\FileStyle;
use Substance\Core\Presentation\Elements\Form;
use Substance\Core\Presentation\Elements\Hidden;
use Substance\Core\Presentation\Elements\ImageButton;
use Substance\Core\Presentation\Elements\InlineStyle;
use Substance\Core\Presentation\Elements\Item;
use Substance\Core\Presentation\Elements\MachineName;
use Substance\Core\Presentation\Elements\Markup;
use Substance\Core\Presentation\Elements\Password;
use Substance\Core\Presentation\Elements\PasswordConfirm;
use Substance\Core\Presentation\Elements\Radio;
use Substance\Core\Presentation\Elements\Radios;
use Substance\Core\Presentation\Elements\Select;
use Substance\Core\Presentation\Elements\Style;
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
class TextTheme extends AbstractTheme {

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderButton()
   */
  public function renderButton( Button $button ) {
    // TODO
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderCheckbox()
   */
  public function renderCheckbox( Checkbox $checkbox ) {
    return $checkbox->getValue();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderCheckboxes()
   */
  public function renderCheckboxes( Checkboxes $checkboxes ) {
    return $checkboxes->getValue();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\AbstractTheme::renderContainer()
   */
  public function renderContainer( Container $container ) {
    $output = '';
    $output .= PHP_EOL;
    foreach ( $container->getElements() as $element ) {
      $output .= $this->render( $element );
      $output .= PHP_EOL;
    }
    return $output;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderDate()
   */
  public function renderDate( Date $date ) {
    return $date->getValue();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderFieldset()
   */
  public function renderFieldset( Fieldset $fieldset ) {
    $output = '';
    foreach ( $fieldset->getElements() as $element ) {
      $output .= $this->render( $element );
      $output .= PHP_EOL;
    }
    return $output;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderFile()
   */
  public function renderFile( File $file ) {
    // TODO
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderFileStyle()
   */
  public function renderFileStyle( FileStyle $file_style ) {
    // TODO
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderForm()
   */
  public function renderForm( Form $form ) {
    return $this->renderContainer( $form );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderHidden()
   */
  public function renderHidden( Hidden $hidden ) {
    return $hidden->getValue();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderImageButton()
   */
  public function renderImageButton( ImageButton $image_button ) {
    // TODO
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderInlineStyle()
   */
  public function renderInlineStyle( InlineStyle $inline_style ) {
    // TODO
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderItem()
   */
  public function renderItem( Item $item ) {
    // TODO
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderMachineName()
   */
  public function renderMachineName( MachineName $machine_name ) {
    return $machine_name->getValue();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderPassword()
   */
  public function renderPassword( Password $password ) {
    return $password->getValue();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderPasswordConfirm()
   */
  public function renderPasswordConfirm( PasswordConfirm $password_confirm ) {
    return $password_confirm->getValue();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderRadio()
   */
  public function renderRadio( Radio $radio ) {
    return $radio->getValue();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderRadios()
   */
  public function renderRadios( Radios $radios ) {
    return $radios->getValue();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderSelect()
   */
  public function renderSelect( Select $select ) {
    return $select->getValue();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderStyle()
   */
  public function renderStyle( Style $style ) {
    // TODO
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderSubmit()
   */
  public function renderSubmit( Submit $submit ) {
    // TODO
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTable()
   */
  public function renderTable( Table $table ) {
    // TODO - Rendering a table like a container will work for the time being.
    return $this->renderContainer( $table );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTableCell()
   */
  public function renderTableCell( TableCell $table_cell ) {
    // Keep it simple and render each item with nothing inbetween
    $output = '';
    foreach ( $table_cell->getElements() as $element ) {
      $output .= $this->render( $element );
    }
    return $output;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTableRow()
   */
  public function renderTableRow( TableRow $table_row ) {
    // Keep it simple and render each cell with a tab inbetween
    $output = array();
    foreach ( $table_row->getElements() as $element ) {
      $output[] = $this->render( $element );
    }
    return implode( ' : ', $output );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTableSelect()
   */
  public function renderTableSelect( TableSelect $table_select ) {
    // TODO
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTextArea()
   */
  public function renderTextArea( TextArea $textarea ) {
    return $textarea->getValue();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTextField()
   */
  public function renderTextField( TextField $textfield ) {
    return $textfield->getValue();
  }

}
