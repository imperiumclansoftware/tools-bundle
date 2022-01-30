<?php

namespace ICS\ToolsBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;


class Select2Type extends AbstractType
{
    private $config;

    public function __construct(ContainerBagInterface $params)
    {
        $this->config = $params->get('tools');
    }

    /**
     * @deprecated for all usage
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }
    /**
     * @deprecated for all usage
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if($this->getParent() != Select2Type::class)
        {
            $view->vars['isSelect2']=true;
        }

        $theme=$this->config['theme'];
        $view->vars['theme'] = $theme;
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix() {

        return "select2type";
    }
}
