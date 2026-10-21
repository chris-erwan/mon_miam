import { createContext, useContext, useEffect, useMemo, useState } from "react";
import { foods } from "../data/zeduc";

const AppContext = createContext(null);
const API_BASE = "http://localhost:3000/api";

const roleHomes = {
  student: "/etudiant/menu",
  employe: "/employe/kds",
  gerant: "/gerant/commandes",
  reseau: "/reseau/dashboard"
};

const seedOperationalOrders = [
  { id:"ZD-4092", time:"08:42", item:"Ndolé aux crevettes × 1", status:"En préparation", channel:"Sur place", total:2800 },
  { id:"ZD-4093", time:"08:44", item:"Poulet DG × 1", status:"En attente", channel:"Livraison Campus", total:2500 },
  { id:"ZD-4094", time:"08:47", item:"Haricots rouges & beignets × 1", status:"Prête", channel:"Sur place", total:2200 },
  { id:"ZD-4095", time:"08:49", item:"Eru & accompagnement × 1", status:"En livraison", channel:"Livraison Campus", total:2400 },
  { id:"ZD-4096", time:"08:53", item:"Poisson braisé × 1", status:"Prête", channel:"Sur place", total:3000 },
  { id:"ZD-4097", time:"09:02", item:"Bissap maison × 2", status:"Servie", channel:"Retrait express", total:1600 }
];

const nextStatus = {
  "En attente":"En préparation",
  "En préparation":"Prête",
  "Prête":"En livraison",
  "En livraison":"Servie",
  "Servie":"Servie"
};

