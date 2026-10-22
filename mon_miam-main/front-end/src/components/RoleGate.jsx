import { Navigate, Outlet, useLocation } from "react-router-dom";
import { useApp } from "../context/AppContext";

export default function RoleGate({ allowed }) {
  const { user } = useApp();
  const location = useLocation();

  if (!user) {
    return <Navigate to="/connexion" replace state={{ from: location.pathname }} />;
  }

  if (!allowed.includes(user.role)) {
    const home = {
      student: "/etudiant/menu",
      employe: "/employe/kds",
      gerant: "/gerant/commandes",
      reseau: "/reseau/dashboard"
    }[user.role] || "/";
    return <Navigate to={home} replace />;
  }

  return <Outlet />;
}
