<?php
namespace AppBundle\Form;

use AppBundle\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if( isset($_GET['date']) ) {
            $date = $_GET['date'];
        } else {
            $date = date('d/m/Y H:i');
            var_dump($date);
        }
        $builder
            ->add('name', TextType::class, array('label' => 'Titre'))
            ->add('description', TextType::class, array('label' => 'Description', 'data' => '', 'required' => false))
            ->add('date', DateType::class, array(
                'label' => 'Date',
                'attr' => array('value' => $date),
                'widget' => 'single_text'
                )
            )
            ->add('start', TimeType::class, array(
                'label' => 'Démarrage',
                )
            )
            ->add('end', TimeType::class, array(
                'label' => 'Fin',
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Event::class,
        ));
    }
}