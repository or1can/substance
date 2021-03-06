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
use Substance\Core\Presentation\Elements\FileStyle;
use Substance\Core\Presentation\Elements\Form;
use Substance\Core\Presentation\Elements\Hidden;
use Substance\Core\Presentation\Elements\ImageButton;
use Substance\Core\Presentation\Elements\InlineStyle;
use Substance\Core\Presentation\Elements\Item;
use Substance\Core\Presentation\Elements\MachineName;
use Substance\Core\Presentation\Elements\Markup;
use Substance\Core\Presentation\Elements\Page;
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
use Substance\Core\Presentation\ElementAttribute;
use Substance\Core\Presentation\ElementAttributes;

/**
 * A renderer renders elements.
 */
class HTMLTheme extends AbstractTheme {

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
    return '<div><input type="checkbox" value="' . $checkbox->getValue() . '" /></div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderCheckboxes()
   */
  public function renderCheckboxes( Checkboxes $checkboxes ) {
    return '<div><input type="checkbox" value="' . $checkboxes->getValue() . '" /></div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\AbstractTheme::renderContainer()
   */
  public function renderContainer( Container $container ) {
    $output = '<div>';
    $output .= parent::renderContainer( $container );
    return $output . '</div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderDate()
   */
  public function renderDate( Date $date ) {
    return '<div><input type="text" value="' . $date->getValue() . '" /></div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderElementAttribute()
   */
  public function renderElementAttribute( ElementAttribute $element_attribute ) {
    return $element_attribute->getName() . '="' . $element_attribute->getValue() . '"';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderElementAttributes()
   */
  public function renderElementAttributes( ElementAttributes $element_attributes ) {
    $output = array();
    foreach ( $element_attributes->getAttributes() as $element_attribute ) {
      $output[] = $this->renderElementAttribute( $element_attribute );
    }
    if ( count( $output ) == 0 ) {
      return '';
    } else {
      return ' ' . implode( ' ', $output );
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderFieldset()
   */
  public function renderFieldset( Fieldset $fieldset ) {
    $output = '<fieldset>';
    $output .= parent::renderContainer( $fieldset );
    return $output . '</fieldset>';
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
    $output = '<link type="text/css" rel="stylesheet" media="';
    $output .= $file_style->getMediaType();
    $output .= '" href="';
    $output .= $file_style->getFile();
    $output .= '" />';
    return $output;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderForm()
   */
  public function renderForm( Form $form ) {
    return '<form>' . parent::renderContainer( $form ) . '</form>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderHidden()
   */
  public function renderHidden( Hidden $hidden ) {
    return '<div><input type="hidden" value="' . $hidden->getValue() . '" /></div>';
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
    $output = '<style type="text/css" media="';
    $output .= $inline_style->getMediaType();
    $output .= '">';
    $output .= $inline_style->getData();
    $output .= '</style>';
    return $output;
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
    return '<div><input type="text" value="' . $machine_name->getValue() . '" /></div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\AbstractTheme::renderPage()
   */
  public function renderPage( Page $page ) {
    $output = '<!DOCTYPE html>';
    $output .= PHP_EOL;
    $output .= '<html>';
    $output .= PHP_EOL;
    $output .= '<head>';
    $output .= PHP_EOL;
    $output .= '    <title>' . $page->getTitle() . '</title>' . PHP_EOL;
    foreach ( $page->getStyles() as $style ) {
      $output .= $style->render( $this );
    }
    $output .= '</head>';
    $output .= PHP_EOL;
    $output .= '<body>';
    // TODO - title
    // TODO - scripts
    // TODO - general headers
    // TODO - body content
    // TODO - i18n/language
    $output .= parent::renderContainer( $page );
    return $output . '</body></html>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderPassword()
   */
  public function renderPassword( Password $password ) {
    return '<div><input type="text" value="' . $password->getValue() . '" /></div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderPasswordConfirm()
   */
  public function renderPasswordConfirm( PasswordConfirm $password_confirm ) {
    return '<div><input type="text" value="' . $password_confirm->getValue() . '" /><input type="text" value="' . $password_confirm->getValue() . '" /></div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderRadio()
   */
  public function renderRadio( Radio $radio ) {
    return '<div><input type="radio" value="' . $radio->getValue() . '" /></div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderRadios()
   */
  public function renderRadios( Radios $radios ) {
    return '<div><input type="radio" value="' . $radios->getValue() . '" /></div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderSelect()
   */
  public function renderSelect( Select $select ) {
    return '<div><select><option selected="" value="' . $select->getValue() . '" /></select></div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderSubmit()
   */
  public function renderSubmit( Submit $submit ) {
    return '<div><input type="submit" value="' . $submit->getValue() . '" /></div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderStyle()
   */
  public function renderStyle( Style $style ) {
    $output = '<link type="text/css" rel="stylesheet" media="';
    $output .= $style->getMediaType();
    $output .= ' href=""';
    $output .= '" />';
    return $output;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTable()
   */
  public function renderTable( Table $table ) {
    return '<table' . $table->renderAttributes( $this ) . '>' . parent::renderContainer( $table ) . '</table>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTableCell()
   */
  public function renderTableCell( TableCell $table_cell ) {
    return '<td>' . parent::renderContainer( $table_cell ) . '</td>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTableRow()
   */
  public function renderTableRow( TableRow $table_row ) {
    return '<tr>' . parent::renderContainer( $table_row ) . '</tr>';
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
    return '<div><textarea>' . $textarea->getValue() . '</textarea><div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTextField()
   */
  public function renderTextField( TextField $textfield ) {
    return '<div><input type="text" value="' . $textfield->getValue() . '" /></div>';
  }

}
