<?php
namespace App\Entity;

 enum State : string
{
    case Lapin ="LAPIN";
    case Nouveau=" NOUVEAU";
    case Annulé="ANNULE";
    case Décalé="DECALE";

}
