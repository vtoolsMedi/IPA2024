<?php

namespace campaign\module\modules\contactModule\v001\model\entity;

use campaign\module\modules\contactModule\v001\types\ContactState;
use campaign\types\SysPurl;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use po2\system\db\doctrine\BaseEntity;
use po2\system\db\exception\SysConnectionError;
use po2\system\exception\SysException;
use po2\system\ServerManager;
use po2\types\extendedtypes\SysGender;
use stdClass;

/**
 * Contact Model
 * @Entity
 * @Table(name="contacts", indexes={@Index(name="sysfields_idx", columns={"syscreateddatetime", "syscreatedby", "syschangeddatetime", "syschangedby", "syschangedby", "sysalloweduser", "sysallowedusergroup"})})
 *
 */
class Contact extends BaseEntity {

    /**
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $vglNr;

    /**
     * @var integer
     * @Column(type="integer", length=3, nullable=false)
     */
    protected $state = ContactState::_EMPTY;

    /**
     * @var string
     * @Column(type="string", length=32, unique=true, nullable=false)
     */
    protected $purl;

    /**
     * @var string|null
     * @Column(type="string", length=255, nullable=true)
     */
    protected $letterSalutation;

    /**
     * @var int|null
     * @Column(type="integer", nullable=true)
     */
    protected $gender;

    /**
     * @var string|null
     * @Column(type="string", length=255, nullable=true)
     */
    protected $company;

    /**
     * @var string|null
     * @Column(type="string", length=255, nullable=true)
     */
    protected $companyAddition;

    /**
     * @var string|null
     * @Column(type="string", length=255, nullable=true)
     */
    protected $firstname;

    /**
     * @var string|null
     * @Column(type="string", length=255, nullable=true)
     */
    protected $lastname;

    /**
     * @var string|null
     * @Column(type="string", length=255, nullable=true)
     */
    protected $street;

    /**
     * @var string|null
     * @Column(type="string", length=255, nullable=true)
     */
    protected $poBox;

    /**
     * @var string|null
     * @Column(type="string", length=255, nullable=true)
     */
    protected $zip;

    /**
     * @var string|null
     * @Column(type="string", length=255, nullable=true)
     */
    protected $place;

    /**
     * @var string|null
     * @Column(type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @var string|null
     * @Column(type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @var string|null
     * @Column(type="string", length=30, nullable=true)
     */
    protected $variant;

    /**
     * @var string|null
     * @Column(type="string", length=255, nullable=true)
     */
    protected $eating;

    /**
     * @var string
     * @Column(type="text", length=65535, nullable=true)
     */
    protected $eatingRemark;

    /**
     * @var string|null
     * @Column(type="string", length=255, nullable=true)
     */
    protected $allergies;

    /**
     * @var string|null
     * @Column(type="text", length=65535, nullable=true)
     */
    protected $allergiesRemark = null;

    /**
     * @var string
     * @Column(type="text", length=65535, nullable=true)
     */
    protected $remark = null;

    /**
     * @var string
     * @Column(type="text", length=65535, nullable=true)
     */
    protected $modification = null;

    /**
     * @Column(type="boolean", nullable=true)
     */
    protected $agbAccept;

    /**
     * @Column(type="boolean", nullable=true)
     */
    protected $newsletter;

    /**
     * @var DateTime|null
     * @Column(type="datetime", nullable=true)
     */
    protected $finished = null;

    /**
     * @var bool
     * @Column(type="boolean", nullable=true)
     */
    protected $newRegistration;

    /**
     * @return int
     */
    public function getState(): int {
        return $this->state;
    }

    /**
     * @param int $state
     */
    public function setState(int $state) {
        $this->state = $state;
    }

    public function getPurl(): ?string {
        return $this->purl;
    }

    /**
     * @param string $purl
     */
    public function setPurl(string $purl) {
        $this->purl = $purl;
    }

    /**
     * @return int|null
     */
    public function getGender(): ?int {
        return $this->gender;
    }

    public function getHumanizedGender(): ?string {
        global $variables;
        switch ($this->gender) {
            case SysGender::_MALE:
                return $variables->contactForm->mr;
            case SysGender::_FEMALE:
                return $variables->contactForm->mrs;
            default:
                return SysGender::EMPTY;
        }
    }

    /**
     * @param int|null $gender
     */
    public function setGender(?int $gender) {
        $this->gender = $gender;
    }

    /**
     * @return string|null
     */
    public function getVglNr(): ?string {
        return $this->vglNr;
    }

    /**
     * @param string|null $vglNr
     */
    public function setVglNr(?string $vglNr) {
        $this->vglNr = $vglNr;
    }

    /**
     * @return string|null
     */
    public function getCompany(): ?string {
        return $this->company;
    }

