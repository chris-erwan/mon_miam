export const foodImages = [
  "/assets/menu/ndole-crevettes.webp",
  "/assets/menu/eru.webp",
  "/assets/menu/haricots-beignets.webp",
  "/assets/menu/feuilles-manioc.webp",
  "/assets/menu/poisson-braise.webp",
  "/assets/menu/poulet-dg.webp",
  "/assets/menu/fufu-sauce-jaune.webp",
  "/assets/menu/koki-plantain.png",
  "/assets/menu/poulet-braise.png",
  "/assets/menu/riz-poisson-sauce-tomate.png",
  "/assets/menu/salade-camerounaise.png",
  "/assets/menu/poulet-sauce.png",
  "/assets/menu/plat-du-jour.png",
  "/assets/menu/soya.png",
  "/assets/menu/spaghetti-sautes.png",
  "/assets/menu/omelette-garnie.png",
  "/assets/menu/sandwich-viande.png",
  "/assets/menu/porc-braise-plantain.png",
  "/assets/menu/poisson-pommes-terre.png"
];


export const beverageImages = [
  "/assets/menu/beignets-maïs.png",
  "/assets/menu/bissap.png",
  "/assets/menu/extra.png",
  "/assets/menu/jus-goyave.png",
  "/assets/menu/jus-ananas-orange.png",
  "/assets/menu/smoothie.png",
  "/assets/menu/boissons-gazeuses.png",
  "/assets/menu/youzou.png",
  "/assets/menu/top-grenadine.png"
];

