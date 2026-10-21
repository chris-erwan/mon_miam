import { Link } from "react-router-dom";

const restaurants = [
  {
    id: "zeduc",
    name: "ZEDUC Space",
    tagline: "The climate of taste & chill",
    campus: "UCAC-Icam · Douala",
    status: "Ouvert",
    affluence: "Normale · 8 min",
    logo: "/assets/zeduc-logo.png",
    path: "/zeduc",
    featured: true,
  },
  {
    id: "coming-1",
    name: "Bientôt disponible",
    tagline: "Nouveau restaurant partenaire",
    campus: "Campus à venir",
    status: "Bientôt",
    affluence: "—",
    logo: null,
    path: null,
    featured: false,
  },
];

export default function RestaurantSelector() {
  return (
    <div className="mm-selector">
      <header className="mm-header">
        <div className="mm-logo-wrap">
          <img src="/assets/mon-miam-miam-logo.png" alt="Mon Miam Miam" className="mm-logo" />
        </div>
        <p className="mm-tagline">Là où tous vos restaurants préférés se trouvent</p>
      </header>

      <section className="mm-restaurants">
        <h2>Choisissez votre restaurant</h2>
        <p className="mm-subtitle">Sélectionnez un établissement pour accéder à son espace, son menu et ses services.</p>

        <div className="mm-grid">
          {restaurants.map((r) => (
            <div key={r.id} className={`mm-card ${r.featured ? "featured" : "disabled"}`}>
              <div className="mm-card-top">
                {r.logo ? (
                  <img src={r.logo} alt={r.name} className="mm-resto-logo" />
                ) : (
                  <div className="mm-resto-placeholder">+</div>
                )}
                <span className={`mm-badge ${r.status === "Ouvert" ? "open" : "soon"}`}>{r.status}</span>
              </div>
              <div className="mm-card-body">
                <h3>{r.name}</h3>
                <p className="mm-card-tagline">{r.tagline}</p>
                <div className="mm-meta">
                  <span>{r.campus}</span>
                  {r.affluence !== "—" && <span>Affluence : {r.affluence}</span>}
                </div>
              </div>
              {r.path ? (
                <Link to={r.path} className="btn-primary mm-enter">
                  Entrer dans l&apos;espace →
                </Link>
              ) : (
                <button className="btn-outline mm-enter" disabled>
                  Bientôt disponible
                </button>
              )}
            </div>
          ))}
        </div>
      </section>

      <footer className="mm-footer">
        <p>© 2025 Mon Miam Miam · Plateforme de gestion multi-restaurants</p>
        <div className="mm-auth-links">
          <Link to="/connexion">Se connecter</Link>
          <Link to="/inscription">Créer un compte</Link>
        </div>
      </footer>
    </div>
  );
}
