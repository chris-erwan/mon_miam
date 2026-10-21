import { useState } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import Brand from "../components/Brand";
import { useApp } from "../context/AppContext";

/**
 * Authentification sans sélecteur de rôle visible.
 * Le rôle est déterminé côté serveur (ou simulé en offline) à partir des identifiants.
 * L'utilisateur n'est jamais informé des autres profils (employé, gérant, direction).
 */
export default function Auth({ mode = "login" }) {
  const isLogin = mode === "login";
  const { login, register } = useApp();
  const nav = useNavigate();
  const location = useLocation();
  const [form, setForm] = useState({ nom: "", email: "", password: "" });
  const [error, setError] = useState("");

  const submit = async (e) => {
    e.preventDefault();
    setError("");
    // Plus de role passé par le client : le backend (ou la simulation) décide
    const r = isLogin
      ? await login(form.email, form.password)
      : register(form);
    if (!r.success) {
      setError(r.message || "Une erreur est survenue.");
      return;
    }
    const fallback = {
      student: "/etudiant/menu",
      employe: "/employe/kds",
      gerant: "/gerant/commandes",
      reseau: "/reseau/dashboard",
    }[r.user.role] || "/etudiant/menu";
    nav(location.state?.from || fallback, { replace: true });
  };

  return (
    <div className="auth-page">
      <div className="auth-card">
        <div className="auth-brand-panel">
          <Brand light />
          <div className="auth-quote">
            <span>THE CLIMATE OF TASTE &amp; CHILL</span>
            <h1>Un campus qui se vit aussi autour de la table.</h1>
            <p>
              Connectez-vous pour accéder à votre espace personnel : commandes, fidélité, événements
              et services adaptés à votre profil.
            </p>
          </div>
          <div className="auth-benefits">
            <span>✓ Carte Resto</span>
            <span>✓ Livraison Campus</span>
            <span>✓ Espace sécurisé</span>
          </div>
        </div>

        <div className="auth-form-panel">
          <div className="auth-switch">
            <Link className={isLogin ? "active" : ""} to="/connexion">Connexion</Link>
            <Link className={!isLogin ? "active" : ""} to="/inscription">Inscription</Link>
          </div>

          <h2>{isLogin ? "Accéder à mon espace" : "Créer mon compte étudiant"}</h2>
          <p className="muted">
            {isLogin
              ? "Utilisez vos identifiants institutionnels. L'accès à votre interface est automatique."
              : "Les comptes créés ici sont des comptes étudiants Mon Miam Miam / ZEDUC Space."}
          </p>

          <form onSubmit={submit}>
            {!isLogin && (
              <label>
                Nom complet
                <input
                  required
                  value={form.nom}
                  onChange={(e) => setForm({ ...form, nom: e.target.value })}
                />
              </label>
            )}
            <label>
              Email institutionnel
              <input
                required
                type="email"
                value={form.email}
                onChange={(e) => setForm({ ...form, email: e.target.value })}
                placeholder="votre.email@campus.cm"
              />
            </label>
            <label>
              Mot de passe
              <input
                required
                type="password"
                value={form.password}
                onChange={(e) => setForm({ ...form, password: e.target.value })}
              />
            </label>
            {error && <div className="form-error">{error}</div>}
            {isLogin && (
              <div className="forgot">
                <a href="#forgot">Mot de passe oublié ?</a>
              </div>
            )}
            <button className="btn-primary w-100" type="submit">
              {isLogin ? "Se connecter →" : "Créer mon compte étudiant"}
            </button>
          </form>

          <div className="auth-note">
            Accès sécurisé. Votre interface s&apos;adapte automatiquement à votre profil.
            <br />
            <Link to="/" className="text-link" style={{ marginTop: 6, display: "inline-block" }}>
              ← Retour à Mon Miam Miam
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}
