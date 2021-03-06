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

use Substance\Core\Alert\Alert;
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
   * Render the specified Actions object.
   *
   * @param Actions $actions the Actions to render.
   * @return string the rendered Actions.
   */
  public function renderActions( Actions $actions );

  /**
   * Render the specified Button object.
   *
   * @param Button $button the Button to render.
   * @return string the rendered Button.
   */
  public function renderButton( Button $button );

  /**
   * Render the specified Checkbox object.
   *
   * @param Checkbox $checkbox the Checkbox to render.
   * @return string the rendered Checkbox.
   */
  public function renderCheckbox( Checkbox $checkbox );

  /**
   * Render the specified Checkboxes object.
   *
   * @param Checkboxes $checkboxes the Checkboxes to render.
   * @return string the rendered Checkboxes.
   */
  public function renderCheckboxes( Checkboxes $checkboxes );

  /**
   * Render the specified Container object.
   *
   * @param Container $container the Container to render.
   * @return string the rendered Container.
   */
  public function renderContainer( Container $container );

  /**
   * Render the specified Date object.
   *
   * @param Date $date the Date to render.
   * @return string the rendered Date.
   */
  public function renderDate( Date $date );

  /**
   * Render the specified ElementAttribute object.
   *
   * @param ElementAttribute $element_attribute the ElementAttribute to render.
   * @return string the rendered ElementAttribute.
   */
  public function renderElementAttribute( ElementAttribute $element_attribute );

  /**
   * Render the specified ElementAttributes object.
   *
   * @param ElementAttributes $element_attributes the ElementAttributes to render.
   * @return string the rendered ElementAttributes.
   */
  public function renderElementAttributes( ElementAttributes $element_attributes );

  /**
   * Render the specified Fieldset object.
   *
   * @param Fieldset $fieldset the Fieldset to render.
   * @return string the rendered Fieldset.
   */
  public function renderFieldset( Fieldset $fieldset );

  /**
   * Render the specified File object.
   *
   * @param File $file the File to render.
   * @return string the rendered File.
   */
  public function renderFile( File $file );

  /**
   * Render the specified FileStyle object.
   *
   * @param FileStyle $file_style the FileStyle to render.
   * @return string the rendered FileStyle.
   */
  public function renderFileStyle( FileStyle $file_style );

  /**
   * Render the specified Form object.
   *
   * @param Form Form the Form to render.
   * @return string the rendered Form.
   */
  public function renderForm( Form $form );

  /**
   * Render the specified Hidden object.
   *
   * @param Hidden $hidden the Hidden to render.
   * @return string the rendered Hidden.
   */
  public function renderHidden( Hidden $hidden );

  /**
   * Render the specified ImageButton object.
   *
   * @param ImageButton $image_button the ImageButton to render.
   * @return string the rendered ImageButton.
   */
  public function renderImageButton( ImageButton $image_button );

  /**
   * Render the specified InlineStyle object.
   *
   * @param InlineStyle $inline_style the InlineStyle to render.
   * @return string the rendered InlineStyle.
   */
  public function renderInlineStyle( InlineStyle $inline_style );

  /**
   * Render the specified Item object.
   *
   * @param Item $item the Item to render.
   * @return string the rendered Item.
   */
  public function renderItem( Item $item );

  /**
   * Render the specified MachineName object.
   *
   * @param MachineName $machine_name the MachineName to render.
   * @return string the rendered MachineName.
   */
  public function renderMachineName( MachineName $machine_name );

  /**
   * Render the specified Markup object.
   *
   * @param Markup $markup the Markup to render.
   * @return string the rendered Markup.
   */
  public function renderMarkup( Markup $markup );

  /**
   * Render the specified Page object.
   *
   * @param Page $page the Page to render.
   * @return string the rendered Page.
   */
  public function renderPage( Page $page );

  /**
   * Render the specified Password object.
   *
   * @param Password $password the Password to render.
   * @return string the rendered Password.
   */
  public function renderPassword( Password $password );

  /**
   * Render the specified PasswordConfirm object.
   *
   * @param PasswordConfirm $password_confirm the PasswordConfirm to render.
   * @return string the rendered PasswordConfirm.
   */
  public function renderPasswordConfirm( PasswordConfirm $password_confirm );

  /**
   * Render the specified Presentable object.
   *
   * @param Presentable $presentable the Presentable to render.
   * @return string the rendered Presentable.
   */
  public function renderPresentable( Presentable $presentable );

  /**
   * Render the specified Radio object.
   *
   * @param Radio $radio the Radio to render.
   * @return string the rendered Radio.
   */
  public function renderRadio( Radio $radio );

  /**
   * Render the specified Radios object.
   *
   * @param Radios $radios the Radios to render.
   * @return string the rendered Radios.
   */
  public function renderRadios( Radios $radios );

  /**
   * Render the specified Select object.
   *
   * @param Select $select the Select to render.
   * @return string the rendered Select.
   */
  public function renderSelect( Select $select );

  /**
   * Render the specified Style object.
   *
   * @param Style $style the Style to render.
   * @return string the rendered Style.
   */
  public function renderStyle( Style $style );

  /**
   * Render the specified Submit object.
   *
   * @param Submit $submit the Submit to render.
   * @return string the rendered Submit.
   */
  public function renderSubmit( Submit $submit );

  /**
   * Render the specified TableSelect object.
   *
   * @param TableSelect $tableselect the TableSelect to render.
   * @return string the rendered TableSelect.
   */
  public function renderTable( Table $table );

  /**
   * Render the specified TableSelect object.
   *
   * @param TableSelect $tableselect the TableSelect to render.
   * @return string the rendered TableSelect.
   */
  public function renderTableCell( TableCell $table_cell );

    /**
   * Render the specified TableSelect object.
   *
   * @param TableSelect $tableselect the TableSelect to render.
   * @return string the rendered TableSelect.
   */
  public function renderTableRow( TableRow $table_row );

  /**
   * Render the specified TableSelect object.
   *
   * @param TableSelect $tableselect the TableSelect to render.
   * @return string the rendered TableSelect.
   */
  public function renderTableSelect( TableSelect $table_select );

  /**
   * Render the specified TextArea object.
   *
   * @param TextArea $textarea the TextArea to render.
   * @return string the rendered TextArea.
   */
  public function renderTextArea( TextArea $textarea );

  /**
   * Render the specified TextField object.
   *
   * @param TextField $textfield the TextField to render.
   * @return string the rendered TextField.
   */
  public function renderTextField( TextField $textfield );

  /**
   * Render the specified Token object.
   *
   * @param Token $token the Token to render.
   * @return string the rendered Token.
   */
  public function renderToken( Token $token );

  /**
   * Render the specified Weight object.
   *
   * @param Weight $weight the Weight to render.
   * @return string the rendered Weight.
   */
  public function renderWeight( Weight $weight );

}
