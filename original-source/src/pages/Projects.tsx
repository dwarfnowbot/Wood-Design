import { useMemo, useState } from "react";
import PageHero from "../components/PageHero";
import SectionHeading from "../components/SectionHeading";
import ProjectCard from "../components/ProjectCard";
import CTASection from "../components/CTASection";
import { images } from "../data/media";
import { projects, projectCategories, type Project } from "../data/projects";

export default function Projects() {
  const [activeCategory, setActiveCategory] = useState<(typeof projectCategories)[number]>("All");
  const [selected, setSelected] = useState<Project | null>(null);

  const filtered = useMemo(
    () => (activeCategory === "All" ? projects : projects.filter((p) => p.category === activeCategory)),
    [activeCategory]
  );

  return (
    <div>
      <PageHero
        eyebrow="Portfolio"
        title="Selected Projects"
        description="A look at the kinds of kitchens, wardrobes, and woodwork projects we design — presented as sample project concepts."
        image={images.living[3]}
      />

      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-16 sm:py-20">
        <SectionHeading
          eyebrow="Browse by Category"
          heading="Our Project Categories"
          description="Sample project concepts shown for presentation purposes. Replace with real completed project photography as it becomes available."
        />

        <div className="mt-10 flex flex-wrap gap-3">
          {projectCategories.map((cat) => (
            <button
              key={cat}
              onClick={() => setActiveCategory(cat)}
              className={`rounded-full px-5 py-2 text-sm uppercase tracking-wide transition-colors duration-300 border ${
                activeCategory === cat
                  ? "bg-espresso text-ivory border-espresso"
                  : "bg-white text-espresso-light border-stone-dark/50 hover:border-espresso"
              }`}
            >
              {cat}
            </button>
          ))}
        </div>

        <div className="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          {filtered.map((project) => (
            <ProjectCard key={project.id} project={project} onView={setSelected} />
          ))}
        </div>
      </section>

      {selected && (
        <div
          className="fixed inset-0 z-50 flex items-center justify-center bg-espresso/80 px-4 py-8"
          onClick={() => setSelected(null)}
        >
          <div
            className="relative max-w-3xl w-full max-h-[90vh] overflow-y-auto rounded-2xl bg-ivory"
            onClick={(e) => e.stopPropagation()}
          >
            <button
              className="absolute top-4 right-4 z-10 flex h-9 w-9 items-center justify-center rounded-full bg-ivory/90 text-espresso text-lg"
              onClick={() => setSelected(null)}
              aria-label="Close project details"
            >
              ×
            </button>
            <div className="h-72 sm:h-96 overflow-hidden rounded-t-2xl">
              <img src={selected.image} alt={selected.title} className="h-full w-full object-cover" />
            </div>
            <div className="p-8 flex flex-col gap-4">
              <span className="text-xs uppercase tracking-wide text-bronze font-medium">{selected.category}</span>
              <h3 className="font-serif-display text-3xl text-espresso">{selected.title}</h3>
              <p className="text-xs uppercase tracking-wide text-espresso-light">{selected.location}</p>
              <p className="text-sm leading-relaxed text-espresso-light">{selected.description}</p>
              {selected.materials && (
                <div className="pt-3 border-t border-stone-dark/40">
                  <p className="text-xs uppercase tracking-wide text-bronze mb-1">Materials &amp; Finishes</p>
                  <p className="text-sm text-espresso-light">{selected.materials}</p>
                </div>
              )}
            </div>
          </div>
        </div>
      )}

      <CTASection
        heading="Like What You See?"
        text="Share your space and inspiration with us, and we'll help you plan a project of your own."
      />
    </div>
  );
}
