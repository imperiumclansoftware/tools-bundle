<?php
namespace ICS\ToolsBundle\Twig;

use Twig\TwigFunction;
use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;
use Symfony\Contracts\Translation\TranslatorInterface;

class ToolsExtension extends AbstractExtension
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;    
    }

    public function getFilters()
    {
        return [
            new TwigFilter('highlight', [$this, 'highlight'], [
                'is_safe' => ['html'],
            ]),
            new TwigFilter('pluriel', [$this, 'pluriel'], []),
        ];
    }

    public function getFunctions()
    {
        return [
            
        ];
    }

    public function highlight($value,$hightlights)
    {
        $text = $value;
        
        foreach($hightlights as $search => $class)
        {
            $regexp = "/($search)(?![^<]+>)/i";

            $replacement = '<span class="'.$class.'">\\1</span>';

            $text = preg_replace ($regexp,$replacement ,$value);
        }

        return $text;
    }

    public function pluriel($value,$single,$many)
    {
        if($value > 1)
        {
            return $value.' '.$this->translator->trans($many);
        }

        return $value.' '.$this->translator->trans($single);
    }
}