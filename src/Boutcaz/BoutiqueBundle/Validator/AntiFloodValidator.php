<?php

// src/Sdz/BlogBundle/Validator/AntiFloodValidator.php

namespace Boutcaz\BoutiqueBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class AntiFloodValidator extends ConstraintValidator
{
  private $request;
  private $em;

  // Les arguments déclarés dans la définition du service arrivent au constructeur
  // On doit les enregistrer dans l'objet pour pouvoir s'en resservir dans la méthode validate()
  public function __construct(Request $request, EntityManager $em)
  {
    $this->request = $request;
    $this->em      = $em;
  }

  public function validate($value, Constraint $constraint)
  {

    // On vérifie si cette IP a déjà posté un message il y a moins de 15 secondes
    $isFlood = $this->em->getRepository('BoutiqueBundle:ville')
                        ->findByPostal($value); // Bien entendu, il faudrait écrire cette méthode isFlood, c'est pour l'exemple
    if ($isFlood == NULL) {
      // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message
      $this->context->addViolation($constraint->message);
    }
  }
}