// Menu ZEDUC centré sur des préparations camerounaises et des plats courants
// de restauration au Cameroun. Aucune recette étrangère de référence n'est utilisée.
export const foods = [
  { id: 1, name: "Ndolé aux crevettes", category: "Plats Traditionnels", price: 2800, points: 3, rating: 4.9, image: foodImages[0], desc: "Feuilles de ndolé mijotées avec crevettes, arachides et oignons, accompagnées de plantain mûr." },
  { id: 2, name: "Eru & accompagnement", category: "Plats Traditionnels", price: 2500, points: 3, rating: 4.9, image: foodImages[1], desc: "Eru mijoté servi avec un accompagnement traditionnel selon la disponibilité du jour." },
  { id: 3, name: "Haricots rouges & beignets", category: "Cuisine Camerounaise", price: 1800, points: 2, rating: 4.8, image: foodImages[2], desc: "Haricots rouges mijotés et beignets moelleux, association populaire dans les cantines camerounaises." },
  { id: 4, name: "Feuilles de manioc", category: "Cuisine Camerounaise", price: 2200, points: 2, rating: 4.7, image: foodImages[3], desc: "Feuilles de manioc longuement mijotées, servies avec un accompagnement de manioc." },
  { id: 5, name: "Poisson braisé", category: "Grillades Camerounaises", price: 3000, points: 3, rating: 4.9, image: foodImages[4], desc: "Poisson braisé assaisonné d'aromates et servi avec garniture au choix." },
  { id: 6, name: "Poulet DG", category: "Plats Traditionnels", price: 2800, points: 3, rating: 4.9, image: foodImages[5], desc: "Poulet mijoté avec plantains mûrs, légumes et épices, grande spécialité camerounaise." },
  { id: 7, name: "Fufu & sauce jaune", category: "Cuisine des Grassfields", price: 2400, points: 2, rating: 4.8, image: foodImages[6], desc: "Fufu servi avec une sauce jaune parfumée et une garniture traditionnelle." },
  { id: 8, name: "Koki & plantain mûr", category: "Cuisine Camerounaise", price: 2000, points: 2, rating: 4.8, image: foodImages[7], desc: "Koki de haricots accompagné de plantain mûr, servi chaud au comptoir." },
  { id: 9, name: "Poulet braisé", category: "Grillades Camerounaises", price: 3000, points: 3, rating: 4.8, image: foodImages[8], desc: "Poulet braisé aux épices, préparé sur place et servi avec une sauce verte maison." },
  { id: 10, name: "Riz au poisson en sauce", category: "Plats du Jour", price: 2300, points: 2, rating: 4.7, image: foodImages[9], desc: "Riz servi avec poisson et sauce tomate relevée, formule adaptée au déjeuner campus." },
  { id: 11, name: "Salade camerounaise", category: "Entrées & Fraîcheur", price: 1500, points: 1, rating: 4.6, image: foodImages[10], desc: "Salade fraîche composée de légumes, oignons et garniture du jour." },
  { id: 12, name: "Poulet en sauce", category: "Plats du Jour", price: 2500, points: 2, rating: 4.7, image: foodImages[11], desc: "Morceaux de poulet mijotés dans une sauce onctueuse, accompagnement au choix." },
  { id: 13, name: "Galette de plantain & garniture", category: "Cuisine Camerounaise", price: 1900, points: 2, rating: 4.6, image: foodImages[12], desc: "Préparation à base de plantain avec garniture salée, proposée en formule rapide." },
  { id: 14, name: "Soya de bœuf", category: "Street-food Camerounaise", price: 1800, points: 2, rating: 4.8, image: foodImages[13], desc: "Brochettes de bœuf grillé, assaisonnées et servies façon street-food camerounaise." },
  { id: 15, name: "Spaghetti sautés à la camerounaise", category: "Plats du Jour", price: 1800, points: 2, rating: 4.6, image: foodImages[14], desc: "Spaghetti sautés avec légumes et assaisonnement maison, formule populaire en restauration rapide." },
  { id: 16, name: "Omelette garnie", category: "Petit Déjeuner & Rapide", price: 1200, points: 1, rating: 4.6, image: foodImages[15], desc: "Omelette généreuse garnie et servie avec pain ou accompagnement disponible." },
  { id: 17, name: "Sandwich à la viande", category: "Street-food Camerounaise", price: 1500, points: 1, rating: 4.5, image: foodImages[16], desc: "Pain garni de viande assaisonnée et de légumes, idéal pour une pause rapide sur le campus." },
  { id: 18, name: "Porc braisé & plantain", category: "Grillades Camerounaises", price: 2800, points: 3, rating: 4.8, image: foodImages[17], desc: "Porc braisé servi avec plantain mûr et garniture de légumes sautés." },
  { id: 19, name: "Poisson & pommes de terre", category: "Plats du Jour", price: 2600, points: 2, rating: 4.7, image: foodImages[18], desc: "Poisson accompagné de pommes de terre et d'une sauce assaisonnée façon cantine camerounaise." },
  { id: 20, name: "Beignets de maïs", category: "Snacks Camerounais", price: 1000, points: 1, rating: 4.7, image: beverageImages[0], desc: "Petits beignets dorés à base de maïs, servis comme encas avec une sauce d'accompagnement." },
  { id: 21, name: "Bissap maison", category: "Boissons & Jus", price: 800, points: 1, rating: 4.8, image: beverageImages[1], desc: "Boisson fraîche à base de fleurs d'hibiscus, servie bien fraîche." },
  { id: 22, name: "Extra", category: "Boissons & Jus", price: 700, points: 1, rating: 4.6, image: beverageImages[2], desc: "Boisson gazeuse proposée en bouteille selon les parfums disponibles." },
  { id: 23, name: "Jus de goyave", category: "Boissons & Jus", price: 1000, points: 1, rating: 4.7, image: beverageImages[3], desc: "Jus de goyave fruité et rafraîchissant, idéal pour accompagner le déjeuner." },
  { id: 24, name: "Jus ananas-orange", category: "Boissons & Jus", price: 1000, points: 1, rating: 4.8, image: beverageImages[4], desc: "Mélange fruité d'ananas et d'orange, préparé pour une pause fraîcheur." },
  { id: 25, name: "Smoothie maison", category: "Boissons & Jus", price: 1200, points: 1, rating: 4.8, image: beverageImages[5], desc: "Smoothie onctueux aux fruits, préparé pour une consommation fraîche sur le campus." },
  { id: 26, name: "Boissons gazeuses", category: "Boissons & Jus", price: 700, points: 1, rating: 4.5, image: beverageImages[6], desc: "Sélection de boissons gazeuses disponibles selon le stock du restaurant." },
  { id: 27, name: "Youzou citron", category: "Boissons & Jus", price: 700, points: 1, rating: 4.6, image: beverageImages[7], desc: "Boisson gazeuse citronnée servie fraîche." },
  { id: 28, name: "Top Grenadine", category: "Boissons & Jus", price: 700, points: 1, rating: 4.6, image: beverageImages[8], desc: "Boisson gazeuse au parfum grenadine, servie fraîche." },
  { id: 29, name: "Burger ZEDUC", category: "Restauration Rapide", price: 2500, points: 2, rating: 4.6, image: "/assets/menu/burger-zeduc.png", desc: "Burger servi avec frites et garniture fraîche, proposé comme formule rapide du campus." },
  { id: 30, name: "Wrap poulet", category: "Restauration Rapide", price: 2200, points: 2, rating: 4.6, image: "/assets/menu/wrap-poulet.png", desc: "Wrap garni de poulet, légumes et sauce maison, adapté aux pauses rapides." },
  { id: 31, name: "Boisson menthe fraîche", category: "Boissons & Jus", price: 1000, points: 1, rating: 4.7, image: "/assets/menu/boisson-menthe.png", desc: "Boisson fraîche à la menthe, servie bien froide." },
  { id: 32, name: "Lait fermenté maison", category: "Boissons & Jus", price: 800, points: 1, rating: 4.7, image: "/assets/menu/lait-fermente.png", desc: "Boisson lactée fraîche, proposée en portion individuelle." },
  { id: 33, name: "Beignets sucrés", category: "Snacks Camerounais", price: 1000, points: 1, rating: 4.7, image: "/assets/menu/beignets-sucre.png", desc: "Beignets dorés et légèrement sucrés, parfaits pour une pause gourmande." }
];

