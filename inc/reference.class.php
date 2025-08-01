<?php

/**
 * -------------------------------------------------------------------------
 * Order plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Order.
 *
 * Order is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * Order is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Order. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2009-2023 by Order plugin team.
 * @license   GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
 * @link      https://github.com/pluginsGLPI/order
 * -------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

class PluginOrderReference extends CommonDBTM
{
    public static $rightname         = 'plugin_order_reference'; //'plugin_order_reference'; //TODO : A développer

    public $dohistory                = true;

    public static $forward_entity_to = ['PluginOrderReference_Supplier'];


    public static function getTypeName($nb = 0)
    {
        return __("Product reference", "order");
    }


    public function cleanDBonPurge()
    {
        $temp = new PluginOrderReference_Supplier();
        $temp->deleteByCriteria([
            'plugin_order_references_id' => $this->fields['id']
        ]);
    }

    public function rawSearchOptions()
    {

        $tab = [];

        $tab[] = [
            'id'            => 'common',
            'name'          => __('Product reference', 'order'),
        ];

        $tab[] = [
            'id'            => 1,
            'table'         => self::getTable(),
            'field'         => 'name',
            'name'          => __('Reference'),
            'datatype'      => 'itemlink',
            'checktype'     => 'text',
            'displaytype'   => 'text',
            'injectable'    => true,
            'autocomplete'  => true,
        ];

        $tab[] = [
            'id'            => 2,
            'table'         => self::getTable(),
            'field'         => 'comment',
            'name'          => __('Comments'),
            'datatype'      => 'text',
            'checktype'     => 'text',
            'displaytype'   => 'multiline_text',
            'injectable'    => true,
        ];

        $tab[] = [
            'id'            => 3,
            'table'         => self::getTable(),
            'field'         => 'itemtype',
            'name'          => __('Item type'),
            'datatype'      => 'specific',
            'itemtype_list' => 'plugin_order_types',
            'checktype'     => 'itemtype',
            'searchtype'    => ['equals'],
            'injectable'    => true,
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'            => 4,
            'table'         => self::getTable(),
            'field'         => 'models_id',
            'name'          => __('Model'),
            'checktype'     => 'text',
            'displaytype'   => 'reference_model',
            'injectable'    => true,
            'massiveaction' => false,
            'nosearch'      => true,
            'additionalfields' => ['itemtype'],
        ];

        $tab[] = [
            'id'            => 5,
            'table'         => 'glpi_manufacturers',
            'field'         => 'name',
            'name'          => __('Manufacturer'),
            'datatype'      => 'dropdown',
            'checktype'     => 'text',
            'displaytype'   => 'dropdown',
            'injectable'    => true,
        ];

        $tab[] = [
            'id'            => 6,
            'table'         => self::getTable(),
            'field'         => 'types_id',
            'name'          => __('Type'),
            'checktype'     => 'text',
            'injectable'    => true,
            'massiveaction' => false,
            'searchtype'    => ['equals'],
            'nosearch'      => true,
            'additionalfields' => ['itemtype'],
        ];

        $tab[] = [
            'id'            => 7,
            'table'         => self::getTable(),
            'field'         => 'templates_id',
            'name'          => __('Template name'),
            'checktype'     => 'text',
            'displaytype'   => 'dropdown',
            'injectable'    => true,
            'massiveaction' => false,
            'nosearch'      => true,
            'additionalfields' => ['itemtype'],
        ];

        $tab[] = [
            'id'            => 8,
            'table'         => self::getTable(),
            'field'         => 'manufacturers_reference',
            'name'          => __('Manufacturer reference', 'order'),
            'autocomplete'  => true,
        ];

        $tab[] = [
            'id'            => 30,
            'table'         => self::getTable(),
            'field'         => 'id',
            'name'          => __('ID'),
            'injectable'    => false,
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'            => 31,
            'table'         => self::getTable(),
            'field'         => 'is_active',
            'name'          => __('Active'),
            'datatype'      => 'bool',
            'checktype'     => 'bool',
            'displaytype'   => 'bool',
            'injectable'    => true,
            'searchtype'    => ['equals'],
        ];

        $tab[] = [
            'id'            => 32,
            'table'         => 'glpi_plugin_order_references_suppliers',
            'field'         => 'price_taxfree',
            'name'          => __('Unit price tax free', 'order'),
            'datatype'      => 'decimal',
            'forcegroupby'  => true,
            'usehaving'     => true,
            'massiveaction' => false,
            'joinparams'    => ['jointype' => 'child'],
        ];

        $tab[] = [
            'id'            => 33,
            'table'         => 'glpi_plugin_order_references_suppliers',
            'field'         => 'reference_code',
            'name'          => __('Manufacturer\'s product reference', 'order'),
            'forcegroupby'  => true,
            'usehaving'     => true,
            'massiveaction' => false,
            'joinparams'    => ['jointype' => 'child'],
        ];

        $tab[] = [
            'id'            => 34,
            'table'         => 'glpi_suppliers',
            'field'         => 'name',
            'name'          => __('Supplier'),
            'datatype'      => 'itemlink',
            'itemlink_type' => 'Supplier',
            'forcegroupby'  => true,
            'usehaving'     => true,
            'massiveaction' => false,
            'joinparams'    => [
                'beforejoin' => [
                    'table'      => 'glpi_plugin_order_references_suppliers',
                    'joinparams' => ['jointype' => 'child']
                ]
            ],
        ];

        $tab[] = [
            'id'            => 35,
            'table'         => self::getTable(),
            'field'         => 'date_mod',
            'name'          => __('Last update'),
            'datatype'      => 'datetime',
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'            => 80,
            'table'         => 'glpi_entities',
            'field'         => 'completename',
            'name'          => __('Entity'),
            'datatype'      => 'dropdown',
            'injectable'    => false,
        ];

        $tab[] = [
            'id'            => 86,
            'table'         => self::getTable(),
            'field'         => 'is_recursive',
            'name'          => __('Child entities'),
            'datatype'      => 'bool',
            'checktype'     => 'text',
            'displaytype'   => 'dropdown',
            'injectable'    => true,
            'searchtype'    => ['equals'],
        ];

        return $tab;
    }

    public static function getSpecificValueToDisplay($field, $values, array $options = [])
    {
        if (!is_array($values)) {
            $values = [$field => $values];
        }
        switch ($field) {
            case 'itemtype':
                $item = new $values['itemtype']();
                return $item->getTypeName();
        }
        return '';
    }

   /**
    * @since version 2.3.0
    *
    * @param $field
    * @param $name               (default '')
    * @param $values             (defaut '')
    * @param $options   array
    **/
    public static function getSpecificValueToSelect($field, $name = '', $values = '', array $options = [])
    {
        if (!is_array($values)) {
            $values = [$field => $values];
        }
        switch ($field) {
            case 'itemtype':
                $types    = PluginOrderOrder_Item::getClasses();
                $itemtype = [];
                foreach ($types as $type) {
                    $item            = new $type();
                    $itemtype[$type] = $item->getTypeName();
                }
                $options['display'] = false;
                return Dropdown::showFromArray($name, $itemtype, $options);
        }
        return parent::getSpecificValueToSelect($field, $name, $values, $options);
    }

    public function post_getEmpty()
    {
        $this->fields['is_active'] = 1;
    }


    public function prepareInputForAdd($input)
    {
        if (!isset($input["name"]) || $input["name"] == '') {
            Session::addMessageAfterRedirect(__("Cannot create reference without a name", "order"), false, ERROR);
            return false;
        }

        if (!$input["itemtype"]) {
            Session::addMessageAfterRedirect(__("Cannot create reference without a type", "order"), false, ERROR);
            return false;
        }

        if (
            !isset($input["transfert"])
            && countElementsInTable(
                self::getTable(),
                ['name' => $input["name"],
                    'entities_id' => $input["entities_id"]
                ]
            )
        ) {
            Session::addMessageAfterRedirect(__("A reference with the same name still exists", "order"), false, ERROR);
            return false;
        }

        if (empty($input["ecotax_price"])) {
            $input["ecotax_price"] = 0;
        }

        return $input;
    }


    public function pre_deleteItem()
    {
        if (!$this->referenceInUse()) {
            return true;
        } else {
            Session::addMessageAfterRedirect(__("Reference(s) in use", "order"), true, ERROR);
            return false;
        }
    }


    public function referenceInUse()
    {
        $number = countElementsInTable(
            "glpi_plugin_order_orders_items",
            ['plugin_order_references_id' => $this->fields["id"]]
        );
        if ($number > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function getReceptionReferenceLink($data)
    {
        $link = Toolbox::getItemTypeFormURL($this->getType());

        if (self::canView()) {
            return "<a href=\"" . $link . "?id=" . $data["id"] . "\">" . $data["name"] . "</a>";
        } else {
            return $data['name'];
        }
    }


    public function defineTabs($options = [])
    {
        $ong = [];
        if (!$this->isNewItem()) {
            $this->addDefaultFormTab($ong);
            $this->addStandardTab('PluginOrderReference_Supplier', $ong, $options);
           // $this->addStandardTab('PluginOrderReference', $ong,$options);
            $this->addStandardTab('Document_Item', $ong, $options);
            $this->addStandardTab('Note', $ong, $options);
            $this->addStandardTab('Log', $ong, $options);
        }
        return $ong;
    }

    /**
     * @return array|string
     */
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        if (get_class($item) == __CLASS__) {
            return [1 => __("Linked orders", "order")];
        }
        return '';
    }


    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        if ($item instanceof self) {
            $item->showOrders($item);
        }
        return true;
    }


    public function dropdownTemplate($name, $entity, $table, $value = 0)
    {
        /** @var \DBmysql $DB */
        global $DB;

        $query = "SELECT `template_name`, `id`
                FROM `$table`
                WHERE `entities_id` = '$entity'
                AND `is_template` = '1'
                AND `template_name` <> ''
                GROUP BY `template_name`
                ORDER BY `template_name`";

        $option[0] = Dropdown::EMPTY_VALUE;
        foreach ($DB->request($query) as $data) {
            $option[$data["id"]] = $data["template_name"];
        }
        return Dropdown::showFromArray($name, $option, ['value'  => $value]);
    }


    public function getTemplateName($itemtype, $ID)
    {
        if ($ID) {
            if (getItemForItemtype($itemtype)) {
                $item = new $itemtype();
                $item->getFromDB($ID);
                return $item->getField("template_name");
            }
        } else {
            return false;
        }
    }


    public function checkIfTemplateExistsInEntity($detailID, $itemtype, $entity)
    {
        /** @var \DBmysql $DB */
        global $DB;

        $table = self::getTable();
        $criteria = [
            'SELECT' => ['ref.templates_id'],
            'FROM' => 'glpi_plugin_order_orders_items AS item',
            'INNER JOIN' => [
                $table . ' AS ref' => [
                    'ON' => [
                        'item' => 'plugin_order_references_id',
                        'ref' => 'id'
                    ]
                ]
            ],
            'WHERE' => ['item.id' => $detailID]
        ];
        $result = $DB->request($criteria);

        if (!count($result)) {
            return 0;
        } else {
            $row = $result->current();
            $item = new $itemtype();
            $item->getFromDB($row["templates_id"]);
            if (
                $item->getField('entities_id') == $entity
                || ($item->maybeRecursive()
                && $item->fields['is_recursive']
                && Session::haveAccessToEntity($entity, true))
            ) {
                return $item->getField('id');
            } else {
               //Workaround when templates are not recursive (ie computers, monitors, etc.)
               //If templates have the same name in several entities : search for a template with
               //the same name
                if ($item->getField('template_name') != NOT_AVAILABLE) {
                    $criteria_template = [
                        'SELECT' => ['id'],
                        'FROM' => $item->getTable(),
                        'WHERE' => [
                            'entities_id' => $entity,
                            'template_name' => $item->fields['template_name'],
                            'is_template' => 1
                        ]
                    ];
                    $result_template = $DB->request($criteria_template);
                    if (count($result_template) >= 1) {
                        $row_template = $result_template->current();
                        return $row_template["id"];
                    } else {
                        return 0;
                    }
                } else {
                    return 0;
                }
            }
        }
    }


    public function dropdownAllItems($options = [])
    {
        /** @var \DBmysql $DB */
        global $DB;

        $p['myname']       = '';
        $p['value']        = "";
        $p['orders_id']    = 0;
        $p['suppliers_id'] = 0;
        $p['entity']       = 0;
        $p['ajax_page']    = '';
        $p['filter']       = '';
        $p['class']        = '';
        $p['span']         = '';

        foreach ($options as $key => $value) {
            $p[$key] = $value;
        }

        $types = PluginOrderOrder_Item::getClasses();

        echo "<select name='" . $p['myname'] . "' id='" . $p['myname'] . "'>";
        echo "<option value='0' selected>" . Dropdown::EMPTY_VALUE . "</option>\n";

        if ($p['filter']) {
            $used  = [];
            $table = self::getTable();
            $criteria = [
                'SELECT' => ['itemtype'],
                'FROM' => $table . ' AS t',
                'LEFT JOIN' => [
                    'glpi_plugin_order_references_suppliers AS s' => [
                        'ON' => [
                            't' => 'id',
                            's' => 'plugin_order_references_id'
                        ]
                    ]
                ],
                'WHERE' => [
                    's.suppliers_id' => $p['suppliers_id']
                ] + getEntitiesRestrictCriteria('t', '', $p['entity'], true)
            ];
            $result = $DB->request($criteria);

            $number = count($result);
            if ($number) {
                foreach ($result as $data) {
                    $used[] = $data["itemtype"];
                }
            }

            foreach ($types as $tmp => $itemtype) {
                if (!in_array($itemtype, $used)) {
                    unset($types[$tmp]);
                }
            }
        }

        foreach ($types as $type) {
            $item = new $type();
            echo "<option value='" . $type . "' ";
            if (isset($p['value']) && $p['value'] == $type) {
                echo "selected";
            }
            echo " >" . $item->getTypeName() . "</option>\n";
        }

        echo "</select>";

        $params = [
            'itemtype'        => '__VALUE__',
            'suppliers_id'    => $p['suppliers_id'],
            'entity_restrict' => $p['entity'],
            'orders_id'       => $p['orders_id'],
            'span'            => 'show_quantity',
        ];

        if ($p['class'] != 'PluginOrderOrder_Item') {
            foreach (["types_id", "models_id", "templates_id"] as $field) {
                $params['field'] = $field;
                $params['plugin_order_references_id'] = $p['value'];
                Ajax::updateItemOnSelectEvent(
                    $p['myname'],
                    "show_$field",
                    $p['ajax_page'],
                    $params
                );
            }
        } else {
            Ajax::updateItemOnSelectEvent(
                $p['myname'],
                "show_reference",
                $p['ajax_page'],
                $params
            );
        }
    }


    public function showForm($id, $options = [])
    {
        /** @var \DBmysql $DB */
        global $DB;

        $this->initForm($id, $options);
        $reference_in_use = !$id ? false : $this->referenceInUse();

       // $this->showTabs($options);
        $this->showFormHeader($options);

        if (isset($options['popup'])) {
            echo Html::hidden('popup', ['value' => $options['popup']]);
        }
        if (!isset($options['item']) || empty($options['item'])) {
            $options['item'] = $this->fields["itemtype"];
        }

        echo "<tr class='tab_bg_1'><td>" . __("Name") . "</td>";
        echo "<td>";
        echo Html::input(
            'name',
            [
                'value' => $this->fields['name'],
            ]
        );
        echo "</td>";
        echo "<td rowspan='2'>" . __("Comments") . "</td>";
        echo "<td rowspan='2'>";
        echo "<textarea cols='50' rows='3' name='comment'>" . $this->fields["comment"] .
            "</textarea>";
        echo "</td></tr>";

        echo "<tr class='tab_bg_1'><td>" . __("Active") . "</td>";
        echo "<td>";
        Dropdown::showYesNo('is_active', $this->fields['is_active']);
        echo "</td></tr>";

        echo "<tr class='tab_bg_1'><td>" . __("Manufacturer") . "</td>";
        echo "<td>";
        Manufacturer::Dropdown(['value' => $this->fields['manufacturers_id']]);
        echo "</td>";
        echo "<td>" . __("Manufacturer reference", "order") . "</td>";
        echo "<td>";
        echo Html::input(
            'manufacturers_reference',
            [
                'value' => $this->fields['manufacturers_reference'],
            ]
        );
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";

        echo "<td>" . __("Item type");
       // Mandatory dropdown :
        if ($id <= 0) {
            echo " <span class='red'>*</span>";
        }
        echo "</td>";
        echo "<td>";
        if ($id > 0) {
            $itemtype = $this->fields["itemtype"];
            $item     = new $itemtype();
            echo $item->getTypeName();
            echo Html::hidden('itemtype', ['value' => $itemtype]);
        } else {
            $this->dropdownAllItems([
                'myname'    => 'itemtype',
                'value'     => $options["item"],
                'entity'    => $_SESSION["glpiactive_entity"],
                'ajax_page' => Plugin::getWebDir('order') . '/ajax/referencespecifications.php',
                'class'     => __CLASS__,
            ]);
        }
        echo "</td>";

        echo "<td>" . __("Type") . "</td>";
        echo "<td>";
        echo "<span id='show_types_id'>";
        if ($options['item']) {
            $itemtypeclass = $options['item'] . "Type";
            if (class_exists($itemtypeclass)) {
                if (!$reference_in_use) {
                    Dropdown::show($itemtypeclass, [
                        'name'  => "types_id",
                        'value' => $this->fields["types_id"],
                    ]);
                } else {
                    echo Dropdown::getDropdownName($itemtypeclass::getTable(), $this->fields["types_id"]);
                }
            }
        }
        echo "</span>";
        echo "</td></tr>";

        echo "<tr class='tab_bg_1'><td>" . __("Model") . "</td>";
        echo "<td>";
        echo "<span id='show_models_id'>";
        if ($options['item']) {
            if (class_exists($itemtypeclass)) {
                Dropdown::show($options['item'] . "Model", [
                    'name'  => "models_id",
                    'value' => $this->fields["models_id"],
                ]);
            }
        }
        echo "</span>";
        echo "</td>";

        echo "<td>" . __("Template name") . "</td>";
        echo "<td>";
        echo "<span id='show_templates_id'>";
        if (
            !empty($options['item'])
            && $DB->fieldExists($options['item']::getTable(), 'is_template')
        ) {
            $this->dropdownTemplate(
                'templates_id',
                $this->fields['entities_id'],
                $options['item']::getTable(),
                $this->fields['templates_id']
            );
        }
        echo "</span>";
        echo "</td></tr>";

        echo "<tr class='tab_bg_1'><td>" . __("Last update") . "</td>";
        echo "<td>";
        echo Html::convDateTime($this->fields["date_mod"]);
        echo "</td>";
        echo "<td colspan='2'></td></tr>";

        echo "<tr class='tab_bg_1'><td>" . __("Eco-responsibility fees") . "</td>";
        echo "<td>";
        echo Html::input(
            'ecotax_price',
            [
                'type'  => 'number',
                'step'  => PLUGIN_ORDER_NUMBER_STEP,
                'min'   => 0,
                'value' => Html::formatNumber($this->fields["ecotax_price"], true),
            ]
        );
        echo "</td>";
        echo "<td colspan='2'></td></tr>";

        $options['canedit'] = true;
        $this->showFormButtons($options);
        Html::closeForm();
        return true;
    }


    public function dropdownAllItemsByType(
        $name,
        $itemtype,
        $entity = 0,
        $types_id = 0,
        $models_id = 0
    ) {
        switch ($itemtype) {
            case 'CartridgeItem':
            case 'ConsumableItem':
            case 'SoftwareLicense':
                $fk        = getForeignkeyFieldForItemType($itemtype . "Type");
                $condition = [$fk => $types_id];
                $rand      = Dropdown::show($itemtype, [
                    'condition'   => $condition,
                    'name'        => $name,
                    'entity'      => $entity,
                    'displaywith' => ['ref'],
                ]);
                break;

            default:
                $item = new $itemtype();

                $condition = [];
                if (class_exists($itemtype . "Type", false) && $types_id != 0) {
                    $fk = getForeignKeyFieldForTable(getTableForItemType($itemtype . "Type"));
                    $condition[$fk] = $types_id;
                }
                if (class_exists($itemtype . "Model", false) && $models_id != 0) {
                    $fk = getForeignKeyFieldForTable(getTableForItemType($itemtype . "Model"));
                    $condition[$fk] = $models_id;
                }
                if ($item->maybeTemplate()) {
                    $condition['is_template'] = 0;
                }
                if ($item->maybeDeleted()) {
                    $condition['is_deleted'] = 0;
                }

                $condition[] = [
                    'NOT' => [
                        $itemtype::getTableField('id') => new QuerySubQuery([
                            'SELECT' => 'items_id',
                            'FROM'   => 'glpi_plugin_order_orders_items',
                            'WHERE'  => [
                                'itemtype' => $itemtype,
                                'items_id' => ['!=', 0],
                            ]
                        ])
                    ]
                ];

                $rand = Dropdown::show($itemtype, [
                    'condition'   => $condition,
                    'name'        => $name,
                    'entity'      => $entity,
                    'comments'    => true,
                    'displaywith' => ['serial', 'otherserial'],
                ]);
                break;
        }
        return $rand;
    }


    public function showOrders($ref)
    {
        /** @var \DBmysql $DB */
        global $DB;

        $order = new PluginOrderOrder();
        $criteria = [
            'SELECT' => ['glpi_plugin_order_orders_items.*'],
            'FROM' => 'glpi_plugin_order_orders_items',
            'LEFT JOIN' => [
                'glpi_plugin_order_references' => [
                    'ON' => [
                        'glpi_plugin_order_references' => 'id',
                        'glpi_plugin_order_orders_items' => 'plugin_order_references_id'
                    ]
                ]
            ],
            'WHERE' => [
                'plugin_order_references_id' => $ref->getID()
            ] + getEntitiesRestrictCriteria('glpi_plugin_order_references', 'entities_id', $ref->fields["entities_id"], true),
            'GROUPBY' => ['glpi_plugin_order_orders_items.plugin_order_orders_id'],
            'ORDER' => ['entities_id', 'name']
        ];

        // Compter d'abord le nombre total
        $count_criteria = $criteria;
        $count_criteria['SELECT'] = ['COUNT DISTINCT' => 'glpi_plugin_order_orders_items.plugin_order_orders_id AS total'];
        unset($count_criteria['GROUPBY']);
        $count_result = $DB->request($count_criteria);
        $count_row = $count_result->current();
        $nb = $count_row['total'];

        echo "<div class='center'>";
        if ($nb) {
            if (isset($_REQUEST["start"])) {
                $start = $_REQUEST["start"];
            } else {
                $start = 0;
            }
            $criteria['START'] = intval($start);
            $criteria['LIMIT'] = intval($_SESSION['glpilist_limit']);

            Html::printAjaxPager(__("Linked orders", "order"), $start, $nb);
            echo "<table class='tab_cadre_fixe'>";
            echo "<tr>";
            echo "<th>" . __("Name") . "</a></th>";
            echo "<th>" . __("Entity") . "</th>";
            echo "</tr>";

            foreach ($DB->request($criteria) as $data) {
                echo "<tr class='tab_bg_1' align='center'>";
                echo "<td>";
                $order->getFromDB($data['plugin_order_orders_id']);
                echo $order->getLink();
                echo "</td>";

                echo "<td>";
                echo Dropdown::getDropdownName("glpi_entities", $order->fields["entities_id"]);
                echo "</td>";

                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<table class='tab_cadre_fixe'>";
            echo "<tr class='tab_bg_1'><td class='center'>" . __("No item to display") . "</td></tr>";
            echo "</table>";
        }

        echo "</div>";
    }


    public function transfer($ID, $entity)
    {
        /** @var \DBmysql $DB */
        global $DB;

        if ($ID <= 0 || !$this->getFromDB($ID)) {
            return 0;
        }

       //If reference is not visible in the target entity : transfer it!
        if (!countElementsInTableForEntity(self::getTable(), $entity, ['id' => $this->getID()])) {
            $input                = $this->fields;
            $input['entities_id'] = $entity;
            $oldref               = $input['id'];
            unset($input['id']);
            $input['transfert']   = 1;
            $newid = $this->add($input);

            $reference_supplier   = new PluginOrderReference_Supplier();
            $reference_supplier->getFromDBByReference($oldref);
            $input = $reference_supplier->fields;
            $input['entities_id']                = $entity;
            $input['plugin_order_references_id'] = $newid;
            unset($input['id']);
            $reference_supplier->add($input);

            $PluginOrderOrder_Item = new PluginOrderOrder_Item();

            $criteria = [
                'SELECT' => ['id'],
                'FROM' => 'glpi_plugin_order_orders_items',
                'WHERE' => ['plugin_order_references_id' => $oldref]
            ];

            $result = $DB->request($criteria);
            $num    = count($result);
            if ($num) {
                foreach ($result as $dataref) {
                    $values["id"]                         = $dataref['id'];
                    $values["plugin_order_references_id"] = $newid;
                    $PluginOrderOrder_Item->update($values);
                }
            }
        }
    }


    public function copy($ID)
    {
        $source = new self();
        $source->getFromDB($ID);

        $target = clone $source;
        unset($target->fields['id']);
        $target->fields['name'] = __("Copy of", "order") . ' ' . $target->fields['name'];
        $target->fields = Toolbox::addslashes_deep($target->fields);
        $newID = $this->add($target->fields);

        foreach (
            getAllDataFromTable(
                'glpi_plugin_order_references_suppliers',
                ['plugin_order_references_id' => $ID]
            ) as $refsup
        ) {
            $reference_supplier = new  PluginOrderReference_Supplier();
            $refsup['plugin_order_references_id'] = $newID;
            unset($refsup['id']);
            $reference_supplier->add($refsup);
        }
        return true;
    }

    public function getForbiddenStandardMassiveAction()
    {

        $forbidden = parent::getForbiddenStandardMassiveAction();
        $forbidden[] = 'update';
        return $forbidden;
    }


   /**
    * @since version 0.85
    *
    * @see CommonDBTM::showMassiveActionsSubForm()
    **/
    public static function showMassiveActionsSubForm(MassiveAction $ma)
    {
        switch ($ma->getAction()) {
            case 'transfert':
                Entity::dropdown();
                echo "&nbsp;" .
                  Html::submit(_x('button', 'Post'), ['name' => 'massiveaction']);
                return true;
            case 'copy_reference':
               //useless ?
                echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" .
                     _sx('button', 'Post') . "\" >";
                return true;
        }
        return false;
    }


    public function getSpecificMassiveActions($checkitem = null)
    {

        $isadmin = static::canUpdate();
        $actions = parent::getSpecificMassiveActions($checkitem);

        if ($isadmin) {
            if (
                Session::haveRight('transfer', READ)
                && Session::isMultiEntitiesMode()
            ) {
                $actions['PluginOrderReference:transfert'] = __('Transfer');
            }
            $actions['PluginOrderReference:copy_reference'] = __("Copy reference", "order");
        }

        return $actions;
    }


   /**
    * @since version 0.85
    *
    * @see CommonDBTM::processMassiveActionsForOneItemtype()
    **/
    public static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item, array $ids)
    {
        switch ($ma->getAction()) {
            case "transfert":
                $input = $ma->getInput();
                $entities_id = $input['entities_id'];

                foreach ($ids as $id) {
                    if ($item->getFromDB($id)) {
                        $item->update([
                            "id" => $id,
                            "entities_id" => $entities_id,
                            "update" => __('Update'),
                        ]);
                         $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_OK);
                    }
                }
                return;
            case "copy_reference":
                foreach ($ids as $id) {
                    if (
                        $item instanceof self
                        && $item->getFromDB($id)
                    ) {
                        $item->copy($id);
                    }
                    $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_OK);
                }
                return;
        }
        return;
    }

    public static function install(Migration $migration)
    {
        /** @var \DBmysql $DB */
        global $DB;

        $default_charset = DBConnection::getDefaultCharset();
        $default_collation = DBConnection::getDefaultCollation();
        $default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

        $table = self::getTable();
        if (!$DB->tableExists($table)) {
            $migration->displayMessage("Installing $table");

           //Install
            $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_order_references` (
               `id` int {$default_key_sign} NOT NULL auto_increment,
               `entities_id` int {$default_key_sign} NOT NULL default '0',
               `is_recursive` tinyint NOT NULL default '0',
               `name` varchar(255) default NULL,
               `manufacturers_id` int {$default_key_sign} NOT NULL default '0' COMMENT 'RELATION to glpi_manufacturers (id)',
               `manufacturers_reference` varchar(255) NOT NULL DEFAULT '',
               `types_id` int {$default_key_sign} NOT NULL default '0' COMMENT 'RELATION to various tables, according to itemtypes tables (id)',
               `models_id` int {$default_key_sign} NOT NULL default '0' COMMENT 'RELATION to various tables, according to itemmodels tables (id)',
               `itemtype` varchar(100) NOT NULL COMMENT 'see .class.php file',
               `templates_id` int {$default_key_sign} NOT NULL default '0' COMMENT 'RELATION to various tables, according to itemtype (id)',
               `comment` text,
               `is_deleted` tinyint NOT NULL default '0',
               `is_active` tinyint NOT NULL default '1',
               `notepad` longtext,
               `date_mod` timestamp NULL default NULL,
               `ecotax_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
               PRIMARY KEY  (`id`),
               KEY `name` (`name`),
               KEY `entities_id` (`entities_id`),
               KEY `manufacturers_id` (`manufacturers_id`),
               KEY `types_id` (`types_id`),
               KEY `models_id` (`models_id`),
               KEY `templates_id` (`templates_id`),
               KEY `is_active` (`is_active`),
               KEY `is_deleted` (`is_deleted`),
               KEY date_mod (date_mod)
            ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC;";
            $DB->query($query) or die($DB->error());
        } else {
           //Upgrade
            $migration->displayMessage("Upgrading $table");

           //1.1.0
            $migration->changeField($table, "FK_manufacturer", "FK_glpi_enterprise", "int {$default_key_sign} NOT NULL DEFAULT '0'");

           ///1.2.0
            $migration->changeField($table, "ID", "id", "int {$default_key_sign} NOT NULL auto_increment");
            $migration->changeField(
                $table,
                "FK_entities",
                "entities_id",
                "int {$default_key_sign} NOT NULL default '0'"
            );
            $migration->changeField(
                $table,
                "recursive",
                "is_recursive",
                "tinyint NOT NULL default '0'"
            );
            $migration->changeField(
                $table,
                "name",
                "name",
                "varchar(255) default NULL"
            );
            $migration->changeField(
                $table,
                "FK_glpi_enterprise",
                "manufacturers_id",
                "int {$default_key_sign} NOT NULL default '0' COMMENT 'RELATION to glpi_manufacturers (id)'"
            );
            $migration->changeField(
                $table,
                "FK_type",
                "types_id",
                "int {$default_key_sign} NOT NULL default '0' COMMENT 'RELATION to various tables, according to itemtypes tables (id)'"
            );
            $migration->changeField(
                $table,
                "FK_model",
                "models_id",
                "int {$default_key_sign} NOT NULL default '0' COMMENT 'RELATION to various tables, according to itemmodels tables (id)'"
            );
            $migration->changeField(
                $table,
                "type",
                "itemtype",
                "varchar(100) NOT NULL COMMENT 'see .class.php file'"
            );
            $migration->changeField(
                $table,
                "template",
                "templates_id",
                "int {$default_key_sign} NOT NULL default '0' COMMENT 'RELATION to various tables, according to itemtype (id)'"
            );
            $migration->changeField(
                $table,
                "comments",
                "comment",
                "text"
            );
            $migration->changeField(
                $table,
                "deleted",
                "is_deleted",
                "tinyint NOT NULL default '0'"
            );
            $migration->addField($table, "notepad", "longtext");
            $migration->addField($table, "is_active", "TINYINT NOT NULL DEFAULT '1'");
            $migration->addField($table, "date_mod", "timestamp");

            $migration->addKey($table, "name");
            $migration->addKey($table, "entities_id");
            $migration->addKey($table, "manufacturers_id");
            $migration->addKey($table, "types_id");
            $migration->addKey($table, "models_id");
            $migration->addKey($table, "templates_id");
            $migration->addKey($table, "is_deleted");
            $migration->addKey($table, "is_active");
            $migration->addKey($table, "date_mod");
            $migration->migrationOneTable($table);

            Plugin::migrateItemType(
                [3151 => 'PluginOrderReference'],
                ["glpi_savedsearches", "glpi_savedsearches_users",
                    "glpi_displaypreferences", "glpi_documents_items",
                    "glpi_infocoms",
                    "glpi_logs"
                ]
            );

            if ($DB->fieldExists('glpi_tickets', 'itemtype')) {
                Plugin::migrateItemType([3151 => 'PluginOrderReference'], ["glpi_tickets"]);
            }

            Plugin::migrateItemType([], [], [$table]);

           //1.3.0
            $DB->query("UPDATE `glpi_plugin_order_references` SET
                        `itemtype`='ConsumableItem'
                     WHERE `itemtype` ='Consumable'") or die($DB->error());
            $DB->query("UPDATE `glpi_plugin_order_references` SET
                        `itemtype`='CartridgeItem'
                     WHERE `itemtype` ='Cartridge'") or die($DB->error());

           //1.7.0
            $migration->addField($table, "date_mod", "timestamp NULL DEFAULT NULL");
            $migration->addKey($table, "date_mod");

           //Displayprefs
            $prefs = [1 => 1, 2 => 4, 4 => 5, 5 => 9, 6 => 6, 7 => 7];
            foreach ($prefs as $num => $rank) {
                if (
                    !countElementsInTable(
                        "glpi_displaypreferences",
                        ['itemtype' => 'PluginOrderReference',
                            'num'      => $num,
                            'users_id' => 0
                        ]
                    )
                ) {
                    $DB->query("INSERT INTO glpi_displaypreferences
                           VALUES (NULL,'PluginOrderReference','$num','$rank','0');");
                }
            }

           //Fix error naming field
            if ($DB->fieldExists($table, 'manufacturer_reference')) {
                $migration->changeField(
                    $table,
                    "manufacturer_reference",
                    "manufacturers_reference",
                    "varchar(255) NOT NULL DEFAULT ''"
                );
                $migration->migrationOneTable($table);
            }

           //2.0.1
            if (!$DB->fieldExists($table, 'manufacturers_reference')) {
                $migration->addField(
                    $table,
                    "manufacturers_reference",
                    "varchar(255) NOT NULL DEFAULT ''"
                );
                $migration->migrationOneTable($table);
            }

           // Add ecotax field if it doesn't exist
            if (!$DB->fieldExists($table, 'ecotax_price')) {
                $migration->addField(
                    $table,
                    'ecotax_price',
                    "decimal(20,6) NOT NULL DEFAULT '0.000000'"
                );
                $migration->migrationOneTable($table);
            }
        }
    }


    public static function uninstall()
    {
        /** @var \DBmysql $DB */
        global $DB;

        $table  = self::getTable();
        foreach (
            ["glpi_displaypreferences", "glpi_documents_items", "glpi_savedsearches",
                "glpi_logs"
            ] as $t
        ) {
            $query = "DELETE FROM `$t` WHERE `itemtype`='" . __CLASS__ . "'";
            $DB->query($query);
        }

        $DB->query("DROP TABLE IF EXISTS `$table`") or die($DB->error());
    }


    public static function getIcon()
    {
        return "ti ti-list-search";
    }

    /**
     * Get the ecotax price for this reference
     *
     * @return float price
     */
    public function getEcotaxPrice()
    {
        return $this->fields['ecotax_price'] ?? 0;
    }
}
