import { trustPoints } from "../data/content";

const icons = [
  // custom-built
  <path key="1" d="M4 21V7l8-4 8 4v14M9 21v-6h6v6" />,
  // quality materials
  <path key="2" d="M12 3l2.5 5.5L20 9l-4 4 1 6-5-3-5 3 1-6-4-4 5.5-.5L12 3z" />,
  // precision craftsmanship
  <path key="3" d="M14.7 6.3a4 4 0 0 1-5.66 5.66L4 17l3 3 5.04-5.04a4 4 0 0 1 5.66-5.66l-3-3z" />,
  // professional installation
  <path key="4" d="M9 12l2 2 4-4M5 6l2-2h10l2 2v12l-2 2H7l-2-2V6z" />,
];

export default function TrustStrip() {
  return (
    <section className="bg-stone border-y border-stone-dark/60">
      <div className="mx-auto max-w-7xl px-5 sm:px-8 py-10 grid grid-cols-2 lg:grid-cols-4 gap-8">
        {trustPoints.map((point, i) => (
          <div key={point} className="flex items-center gap-3 justify-center lg:justify-start text-center lg:text-left">
            <svg
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth={1.4}
              className="h-8 w-8 shrink-0 text-walnut"
            >
              {icons[i % icons.length]}
            </svg>
            <span className="text-sm sm:text-[0.95rem] font-medium text-espresso">{point}</span>
          </div>
        ))}
      </div>
    </section>
  );
}
