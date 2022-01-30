<?php
namespace ICS\ToolsBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use ICS\ToolsBundle\Form\Transformer\ObjectToIdTransformer;
use Doctrine\Persistence\ManagerRegistry;

class AutoCompleteType extends AbstractType
{
    /**
     * @var ManagerRegistry
     */
    private $registry;
    private $config;

    public function __construct(ManagerRegistry $registry, ContainerBagInterface $params)
    {
        $this->registry = $registry;
        $this->config = $params->get('tools');
    }

    /**
     * @deprecated for all usage
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ObjectToIdTransformer($this->registry, $options['class']);
        $builder->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'invalid_message' => 'The selected item does not exist',
            'placeholder' => 'Search for select',
            'required' => true,
            'data_class' => null,
            'choices' => []
        ]);
        $resolver->setRequired([
            'class',
        ]);
        $resolver->setAllowedTypes('class', [
            'string',
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['entity'] = $form->getData();
        $view->vars['class'] = $options['class'];
        $view->vars['placeholder'] = $options['placeholder'];
        $view->vars['required'] = $options['required'];
        $theme=$this->config['theme'];
        $view->vars['theme'] = $theme;
    }
    public function getParent()
    {
        // return ChoiceType::class;
        return TextType::class;
    }

    public function getBlockPrefix() {
        return "autocomplete";
    }

}