import { Link } from "react-router-dom";

export default function Brand({ light = false }) {
  return <Link className={`brand ${light ? "brand-light" : ""}`} to="/">
    <img src="/assets/zeduc-logo.png" alt="ZEDUC-SP@CE" />
    <span><b>ZEDUC-SP@CE</b><small>The climate of taste & chill.</small></span>
  </Link>;
}
