import { useState } from "react";
import { Link } from "react-router-dom";
import { useApp } from "../context/AppContext";
import PageHeader from "../components/PageHeader";
export default function Cart(){
 const {cart,cartTotal,changeQty,orders,placeOrder}=useApp();
 const [reception,setReception]=useState("Sur place");
 const [destination,setDestination]=useState("Comptoir principal UCAC-Icam");
 const [message,setMessage]=useState("");
 const confirm=()=>{
  const r=placeOrder({reception,destination});
  setMessage(r.success ? `Commande ${r.order.id} enregistrée et transmise à la cuisine.` : r.message);
 };
 return <><PageHeader eyebrow="Espace Restaurant Universitaire / Panier & Suivi d'Activité" title="Panier & Commandes" subtitle="Le panier est partagé avec le menu et persiste dans le navigateur."/>
 <div className="cart-layout"><section>
  <div className="panel"><div className="panel-title"><h2>Panier Actif</h2><span>{cart.reduce((s,i)=>s+i.qty,0)} articles</span></div>
   {cart.length===0?<div className="empty-state"><b>Votre panier est vide</b><p>Ajoutez un plat depuis le menu pour commencer.</p><Link className="btn-primary" to="/etudiant/menu">Retour au menu</Link></div>:
   cart.map(i=><div className="cart-item" key={i.id}><img src={i.image} alt=""/><div className="cart-item-info"><b>{i.name}</b><span>{i.desc}</span><small>{i.price.toLocaleString("fr-FR")} FCFA · +{i.points} pts resto</small></div><div className="qty"><button onClick={()=>changeQty(i.id,-1)} aria-label="Diminuer">−</button><b>{i.qty}</b><button onClick={()=>changeQty(i.id,1)} aria-label="Augmenter">+</button></div><strong>{(i.price*i.qty).toLocaleString("fr-FR")} F</strong></div>)}
  </div>
  <div className="panel"><div className="panel-title"><h2>Mode de Réception</h2></div><div className="choice-grid">
   {["Sur place","Livraison Campus"].map(x=><label className={`choice ${reception===x?"active":""}`} key={x}><input type="radio" name="delivery" checked={reception===x} onChange={()=>setReception(x)}/><b>{x}</b><span>{x==="Sur place"?"Table ou comptoir express":"Pavillon & bâtiments du campus"}</span></label>)}
  </div><div className="form-grid"><label>Destination campus<select value={destination} onChange={e=>setDestination(e.target.value)}><option>Comptoir principal UCAC-Icam</option><option>Pavillon A</option><option>Pavillon B</option><option>Médiathèque centrale</option></select></label></div></div>
  <div className="panel"><div className="panel-title"><h2>Historique des Commandes</h2><span>{orders.length} commande(s)</span></div>{orders.length===0?<p className="muted">Aucune commande enregistrée sur ce navigateur.</p>:orders.map(o=><div className="order-row" key={o.id}><div><b>CMD #{o.id}</b><small>{o.date} · {o.status}</small><span>{o.item}</span></div><strong>{o.total.toLocaleString("fr-FR")} FCFA</strong></div>)}</div>
 </section><aside className="summary-panel"><span className="eyebrow">Panier local ZEDUC</span><h2>Résumé</h2><div><span>Sous-total articles</span><b>{cartTotal.toLocaleString("fr-FR")} FCFA</b></div><div><span>Frais service & emballage</span><b>0 FCFA</b></div><div><span>Remise fidélité Zeduc</span><b className="positive">− 1 000 F</b></div><hr/><div className="total"><span>Total net à payer</span><b>{Math.max(0,cartTotal-1000).toLocaleString("fr-FR")} FCFA</b></div><button className="btn-primary w-100" disabled={!cart.length} onClick={confirm}>Confirmer et enregistrer →</button>{message&&<div className="success-banner">{message}</div>}<small>Le paiement réel sera branché à l'API et au moyen de paiement choisi.</small></aside></div></>
}
