<?php

/*
 * This file is part of the CustomTimesheetFormBundle for Kimai 2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\CustomTimesheetFormBundle\Form;

use App\Entity\Timesheet;
use App\Form\TimesheetEditForm as TimesheetEditFormBase;
use App\Form\Type\DatePickerType;
use KimaiPlugin\CustomTimesheetFormBundle\Form\Type\TimePickerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Defines the form used to manipulate Timesheet entries.
 */
class TimesheetEditForm extends TimesheetEditFormBase
{
    protected function addBegin(FormBuilderInterface $builder, array $dateTimeOptions)
    {
        $builder->add('begindate', DatePickerType::class, array_merge($dateTimeOptions, [
            'label' => 'label.date',
            'mapped' => false,
            'constraints' => [
                new NotBlank()
            ]
        ]));
        $builder->add('begintime', TimePickerType::class, [
            'widget' => 'single_text',
            'label' => 'label.starttime',
            'mapped' => false,
            'constraints' => [
                new NotBlank()
            ]
        ]);

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                /** @var Timesheet|null $data */
                $data = $event->getData();

                if (null !== $data->getBegin()) {
                    $event->getForm()->get('begindate')->setData($data->getBegin());
                    $event->getForm()->get('begintime')->setData($data->getBegin());
                }
            }
        );

        // make sure that date & time fields are mapped back to begin & end fields
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                /** @var Timesheet $data */
                $data = $event->getData();

                if ($event->getForm()->get('begindate')->getData() === null || $event->getForm()->get('begintime')->getData() === null) {
                    return;
                }

                $begindate = clone $event->getForm()->get('begindate')->getData();
                $begintime = clone $event->getForm()->get('begintime')->getData();

                $data->setBegin($begindate);
                $data->getBegin()->setTime($begintime->format('H'), $begintime->format('i'));
            }
        );
    }

    protected function addEnd(FormBuilderInterface $builder, array $dateTimeOptions)
    {
        $builder->add('endtime', TimePickerType::class, [
            'widget' => 'single_text',
            'required' => false,
            'label' => 'label.endtime',
            'mapped' => false
        ]);

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                /** @var Timesheet|null $data */
                $data = $event->getData();

                if (null !== $data->getEnd()) {
                    $event->getForm()->get('endtime')->setData($data->getEnd());
                }
            }
        );

        // make sure that date & time fields are mapped back to begin & end fields
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                /** @var Timesheet $data */
                $data = $event->getData();

                $data->setEnd(null);
                if ($event->getForm()->get('endtime')->getData() === null) {
                    return;
                }

                $endtime = clone $event->getForm()->get('endtime')->getData();
                $begindate = clone $event->getForm()->get('begindate')->getData();
                $begintime = clone $event->getForm()->get('begintime')->getData();

                // enddate is always begindate
                $data->setEnd($begindate);

                $data->getEnd()->setTime($endtime->format('H'), $endtime->format('i'));

                if ($endtime->getTimestamp() < $begintime->getTimestamp()) {
                    // add +1 day to begindate
                    $data->getEnd()->modify('+ 1 day');
                }
            }
        );
    }

    protected function addDuration(FormBuilderInterface $builder)
    {
        return;
    }

    protected function addExported(FormBuilderInterface $builder, array $options)
    {
        return;
    }
}
