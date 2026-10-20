# Mon Miam Miam — Front-end React (multi-restaurants)

Plateforme de **gestion et d’accès multi-restaurants**.  
Le premier établissement intégré est **ZEDUC Space** (UCAC-Icam).

Slogan de l’écran d’accueil :  
**« Là où tous vos restaurants préférés se trouvent »**

---

## Architecture des interfaces

| Niveau | Route | Description |
|--------|--------|-------------|
| Plateforme | `/` | Sélecteur de restaurants (logo Mon Miam Miam + choix) |
| Restaurant public | `/zeduc` | Landing ZEDUC Space |
| Auth | `/connexion`, `/inscription` | Connexion / inscription **sans affichage des rôles** |
| Étudiant | `/etudiant/*` | Menu, panier, classement, événements, fidélité & parrainage, réclamations, profil |
| Employé | `/employe/*` | KDS, menu & portions, réclamations, production |
| Gérant | `/gerant/*` | Supervision commandes, dashboard, comptes, arbitrage |
| Direction réseau | `/reseau/*` | Gouvernance, catalogue, événements, qualité, paramètres |

Le composant `RoleGate` empêche l’accès à une interface d’un autre rôle.  
**L’utilisateur ne choisit jamais son rôle** : il est déterminé par les identifiants (backend ou simulation offline).

---

## Authentification professionnelle (sans rôles visibles)

- Plus de sélecteur « Étudiant / Employé / Gérant / Direction » à l’écran.
- En production, le backend renvoie le rôle dans la réponse de login.
- En mode démo offline, le rôle est déduit de l’**email** :
  - contient `employe`, `agent`, `cuisine`, `kds` → employé  
  - contient `gerant`, `manager`, `responsable` → gérant  
  - contient `reseau`, `admin`, `direction`, `network` → direction réseau  
  - sinon → étudiant  

Exemples de test offline :
- `etudiant@campus.cm` / n’importe quel mot de passe → espace étudiant  
- `employe.cuisine@campus.cm` → KDS  
- `gerant.site@campus.cm` → supervision  
- `admin.reseau@campus.cm` → direction  

Chaque profil n’accède qu’à **son** interface, sans savoir que d’autres rôles existent dans l’application.

---

## Parcours Mon Miam Miam → ZEDUC

1. Ouvrir `/` → logo Mon Miam Miam + phrase d’accroche.  
2. Carte **ZEDUC Space** (logo Zeduc) → bouton « Entrer dans l’espace ».  
3. Arrivée sur la landing ZEDUC (`/zeduc`) ou, si déjà connecté, redirection vers le menu / dashboard selon le rôle.  
4. D’autres restaurants peuvent être ajoutés dans `RestaurantSelector.jsx` (grille multi-cartes).

---

## Fonctionnalités étudiant enrichies

### Événements
- Cartes enrichies avec **photo**, badge, points Zeduc, lieu, date, places restantes, description détaillée, tarif et CTA (aligné sur la maquette fournie).
- Images locales : produits menu utilisés comme visuels d’ambiance (`burger-zeduc`, `poulet-braise`, `soya`).

### Fidélité & Parrainage
- Grades Classic → Bronze → Silver → Gold (plafonds de filleuls).
- Code parrainage copiable (`ZEDUC-CAMPUS-24`).
- Stats filleuls, historique des derniers filleuls, règles affichées.
- Points crédités après 1ʳᵉ commande payée du filleul (logique métier à brancher côté API).

---

## Identité visuelle

| Élément | Fichier |
|---------|---------|
| Logo plateforme | `public/assets/mon-miam-miam-logo.png` |
| Logo ZEDUC Space | `public/assets/zeduc-logo.png` |
| Menu / plats | `public/assets/menu/*` |

Le logo Mon Miam Miam est affiché sur fond clair (sélection restaurant).  
Les logos restaurant (ex. Zeduc) restent dans leur zone sombre circulaire pour le contraste.

---

## Stack technique

- React 19 + Vite  
- React Router 7  
- Context API (`AppContext`) — panier, commandes, stock, auth, thème  
- Bootstrap / React-Bootstrap (disponible)  
- Persistance locale : `localStorage`  
- API optionnelle : `http://localhost:3000/api` (fallback offline)

---

## Lancement

```bash
cd zeduc_space_react
npm install
npm run dev
```

Ouvrir l’URL Vite (ex. `http://localhost:5173`).

### Build production

```bash
npm run build
npm run preview
```

---

## Données & démo

- Catalogue, ranks, navigations : `src/data/zeduc.js`  
- Commandes opérationnelles seedées dans `AppContext`  
- Actions locales (avancer un ticket, stock, etc.) pour démonstration  

En production : authentification, rôles, commandes, paiements et stats doivent être validés **côté serveur**.

---

## Structure des fichiers principaux

```
src/
  App.jsx                   # Routes (racine = sélecteur restaurants)
  pages/
    RestaurantSelector.jsx  # Accueil Mon Miam Miam
    Landing.jsx             # Landing ZEDUC
    Auth.jsx                # Login / register sans rôles visibles
    StudentPages.jsx        # Classement, fidélité+parrainage, événements, profil, réclamations
    StudentMenu.jsx, Cart.jsx
    OperationsPages.jsx     # Employé / Gérant
    NetworkPages.jsx        # Direction
  components/
    Brand.jsx, StudentLayout.jsx, AdminLayout.jsx, RoleGate.jsx, …
  context/AppContext.jsx
  data/zeduc.js
public/assets/
  mon-miam-miam-logo.png
  zeduc-logo.png
  menu/…
```

---

## Évolutions suggérées

1. Brancher l’API réelle d’auth (JWT + rôle serveur uniquement).  
2. Ajouter d’autres restaurants dans le sélecteur (données + sous-routes).  
3. Photos d’événements dédiées (actuellement des visuels menu).  
4. Flux parrainage complet (génération de code unique, validation serveur).  
5. PWA / notifications push pour rappels d’événements.

---

© 2025 Mon Miam Miam · ZEDUC Space (premier restaurant partenaire)
