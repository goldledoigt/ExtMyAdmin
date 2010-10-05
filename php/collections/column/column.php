<?php

/**
 * Column collection class.
 *
 * @class Column
 * @extends ICollection
 */
abstract class Column extends ICollection {
  /**
   * Column constructor.
   *
   * @constructor
   * @param array $values Values
   */
  public function __construct(array $values) {
    $this->set_design(array('name',
                            'type',
                            'length',
                            'unsigned',
                            'charset',
                            'null',
                            'key',
                            'default',
                            'extra'));
    foreach ($this->get_design() as $key) {
      $this->set($key, $values);
    }
  }

  /**
   * Return header of column.
   *
   * @method get_header
   * @return string Header
   */
  public function get_header() {
    $key = $this->get('key');
    if ($key == 'PRI') {
      return ('<b style="color: #ff0000">'.
              $this->gethtml('name').'</b>');
    }
    return ('<b>'.$this->gethtml('name').'</b>');
  }

  /**
   * Return xtype of column.
   *
   * @method get_xtype
   * @return string Xtype
   */
  public function get_xtype() {
    $type = $this->get('type');
    if ($type == 'int' or
        $type == 'float' or
        $type == 'decimal' or
        $type == 'double') {
      return ('numberfield');
    } else if ($type == 'datetime' or
               $type == 'date') {
      return ('datefield');
    }
    return ('textfield');
  }

  /**
   * Return column type.
   *
   * @method get_columntype
   * @return string Column type
   */
  public function get_columntype() {
    $type = $this->get('type');
    if ($type == 'int' or
        $type == 'float' or
        $type == 'decimal' or
        $type == 'double') {
      return ('numbercolumn');
    } else if ($type == 'datetime' or
               $type == 'date') {
      return ('datecolumn');
    }
    return ('gridcolumn');
  }

  /**
   * Return width of column.
   *
   * @method get_width
   * @return int Width
   */
  public function get_width() {
    $type = $this->get('type');
    if ($type == 'int' or
        $type == 'float' or
        $type == 'decimal' or
        $type == 'double') {
      if ($this->get('key') == 'PRI') {
        return (60);
      }
      return (100);
    } else if ($type == 'datetime' or
               $type == 'date') {
      return (80);
    } else if ($type == 'varchar' or
               $type == 'text') {
      return (150);
    }
    return (110);
  }

  /**
   * Return tooltip of column.
   *
   * @method get_tooltip
   * @return string Tooltip
   */
  public function get_tooltip() {
    $tpl = '<table class=\'tooltip-column-details\'>'.
      ' <tr>'.
      '  <td>Type:</td>'.
      '  <td>'.$this->gethtml('type').' ('.$this->gethtml('length').')'.
      ($this->get('unsigned') ? ' unsigned' : '').
      '  </td>'.
      ' </tr>'.
      ' <tr>'.
      '  <td>Nullable:</td>'.
      '  <td>'.($this->get('null') ? 'YES' : 'NO').'</td>'.
      ' </tr>'.
      ' <tr>'.
      '  <td>Extra:</td>'.
      '  <td>'.$this->gethtml('extra').'</td>'.
      ' </tr>'.
      '</table>';
    return ($tpl);
  }
}