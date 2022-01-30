<?php
namespace ICS\ToolsBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use SDI\FormextensionsBundle\Interfaces\AutoCompletionInterface;
use Exception;
use Doctrine\ORM\EntityManagerInterface;

class AutoCompleteController extends AbstractController
{

    /**
    * @Route("/autocomplete",name="ics-tools-autocomplete")
    */
    public function autocompleteList(Request $request, EntityManagerInterface $em)
    {
        $results = [];
        $final = [];
        $class = $request->get('class');
        $query=$request->get('q');
        $required=$request->get('required') == "true";
        if(!$required)
        {
            $tmp['id'] = "0";
            $tmp['text'] = '<i class="fa fa-ban"></i> Aucun';
            $final[]=$tmp;
        }

        if(isset($query['term']))
        {
            $search = $query['term'];
            $repository = $em->getRepository($class);
            
            if(\method_exists($repository,'searchAutocomplete'))
            {
                if($search != null && $search != '')
                {
                    $results = $repository->searchAutocomplete($search);
                }
                else
                {
                    $results = $repository->findAll(10,0);
                }
            }
            else
            {
                throw new Exception('Method searchAutocomplete, is undefined in '.$class.' repository (not implement AutoCompletionInterface');
            }

            
            foreach($results as $result)
            {
                $tmp=[];
                $tmp['id'] = $result->getId();
                $tmp['text'] = $result->__toString();
                $final[] = $tmp;
            }
        }
        
        return new JsonResponse($final);

    }

    /**
    * @Route("/autocomplete/get/{id}",name="ics-tools-autocomplete-get")
    */
    public function autocompleteGet(Request $request, EntityManagerInterface $em, $id=null)
    {
        $final=[];
        $class = $request->get('class');
        $repository = $em->getRepository($class);
        $result = $repository->find($id);
        if($result != null)
        {
            $final['id'] = $result->getId();
            $final['text'] = $result->__toString();
        }

        return  new JsonResponse($final);
    }

}