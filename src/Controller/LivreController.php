<?php

namespace App\Controller;
use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LivreController extends AbstractController
{
    #[Route('/livre', name: 'app_livre')]
    public function index(EntityManagerInterface $entityManager):Response
    {
        $livres=$entityManager->getRepository(Livre::class)->findAll();
        return $this->render('livre/index.html.twig',['livres'=>$livres]);
    }
    #[Route('/livre/ajout', name: 'app_livre_ajouter',methods:['POST','GET'])]
    public function ajouter(Request $request,EntityManagerInterface $entityManager):Response
    {
        if($request->isMethod('POST')){
            $livre=new Livre();
            $livre->setTitre($request->request->get('titre'));
            $livre->setAuteur($request->request->get('auteur'));
            $livre->setDescription($request->request->get('description'));
            $livre->setDatePublication(new \DateTime($request->request->get('DatePublication')));
            $entityManager->persist($livre);
            $entityManager->flush();
            return $this->redirectToRoute('app_livre');
        }
        return $this->render('livre/ajouter.html.twig');
    }
    #[Route('/livre/{id}', name: 'description_de_livre',methods:['POST','GET'])]
    public function description (EntityManagerInterface $entityManager,int $id):Response
    {
        $livre =$entityManager->getRepository(Livre::class)->find($id);

        return $this->render('livre/description.html.twig',['livre'=>$livre]);
    }
    #[Route('/livre/supp-livre/{id}', name: 'supprimer_un_livre')]
    public function supprimer (EntityManagerInterface $entityManager,int $id):Response
    {
        $livre =$entityManager->getRepository(Livre::class)->find($id);

        $entityManager->remove($livre);
        $entityManager->flush();

        return $this->redirectToRoute('app_livre');
    }


    
    
}
