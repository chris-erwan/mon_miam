import { NavLink, Outlet } from "react-router-dom";
import { useState } from "react";
import { adminNav } from "../data/zeduc";
import Icon from "./Icon";
import Brand from "./Brand";
import { useApp } from "../context/AppContext";

export default function AdminLayout({ type = "gerant" }) {
  const [open,setOpen]=useState(false);
  const { theme,setTheme,notifications,setNotifications,user,logout }=useApp();
  const nav=adminNav[type];
  const labels={employe:"Espace Employé",gerant:"Espace Gérant",reseau:"Direction Réseau"};
  const roleDescriptions={employe:"Opérations cuisine & service",gerant:"Pilotage du point de restauration",reseau:"Gouvernance multi-sites"};
  return <div className="admin-shell">
    <aside className={`admin-sidebar ${open?"open":""}`}>
      <div className="admin-brand"><Brand light /></div>
      <div className="workspace-title"><span className="workspace-icon">{type==="employe"?"KDS":type==="gerant"?"POS":"DG"}</span><div><b>{labels[type]}</b><small>{roleDescriptions[type]}</small></div></div>
      <nav className="side-nav admin-nav">
        {nav.map(([to,label,icon])=><NavLink key={to} to={to} onClick={()=>setOpen(false)} className={({isActive})=>isActive?"active":""}><span className="mi">{icon}</span>{label}</NavLink>)}
      </nav>
      <div className="admin-footer"><small>Session sécurisée</small><span>● En ligne</span></div>
    </aside>
    <div className="admin-main">
      <header className="admin-topbar">
        <button className="icon-btn mobile-only" onClick={()=>setOpen(v=>!v)}><Icon name="menu"/></button>
        <div><b>{labels[type]}</b><span className="muted"> / ZEDUC-SP@CE</span></div>
        <div className="top-actions">
          <button className={`icon-btn ${notifications?"has-dot":""}`} onClick={()=>setNotifications(v=>!v)} title="Notifications"><Icon name="bell"/></button>
          <button className="theme-toggle" onClick={()=>setTheme(theme==="dark"?"light":"dark")}>{theme==="dark"?"Clair":"Sombre"}</button>
          <span className="admin-profile"><b>{user?.name || labels[type]}</b><small>{user?.email || "Session authentifiée"}</small></span>
          <button className="logout-mini" onClick={logout}>Déconnexion</button>
        </div>
      </header>
      <main className="page-container"><Outlet /></main>
    </div>
  </div>;
}
