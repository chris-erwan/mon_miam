import { NavLink, Outlet, useNavigate } from "react-router-dom";
import { useState } from "react";
import { studentNav } from "../data/zeduc";
import { useApp } from "../context/AppContext";
import Icon from "./Icon";
import Brand from "./Brand";

export default function StudentLayout() {
  const { cartCount, user, logout, theme, setTheme, notifications, setNotifications } = useApp();
  const [open, setOpen] = useState(false);
  const navigate = useNavigate();

  return <div className="student-shell">
    <aside className={`student-sidebar ${open ? "open" : ""}`}>
      <div className="sidebar-brand"><Brand light /></div>
      <div className="student-status">
        <span className="material-dot"></span><div><small>Solde Carte Resto</small><b>Silver · 14 250 FCFA</b></div>
      </div>
      <nav className="side-nav">
        {studentNav.map(([to,label,icon]) => <NavLink key={to} to={to} onClick={() => setOpen(false)} className={({isActive}) => isActive ? "active" : ""}><span className="mi">{icon}</span>{label}{label.startsWith("Panier") && cartCount > 0 && <span className="nav-count">{cartCount}</span>}</NavLink>)}
      </nav>
      <div className="sidebar-bottom">
        <div className="location-box"><b>Comptoir Principal UCAC-Icam</b><small>Affluence : Normale · 8 min</small></div>
        <button className="side-user" onClick={() => navigate("/etudiant/profil")}><img src="/assets/avatar.png" /><span><b>{user?.name || "Membre étudiant"}</b><small>Zeduc Silver · Membre vérifié</small></span></button>
      </div>
    </aside>
    <div className="student-main">
      <header className="topbar">
        <button className="icon-btn mobile-only" onClick={() => setOpen(v=>!v)}><Icon name="menu"/></button>
        <div className="crumb">Campus UCAC-Icam <span>•</span> Service Midi 11h30 – 14h45</div>
        <div className="top-actions">
          <button className={`icon-btn ${notifications ? "has-dot" : ""}`} onClick={() => setNotifications(v=>!v)} title="Notifications"><Icon name="bell"/></button>
          <button className="avatar-btn" onClick={() => navigate("/etudiant/profil")}><img src="/assets/avatar.png" alt="" /></button>
          <button className="theme-toggle" onClick={() => setTheme(theme === "dark" ? "light" : "dark")}>{theme === "dark" ? "Clair" : "Sombre"}</button>
          <button className="logout-mini" onClick={logout}>Déconnexion</button>
        </div>
      </header>
      <main className="page-container"><Outlet /></main>
    </div>
  </div>;
}
