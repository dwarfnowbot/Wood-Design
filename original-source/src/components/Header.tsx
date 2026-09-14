import { useEffect, useState } from "react";
import { Link, NavLink, useLocation } from "react-router-dom";
import { navLinks, siteConfig } from "../data/siteConfig";

export default function Header() {
  const [scrolled, setScrolled] = useState(false);
  const [menuOpen, setMenuOpen] = useState(false);
  const location = useLocation();
  const isHome = location.pathname === "/";

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 24);
    onScroll();
    window.addEventListener("scroll", onScroll);
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => {
    setMenuOpen(false);
  }, [location.pathname]);

  const solid = scrolled || !isHome || menuOpen;

  return (
    <header
      className={`fixed top-0 left-0 right-0 z-40 transition-all duration-500 ${
        solid ? "bg-ivory/95 backdrop-blur border-b border-stone-dark/60 shadow-sm" : "bg-transparent"
      }`}
    >
      <div className="mx-auto flex max-w-7xl items-center justify-between px-5 sm:px-8 py-4">
        <Link to="/" className="flex flex-col leading-none">
          <span
            className={`font-serif-display text-2xl sm:text-[1.7rem] tracking-wide ${
              solid ? "text-espresso" : "text-ivory"
            }`}
          >
            {siteConfig.brandName}
          </span>
          <span
            className={`text-[10px] sm:text-[11px] uppercase tracking-[0.25em] mt-0.5 ${
              solid ? "text-bronze" : "text-champagne"
            }`}
          >
            Kitchens &amp; Home Woodwork
          </span>
        </Link>

        <nav className="hidden lg:flex items-center gap-8">
          {navLinks.map((link) => (
            <NavLink
              key={link.to}
              to={link.to}
              className={({ isActive }) =>
                `text-sm uppercase tracking-wide transition-colors duration-300 ${
                  solid
                    ? isActive
                      ? "text-walnut-dark font-medium"
                      : "text-espresso-light hover:text-walnut-dark"
                    : isActive
                    ? "text-champagne font-medium"
                    : "text-ivory/90 hover:text-champagne"
                }`
              }
            >
              {link.label}
            </NavLink>
          ))}
        </nav>

        <div className="hidden lg:block">
          <Link
            to="/get-a-quote"
            className="inline-flex items-center justify-center rounded-full bg-bronze px-6 py-2.5 text-sm uppercase tracking-wide font-medium text-espresso transition-all duration-300 hover:bg-bronze-light"
          >
            Get a Quote
          </Link>
        </div>

        <button
          aria-label="Toggle menu"
          className={`lg:hidden flex h-10 w-10 flex-col items-center justify-center gap-1.5 ${
            solid ? "text-espresso" : "text-ivory"
          }`}
          onClick={() => setMenuOpen((v) => !v)}
        >
          <span
            className={`block h-[1.5px] w-6 bg-current transition-transform duration-300 ${
              menuOpen ? "translate-y-[7px] rotate-45" : ""
            }`}
          />
          <span
            className={`block h-[1.5px] w-6 bg-current transition-opacity duration-300 ${
              menuOpen ? "opacity-0" : "opacity-100"
            }`}
          />
          <span
            className={`block h-[1.5px] w-6 bg-current transition-transform duration-300 ${
              menuOpen ? "-translate-y-[7px] -rotate-45" : ""
            }`}
          />
        </button>
      </div>

      {menuOpen && (
        <div className="lg:hidden bg-ivory border-t border-stone-dark/60 px-6 py-6 flex flex-col gap-5">
          {navLinks.map((link) => (
            <NavLink
              key={link.to}
              to={link.to}
              className={({ isActive }) =>
                `text-base uppercase tracking-wide ${
                  isActive ? "text-walnut-dark font-medium" : "text-espresso-light"
                }`
              }
            >
              {link.label}
            </NavLink>
          ))}
          <div className="flex flex-col gap-3 pt-2">
            <Link
              to="/get-a-quote"
              className="inline-flex items-center justify-center rounded-full bg-bronze px-6 py-3 text-sm uppercase tracking-wide font-medium text-espresso"
            >
              Get a Quote
            </Link>
            <a
              href={`tel:${siteConfig.phone.replace(/\s/g, "")}`}
              className="inline-flex items-center justify-center rounded-full border border-espresso/30 px-6 py-3 text-sm uppercase tracking-wide font-medium text-espresso"
            >
              Call {siteConfig.phone}
            </a>
          </div>
        </div>
      )}
    </header>
  );
}
