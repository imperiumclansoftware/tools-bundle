<?php

namespace ICS\ToolsBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;


class IconType extends AbstractType
{

    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @deprecated for all usage
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $defaultPath = 'bundles/tools/libs/fontawesome-free-5.15.3-web';

        $resolver->setDefaults([
            'attr' => [
                'class' => 'select2-icon' //-'.\date('YmdHism')
            ],
            'data_class' => null,
            'fontawesomePath' => $defaultPath,
        ]);

        $options = $defaultPath;

        $iconsFile = \file_get_contents($this->kernel->getProjectDir().'/public/'.$options.'/metadata/icons.json');

        $icons = \json_decode($iconsFile);
        $iconList=[];

        foreach($icons as $prop=>$icon)
        {
            if(isset($icon->free))
            {
                if(in_array('regular',$icon->free))
                {
                    $class="fa fa-".$prop;
                    $iconList[$class] = $class;
                }
                if(in_array('solid',$icon->free))
                {
                    $class="fas fa-".$prop;
                    $iconList[$class] = $class;
                }

                if(in_array('brands',$icon->free))
                {
                    $class="fab fa-".$prop;
                    $iconList[$class] = $class;
                }
            }
        }

        $resolver->setDefaults([
            'choices' => $iconList,
        ]);

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['fontawesomePath'] = $options['fontawesomePath'];
    }

    public function getParent()
    {
        return Select2Type::class;
    }

    public function getBlockPrefix() {
        return "icontype";
    }
}
