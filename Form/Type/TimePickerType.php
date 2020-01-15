<?php

/*
 * This file is part of the CustomTimesheetFormBundle for Kimai 2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\CustomTimesheetFormBundle\Form\Type;

use App\Timesheet\UserDateTimeFactory;
use App\Utils\LocaleSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Custom form field type to display the date input fields.
 */
class TimePickerType extends AbstractType
{
    /**
     * @var LocaleSettings
     */
    protected $localeSettings;

    /**
     * @var UserDateTimeFactory
     */
    protected $dateTime;

    /**
     * @param LocaleSettings $localeSettings
     * @param UserDateTimeFactory $dateTime
     */
    public function __construct(LocaleSettings $localeSettings, UserDateTimeFactory $dateTime)
    {
        $this->localeSettings = $localeSettings;
        $this->dateTime = $dateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $dateTimePickerFormat = $this->localeSettings->getDateTimePickerFormat();
        $timeFormat = $this->localeSettings->getTimeFormat();

        $timezone = $this->dateTime->getTimezone()->getName();
        $timePickerFormat = $this->getTimePickerFormat($dateTimePickerFormat);

        $resolver->setDefaults([
            'input' => 'datetime',
            'input_format' => 'H:i',
            'with_minutes' => true,
            'placeholder' => $timeFormat,
            'html5' => true,

            // configs from datetimepicker type
            'widget' => 'single_text',
            'format' => $timeFormat,
            'format_picker' => $timePickerFormat,
            'model_timezone' => $timezone,
            'view_timezone' => $timezone,
        ]);

        $resolver->setDefault('attr', function (Options $options) {
            return [
                'autocomplete' => 'off',
                'placeholder' => $options['format'],
                'data-format' => $options['format_picker'],
            ];
        });
    }

    /**
     * @param $dateTimePicker
     * @return string
     */
    private function getTimePickerFormat($dateTimePicker): string
    {
        $timePickerFormat = explode(' ', $dateTimePicker);

        if (count($timePickerFormat) > 1) {
            return (string)end($timePickerFormat);
        }

        $string = 'HH:mm';

        return $string;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TimeType::class;
    }
}
