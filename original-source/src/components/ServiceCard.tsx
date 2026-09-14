import { Link } from "react-router-dom";
import type { Service } from "../data/services";

export default function ServiceCard({ service }: { service: Service }) {
  return (
    <div className="group flex flex-col overflow-hidden rounded-2xl border border-stone-dark/50 bg-white transition-shadow duration-500 hover:shadow-xl hover:shadow-espresso/10">
      <div className="relative h-64 overflow-hidden">
        <img
          src={service.image}
          alt={service.title}
          loading="lazy"
          className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
        />
      </div>
      <div className="flex flex-1 flex-col gap-3 p-7">
        <h3 className="font-serif-display text-2xl text-espresso">{service.title}</h3>
        <p className="text-sm leading-relaxed text-espresso-light flex-1">{service.shortDescription}</p>
        <Link
          to={service.to}
          className="mt-2 inline-flex items-center gap-2 text-sm uppercase tracking-wide text-walnut-dark font-medium group-hover:gap-3 transition-all duration-300"
        >
          Explore Service
          <span aria-hidden="true">&rarr;</span>
        </Link>
      </div>
    </div>
  );
}
