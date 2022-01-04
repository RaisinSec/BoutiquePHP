<?php

require_once 'Controller/ControllerCatalog.php';
require_once 'Controller/ControllerProduct.php';
require_once 'Controller/ControllerCart.php';
require_once 'Controller/ControllerLogin.php';
require_once 'Controller/ControllerRegister.php';

require_once 'View/View.php';
class Router {

    private $ctrlCatalog;
    private $ctrlProduct;
    private $ctrlCart;
    private $ctrlLogin;
    private $ctrlRegister;

    public function __construct() {
        $this->ctrlCatalog = new ControllerCatalog();
        $this->ctrlProduct = new ControllerProduct();
        $this->ctrlCart = new ControllerCart();
        $this->ctrlLogin = new ControllerLogin();
        $this->ctrlRegister = new ControllerRegister();
    }
    
    public function rooting(){
        try {
            if (isset($_GET['page'])) {
                switch ($_GET['page'])
                {
                    case 'catalog':
                        $this->routCatalog();
                        break;
                    
                    case 'product':
                        if(isset($_GET['id'])){
                            $this->routProduct();
                            break;
                        }
                        else {
                            $this->routCatalog();
                            break;
                        }
                    
                    case 'login':
                        if (!$_SESSION["logged"]) { //Si l'utilisateur n'est pas connecté : affichage de la page connexion
                            $this->routLogin();
                            break;
                        }
                        else {// Si l'utilisateur est connecté, on le déconnecte
                            $_SESSION["logged"] = false;
                            $_SESSION["username"] = null;
                            header('Location: index.php');
                            break;
                        }
                    
                    case 'register':
                        $this->routRegister();
                        break;
                    
                    default:
                        throw new Exception("Action non valide");
                        break;
                }
            }
            else{
                $this->ctrlCatalog->accueil(0);
            }


        }

        catch (Exception $e) {
            $this->erreur($e->getMessage());
        }

    }

    private function routCatalog(){
        if (isset($_GET['cat'])) {
            $this->ctrlCatalog->accueil(intval($_GET['cat']));
        }
        else {
            $this->ctrlCatalog->accueil(0);
        }
    }

    private function routProduct(){
        $id = intval($this->getParametre($_GET, 'id'));
        $continue = true; // Booléen attestant la validité du formulaire
        if (isset($_POST["confirm-review"])) //Si le formulaire a ete rempli au moins une fois
        {
            $this->ctrlProduct->ctrlAddReview(intval($this->getParametre($_GET, 'id')), $this->getParametre($_POST, "review_form_name_user" ), $this->getParametre($_POST, "review_form_photo_user"), 
                                                    $this->getParametre($_POST, "review_form_stars"), $this->getParametre($_POST, "review_form_title"), $this->getParametre($_POST, "review_form_description"));
            header("Location: index.php?page=product&id=".$_GET['id']."#addReviewSection");//on redirige vers la page du produit
            
        }
        $this->ctrlProduct->showProduct($id);
    }

    private function routLogin(){
        if (isset($_POST["login-request"])) {
            $username = $this->getParametre($_POST, "login_form_username");
            $hashedPassword = sha1($this->getParametre($_POST, "login_form_password")); //Exemple de login : username : Aymeric0, mdp : lol
            
            $query = $this->ctrlLogin->ctrlGetUser($username, $hashedPassword);
            if ($query == null) {
                $errorMessage = "Utilisateur non reconnu, veuillez vérifier les informations entrées";
                $this->ctrlLogin->showLoginPage($errorMessage);
            } else { //La connexion a bien été faite ici
                //$q = $query->fetch(); //On conserve dans $q la premiere ligne de la requete $query, c'est à dire la seule
                $_SESSION["logged"] = true;
                $_SESSION["username"] = $username;
                header('Location: index.php'); //Et on redirige l'utilisateur vers l'accueil
            }
        } 
        else {
            $this->ctrlLogin->showLoginPage();
        }
    }

    private function routRegister(){
        if (isset($_POST["register-request"])) {
            $username = $this->getParametre($_POST,"register_form_username");
            $hashedPassword = sha1($this->getParametre($_POST,"register_form_password"));
            $hashedPasswordConfirmation = sha1($this->getParametre($_POST,"register_form_password_confirmation"));

            $email = $this->getParametre($_POST,"register_form_email");//On récupère également l'adresse mail pour effectuer une vérification sur la table customers
            if (strlen($username) < 1) {
                $errorMessage = "Veuillez entrer un nom d'utilisateur";
                $this->ctrlRegister->showRegisterPage($errorMessage);
            } 
            else if (strlen($this->getParametre($_POST,"register_form_password")) < 1) {
                $errorMessage = "Veuillez entrer un nom mot de passe";
                $this->ctrlRegister->showRegisterPage($errorMessage);
            } 
            else if (strlen($this->getParametre($_POST,"register_form_password_confirmation")) < 1) {
                $errorMessage = "Veuillez confirmer votre mot de passe";
                $this->ctrlRegister->showRegisterPage($errorMessage);
            }
            else if ($hashedPasswordConfirmation != $hashedPassword) {
                $errorMessage = "Le mot de passe n'a pas été correctement confirmé";
                $this->ctrlRegister->showRegisterPage($errorMessage);
            }
            else if ($this->ctrlRegister->ctrlUserAlreadyExists($username)) {
                $errorMessage = "Ce nom d'utilisateur existe déjà";
                $this->ctrlRegister->showRegisterPage($errorMessage);
            }
            else if ($this->ctrlRegister->ctrlEMailAlreadyExists($email)) {
                $errorMessage = "Cette adresse mail a déjà été utilisée";
                $this->ctrlRegister->showRegisterPage($errorMessage);
            }
            else { //L'inscription est valide, on peut enregister l'utilisateur, en récupérant les informations personnelles
                $firstname = $this->getParametre($_POST,"register_form_firstname");
                $surname = $this->getParametre($_POST,"register_form_surname") ;
                $add1 = $this->getParametre($_POST,"register_form_add1");
                $add2 = $this->getParametre($_POST,"register_form_add2");
                $city = $this->getParametre($_POST,"register_form_city");
                $postcode = $this->getParametre($_POST,"register_form_postcode");
                $phone = $this->getParametre($_POST,"register_form_phone");

                $this->ctrlRegister->ctrlRegisterUser($username, $hashedPassword, $firstname, 
                    $surname, $add1, $add2, $city, $postcode, $phone, $email);
                $_SESSION["logged"] = true; //Une fois enregistré on connecte l'utilisateur
                $_SESSION["username"] = $username;

                
                header('Location: index.php');
            }

        } 
        else {
            $this->ctrlRegister->showRegisterPage();
        }

    }

    

    // Affiche une erreur
    private function erreur($msgErreur) {
        $vue = new View("Error");
        $vue->generate(array('msgErreur' => $msgErreur));
    }

    private function getParametre($tableau, $nom) { 
        if (isset($tableau[$nom])) { 
            return $tableau[$nom]; 
        } 
        else throw new Exception("Paramètre '$nom' absent"); 
    }


}