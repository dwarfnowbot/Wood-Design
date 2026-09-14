import { Link } from "react-router-dom";
import { navLinks, siteConfig } from "../data/siteConfig";
import { services } from "../data/services";

export default function Footer() {
  return (
    <footer className="bg-charcoal text-ivory/90">
      <div className="mx-auto max-w-7xl px-5 sm:px-8 py-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
        <div className="flex flex-col gap-4">
          <span className="font-serif-display text-2xl text-ivory">{siteConfig.brandName}</span>
          <p className="text-sm leading-relaxed text-ivory/60 max-w-xs">
            Custom kitchens, wardrobes, and complete home woodwork for homeowners across Lahore —
            designed around your space and crafted with care.
          </p>
          <div className="flex gap-4 pt-2">
            {Object.entries(siteConfig.social).map(([key, url]) => (
              <a
                key={key}
                href={url}
                target="_blank"
                rel="noopener noreferrer"
                className="text-xs uppercase tracking-wide text-ivory/60 hover:text-champagne transition-colors"
              >
                {key}
              </a>
            ))}
          </div>
        </div>

        <div className="flex flex-col gap-4">
          <h3 className="text-xs uppercase tracking-[0.2em] text-champagne">Quick Links</h3>
          <ul className="flex flex-col gap-2.5">
            {navLinks.map((link) => (
              <li key={link.to}>
                <Link to={link.to} className="text-sm text-ivory/70 hover:text-ivory transition-colors">
                  {link.label}
                </Link>
              </li>
            ))}
            <li>
              <Link to="/get-a-quote" className="text-sm text-ivory/70 hover:text-ivory transition-colors">
                Get a Quote
              </Link>
            </li>
          </ul>
        </div>

        <div className="flex flex-col gap-4">
          <h3 className="text-xs uppercase tracking-[0.2em] text-champagne">Services</h3>
          <ul className="flex flex-col gap-2.5">
            {services.map((service) => (
              <li key={service.slug}>
                <Link to={service.to} className="text-sm text-ivory/70 hover:text-ivory transition-colors">
                  {service.title}
                </Link>
              </li>
            ))}
          </ul>
        </div>

        <div className="flex flex-col gap-4">
          <h3 className="text-xs uppercase tracking-[0.2em] text-champagne">Contact</h3>
          <ul className="flex flex-col gap-2.5 text-sm text-ivory/70">
            <li>{siteConfig.address}</li>
            <li>Serving {siteConfig.serviceArea}</li>
            <li>
              <a href={`tel:${siteConfig.phone.replace(/\s/g, "")}`} className="hover:text-ivory transition-colors">
                {siteConfig.phone}
              </a>
            </li>
            <li>
              <a href={`mailto:${siteConfig.email}`} className="hover:text-ivory transition-colors">
                {siteConfig.email}
              </a>
            </li>
            {siteConfig.businessHours.map((bh) => (
              <li key={bh.day} className="text-ivory/50 text-xs">
                {bh.day}: {bh.hours}
              </li>
            ))}
          </ul>
        </div>
      </div>

      <div className="border-t border-ivory/10">
        <div className="mx-auto max-w-7xl px-5 sm:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-ivory/50">
          <p>© {new Date().getFullYear()} {siteConfig.brandName}. All rights reserved.</p>
          <p>Custom Kitchens &amp; Complete Home Woodwork — Lahore, Pakistan</p>
        </div>
      </div>
    </footer>
  );
}
