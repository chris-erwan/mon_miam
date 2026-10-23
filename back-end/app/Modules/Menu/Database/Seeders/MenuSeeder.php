<?php

namespace App\Modules\Menu\Database\Seeders;

use App\Modules\Menu\Models\MenuCategory;
use App\Modules\Menu\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    private const RESTAURANT_ID = 1;

    public function run(): void
    {
        foreach ($this->menu() as $libelle => [$ordre, $items]) {
            $category = MenuCategory::updateOrCreate(
                ['id_restaurant' => self::RESTAURANT_ID, 'libelle' => $libelle],
                ['ordre_affichage' => $ordre],
            );

            foreach ($items as [$nom, $prix, $description]) {
                if ($prix === null) {
                    continue; // prix pas encore connu
                }

                MenuItem::updateOrCreate(
                    ['id_categorie' => $category->id_categorie, 'nom' => $nom],
                    [
                        'description'  => $description,
                        'prix'         => $prix,
                        'disponible'   => true,
                        'plat_du_jour' => $libelle === 'Plats du Jour',
                    ],
                );
            }
        }
    }

    /**
     * libellé de la catégorie => [ordre d'affichage, [[nom, prix en FCFA, description], ...]]
     */
    private function menu(): array
    {
        return [
            'Plats Traditionnels' => [1, [
                ['Ndolé aux crevettes', 2800, 'Feuilles de ndolé mijotées avec crevettes, arachides et oignons, accompagnées de plantain mûr.'],
                ['Eru & accompagnement', 2500, 'Eru mijoté servi avec un accompagnement traditionnel selon la disponibilité du jour.'],
                ['Poulet DG', 2800, 'Poulet mijoté avec plantains mûrs, légumes et épices, grande spécialité camerounaise.'],
            ]],
            'Cuisine Camerounaise' => [2, [
                ['Haricots rouges & beignets', 1800, 'Haricots rouges mijotés et beignets moelleux, association populaire dans les cantines camerounaises.'],
                ['Feuilles de manioc', 2200, 'Feuilles de manioc longuement mijotées, servies avec un accompagnement de manioc.'],
                ['Koki & plantain mûr', 2000, 'Koki de haricots accompagné de plantain mûr, servi chaud au comptoir.'],
                ['Galette de plantain & garniture', null, 'Préparation à base de plantain avec garniture salée, proposée en formule rapide.'],
            ]],
            'Grillades Camerounaises' => [3, [
                ['Poisson braisé', 3000, "Poisson braisé assaisonné d'aromates et servi avec garniture au choix."],
                ['Poulet braisé', 3000, 'Poulet braisé aux épices, préparé sur place et servi avec une sauce verte maison.'],
                ['Porc braisé & plantain', 2800, 'Porc braisé servi avec plantain mûr et garniture de légumes sautés.'],
            ]],
            'Cuisine des Grassfields' => [4, [
                ['Fufu & sauce jaune', 2400, 'Fufu servi avec une sauce jaune parfumée et une garniture traditionnelle.'],
            ]],
            'Plats du Jour' => [5, [
                ['Riz au poisson en sauce', 2300, 'Riz servi avec poisson et sauce tomate relevée, formule adaptée au déjeuner campus.'],
                ['Poulet en sauce', 2500, 'Morceaux de poulet mijotés dans une sauce onctueuse, accompagnement au choix.'],
                ['Spaghetti sautés à la camerounaise', null, 'Spaghetti sautés avec légumes et assaisonnement maison, formule populaire en restauration rapide.'],
                ['Poisson & pommes de terre', null, "Poisson accompagné de pommes de terre et d'une sauce assaisonnée façon cantine camerounaise."],
            ]],
            'Entrées & Fraîcheur' => [6, [
                ['Salade camerounaise', 1500, 'Salade fraîche composée de légumes, oignons et garniture du jour.'],
            ]],
            'Street-food Camerounaise' => [7, [
                ['Sandwich à la viande', 1500, 'Pain garni de viande assaisonnée et de légumes, idéal pour une pause rapide sur le campus.'],
                ['Soya de bœuf', null, 'Brochettes de bœuf grillé, assaisonnées et servies façon street-food camerounaise.'],
            ]],
            'Petit Déjeuner & Rapide' => [8, [
                ['Omelette garnie', 1200, 'Omelette généreuse garnie et servie avec pain ou accompagnement disponible.'],
            ]],
            'Snacks Camerounais' => [9, [
                ['Beignets de maïs', null, "Petits beignets dorés à base de maïs, servis comme encas avec une sauce d'accompagnement."],
                ['Beignets sucrés', null, 'Beignets dorés et légèrement sucrés, parfaits pour une pause gourmande.'],
            ]],
            'Boissons & Jus' => [10, [
                ['Extra', 700, 'Boisson gazeuse proposée en bouteille selon les parfums disponibles.'],
                ['Jus de goyave', 1000, 'Jus de goyave fruité et rafraîchissant, idéal pour accompagner le déjeuner.'],
                ['Jus ananas-orange', 1000, "Mélange fruité d'ananas et d'orange, préparé pour une pause fraîcheur."],
                ['Top Grenadine', 700, 'Boisson gazeuse au parfum grenadine, servie fraîche.'],
                ['Bissap maison', null, "Boisson fraîche à base de fleurs d'hibiscus, servie bien fraîche."],
                ['Smoothie maison', null, 'Smoothie onctueux aux fruits, préparé pour une consommation fraîche.'],
                ['Boissons gazeuses', null, 'Sélection de boissons gazeuses disponibles selon le stock du jour.'],
                ['Youzou citron', null, 'Boisson gazeuse citronnée servie fraîche.'],
                ['Boisson menthe fraîche', null, 'Boisson fraîche à la menthe, servie bien froide.'],
                ['Lait fermenté maison', null, 'Boisson lactée fraîche, proposée en portion individuelle.'],
            ]],
            'Restauration Rapide' => [11, [
                ['Burger ZEDUC', 2500, 'Burger servi avec frites et garniture fraîche, proposé comme formule rapide du campus.'],
                ['Wrap poulet', 2200, 'Wrap garni de poulet, légumes et sauce maison, adapté aux pauses rapides.'],
            ]],
        ];
    }
}