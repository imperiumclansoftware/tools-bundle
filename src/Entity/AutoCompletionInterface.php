<?php
namespace ICS\ToolsBundle\Entity;

interface AutoCompletionInterface
{
    public function searchAutocomplete($search);
}