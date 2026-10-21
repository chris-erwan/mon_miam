export default function MetricCard({label,value,detail,icon,trend}) {
  return <div className="metric-card"><div className="metric-top"><span className="metric-icon">{icon}</span>{trend&&<span className="trend">{trend}</span>}</div><b>{value}</b><span>{label}</span>{detail&&<small>{detail}</small>}</div>;
}
