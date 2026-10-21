import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import StudentLayout from "./components/StudentLayout";
import AdminLayout from "./components/AdminLayout";
import RoleGate from "./components/RoleGate";
import RestaurantSelector from "./pages/RestaurantSelector";
import Landing from "./pages/Landing";
import Auth from "./pages/Auth";
import StudentMenu from "./pages/StudentMenu";
import Cart from "./pages/Cart";
import { Ranking, Loyalty, Events, Profile, Claims } from "./pages/StudentPages";
import {
  KDS,
  EmployeeMenu,
  EmployeeClaims,
  Production,
  ManagerOrders,
  ManagerDashboard,
  ManagerAccounts,
  ManagerClaims,
} from "./pages/OperationsPages";
import {
  NetworkEmployees,
  NetworkDashboard,
  NetworkMenu,
  NetworkEvents,
  NetworkQuality,
  NetworkSettings,
} from "./pages/NetworkPages";

export default function App() {
  return (
    <BrowserRouter>
      <Routes>
        {/* Racine Mon Miam Miam : sélection du restaurant */}
        <Route path="/" element={<RestaurantSelector />} />

        {/* Espace public ZEDUC Space */}
        <Route path="/zeduc" element={<Landing />} />

        <Route path="/connexion" element={<Auth mode="login" />} />
        <Route path="/inscription" element={<Auth mode="register" />} />

        {/* Interfaces protégées par rôle (rôle invisible pour l'utilisateur) */}
        <Route element={<RoleGate allowed={["student"]} />}>
          <Route path="/etudiant" element={<StudentLayout />}>
            <Route index element={<Navigate to="menu" replace />} />
            <Route path="menu" element={<StudentMenu />} />
            <Route path="panier" element={<Cart />} />
            <Route path="classement" element={<Ranking />} />
            <Route path="evenements" element={<Events />} />
            <Route path="fidelite" element={<Loyalty />} />
            <Route path="reclamations" element={<Claims />} />
            <Route path="profil" element={<Profile />} />
          </Route>
        </Route>

        <Route element={<RoleGate allowed={["employe"]} />}>
          <Route path="/employe" element={<AdminLayout type="employe" />}>
            <Route index element={<Navigate to="kds" replace />} />
            <Route path="kds" element={<KDS />} />
            <Route path="menu" element={<EmployeeMenu />} />
            <Route path="reclamations" element={<EmployeeClaims />} />
            <Route path="production" element={<Production />} />
          </Route>
        </Route>

        <Route element={<RoleGate allowed={["gerant"]} />}>
          <Route path="/gerant" element={<AdminLayout type="gerant" />}>
            <Route index element={<Navigate to="commandes" replace />} />
            <Route path="commandes" element={<ManagerOrders />} />
            <Route path="dashboard" element={<ManagerDashboard />} />
            <Route path="comptes" element={<ManagerAccounts />} />
            <Route path="reclamations" element={<ManagerClaims />} />
          </Route>
        </Route>

        <Route element={<RoleGate allowed={["reseau"]} />}>
          <Route path="/reseau" element={<AdminLayout type="reseau" />}>
            <Route index element={<Navigate to="dashboard" replace />} />
            <Route path="employes" element={<NetworkEmployees />} />
            <Route path="dashboard" element={<NetworkDashboard />} />
            <Route path="menu" element={<NetworkMenu />} />
            <Route path="evenements" element={<NetworkEvents />} />
            <Route path="qualite" element={<NetworkQuality />} />
            <Route path="parametres" element={<NetworkSettings />} />
          </Route>
        </Route>

        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </BrowserRouter>
  );
}
