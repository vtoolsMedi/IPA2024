<?php

namespace appl\module\modules\contactModule\v001\model\entity;

use campaign\module\modules\attendanceModule\v001\model\traits\AttendanceFieldsTrait;
use campaign\module\modules\entryControlAppModule\v001\model\traits\EntryControlFieldsTrait;
use campaign\module\modules\surveyModule\v001\model\traits\SurveyFieldsTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * Contact Model
 *
 * @Entity
 * @Table(name="contacts", indexes={@Index(name="sysfields_idx", columns={"syscreateddatetime", "syscreatedby", "syschangeddatetime", "syschangedby", "syschangedby", "sysalloweduser", "sysallowedusergroup"})})
 */
class Contact extends \campaign\module\modules\contactModule\v001\model\entity\Contact {


    use AttendanceFieldsTrait;
    use SurveyFieldsTrait;
    use EntryControlFieldsTrait;

}