    /**
     * @param string|null $company
     */
    public function setCompany(?string $company) {
        $this->company = $company;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     */
    public function setFirstname(?string $firstname) {
        $this->firstname = $firstname;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string {
        return $this->lastname;
    }

    /**
     * @param string|null $lastname
     */
    public function setLastname(?string $lastname) {
        $this->lastname = $lastname;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string {
        return $this->street;
    }

    /**
     * @param string|null $street
     */
    public function setStreet(?string $street) {
        $this->street = $street;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string {
        return $this->zip;
    }

    /**
     * @param string|null $zip
     */
    public function setZip(?string $zip) {
        $this->zip = $zip;
    }

    /**
     * @return string|null
     */
    public function getPlace(): ?string {
        return $this->place;
    }

    /**
     * @param string|null $place
     */
    public function setPlace(?string $place) {
        $this->place = $place;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email) {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone) {
        $this->phone = $phone;
    }

    /**
     * @return string|null
     */
    public function getRemark(): ?string {
        return $this->remark;
    }

    /**
     * @param string|null $remark
     */
    public function setRemark(?string $remark) {
        $this->remark = $remark;
    }

    /**
     * @return string|null
     */
    public function getModification(): ?string {
        return $this->modification;
    }

    /**
     * @param string|null $modification
     */
    public function setModification(?string $modification) {
        $this->modification = $modification;
    }

    /**
     * @return DateTime|null
     */
    public function getFinished(): ?DateTime {
        return $this->finished;
    }

    /**
     * @param DateTime|null $finished
     */
    public function setFinished(?DateTime $finished) {
        $this->finished = $finished;
    }

    /**
     * @return array|null
     */
    public function getEating() {
        if (is_array($this->eating)) {
            return $this->eating;
        }
        return explode(';', $this->eating);
    }

    /**
     * @param string|null|array $eating
     */
    public function setEating($eating) {
        if (is_array($eating)) {
            $this->eating = implode(';', $eating);
        } else {
            $this->eating = $eating;
        }
    }

    /**
     * @return string|null
     */
    public function getEatingRemark(): ?string {
        return $this->eatingRemark;
    }

    /**
     * @param string|null $eatingRemark
     */
    public function setEatingRemark(?string $eatingRemark) {
        $this->eatingRemark = $eatingRemark;
    }

    /**
     * @return array|null
     */
    public function getAllergies(): ?array {
        if (is_array($this->allergies)) {
            return $this->allergies;
        }
        return explode(';', $this->allergies);
    }

    /**
     * @param string|null|array $allergies
     */
    public function setAllergies($allergies) {
        if (is_array($allergies)) {
            $this->allergies = implode(';', $allergies);
        } else {
            $this->allergies = $allergies;
        }
    }

    /**
     * @return string|null
     */
    public function getAllergiesRemark(): ?string {
        return $this->allergiesRemark;
    }

    /**
     * @param string|null $allergiesRemark
     */
    public function setAllergiesRemark(?string $allergiesRemark) {
        $this->allergiesRemark = $allergiesRemark;
    }

    /**
     * @return string|null
     */
    public function getCompanyAddition(): ?string {
        return $this->companyAddition;
    }

    /**
     * @param string|null $companyAddition
     */
    public function setCompanyAddition(?string $companyAddition) {
        $this->companyAddition = $companyAddition;
    }

    /**
     * @return string|null
     */
    public function getLetterSalutation(): ?string {
        return $this->letterSalutation;
    }

    /**
     * @param string|null $letterSalutation
     */
    public function setLetterSalutation(?string $letterSalutation) {
        $this->letterSalutation = $letterSalutation;
    }

    /**
     * @return string|null
     */
    public function getPoBox(): ?string {
        return $this->poBox;
    }

    /**
     * @param string|null $poBox
     */
    public function setPoBox(?string $poBox) {
        $this->poBox = $poBox;
    }

    /**
     * @return bool|null
     */
    public function getAgbAccept(): ?bool {
        return $this->agbAccept;
    }

    /**
     * @return mixed
     */
    public function getNewsletter() {
        return $this->newsletter;
    }

    /**
     * @param mixed $newsletter
     */
    public function setNewsletter($newsletter): void {
        $this->newsletter = $newsletter;
    }

    /**
     * @param bool|null $agbAccept
     */


    public function setAgbAccept(?bool $agbAccept) {
        $this->agbAccept = $agbAccept;
    }

    public function isNewRegistration(): bool {
        return $this->newRegistration;
    }

    public function setNewRegistration(bool $newRegistration) {
        $this->newRegistration = $newRegistration;
    }



    public function isRegistered(): bool {
        return $this->state == ContactState::_REGISTERED || $this->state == ContactState::_UNREGISTERED;
    }

    public function getVariant(): ?string {
        return $this->variant;
    }

    public function setVariant(?string $variant): void {
        $this->variant = $variant;
    }

    public function emptyRecord($state) {
        $this->setFirstname('');
        $this->setLastname('');
        $this->setEmail('');
        $this->setCompany('');
        $this->setState($state);
        $this->setStreet('');
        $this->setZip('');
        $this->setPlace('');
        $this->setEating('');
        $this->setPhone('');
        $this->setGender(SysGender::_EMPTY);
    }

    /**
     * @param $name
     *
     * @return bool|string
     * @throws SysConnectionError
     * @throws SysException
     */
    public static function generatePurl($name) {
        $hashBag = new stdClass();
        $db = ServerManager::getDefault()->getConfig()->getDb();
        SysPurl::loadExternalPurlsToBag($db, $hashBag);
        return SysPurl::generatePurlByName($name, $hashBag);
    }

}