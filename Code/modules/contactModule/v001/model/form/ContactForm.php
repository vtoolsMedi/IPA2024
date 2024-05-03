<?php

namespace appl\module\modules\contactModule\v001\model\form;

use campaign\module\baseClasses\ModuleInitializer;
use campaign\module\baseClasses\symfony\FormFieldBuilder;
use campaign\module\modules\attendanceModule\v001\AttendanceModule;
use campaign\module\modules\contactModule\v001\model\entity\ContactDef;
use campaign\module\modules\contactModule\v001\types\ContactState;
use Exception;
use po2\system\exception\SysException;
use po2\types\extendedtypes\SysGender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ContactForm extends \campaign\module\modules\contactModule\v001\model\form\ContactForm {

    /**
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {

        global $variables;

        $symfonyFieldBuilder = FormFieldBuilder::getDefault();

        $symfonyFieldBuilder->buildField($builder, ContactDef::$state, false, ChoiceType::class,
            $variables->contactForm->participationOn, true, [], [
                $variables->contactForm->register => ContactState::_REGISTERED, $variables->contactForm->unregister => ContactState::_UNREGISTERED],
            true,false, ['data-col-class' => 'span-col-full' ]);

        $symfonyFieldBuilder->buildField($builder, ContactDef::$company, $variables->contactForm->company, TextType::class,
            $variables->contactForm->companyOn, $variables->contactForm->companyRequired, [], null, false, false,
            ['data-col-class' => 'span-col-full']);

        $symfonyFieldBuilder->buildField($builder, ContactDef::$gender, $variables->contactForm->gender, ChoiceType::class,
            $variables->contactForm->genderOn, $variables->contactForm->genderRequired, [], [
                $variables->contactForm->mr => SysGender::_MALE, $variables->contactForm->mrs => SysGender::_FEMALE], false, false, ['data-col-class' => 'span-col-2']);

        $symfonyFieldBuilder->buildField($builder, ContactDef::$firstname, $variables->contactForm->firstname,
            TextType::class, $variables->contactForm->firstnameOn, $variables->contactForm->firstnameRequired, [], null, false, false, ['data-col-class' => 'span-col-5']);

        $symfonyFieldBuilder->buildField($builder, ContactDef::$lastname, $variables->contactForm->lastname, TextType::class,
            $variables->contactForm->lastnameOn, $variables->contactForm->lastnameRequired, [], null, false, false, ['data-col-class' => 'span-col-5']);

        $symfonyFieldBuilder->buildField($builder, ContactDef::$email, $variables->contactForm->email, EmailType::class,
            $variables->contactForm->emailOn, $variables->contactForm->emailRequired,
            [new Assert\Regex('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $variables->contactForm->errorInvalidEmail)],
            null, false, false, ['data-col-class' => 'span-col-6', 'pattern' => '[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$']);

        $symfonyFieldBuilder->buildField($builder, ContactDef::$street, $variables->contactForm->street, TextType::class,
            $variables->contactForm->streetOn, $variables->contactForm->streetRequired, [], null, false, false, ['data-col-class' => 'span-col-6']);

        $symfonyFieldBuilder->buildField($builder, ContactDef::$zip, $variables->contactForm->zip, TextType::class,
            $variables->contactForm->zipOn, $variables->contactForm->zipRequired, [], null, false, false, ['data-col-class' => 'span-col-2']);

        $symfonyFieldBuilder->buildField($builder, ContactDef::$place, $variables->contactForm->place, TextType::class,
            $variables->contactForm->placeOn, $variables->contactForm->placeRequired, [], null, false, false, ['data-col-class' => 'span-col-4']);

        $symfonyFieldBuilder->buildField($builder, ContactDef::$phone, $variables->contactForm->phone, TextType::class,
            $variables->contactForm->phoneOn, $variables->contactForm->phoneRequired,
            [new Assert\Regex('/^[0-9]{3} [0-9]{3} [0-9]{2} [0-9]{2}$/', $variables->contactForm->errorInvalidPhone)],
            null, false, false, ['data-col-class' => 'span-col-6', 'pattern' => '[0-9]{3} [0-9]{3} [0-9]{2} [0-9]{2}']);

        $symfonyFieldBuilder->buildField($builder, ContactDef::$eating, $variables->contactForm->eating, ChoiceType::class,
            $variables->contactForm->eatingOn, $variables->contactForm->eatingRequired, [], $this->getEatingOptions($variables),
            true, false, ['data-col-class' => 'span-col-3 ']);

        if ($variables->contactForm->eatingOtherOption != '') {
            $symfonyFieldBuilder->buildField($builder, ContactDef::$eatingRemark, $variables->contactForm->eatingRemark, TextareaType::class,
                $variables->contactForm->eatingOn, false, [], null, false, false, ['data-col-class' => 'span-col-9']);
        }

        $symfonyFieldBuilder->buildField($builder, ContactDef::$allergies, $variables->contactForm->allergies, ChoiceType::class,
            $variables->contactForm->allergiesOn, false, [], $this->getAllergiesOptions($variables), true, true, ['data-col-class' => 'span-col-3']);

        if ($variables->contactForm->allergiesOtherOption != '') {
            $symfonyFieldBuilder->buildField($builder, ContactDef::$allergiesRemark, $variables->contactForm->allergiesRemark, TextareaType::class,
                $variables->contactForm->allergiesOn, false, [], null, false, false, ['data-col-class' => 'span-col-9']);
        }

        if (ModuleInitializer::getDefault()->isModuleEnabled(AttendanceModule::class)) {
            if ($variables->attendanceForm->attendanceOn) {
                $builder->add('addAttendance', ButtonType::class,
                    ['label' => $variables->attendanceForm->attendance,
                     'attr' => ['class' => 'btn-block bg-primary']]);
            }
        }

        $symfonyFieldBuilder->buildField($builder, ContactDef::$remark, $variables->contactForm->remark, TextareaType::class,
            $variables->contactForm->remarkOn, false, [], null, false, false, ['data-col-class' => 'span-col-full']);

        $symfonyFieldBuilder->buildField($builder, ContactDef::$newsletter, $variables->contactForm->newsletter, CheckboxType::class,
            $variables->contactForm->newsletterOn, false, [], null, false, false, ['data-col-class' => 'span-col-full']);

        $symfonyFieldBuilder->buildField($builder, ContactDef::$agbAccept, $variables->contactForm->agbAccept, CheckboxType::class,
            $variables->contactForm->agbOn, true, [], null, false, false, ['data-col-class' => 'span-col-full']);

        $builder->add('submit', SubmitType::class, ['label' => $variables->contactForm->submit, 'attr' => ['class' => 'btn-block bg-primary']]);
    }

    public static function getAllergiesOptions($variables): array {
        $options = explode(';', $variables->contactForm->allergiesOptions);
        $optionsArray = [];
        foreach ($options as $option) {
            $optionsArray[$option] = $option;
        }
        if ($variables->contactForm->allergiesOtherOption != '') {
            $optionsArray[$variables->contactForm->allergiesOtherOption] = $variables->contactForm->allergiesOtherOption;
        }
        return $optionsArray;
    }

    public static function getEatingOptions($variables): array {
        $options = explode(';', $variables->contactForm->eatingOptions);
        $optionsArray = [];
        foreach ($options as $option) {
            $optionsArray[$option] = $option;
        }
        if ($variables->contactForm->eatingOtherOption != '') {
            $optionsArray[$variables->contactForm->eatingOtherOption] = $variables->contactForm->eatingOtherOption;
        }
        return $optionsArray;
    }

    /**
     *
     * if user registers then the required fields need to be set
     *
     * @param                           $fieldValue
     * @param ExecutionContextInterface $context
     *
     * @return void
     * @throws SysException
     * @throws Exception
     */
    private function validateInput($fieldValue, ExecutionContextInterface $context) {
        $form = $context->getRoot();
        $participationValue = $form->get(ContactDef::$state)->getData();

        if ($participationValue == ContactState::_REGISTERED && $fieldValue == null) {
            global $variables;
            $context->addViolation($variables->contactForm->errorNotBlank);
        }
    }
}