import { useApp } from "../context/AppContext";
export default function FoodCard({ food, compact=false }) {
  const { addToCart }=useApp();
  return <article className={`food-card ${compact?"compact":""}`}>
    <div className="food-img"><img src={food.image} alt={food.name}/><span className="food-tag">{food.points ? `+${food.points} pts` : "Nouveau"}</span></div>
    <div className="food-body"><small>{food.category}</small><h3>{food.name}</h3><p>{food.desc}</p><div className="food-bottom"><b>{food.price.toLocaleString("fr-FR")} <small>FCFA</small></b><span>★ {food.rating||"4.8"}</span><button className="add-btn" onClick={()=>addToCart(food)}>+ Ajouter</button></div></div>
  </article>;
}
