export default function Icon({ name, size = 20, className = "" }) {
  const paths = {
    menu: <><path d="M4 6h16M4 12h16M4 18h16"/></>,
    close: <><path d="M6 6l12 12M18 6L6 18"/></>,
    arrow: <><path d="M5 12h14M13 6l6 6-6 6"/></>,
    bell: <><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></>,
    cart: <><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/><path d="M3 4h2l2.2 11.1a2 2 0 0 0 2 1.6h8.8a2 2 0 0 0 1.9-1.4L21 8H6"/></>,
    search: <><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></>,
    plus: <><path d="M12 5v14M5 12h14"/></>,
    minus: <><path d="M5 12h14"/></>,
    check: <><path d="m5 12 4 4L19 6"/></>,
    chevron: <><path d="m9 18 6-6-6-6"/></>,
    home: <><path d="m3 11 9-8 9 8v9H3z"/><path d="M9 20v-6h6v6"/></>,
    download: <><path d="M12 3v12M7 10l5 5 5-5M5 21h14"/></>,
    edit: <><path d="m4 20 4.5-1 10-10a2.1 2.1 0 0 0-3-3l-10 10zM14.5 7.5l3 3"/></>,
    trash: <><path d="M4 7h16M10 11v6M14 11v6M6 7l1 14h10l1-14M9 7V4h6v3"/></>,
    settings: <><path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/><path d="m19.4 15 .1.1a2 2 0 0 1-2.8 2.8l-.1-.1a2 2 0 0 0-3.4 1.4v.2a2 2 0 0 1-4 0v-.2a2 2 0 0 0-3.4-1.4l-.1.1A2 2 0 0 1 3 15.1l.1-.1a2 2 0 0 0-1.4-3.4h-.2a2 2 0 0 1 0-4h.2A2 2 0 0 0 3.1 4.2L3 4.1A2 2 0 0 1 5.8 1.3l.1.1a2 2 0 0 0 3.4-1.4V0a2 2 0 0 1 4 0v.2a2 2 0 0 0 3.4 1.4l.1-.1A2 2 0 0 1 19.6 4l-.1.1A2 2 0 0 0 20.9 7.5h.2a2 2 0 0 1 0 4h-.2a2 2 0 0 0-1.5 3.5Z"/></>,
  };
  const p = paths[name] || <circle cx="12" cy="12" r="8"/>;
  return <svg className={className} width={size} height={size} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">{p}</svg>;
}
