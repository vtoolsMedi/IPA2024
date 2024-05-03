<?php
/**
 * Created by PhpStorm.
 * User: peto
 * Date: 24.07.17
 * Time: 11:20
 */

namespace campaign\module\modules\contactModule\v001\types;
use po2\system\db\FieldDef;
use po2\types\DataType;
use po2\types\Privilege;

/**
 * Class ContactState
 *
 * @package appl\types\extendedtypes
 */
class ContactState extends FieldDef {

    const EMPTY = '';
    const NO_REACTION = 'keine Reaktion';
    const VISITED = 'Besucht';
    const UNREGISTERED = 'Abgemeldet';
    const REGISTERED = 'Angemeldet/Abgeschlossen';
    const DELETE = 'Löschen';
    const _EMPTY = 0;
    const _NO_REACTION = 1;
    const _VISITED = 10;
    const _UNREGISTERED = 20;
    const _REGISTERED = 30;
    const _DELETE = 99;

    public function __construct($name = 'state', $description = 'Status', $helpText = 'Status',
                                Privilege $privilege = null, DataType $type = null, $defaultValue = null,
                                $class = ContactState::class) {
        if ($privilege === null) {
            $privilege = new Privilege();
        }
        if ($type === null) {
            $type = new DataType(DataType::ENUM, false, false, 0, 12, true,
                array(ContactState::_EMPTY => ContactState::EMPTY,
                      ContactState::_NO_REACTION => ContactState::NO_REACTION,
                      ContactState::_VISITED => ContactState::VISITED,
                      ContactState::_UNREGISTERED => ContactState::UNREGISTERED,
                      ContactState::_REGISTERED => ContactState::REGISTERED,
                      ContactState::_DELETE => ContactState::DELETE));
        }
        parent::__construct($name, $description, $helpText, $privilege, $type, $defaultValue, $class);
    }
}