export function AppProvider({ children }) {
  const [user, setUser] = useState(() => {
    try { return JSON.parse(localStorage.getItem("zeduc_user")) || null; } catch { return null; }
  });
  const [cart, setCart] = useState(() => {
    try { return JSON.parse(localStorage.getItem("zeduc_cart")) || []; } catch { return []; }
  });
  const [orders, setOrders] = useState(() => {
    try { return JSON.parse(localStorage.getItem("zeduc_orders")) || []; } catch { return []; }
  });
  const [operationalOrders, setOperationalOrders] = useState(() => {
    try {
      const saved = JSON.parse(localStorage.getItem("zeduc_operational_orders"));
      return Array.isArray(saved) && saved.length ? saved : seedOperationalOrders;
    } catch { return seedOperationalOrders; }
  });
  const [stock, setStock] = useState(() => {
    try { return JSON.parse(localStorage.getItem("zeduc_stock")) || {}; } catch { return {}; }
  });
  const [theme, setTheme] = useState(() => localStorage.getItem("zeduc_theme") || "light");
  const [notifications, setNotifications] = useState(true);
  const [query, setQuery] = useState("");

  useEffect(() => localStorage.setItem("zeduc_user", JSON.stringify(user)), [user]);
  useEffect(() => localStorage.setItem("zeduc_cart", JSON.stringify(cart)), [cart]);
  useEffect(() => localStorage.setItem("zeduc_orders", JSON.stringify(orders)), [orders]);
  useEffect(() => localStorage.setItem("zeduc_operational_orders", JSON.stringify(operationalOrders)), [operationalOrders]);
  useEffect(() => localStorage.setItem("zeduc_stock", JSON.stringify(stock)), [stock]);
  useEffect(() => {
    localStorage.setItem("zeduc_theme", theme);
    document.documentElement.dataset.theme = theme;
  }, [theme]);

  const filteredFoods = useMemo(() => {
    const q = query.trim().toLowerCase();
    return q ? foods.filter(f => `${f.name} ${f.category} ${f.desc}`.toLowerCase().includes(q)) : foods;
  }, [query]);

  const addToCart = (food) => {
    setCart(prev => {
      const found = prev.find(i => i.id === food.id);
      if (found) return prev.map(i => i.id === food.id ? { ...i, qty: i.qty + 1 } : i);
      return [...prev, { ...food, qty: 1 }];
    });
  };

  const changeQty = (id, delta) => setCart(prev => prev
    .map(i => i.id === id ? { ...i, qty: Math.max(0, i.qty + delta) } : i)
    .filter(i => i.qty > 0));

  const clearCart = () => setCart([]);
  const cartCount = cart.reduce((sum, i) => sum + i.qty, 0);
  const cartTotal = cart.reduce((sum, i) => sum + i.price * i.qty, 0);

  function placeOrder({ reception = "Sur place", destination = "Comptoir principal" } = {}) {
    if (!cart.length) return { success: false, message: "Le panier est vide." };
    const id = `ZD-${Date.now().toString().slice(-6)}`;
    const total = Math.max(0, cartTotal - 1000);
    const order = {
      id,
      date: new Date().toLocaleString("fr-FR", { day:"2-digit", month:"2-digit", hour:"2-digit", minute:"2-digit" }),
      status: "En préparation",
      total,
      item: cart.map(i => `${i.name} × ${i.qty}`).join(" · "),
      reception,
      destination
    };
    setOrders(prev => [order, ...prev]);
    setOperationalOrders(prev => [{ id, time:new Date().toLocaleTimeString("fr-FR", {hour:"2-digit", minute:"2-digit"}), item:order.item, status:order.status, channel:reception, total }, ...prev]);
    setCart([]);
    return { success:true, order };
  }

  function advanceOrder(id) {
    setOperationalOrders(prev => prev.map(o => o.id === id ? { ...o, status: nextStatus[o.status] || o.status } : o));
    setOrders(prev => prev.map(o => o.id === id ? { ...o, status: nextStatus[o.status] || o.status } : o));
  }

  function updateStock(foodId, value) {
    setStock(prev => ({ ...prev, [foodId]: Math.max(0, Number(value) || 0) }));
  }

  function resetDemoData() {
    setOperationalOrders(seedOperationalOrders);
    setOrders([]);
    setCart([]);
  }

  /**
   * Login sans rôle fourni par le client.
   * En production le backend renvoie le rôle. En mode démo offline :
   * - email contenant "employe" ou "agent" → employé
   * - email contenant "gerant" ou "manager" → gérant
   * - email contenant "reseau" ou "admin" ou "direction" → direction
   * - sinon → étudiant
   * L'utilisateur ne voit jamais la liste des rôles.
   */
  async function login(email, password) {
    try {
      const res = await fetch(`${API_BASE}/auth/login`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
      });
      if (res.ok) {
        const data = await res.json();
        const authenticatedUser = { ...data.user, role: data.user?.role || "student" };
        setUser(authenticatedUser);
        return { success: true, user: authenticatedUser };
      }
    } catch {}

    if (email && password) {
      const lower = String(email).toLowerCase();
      let role = "student";
      if (/employe|agent|cuisine|kds/.test(lower)) role = "employe";
      else if (/gerant|manager|responsable/.test(lower)) role = "gerant";
      else if (/reseau|admin|direction|network/.test(lower)) role = "reseau";

      const names = {
        student: "Membre étudiant",
        employe: "Agent de restauration",
        gerant: "Responsable de site",
        reseau: "Direction réseau",
      };
      const authenticatedUser = {
        name: names[role] || "Utilisateur ZEDUC",
        email,
        role,
      };
      setUser(authenticatedUser);
      return { success: true, offline: true, user: authenticatedUser };
    }
    return { success: false, message: "Veuillez renseigner vos identifiants." };
  }

  function register(form) {
    if (!form.nom || !form.email || !form.password) return { success:false, message:"Tous les champs obligatoires doivent être remplis." };
    const newUser = { name:form.nom, email:form.email, role:"student" };
    setUser(newUser);
    return { success:true, user:newUser };
  }

  const logout = () => setUser(null);
  const roleHome = user ? roleHomes[user.role] || "/" : "/connexion";

  const operationalStats = useMemo(() => {
    const total = operationalOrders.reduce((s,o)=>s+Number(o.total||0),0);
    const counts = operationalOrders.reduce((a,o)=>{a[o.status]=(a[o.status]||0)+1; return a;},{});
    return { total, count:operationalOrders.length, average:operationalOrders.length ? Math.round(total/operationalOrders.length) : 0, counts };
  }, [operationalOrders]);

  return <AppContext.Provider value={{
    user,setUser,cart,cartCount,cartTotal,addToCart,changeQty,clearCart,placeOrder,orders,
    operationalOrders,operationalStats,advanceOrder,resetDemoData,stock,updateStock,
    theme,setTheme,notifications,setNotifications,query,setQuery,filteredFoods,login,register,logout,roleHome
  }}>{children}</AppContext.Provider>;
}

export function useApp() {
  const ctx = useContext(AppContext);
  if (!ctx) throw new Error("useApp must be used within AppProvider");
  return ctx;
}
