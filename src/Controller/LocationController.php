<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Chambre;
use App\Entity\Location ; 
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class LocationController extends Controller
{
    /**
     * @Route("/viewLocations", name="viewLocations")
     */

    public function viewLocations() // c est une action
    {
        $em = $this->getDoctrine()->getManager();

        //Acceder a le repo et fetcher tous les locations
        $repo = $em->getRepository(Location::class);

        $locations = $repo->findBy([], ['Chambre' => 'ASC']);

        return $this->render('location/viewLocations.html.twig', [
            'locations' => $locations,
        ]);

    }

    /**
     * @Route("/louerChambre/{id}", name="louerChambre")
     */
    public function addLocation($id, Request $request){
    	$em = $this->getDoctrine()->getManager();

	        //Acceder a le repo et fetcher tous les locations
	    $repo = $em->getRepository(Location::class);
	    $repo2 = $em->getRepository(Chambre::class);

	    $chambre = $repo2->findOneById($id) ; 

    	if($request->isMethod('POST')){
	    	
	    	$data = $request->request->all();

	    	$datedeb = \DateTime::createFromFormat('Y-m-d', $data['datedeb']) ; 
	    	$datefin = \DateTime::createFromFormat('Y-m-d', $data['datefin']) ;
	    	$today = date('Y-m-d');
	    	$bool = true ;

	    	if ($datedeb > $datefin){
	    		return $this->render('location/addLocation.html.twig', [
            		'chambre' => $chambre, 'bool' => $bool, 
        		]);
	    	}

	    	/*
	    	if ($today<$datedeb){
	    		return $this->redirectToRoute('viewChambres', array('id' => $id)) ; 

	    	}*/

	        $locations = $chambre->getLocations() ; 

	        foreach($locations as $loc ){
	        	$datedeb2 = $loc->getDateDeb() ; 
	        	$datefin2 = $loc->getDateFin() ; 

	        	if ($datedeb <= $datedeb2 && $datefin > $datedeb2){
	        		$bool = false ;
	        	}


	        	if ($datedeb <= $datedeb2 && $datefin > $datefin2 ){
	        		$bool = false ; 
	        	}

	        	if ($datedeb >= $datedeb2 && $datefin < $datefin2){
	        		$bool = false ; 
	        	}

	        	if ($datedeb < $datefin2 && $datefin > $datefin2){
	        		$bool = false ; 
	        	}

	        }

	        if ($bool){
		        $location = new Location ; 

		        $location->setDatedeb(\DateTime::createFromFormat('Y-m-d', $data['datedeb'])) ; 
		        $location->setDatefin(\DateTime::createFromFormat('Y-m-d', $data['datefin'])) ;
		        $location->setNomLocataire($data['nom']) ;
		        $location->setPrenomLocataire($data['prenom']) ;
		        $location->setTelephone($data['telephone']) ;
		        $location->setChambre($chambre) ; 

		        $em->persist($location);
			    $em->flush();
			    return $this->redirectToRoute('viewLocations') ; 
			}
			else{
				return $this->render('location/addLocation.html.twig', [
            		'chambre' => $chambre, 'bool' => $bool, 
        ]);
			}
		}
		else{
			return $this->render('location/addLocation.html.twig', [
            'chambre' => $chambre,
        ]);
		}

	}

	/**
    * @Route("/deleteLocation/{id}", name="deleteLocation")
    */
	public function deleteChambreLocation($id){
        $em = $this->getDoctrine()->getManager();

        //Acceder a le repo et fetcher tous les locations
        $repo = $em->getRepository(Location::class);
        $location = $repo->findOneById($id) ;
        $locations = $repo->findAll() ;  

        $em->remove($location);
		$em->flush();

		return $this->redirectToRoute('viewLocations') ; 
		
	}


	/**
     * @Route("/modifierLocation/{id}", name="modifierLocation")
     */
    public function ModifierLocation($id, Request $request){
    	$em = $this->getDoctrine()->getManager();

	        //Acceder a le repo et fetcher tous les locations
	    $repo = $em->getRepository(Location::class);
	    $repo2 = $em->getRepository(Chambre::class);
	    $location = $repo->findOneById($id) ; 

	    $chambre = $repo2->findOneById($location->getChambre()->getId()) ; 

    	if($request->isMethod('POST')){
	    	
	    	$data = $request->request->all();

	    	$datedeb = \DateTime::createFromFormat('Y-m-d', $data['datedeb']) ; 
	    	$datefin = \DateTime::createFromFormat('Y-m-d', $data['datefin']) ;

	    	if ($datedeb > $datefin){
	    		return $this->render('location/modifierLocation.html.twig', [
            		'chambre' => $chambre, 'location'=>$location, 'erreur'=> 'Date fin doit etre supérieure à date début',
        ]);
	    	}

	    	$today = date('Y-m-d');
	    	$bool = true ;

	    	/*
	    	if ($today<$datedeb){
	    		return $this->redirectToRoute('viewChambres', array('id' => $id)) ; 

	    	}*/

	        $locations = $chambre->getLocations() ; 

	        foreach($locations as $loc ){

	        	if (!($loc == $location)){
		        	$datedeb2 = $loc->getDateDeb() ; 
		        	$datefin2 = $loc->getDateFin() ; 

		        	if ($datedeb <= $datedeb2 && $datefin > $datedeb2){
		        		$bool = false ;
		        	}


		        	if ($datedeb <= $datedeb2 && $datefin > $datefin2 ){
		        		$bool = false ; 
		        	}

		        	if ($datedeb >= $datedeb2 && $datefin < $datefin2){
		        		$bool = false ; 
		        	}

		        	if ($datedeb < $datefin2 && $datefin > $datefin2){
		        		$bool = false ; 
		        	}
	        }

	        }

	        if ($bool){
		         

		        $location->setDatedeb(\DateTime::createFromFormat('Y-m-d', $data['datedeb'])) ; 
		        $location->setDatefin(\DateTime::createFromFormat('Y-m-d', $data['datefin'])) ;
		        $location->setNomLocataire($data['nom']) ;
		        $location->setPrenomLocataire($data['prenom']) ;
		        $location->setTelephone($data['telephone']) ;
		        $location->setChambre($chambre) ; 

			    $em->flush();
			    return $this->redirectToRoute('viewLocations') ; 
			}
			else{
				return $this->render('location/modifierLocation.html.twig', [
            		'chambre' => $chambre, 'bool' => $bool, 'location'=>$location,
        ]);
			}
		}
		else{
			return $this->render('location/modifierLocation.html.twig', [
            'chambre' => $chambre, 'location'=>$location,
        ]);
		}

	}
	

}
