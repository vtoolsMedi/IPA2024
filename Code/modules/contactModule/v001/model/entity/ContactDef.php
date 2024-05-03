<?php
/**
 * Created by PhpStorm.
 * User: peto
 * Date: 12.07.17
 * Time: 19:00
 */

namespace appl\module\modules\contactModule\v001\model\entity;

use po2\types\extendedtypes\SysBoolean;
use po2\types\extendedtypes\SysDateTime;
use po2\types\extendedtypes\SysString;

/**
 * Class ContactDef
 *
 * @package appl\models\entity\doctrine
 */
class ContactDef extends \campaign\module\modules\contactModule\v001\model\entity\ContactDef {

    public static $entityClass = 'appl\module\modules\contactModule\v001\model\entity\Contact';

    public static $present = 'present';
    public static $lastScanDate = 'lastScanDate';
    public static $scans = 'scans';
    public static $lostTicket = 'lostTicket';
    public static $vip = 'vip';
    public static $scanUser = 'scanUser';

    /**
     * ContactDef constructor.
     *
     * @param mixed $entityStackInfo
     *
     */
    public function __construct($entityStackInfo = null) {
        parent::__construct($entityStackInfo, self::$entityClass);

        // @formatter:off
        parent::appendFieldsDef(array(
            self::$present => new SysBoolean(ContactDef::$present, 'Anwesend', 'Anwesend'),
            self::$lastScanDate => new SysDateTime(ContactDef::$lastScanDate, 'Letzter Scan', 'Letzter Scan'),
            self::$scans => new SysString(ContactDef::$scans, 'Anzahl Scans', 'Anzahl Scans'),
            self::$lostTicket => new SysBoolean(ContactDef::$lostTicket, 'Ticket vergessen', 'Ticket vergessen'),
            self::$vip => new SysBoolean(ContactDef::$vip, 'VIP', 'VIP'),
            self::$scanUser => new SysString(ContactDef::$scanUser, 'gescannt von Benutzer', 'gescannt von Benutzer'),
            ));
        // @formatter:on
    }
}