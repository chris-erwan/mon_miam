export default function StatCard({title,value,caption,progress}) {
 return <div className="stat-card"><div><span>{title}</span><b>{value}</b><small>{caption}</small></div>{progress!==undefined&&<div className="progress slim"><div className="progress-bar" style={{width:`${progress}%`}}></div></div>}</div>;
}
