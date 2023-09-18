<?php
namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Livre;
use App\Entity\Client;
use App\Form\LivreType;
use App\Form\AuteurType;
use App\Form\ClientType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExemplesFormulaireController extends AbstractController {

    // Action qui AFFICHE le formulaire
    #[Route ("/exemple/form/independant")]
    public function exempleFormIndependant (){
        return $this->render ('exemples_formulaire/exemple_form_independant.html.twig');
    }

    #[Route ("/exemple/form/independant/traitement", name: "form_independant_traitement")]
    public function exempleFormIndependantTraitement(Request $req){
        // traiter le formulaire: envoyer un mail, agir sur le modèle...
        
        // si Form get, on accéde : $req->get ('nom') - pareil que pour prendre les params de l'URL

        // si Form post, accéde: $req->request->get ('nom')
        $nom = $req->request->get ('nom');
        $age = $req->request->get ('age');
        
        dump ($nom);
        dump ($age);

        dd("on traite le formulaire");
    }

    // action pour afficher le formulaire LivreType
    #[Route('/affiche/form/livre')]
    public function afficheFormLivre (){

        // créer un objet formulaire
        $formLivre = $this->createForm(LivreType::class);
        
        // envoyer le form à la vue
        $vars = ['formLivre' => $formLivre];

        return $this->render ("exemples_formulaire/affiche_form_livre.html.twig", $vars);
    }

    //action pour afficher le formulaire ClientType
    #[Route('/affiche/form/client')]
    public function afficheFormClient (){

        // créer un objet formulaire
        $formClient = $this->createForm(ClientType::class);
        
        // envoyer le form à la vue
        $vars = ['formClient' => $formClient];

        return $this->render ("exemples_formulaire/affiche_form_client.html.twig", $vars);
    }

    // action pour afficher le formulaire AuteurType
    #[Route('/affiche/form/auteur')]
    public function afficheFormAuteur (){

        // créer un objet formulaire
        $formAuteur = $this->createForm(AuteurType::class);
        
        // envoyer le form à la vue
        $vars = ['formAuteur' => $formAuteur];

        return $this->render ("exemples_formulaire/affiche_form_auteur.html.twig", $vars);
    }

    #[Route('/livre/add')]
    public function livreAdd (Request $req, ManagerRegistry $doctrine){

        //creer un object formulaire
        $livre = new Livre();

        $formLivre = $this->createForm(LivreType::class, $livre);

        // récupérer les données du formulaire
        $formLivre->handleRequest($req);

        // si le formulaire est soumis et valide
        if ($formLivre->isSubmitted()){

            // enregistrer le livre en BDD
            $em = $doctrine->getManager();
            $em->persist($livre);
            $em->flush();


            // message flash
            //return new Response ("Le livre a bien été enregistré");
                        // ex: aller dans la liste de Livres, aller dans le détail du Livre
            // redirectToRoute reçoit le nom d'une route
            return $this->redirectToRoute("livre_all");

        }
        // envoyer le form à la vue
        $vars = ['formLivre' => $formLivre->createView()];

        return $this->render ("exemples_formulaire/livre_add.html.twig", $vars);
        }
        //pour eviter l'insertion si on recharge la page, on va charger une autre action -> aller dans la liste de livres, au aller dans le detial du livre
        //redirect to route recoit le nom d'une route
        #[Route('/livre/all', name: 'livre_all')]
        public function livreAll (ManagerRegistry $doctrine){
            // récupérer les livres en BDD
            $repoLivre = $doctrine->getRepository(Livre::class);
            $arrayObjetLivres = $repoLivre->findAll();

            $vars = ['arrayObjetLivres' => $arrayObjetLivres];

            // envoyer les livres à la vue
            return $this->render("exemples_formulaire/livre_all.html.twig", $vars);

        }

        #[Route('/client/add')]
        public function clientAdd(Request $req, ManagerRegistry $doctrine){
            $client = new Client();
            $formClient = $this->createForm(ClientType::class, $client);
            $formClient->handleRequest($req);
            if ($formClient->isSubmitted()){
                $em = $doctrine->getManager();
                $em->persist($client);
                $em->flush();
                return $this->redirectToRoute("client_list");
            }
            $vars = ['formClient' => $formClient->createView()];
            return $this->render("exemples_formulaire/client_add.html.twig", $vars);
        }

            #[Route('/client/list', name: 'client_list')]
            public function clientList(ManagerRegistry $doctrine){
                $repoClient = $doctrine->getRepository(Client::class);
                $arrayObjetClients = $repoClient->findAll();
                $vars = ['arrayObjetClients' => $arrayObjetClients];

                return $this->render("exemples_formulaire/client_list.html.twig", $vars);
            }
            
            #[Route('/auteur/add')]
            public function auteurAdd(Request $req, ManagerRegistry $doctrine){
                $auteur = new Auteur();
                $formAuteur = $this->createForm(AuteurType::class, $auteur);
                $formAuteur->handleRequest($req);
                if ($formAuteur->isSubmitted()){
                    $em = $doctrine->getManager();
                    $em->persist($auteur);
                    $em->flush();
                    return $this->redirectToRoute("auteur_list");
                }
                $vars = ['formAuteur' => $formAuteur->createView()];
                return $this->render("exemples_formulaire/auteur_add.html.twig", $vars);
            }
            #[Route('/auteur/list', name: 'auteur_list')]
            public function auteurList(ManagerRegistry $doctrine){
                $repoAuteur = $doctrine->getRepository(Auteur::class);
                $arrayObjetAuteurs = $repoAuteur->findAll();
                $vars = ['arrayObjetAuteurs' => $arrayObjetAuteurs];
    
                return $this->render("exemples_formulaire/auteur_list.html.twig", $vars);
            }
    /*
            #[Route('/livre/fiche/{id}', name: 'livre_fiche')]
            public function livreFiche(Request $req, ManagerRegistry $doctrine, int $id){
                $id= $req->get('id');
                $em = $doctrine->getManager();
                $req = $em->getRepository(Livre::class);
                $livre = $req->find($id);
                dd($livre);
                return $this->render("exemples_formulaire/livre_fiche.html.twig");
            }
            */

            //Grace a ParamConverter, on peut faire comme ça:
           //afficher detail d'un Livre
            #[Route('/livre/fiche/{id}', name: 'livre_fiche')]
                public function livreFiche(Livre $livre){
                    $vars = ['livre' => $livre];
                    return $this->render("exemples_formulaire/livre_fiche.html.twig", $vars);
            }
            //delete livre
            #[Route('/livre/delete/{id}', name: 'livre_delete')]
            public function livreDelete(Livre $livre, ManagerRegistry $doctrine){
                $vars = ['livre' => $livre];
                $em = $doctrine->getManager();
//if you already have the object, you don't need to persist it
                $em->remove($livre);
                $em->flush();
                
                return $this->redirectToRoute("livre_all");
        }
        //update livre
        #[Route('/livre/update/{id}', name: 'livre_update')]
        public function livreUpdate(Livre $livre, Request $req, ManagerRegistry $doctrine){
            $formLivre = $this->createForm(LivreType::class, $livre);
            $formLivre->handleRequest($req);
            if ($formLivre->isSubmitted()){
                $em = $doctrine->getManager();
//if you already have the object, you don't need to persist it
                $em->flush();
                return $this->redirectToRoute("livre_all");
            }
            $vars = ['formLivre' => $formLivre];
            return $this->render("exemples_formulaire/livre_update.html.twig", $vars);
        }
    }

?>

