<?php
/**
 * Created by PhpStorm.
 * User: peto
 * Date: 12.07.17
 * Time: 19:00
 */

namespace campaign\module\modules\contactModule\v001\model\entity;

use campaign\module\modules\contactModule\v001\types\ContactState;
use campaign\types\SysPurl;
use DateTime;
use Doctrine\ORM\Exception\NotSupported;
use po2\system\db\doctrine\BaseEntityDef;
use po2\system\db\doctrine\EntityManager;
use po2\system\db\exception\SysConnectionError;
use po2\system\exception\SysException;
use po2\types\BaseDef;
use po2\types\extendedtypes\SysBoolean;
use po2\types\extendedtypes\SysCity;
use po2\types\extendedtypes\SysDateTime;
use po2\types\extendedtypes\SysDescription;
use po2\types\extendedtypes\SysEmail;
use po2\types\extendedtypes\SysFirstName;
use po2\types\extendedtypes\SysGender;
use po2\types\extendedtypes\SysId;
use po2\types\extendedtypes\SysLastName;
use po2\types\extendedtypes\SysPhone;
use po2\types\extendedtypes\SysStreet;
use po2\types\extendedtypes\SysString;
use po2\types\extendedtypes\SysZip;
use po2\types\Privilege;

/**
 * Class ContactDef
 *
 * @package campaign\module\modules\contactModule\v001\model\entity
 */
class ContactDef extends BaseEntityDef {

    public static $entityClass = 'campaign\module\modules\contactModule\v001\model\entity\Contact';
    public static $entityType = 'DB_DOCTRINE';

    public static $id = 'id';
    public static $vglNr = 'vglNr';
    public static $state = 'state';
    public static $purl = 'purl';
    public static $letterSalutation = 'letterSalutation';
    public static $gender = 'gender';
    public static $company = 'company';
    public static $companyAddition = 'companyAddition';
    public static $firstname = 'firstname';
    public static $lastname = 'lastname';
    public static $street = 'street';
    public static $poBox = 'poBox';
    public static $zip = 'zip';
    public static $place = 'place';
    public static $email = 'email';
    public static $phone = 'phone';
    public static $variant = 'variant';
    public static $eating = 'eating';
    public static $eatingRemark = 'eatingRemark';
    public static $allergies = 'allergies';
    public static $allergiesRemark = 'allergiesRemark';
    public static $agbAccept = 'agbAccept';
    public static $remark = 'remark';
    public static $newsletter = 'newsletter';
    public static $modification = 'modification';
    public static $finished = 'finished';
    public static $newRegistration = 'newRegistration';

    public static $surveySent = 'surveySent';

    public static $attendance = 'attendance';
    public static $attendances = 'attendances';

    /**
     * ContactDef constructor.
     *
     * @param mixed $entityStackInfo
     *
     */
    public function __construct($entityStackInfo = null, $contactDef = null) {
        if ($contactDef === null) {
            $contactDef = ContactDef::$entityClass;
        }
        // @formatter:off
        parent::__construct($entityStackInfo, new BaseDef($contactDef, 'Kontakte', '', new Privilege()),
            array(ContactDef::$id => new SysId(),
                  ContactDef::$vglNr => new SysString(ContactDef::$vglNr, 'Nr.', 'Vergleichs-Nummer'),
                  ContactDef::$purl => new SysPurl(),
                  ContactDef::$state => new ContactState(),
                  ContactDef::$finished => new SysDateTime(ContactDef::$finished, 'Datum Rückmeldung', ''),
                  ContactDef::$newRegistration => new SysBoolean(ContactDef::$newRegistration, 'Neu Angemeldet', ''),
                  ContactDef::$newsletter => new SysBoolean(ContactDef::$newsletter, 'Newsletter', ''),
                  ContactDef::$variant => new SysString(ContactDef::$variant, 'Sprache', 'Sprache'),
                  ContactDef::$company => new SysString(ContactDef::$company, 'Firma', 'Firma'),
                  ContactDef::$companyAddition => new SysString(ContactDef::$companyAddition, 'Firmenzusatz', 'Firmenzusatz'),
                  ContactDef::$letterSalutation => new SysString(ContactDef::$letterSalutation, 'Briefanrede', 'Briefanrede'),
                  ContactDef::$gender => new SysGender(),
                  ContactDef::$firstname => new SysFirstName(),
                  ContactDef::$lastname => new SysLastName(ContactDef::$lastname, 'Name'),
                  ContactDef::$street => new SysStreet(ContactDef::$street,'Strasse' , 'Adresse'),
                  ContactDef::$poBox => new SysString(ContactDef::$poBox,'Postfach' , 'Postfach'),
                  ContactDef::$zip => new SysZip(ContactDef::$zip, 'PLZ'),
                  ContactDef::$place => new SysCity(ContactDef::$place),
                  ContactDef::$phone => new SysPhone(),
                  ContactDef::$email => new SysEmail(ContactDef::$email, 'E-Mail'),
                  ContactDef::$eating => new SysString(ContactDef::$eating, 'Essenswunsch'),
                  ContactDef::$eatingRemark => new SysDescription(ContactDef::$eatingRemark, 'Essenswunsch Bemerkung'),
                  ContactDef::$allergies => new SysString(ContactDef::$allergies, 'Allergien'),
                  ContactDef::$allergiesRemark => new SysDescription(ContactDef::$allergiesRemark, 'Allergien Bemerkung'),
                  ContactDef::$remark => new SysDescription(ContactDef::$remark, 'Bemerkungen','Diese Bemerkungen wurden vom Empfänger eingegeben.'),
                  ContactDef::$modification => new SysString(ContactDef::$modification, 'Veränderung', 'Veränderung', 65535, 50, true),
            ));
        // @formatter:on

        //        $this->addRelation(Contact::class, Relation::MANY_TO_ONE, ContactDef::$attendances, ContactDef::$attendance);

    }

    /**
     * @param mixed $entity
     * @param mixed $hashBag
     *
     * @return bool
     * @override
     * @throws NotSupported
     */
    public function onImport($entity, $hashBag = null): bool {

        if (!isset($hashBag->usedPurls)) {
            $em = EntityManager::getDefault()->getEm();
            $records = $em->getRepository(ContactDef::$entityClass)->findAll();
            $hashBag->usedPurls = array();

            /** @var Contact $record */
            foreach ($records as $record) {
                $hashBag->usedPurls[$record->getPurl()] = 1;
            }
        }
        $this->onInsert($entity, $hashBag);
        return true;
    }

    /**
     * @param Contact $entity
     *
     * @return bool
     * @throws SysConnectionError
     * @throws SysException
     */
    public function onDuplicate($entity) {
        $entity->setPurl(Contact::generatePurl($entity->getLastname()));
        $entity->setFinished(null);
        $entity->setAllergies(null);
        $entity->setEating(null);
        $entity->setSysCreatedDateTime(new DateTime());
        $entity->setVglNr(null);
        return true;
    }

    /**
     * @param Contact $entity
     * @param mixed   $hashBag
     *
     * @return bool
     * @override
     */
    public function onInsert($entity, $hashBag = null) {
        if ($entity->getPurl() == null) {
            $entity->setPurl(Contact::generatePurl($entity->getLastname()));
        }
        $entity->setFinished(null);
        return true;
    }
}