<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Chambre;

class ChambreController extends Controller
{
    /**
     * @Route("/addchambre", name="addchambre")
     */
    public function add(Request $request)
    {

    	if($request->isMethod('POST')){
    		$em = $this->getDoctrine()->getManager();

	        //Acceder a le repo et fetcher tous les locations
	        $repo = $em->getRepository(Chambre::class);
        	$chambres = $repo->findAll();

            $data = $request->request->all();
            $bool = true ; 
            foreach ($chambres as $chambre){
            	if ($chambre->getNum() == $data['Num']){
					$bool = false ; 
				}
				
            }
            
	        
	        if ($bool == true){	        
	        //Creer l objet chambre
		        $chambre = new Chambre ;
		        $chambre->setNum($data['Num']);
		        $chambre->setTarif($data['Tarif']);
		        $chambre->setEtage($data['Etage']);
		        $chambre->setDescription($data['Description']);
		        $em->persist($chambre);
	            $em->flush();
	            return $this->render('chambre/addChambre.html.twig', [
		            'controller_name' => 'ChambreController',
		            'bool' => $bool,
		        ]);
	        }
	        else {
            return $this->render('chambre/addChambre.html.twig', [
            'controller_name' => 'ChambreController',
            'bool' => $bool,
        	]);
        	}

        }
        else {
        return $this->render('chambre/addChambre.html.twig', [
            'controller_name' => 'ChambreController',
        ]);
    }
    }

    /**
     * @Route("/viewChambres", name="viewChambres")
     */

    public function viewChambre() // c est une action
    {
        $em = $this->getDoctrine()->getManager();

        //Acceder a le repo et fetcher tous les locations
        $repo = $em->getRepository(Chambre::class);
        $chambres = $repo->findAll();

        return $this->render('chambre/viewChambres.html.twig', [
            'Chambres' => $chambres,
        ]);

    }

    /**
     * @Route("/deleteChambre/{id}", name="deleteChambre")
     */
	public function deleteChambre($id) // c est une action
    {
        $em = $this->getDoctrine()->getManager();

        //Acceder a le repo et fetcher tous les locations
        $repo = $em->getRepository(Chambre::class);
        

        $chambre = $repo->findOneById($id) ;
        $chambres = $repo->findAll() ;
        $locations = $chambre->getLocations() ;

        foreach($locations as $loc){
        	$em->remove($loc) ; 
        }  

        $em->remove($chambre);
		$em->flush();

		return $this->redirectToRoute('viewChambres') ; 
		
	}


	/**
     * @Route("/modifierChambre/{id}", name="modifierChambre")
     */
	public function modifierChambre($id, Request $request){


		if($request->isMethod('POST')){
    		$em = $this->getDoctrine()->getManager();

	        //Acceder a le repo et fetcher tous les locations
	        $repo = $em->getRepository(Chambre::class);
        	$chambres = $repo->findAll();
        	$chambre = $repo->findOneById($id) ; 

            $data = $request->request->all();
            $bool = true ; 
            foreach ($chambres as $chambre){
            	if ($chambre->getNum() == $data['Num']){
					$bool = false ; 
				}
				
            }
            
	        
	        if ($bool == true){	        
	        //Creer l objet chambre
		        $chambre->setNum($data['Num']);
		        $chambre->setTarif($data['Tarif']);
		        $chambre->setEtage($data['Etage']);
		        $chambre->setDescription($data['Description']);
	            $em->flush();
	            return $this->redirectToRoute('viewChambres') ; 
	        }
	        else {
            return $this->render('chambre/modifierChambre.html.twig', [
            'controller_name' => 'ChambreController',
            'bool' => $bool,
        	]);
        	}

        }
        else {
        return $this->render('chambre/modifierChambre.html.twig', [
            'controller_name' => 'ChambreController',
        ]);
    }
}




}
