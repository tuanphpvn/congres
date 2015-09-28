<?php

namespace AppBundle\Form\Election;

use AppBundle\Entity\Adherent;
use AppBundle\Entity\Organ;
use AppBundle\Entity\AdherentRepository;
use AppBundle\Entity\Responsability;
use AppBundle\Entity\ResponsabilityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class DepartmentalElectionType extends AbstractType
{
    private $adherent;

    private $departement;

    public function __construct(Adherent $adherent, $departement)
    {
        $this->adherent = $adherent;
        $this->departement = $departement;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $departement = $this->departement;
            $departmentalElection = $event->getData();
            $form = $event->getForm();

            $adherentFemaleQueryBuilder = function (AdherentRepository $repository) use ($departement) {
                return $repository->getSearchAdherentByOrganQueryBuilder($departement)
                    ->andWhere('a.gender = :gender')
                    ->setParameter('gender', 'F')
                    ->orderBy('a.lastname', 'ASC');
            };
            $adherentMaleQueryBuilder = function (AdherentRepository $repository) use ($departement) {
                return $repository->getSearchAdherentByOrganQueryBuilder($departement)
                    ->andWhere('a.gender = :gender')
                    ->setParameter('gender', 'M')
                    ->orderBy('a.lastname', 'ASC');
            };
            $adherentQueryBuilder = function (AdherentRepository $repository) use ($departement) {
                return $repository->getSearchAdherentByOrganQueryBuilder($departement)
                                  ->orderBy('a.lastname', 'ASC');;
            };


            $form->add('responsableElection', null, array(
                'label' => 'Nom du rapporteur *',
                'required' => true,
                'disabled' => true,
                'data' => $this->adherent->getFirstname().' '.$this->adherent->getLastname(),
            ))
                ->add('department', null, array(
                    'label' => 'Departement *',
                    'required' => true,
                    'disabled' => true,
                    'data' => $departement->getDescription(),
            ))
                ->add('date', 'date', array('label' => "Date de l'élection *"))
                ->add('numberOfVoters', 'integer', array(
                    'label' => 'Nombre de votants',
                    'required' => false,
                    'attr' => array(
                        'min' => '1',
                    ), ))
                ->add('validVotes', 'integer', array(
                    'label' => 'Votes exprimés',
                    'attr' => array(
                        'min' => '1',
                    ), ))
                ->add('blankVotes',
                    'integer', array(
                    'label' => 'Votes blancs',
                    'required' => false,
                        'attr' => array(
                           'min' => '1',
                    ), ))
                ->add('coSecWomen', 'entity', array(
                    'label' => 'Co-secrétaire femme *',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => true,
                    'property' => 'getUpperNames',
                    'class' => 'AppBundle:Adherent',
                    'query_builder' => $adherentFemaleQueryBuilder
                ))
                ->add('oldCoSecWomen', 'entity', array(
                    'label' => 'Ancienne co-secrétaire femme',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false,
                    'property' => 'getUpperNames',
                    'class' => 'AppBundle:Adherent',
                    'query_builder' => $adherentFemaleQueryBuilder
                ))
                ->add('coSecMen', 'entity', array(
                    'label' => 'Co-secrétaire homme *',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => true,
                    'property' => 'getUpperNames',
                    'class' => 'AppBundle:Adherent',
                    'query_builder' => $adherentMaleQueryBuilder
                ))
                ->add('oldCoSecMen', 'entity', array(
                    'label' => 'Ancien co-secrétaire homme',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false,
                    'property' => 'getUpperNames',
                    'class' => 'AppBundle:Adherent',
                    'query_builder' => $adherentMaleQueryBuilder
                ))
                ->add('coTreasureWomen', 'entity', array(
                    'label' => 'Co-trésorière femme *',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => true,
                    'property' => 'getUpperNames',
                    'class' => 'AppBundle:Adherent',
                    'query_builder' => $adherentFemaleQueryBuilder
                ))
                ->add('oldCoTreasureWomen', 'entity', array(
                    'label' => 'Ancienne Co-trésorière femme',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false,
                    'property' => 'getUpperNames',
                    'class' => 'AppBundle:Adherent',
                    'query_builder' => $adherentFemaleQueryBuilder
                ))
                ->add('coTreasureMen', 'entity', array(
                    'label' => 'Co Trésorier homme *',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => true,
                    'property' => 'getUpperNames',
                    'class' => 'AppBundle:Adherent',
                    'query_builder' => $adherentMaleQueryBuilder
                ))
                ->add('oldCoTreasureMen', 'entity', array(
                    'label' => 'Ancien Co-trésorier homme',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false,
                    'property' => 'getUpperNames',
                    'class' => 'AppBundle:Adherent',
                    'query_builder' => $adherentMaleQueryBuilder
                ))
                ->add('responsability1', 'entity', array(
                    'label' => 'Poste fonctionnel 1',
                    'required' => false,
                    'expanded' => false,
                    'multiple' => false,
                    'class' => 'AppBundle:Responsability'
                ))
                ->add('responsable1', 'entity', array(
                    'label' => 'Elu à ce poste',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false,
                    'property' => 'getUpperNames',
                    'class' => 'AppBundle:Adherent',
                    'query_builder' =>  $adherentQueryBuilder
                ))
                ->add('responsability2', 'entity', array(
                    'label' => 'Poste fonctionnel 2',
                    'required' => false,
                    'expanded' => false,
                    'multiple' => false,
                    'class' => 'AppBundle:Responsability'
                ))
                ->add('responsable2', 'entity', array(
                    'label' => 'Elu à ce poste',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false,
                    'property' => 'getUpperNames',
                    'class' => 'AppBundle:Adherent',
                    'query_builder' =>  $adherentQueryBuilder
                ))
                ->add('responsability3', 'entity', array(
                    'label' => 'Poste fonctionnel 3',
                    'required' => false,
                    'expanded' => false,
                    'multiple' => false,
                    'class' => 'AppBundle:Responsability'
                ))
                ->add('responsable3', 'entity', array(
                    'label' => 'Elu à ce poste',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false,
                    'property' => 'getUpperNames',
                    'class' => 'AppBundle:Adherent',
                    'query_builder' => $adherentQueryBuilder
                ))
                ->add('responsability4', 'entity', array(
                    'label' => 'Poste fonctionnel 4',
                    'required' => false,
                    'expanded' => false,
                    'multiple' => false,
                    'class' => 'AppBundle:Responsability'
                ))
                ->add('responsable4', 'entity', array(
                    'label' => 'Elu à ce poste',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false,
                    'property' => 'getUpperNames',
                    'class' => 'AppBundle:Adherent',
                    'query_builder' => $adherentQueryBuilder
                ))
                ->add('responsability5', 'entity', array(
                    'label' => 'Poste fonctionnel 5',
                    'required' => false,
                    'expanded' => false,
                    'multiple' => false,
                    'class' => 'AppBundle:Responsability'
                ))
                ->add('responsable5', 'entity', array(
                    'label' => 'Elu à ce poste',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false,
                    'property' => 'getUpperNames',
                    'class' => 'AppBundle:Adherent',
                    'query_builder' => $adherentQueryBuilder
                ))
                ->add('responsability6', 'entity', array(
                    'label' => 'Poste fonctionnel 6',
                    'required' => false,
                    'expanded' => false,
                    'multiple' => false,
                    'class' => 'AppBundle:Responsability'
                ))
                ->add('responsable6', 'entity', array(
                    'label' => 'Elu à ce poste',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false,
                    'property' => 'getUpperNames',
                    'class' => 'AppBundle:Adherent',
                    'query_builder' => $adherentQueryBuilder
                ))
            ;
        });
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_election_election';
    }
}
