<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Election\Election;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Election  administration.
 *
 * @author Clément Talleu <clement@les-tilleuls.coop>
 */
class ElectionAdmin extends Admin
{
    protected $baseRouteName = 'election_admin';
    protected $baseRoutePattern = 'election_admin';

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('electionGroup', null, array('label' => "Type d'élection"))
            ->add('organ', null, array('label' => "Lieu concerné"))
            ->add('status', null, array(
                'label' => 'Statut',
                'choices' => array(
                    Election::STATUS_OPEN => 'Election Ouverte.',
                    Election::STATUS_CLOSED => 'Election fermée.',
                ),
                'multiple' => false,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('electionGroup', null, array('label' => "Type d'élection"))
            ->add('organ', null, array('label' => "Lieu concerné"))
            ->add('status', null, array('label' => 'Status'))
            ->add('result', null, array('label' => 'Elus'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('electionGroup', null, array('label' => "Type d'élection"))
            ->add('organ', null, array('label' => "Lieu concerné"))
            ->add('status', 'choice', array(
                'label' => 'Statut',
                'choices' => array(
                    Election::STATUS_OPEN => 'Election Ouverte.',
                    Election::STATUS_CLOSED => 'Election fermée.',
                ),
                'multiple' => false,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('electionGroup', null, array('label' => "Type d'élection"))
            ->add('organ', null, array('label' => "Lieu concerné"))
            ->add('status', null, array('label' => "Statut de l'élection"))
        ;
    }

    /**
     * @return array
     */
    public function getExportFields()
    {
        return array(
            'name',
            'organ',
            'status',
            'electionResponsable',
            'electionResponsabilities',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getExportFormats()
    {
        return array(
            'xls',
        );
    }
}