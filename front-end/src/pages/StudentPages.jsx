import { useState } from "react";
import { Link } from "react-router-dom";
import PageHeader from "../components/PageHeader";
import MetricCard from "../components/MetricCard";
import { ranks } from "../data/zeduc";

export function Ranking() {
  const users = [
    ["Membre campus 01", "Master Finance • ICAM", "Silver", 34, 1720],
    ["Membre campus 02", "Ingénierie Industrielle 4A", "Gold", 41, 2150],
    ["Membre campus 03", "Management & RH 3A", "Bronze", 30, 1490],
    ["Membre étudiant", "Filière Informatique & SI", "Silver", 28, 1420],
    ["Membre campus 05", "Télécoms & Réseaux", "Silver", 26, 1350],
    ["Membre campus 06", "Génie Électrique", "Bronze", 24, 1210],
  ];
  return (
    <>
      <PageHeader
        eyebrow="ZEDUC-SP@CE · Taste & Chill"
        title="Votre position : 4e place"
        subtitle="Plus que 2 commandes pour monter sur le podium mensuel (+15 pts bonus en jeu)."
      />
      <div className="metrics-grid">
        <MetricCard label="Commandes ce mois" value="28" detail="validées" icon="↗" />
        <MetricCard label="Points fidélité" value="1 420" detail="pts" icon="★" />
        <MetricCard label="Prochain lot" value="Podium Bronze" detail="bonus actif" icon="◆" />
      </div>
      <div className="rank-layout">
        <section className="panel">
          <div className="filter-pills">
            <button className="active">Ce Mois-ci</button>
            <button>Cette Semaine</button>
            <button>Aujourd&apos;hui</button>
          </div>
          <div className="cycle-cards">
            <div>
              <span>Fin cycle semaine</span>
              <b>3j : 08h : 42m</b>
            </div>
            <div>
              <span>Clôture Mensuelle</span>
              <b>11j : 14h : 18m</b>
            </div>
          </div>
          <h2>Récompenses Mensuelles</h2>
          <div className="reward-list">
            {[
              ["1er Prix", "+60 pts", "Repas Découverte offert"],
              ["2e Prix", "+35 pts", "Formule Express offerte"],
              ["3e Prix", "+15 pts", "Boisson & Cookie artisanal"],
            ].map((x) => (
              <div key={x[0]}>
                <span className="rank-number">{x[0]}</span>
                <b>{x[1]}</b>
                <small>{x[2]}</small>
              </div>
            ))}
          </div>
          <h2>Récompenses de la Semaine</h2>
          <div className="reward-list compact">
            <div>
              <span>1er Prix</span>
              <b>+10 pts</b>
              <small>Café Spécialité offert</small>
            </div>
            <div>
              <span>2e Prix</span>
              <b>+5 pts</b>
              <small>Garniture supplémentaire</small>
            </div>
            <div>
              <span>3e Prix</span>
              <b>+3 pts</b>
              <small>Double points le lundi</small>
            </div>
          </div>
        </section>
        <aside className="panel podium">
          <h2>Podium des Étoiles Zeduc</h2>
          <small>Mise à jour en temps réel à chaque encaissement</small>
          {users.slice(0, 3).map((u, i) => (
            <div className={`podium-row p${i + 1}`} key={u[0]}>
              <b>{i + 1}</b>
              <div>
                <strong>{u[0]}</strong>
                <span>{u[1]}</span>
              </div>
              <em>
                {u[3]} cdes
                <br />
                {u[4]} pts
              </em>
            </div>
          ))}
        </aside>
      </div>
      <section className="panel">
        <div className="panel-title">
          <h2>Top 10 — Les Conquérants du Campus</h2>
          <span>Votre position : 4e</span>
        </div>
        <div className="table-scroll">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Membre</th>
                <th>Filière</th>
                <th>Grade</th>
                <th>Commandes</th>
                <th>Points</th>
              </tr>
            </thead>
            <tbody>
              {users.map((u, i) => (
                <tr key={u[0]} className={i === 3 ? "highlight" : ""}>
                  <td>{i + 1}</td>
                  <td>{u[0]}</td>
                  <td>{u[1]}</td>
                  <td>{u[2]}</td>
                  <td>{u[3]}</td>
                  <td>{u[4]}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </section>
    </>
  );
}

export function Loyalty() {
  const [copied, setCopied] = useState(false);
  const [filleuls] = useState([
    { nom: "K. Mbarga", date: "12/04", statut: "1re commande validée", pts: "+15" },
    { nom: "S. Nguema", date: "28/03", statut: "Inscrit", pts: "+5" },
    { nom: "A. Fotso", date: "05/03", statut: "1re commande validée", pts: "+15" },
  ]);
  const code = "ZEDUC-CAMPUS-24";

  const copyCode = () => {
    navigator.clipboard?.writeText(code).catch(() => {});
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <>
      <PageHeader
        eyebrow="Privilèges Campus · Validité annuelle 12 mois"
        title="Programme Fidélité & Parrainage"
        subtitle="Commandez au comptoir, cumulez des points convertibles en repas offerts et hissez-vous jusqu'au rang Zeduc Gold."
      />
      <div className="conversion-card">
        <div>
          <span>Formule de conversion</span>
          <b>1 000 F = 1 Pt</b>
        </div>
        <strong>15 Pts = 1 000 F net</strong>
        <div>
          <span>Points disponibles</span>
          <b>45 PTS</b>
        </div>
      </div>
      <div className="membership-card">
        <div className="member-logo">Z</div>
        <div>
          <span>ZEDUC-SP@CE</span>
          <h2>Carte de Membre Officielle</h2>
          <small>Grade Silver · Membre vérifié</small>
        </div>
        <div className="member-balance">
          <b>3 000 FCFA</b>
          <span>Valeur déductible immédiate</span>
        </div>
      </div>

      <section className="panel">
        <div className="panel-title">
          <h2>Les 4 Grades Officiels ZEDUC-SP@CE</h2>
          <span>Votre fidélité augmente votre plafond de parrainage</span>
        </div>
        <div className="rank-grid">
          {ranks.map((r) => (
            <div className={`rank-card ${r.color}`} key={r.name}>
              <span>{r.step}</span>
              <h3>{r.name}</h3>
              <b>{r.condition}</b>
              <small>✓ Max {r.max}</small>
              <small>✓ Réductions et accès privilégiés</small>
            </div>
          ))}
        </div>
      </section>

      <div className="progress-layout">
        <div className="panel">
          <h2>Objectif Grade Suprême</h2>
          <div className="goal">
            <span>Commandes confirmées</span>
            <b>78 / 100</b>
            <div className="progress">
              <div className="progress-bar" style={{ width: "78%" }} />
            </div>
            <small>Plus que 22 commandes pour valider le critère repas</small>
          </div>
          <div className="goal">
            <span>Points cumulés</span>
            <b>112 / 150</b>
            <div className="progress">
              <div className="progress-bar" style={{ width: "75%" }} />
            </div>
            <small>Plus que 38 points requis pour basculer en statut Gold</small>
          </div>
        </div>

        {/* Bloc parrainage enrichi pour les étudiants */}
        <div className="panel referral">
          <h2>Parrainage Universitaire</h2>
          <p>
            Invitez vos camarades : votre filleul bénéficie de <b>10 % de réduction</b> sur sa
            première commande, et vous gagnez des points dès sa première validation.
          </p>
          <div className="ref-code">{code}</div>
          <div className="button-row">
            <button className="btn-primary" type="button" onClick={copyCode}>
              {copied ? "Code copié ✓" : "Copier mon code"}
            </button>
            <button className="btn-outline" type="button">
              Partager par WhatsApp
            </button>
          </div>
          <div className="referral-stats">
            <div>
              <b>12</b>
              <span>Filleuls actifs</span>
            </div>
            <div>
              <b>+35 pts</b>
              <span>Gagnés ce mois</span>
            </div>
            <div>
              <b>60</b>
              <span>Plafond Silver</span>
            </div>
          </div>
          <h3 style={{ marginTop: 20, fontSize: 15 }}>Mes derniers filleuls</h3>
          <ul className="filleul-list">
            {filleuls.map((f) => (
              <li key={f.nom}>
                <strong>{f.nom}</strong>
                <span>{f.date} · {f.statut}</span>
                <em>{f.pts}</em>
              </li>
            ))}
          </ul>
          <p className="muted" style={{ marginTop: 12, fontSize: 13 }}>
            Règles : le code est personnel. Points crédités après la 1re commande payée du filleul.
            Plafond selon votre grade (voir cartes ci-dessus).
          </p>
        </div>
      </div>
    </>
  );
}

export function Events() {
  const events = [
    {
      id: 1,
      badge: "TOURNOI · SPORT & FOOD",
      title: "Tournoi Gaming & Délices FC 24",
      image: "/assets/menu/burger-zeduc.png",
      lieu: "Terrasse Panoramique UCAC-Icam",
      pts: "+150 pts Zeduc",
      date: "Vendredi 16 Mai · 18h00 – 22h30",
      places: "14 places restantes sur 50",
      desc: "Affrontez les meilleurs stratèges du campus manette en main tout en dégustant nos mini-burgers smashés au bœuf d'Adamaoua et nos frites de patates douces maison. Boissons fraîches artisanales incluses.",
      tarif: "3 500 FCFA / repas inclus",
      tarifLabel: "Tarif Membre Silver",
      cta: "Réserver ma place",
      ctaClass: "btn-outline",
    },
    {
      id: 2,
      badge: "MASTERCLASS GASTRONOMIQUE",
      title: "Masterclass Chef Exécutif : Grillades Secrètes",
      image: "/assets/menu/poulet-braise.png",
      lieu: "Esplanade Bâtiment C",
      pts: "+200 pts Zeduc",
      date: "Dimanche 18 Mai · 14h30 – 17h00",
      places: "8 places restantes sur 25",
      desc: "Plongez dans l'art de la marinade camerounaise revisitée par le Chef Martial. Atelier participatif, découpe, fumage au bois noble et dégustation d'accords mets-jus pressés minute. Tablier et fiche recettes offerts.",
      tarif: "5 000 FCFA / atelier complet",
      tarifLabel: "Tarif Exclusif Étudiant",
      cta: "Participer à l'atelier",
      ctaClass: "btn-primary",
    },
    {
      id: 3,
      badge: "SOIRÉE CHILL CAMPUS",
      title: "Soirée Acoustique & Planches Tapas",
      image: "/assets/menu/soya.png",
      lieu: "Patio Central ZEDUC",
      pts: "+80 pts Zeduc",
      date: "Samedi 25 Mai · 19h00 – 23h00",
      places: "22 places restantes sur 40",
      desc: "Ambiance intimiste avec artistes campus, planches de soya, koki mini et jus artisanaux. Réservation recommandée, places limitées pour garantir le confort.",
      tarif: "2 500 FCFA / entrée + planche",
      tarifLabel: "Tarif Membre",
      cta: "Réserver ma place",
      ctaClass: "btn-outline",
    },
  ];

  return (
    <>
      <PageHeader
        eyebrow="PROGRAMME CULINAIRE & CONVIVIALITÉ · The Climate of Taste & Chill"
        title="Événements Campus & Masterclass"
        subtitle="Participez aux tournois gastronomiques, dégustations exclusives et soirées chill sur le campus UCAC-Icam. Cumulez des points Zeduc et partagez des moments inoubliables."
      />

      <div className="event-status-bar">
        <span className="eyebrow">Inscriptions en cours & Temps Restant</span>
        <span className="places-badge">Places limitées · Clôture imminente</span>
      </div>

      <div className="event-rich-grid">
        {events.map((e) => (
          <article className="event-rich-card" key={e.id}>
            <div className="event-rich-media">
              <img src={e.image} alt={e.title} />
              <span className="event-rich-badge">{e.badge}</span>
              <span className="event-rich-pts">{e.pts}</span>
            </div>
            <div className="event-rich-body">
              <h3>{e.title}</h3>
              <p className="event-lieu">📍 {e.lieu}</p>
              <p className="event-date-line">
                📅 {e.date} · {e.places}
              </p>
              <p className="event-desc">{e.desc}</p>
              <div className="event-rich-footer">
                <div>
                  <small>{e.tarifLabel}</small>
                  <b>{e.tarif}</b>
                </div>
                <button className={e.ctaClass} type="button">
                  {e.cta}
                </button>
              </div>
            </div>
          </article>
        ))}
      </div>

      <section className="panel" style={{ marginTop: 28 }}>
        <div className="panel-title">
          <h2>Vainqueurs & Palmarès des Derniers Événements</h2>
        </div>
        <div className="winner-grid">
          <div>
            <b>🥇 Participant 01</b>
            <span>Tournoi FIFA Campus</span>
          </div>
          <div>
            <b>🥈 Participant 02</b>
            <span>Meilleur Pitch Étudiant</span>
          </div>
          <div>
            <b>🥇 Duo Équipe gagnante</b>
            <span>Challenge Chill</span>
          </div>
        </div>
      </section>

      <section className="ambiance-banner">
        <div>
          <span className="eyebrow">L&apos;Ambiance Chill</span>
          <h2>Des soirées pensées pour la communauté.</h2>
          <p>
            Réservez votre place depuis l&apos;application et recevez un rappel avant l&apos;ouverture
            des portes.
          </p>
        </div>
        <Link className="btn-outline" to="/etudiant/menu">
          Retour au menu
        </Link>
      </section>
    </>
  );
}

export function Profile() {
  return (
    <>
      <PageHeader
        eyebrow="Compte étudiant · Session Active · Terminal Web Authentifié"
        title="Membre étudiant"
        subtitle="Zeduc Silver • Membre Vérifié UCAC-Icam · Matricule sécurisé · Campus Douala"
      />
      <div className="profile-hero panel">
        <img src="/assets/avatar.png" alt="Profil étudiant" />
        <div>
          <h2>Membre étudiant</h2>
          <span>Zeduc Silver • Membre Vérifié UCAC-Icam</span>
          <p>
            Solde Fidélité <b>112 pts</b>
          </p>
        </div>
        <button className="btn-outline" type="button">
          Déconnexion
        </button>
      </div>
      <div className="progress-layout">
        <section className="panel">
          <h2>
            Élevez votre statut : Devenez <em>Zeduc Gold</em>
          </h2>
          <p>
            Rejoignez l&apos;élite gourmande de l&apos;UCAC-Icam. Débloquez un plafond élargi à 100
            filleuls, des remises instantanées majorées et vos invitations VIP.
          </p>
          <div className="goal">
            <span>Avancement Niveau Gold</span>
            <b>78%</b>
            <div className="progress">
              <div className="progress-bar" style={{ width: "78%" }} />
            </div>
          </div>
          <div className="mini-metrics">
            <MetricCard label="Filleuls" value="12" icon="↗" />
            <MetricCard label="Commandes" value="78" icon="▣" />
            <MetricCard label="Points" value="112" icon="★" />
          </div>
        </section>
        <aside className="panel">
          <h2>Voies d&apos;accès</h2>
          <div className="access-card">
            <b>Voie 1 · Consommation</b>
            <span>Plus que 22 commandes au comptoir ou en livraison.</span>
          </div>
          <div className="access-card">
            <b>Voie 2 · Parrainage Express</b>
            <span>Parrainez 3 nouveaux camarades avec premier plat commandé.</span>
          </div>
        </aside>
      </div>
      <section className="panel form-panel">
        <h2>Informations Personnelles &amp; Livraison</h2>
        <p className="muted">
          Données enregistrées pour vos réceptions aux tables, bureaux et pavillons UCAC-Icam.
        </p>
        <div className="form-grid three">
          <label>
            Nom Complet
            <input defaultValue="Membre étudiant" />
          </label>
          <label>
            Numéro Téléphone / WhatsApp
            <input defaultValue="+237 6 90 00 00 00" />
          </label>
          <label>
            Adresse de livraison habituelle
            <select>
              <option>Pavillon B • R+2</option>
              <option>Pavillon A • RDC</option>
            </select>
          </label>
        </div>
        <div className="preference-row">
          <span>Préférences Culinaires &amp; Régimes Particuliers</span>
          {["Sans piment", "Végétarien strict", "Sans arachides", "Sans lactose"].map((x) => (
            <label key={x}>
              <input type="checkbox" />
              {x}
            </label>
          ))}
        </div>
        <button className="btn-primary" type="button">
          Sauvegarder les modifications
        </button>
      </section>
      <section className="panel">
        <h2>Sécurité du Compte &amp; Confidentialité</h2>
        <div className="form-grid">
          <label>
            Nouveau mot de passe
            <input type="password" placeholder="Minimum 8 caractères" />
          </label>
          <label>
            Confirmer le mot de passe
            <input type="password" />
          </label>
        </div>
        <button className="btn-outline" type="button">
          Mettre à jour le mot de passe
        </button>
        <div className="settings-list">
          <div>
            <b>Conformité RGPD &amp; Cookies Campus</b>
            <span>Données chiffrées strictement restreintes aux services de cantine UCAC.</span>
          </div>
          <div>
            <b>Alertes &amp; Notifications</b>
            <span>
              Commandes prêtes · crédits parrainage · arbitrage · offres éphémères chef.
            </span>
          </div>
        </div>
      </section>
    </>
  );
}

export function Claims() {
  return (
    <>
      <PageHeader
        eyebrow="Support étudiant · Arbitrage"
        title="Réclamations d'Incident"
        subtitle="Suivez les incidents, justificatifs et décisions d'arbitrage."
        actions={
          <button className="btn-primary" type="button">
            + Déposer une réclamation
          </button>
        }
      />
      <div className="claim-types">
        <div>
          <b>1. Sur place</b>
          <span>Erreur plat, attente &gt;25 min</span>
        </div>
        <div>
          <b>2. Livré</b>
          <span>Retard coursier, plat tiède</span>
        </div>
        <div>
          <b>3. Qualité</b>
          <span>Goût, température, hygiène</span>
        </div>
      </div>
      <section className="panel">
        <div className="panel-title">
          <h2>Mes réclamations récentes</h2>
        </div>
        <div className="table-scroll">
          <table>
            <thead>
              <tr>
                <th>Réf.</th>
                <th>Type</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Décision</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>RC-1042</td>
                <td>Sur place</td>
                <td>02/05</td>
                <td>Résolu</td>
                <td>Avoir 1 000 F</td>
              </tr>
              <tr>
                <td>RC-1038</td>
                <td>Livré</td>
                <td>28/04</td>
                <td>En cours</td>
                <td>—</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </>
  );
}
