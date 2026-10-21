import { Link } from "react-router-dom";
import Brand from "../components/Brand";
import { foods } from "../data/zeduc";
import FoodCard from "../components/FoodCard";

export default function Landing() {
  return (
    <div className="landing">
      <nav className="landing-nav">
        <Brand />
        <div className="landing-links">
          <a href="#menu">Menu du jour</a>
          <a href="#offres">Offres</a>
          <a href="#campus">Campus</a>
          <Link className="btn-outline" to="/connexion">Se connecter</Link>
          <Link className="btn-primary" to="/inscription">Créer mon compte</Link>
        </div>
      </nav>

      <section className="hero-landing">
        <div className="hero-copy">
          <span className="eyebrow">ZEDUC-SP@CE · UCAC-ICAM · via Mon Miam Miam</span>
          <h1>The Space to <em>Be</em>.</h1>
          <p>La restauration universitaire moderne pensée pour manger, commander, cumuler et profiter du campus sans friction.</p>
          <div className="hero-actions">
            <Link className="btn-primary btn-lg" to="/etudiant/menu">Découvrir le menu <span>→</span></Link>
            <Link className="text-link" to="/connexion">J&apos;ai déjà un compte</Link>
          </div>
          <div className="hero-trust">
            <span>✓ Paiement sécurisé</span>
            <span>✓ Livraison campus</span>
            <span>✓ Points fidélité</span>
          </div>
        </div>
        <div className="hero-visual">
          <div className="hero-logo">
            <img src="/assets/zeduc-logo.png" alt="Zeduc Space" />
          </div>
          <div className="floating-card">
            <b>Service Midi</b>
            <span>Affluence normale</span>
            <strong>8 min</strong>
          </div>
        </div>
      </section>

      <section className="feature-strip">
        <div><b>Commande express</b><span>Retrait au comptoir ou livraison intra-campus.</span></div>
        <div><b>Carte Resto</b><span>Solde, paiements et historique centralisés.</span></div>
        <div><b>Fidélité Zeduc</b><span>Transformez vos commandes en privilèges.</span></div>
      </section>

      <section id="menu" className="landing-section">
        <div className="section-title">
          <div>
            <span className="eyebrow">Aujourd&apos;hui sur le campus</span>
            <h2>Menu du Jour</h2>
          </div>
          <Link to="/etudiant/menu">Voir tout le menu →</Link>
        </div>
        <div className="food-grid">
          {foods.slice(0, 3).map((f) => <FoodCard key={f.id} food={f} />)}
        </div>
      </section>

      <section id="offres" className="offer-banner">
        <div>
          <span className="eyebrow">Offres spéciales</span>
          <h2>Happy Hour Smoothie &amp; Pâtisserie</h2>
          <p>De 15h00 à 18h30 · doublez vos points sur une sélection fraîche.</p>
        </div>
        <Link className="btn-primary" to="/etudiant/fidelite">Profiter de l&apos;offre</Link>
      </section>

      <section id="campus" className="landing-section campus-section">
        <div className="section-title">
          <div>
            <span className="eyebrow">Vie du campus</span>
            <h2>Événements Campus &amp; Chill</h2>
          </div>
          <Link to="/etudiant/evenements">Voir les événements →</Link>
        </div>
        <div className="event-preview">
          <div>
            <span>16 MAI</span>
            <h3>Tournoi Gaming &amp; Délices FC 24</h3>
            <p>Compétition inter-promotions, mini-burgers et frites maison.</p>
          </div>
          <div>
            <span>18 MAI</span>
            <h3>Masterclass Chef Exécutif</h3>
            <p>Grillades secrètes, marinade camerounaise et accords mets-jus.</p>
          </div>
          <div>
            <span>25 MAI</span>
            <h3>Soirée Acoustique &amp; Planches</h3>
            <p>Ambiance chill, musique live et street-food Zeduc.</p>
          </div>
        </div>
      </section>

      <footer className="landing-footer">
        <Brand />
        <p>© 2025 ZEDUC-SP@CE · Propulsé par Mon Miam Miam</p>
        <Link to="/" className="text-link" style={{ marginTop: 8, display: "inline-block" }}>← Retour à Mon Miam Miam</Link>
      </footer>
    </div>
  );
}