export const studentNav = [
  ["/etudiant/menu", "Menu", "restaurant_menu"],
  ["/etudiant/panier", "Panier & Commandes", "shopping_bag"],
  ["/etudiant/classement", "Classement", "leaderboard"],
  ["/etudiant/evenements", "Événements", "celebration"],
  ["/etudiant/fidelite", "Fidélité & Parrainage", "loyalty"],
  ["/etudiant/reclamations", "Réclamations & Arbitrage", "support_agent"],
  ["/etudiant/profil", "Paramètres Compte", "settings"]
];

export const adminNav = {
  employe: [
    ["/employe/kds", "Commandes KDS", "receipt_long"],
    ["/employe/menu", "Menu & Portions", "restaurant_menu"],
    ["/employe/reclamations", "Réclamations", "support_agent"],
    ["/employe/production", "Dashboard Production", "monitoring"]
  ],
  gerant: [
    ["/gerant/commandes", "Supervision Commandes", "point_of_sale"],
    ["/gerant/dashboard", "Dashboard", "dashboard"],
    ["/gerant/comptes", "Comptes Employés", "manage_accounts"],
    ["/gerant/reclamations", "Arbitrage Réclamations", "gavel"]
  ],
  reseau: [
    ["/reseau/employes", "Équipes & Gouvernance", "groups"],
    ["/reseau/dashboard", "Direction Générale", "query_stats"],
    ["/reseau/menu", "Catalogue Réseau", "menu_book"],
    ["/reseau/evenements", "Événements & Promotions", "campaign"],
    ["/reseau/qualite", "Qualité & Réclamations", "verified"],
    ["/reseau/parametres", "Configuration Réseau", "settings"]
  ]
};

export const ranks = [
  { name: "Zeduc Classic", step: "Étape 1", condition: "À l'inscription initiale", max: "5 filleuls", color: "classic" },
  { name: "Zeduc Bronze", step: "Étape 2", condition: "50 commandes & 50 pts", max: "20 filleuls", color: "bronze" },
  { name: "Zeduc Silver", step: "Votre Grade", condition: "75 commandes & 100 pts", max: "60 filleuls", color: "silver" },
  { name: "Zeduc Gold", step: "Suprême", condition: "+100 commandes & 150 pts", max: "100 filleuls", color: "gold" }
];
