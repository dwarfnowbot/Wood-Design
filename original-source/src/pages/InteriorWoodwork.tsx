import PageHero from "../components/PageHero";
import SectionHeading from "../components/SectionHeading";
import ProcessTimeline from "../components/ProcessTimeline";
import CTASection from "../components/CTASection";
import { images } from "../data/media";
import { processSteps, interiorWoodworkCategories } from "../data/content";

const galleryMap: Record<string, string> = {
  "TV Units": images.living[1],
  "Media Walls": images.living[6],
  "Wall Panels": images.living[7],
  "Bathroom Vanities": images.vanity[0],
  "Shoe Cabinets": images.entryway[0],
  "Storage Cabinets": images.entryway[1],
  "Bedroom Woodwork": images.wardrobe[6],
  "Living Room Woodwork": images.living[3],
  "Custom Shelving": images.study[1],
  "Study / Workspace Units": images.study[0],
};

export default function InteriorWoodwork() {
  return (
    <div>
      <PageHero
        eyebrow="Interior Woodwork"
        title="Complete Woodwork for Considered Interiors"
        description="From TV units to wall panels, vanities, and storage — coordinated joinery designed as part of one cohesive interior."
        image={images.living[6]}
      />

      {/* Introduction */}
      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-20 sm:py-28 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        <div>
          <SectionHeading
            eyebrow="Introduction"
            heading="One Studio for Every Woodwork Element in Your Home"
            description="Beyond kitchens and wardrobes, we design and build the smaller woodwork elements that tie an interior together — media walls, wall panelling, vanities, and custom storage — all designed to feel like part of one considered scheme."
          />
        </div>
        <div className="aspect-[4/5] overflow-hidden rounded-2xl">
          <img src={images.living[2]} alt="Living room with coordinated wood joinery" className="h-full w-full object-cover" />
        </div>
      </section>

      {/* Category gallery */}
      <section className="bg-stone py-20 sm:py-28">
        <div className="mx-auto max-w-7xl px-5 sm:px-8">
          <SectionHeading eyebrow="Our Services" heading="Woodwork Categories" />
          <div className="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            {interiorWoodworkCategories.map((cat) => (
              <div key={cat.title} className="group overflow-hidden rounded-2xl border border-stone-dark/50 bg-white">
                <div className="h-52 overflow-hidden">
                  <img
                    src={galleryMap[cat.title]}
                    alt={cat.title}
                    loading="lazy"
                    className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                  />
                </div>
                <div className="p-6">
                  <h3 className="font-serif-display text-lg text-espresso mb-2">{cat.title}</h3>
                  <p className="text-sm leading-relaxed text-espresso-light">{cat.description}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Process */}
      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-20 sm:py-28">
        <SectionHeading eyebrow="How We Work" heading="Our Process" />
        <div className="mt-14">
          <ProcessTimeline steps={processSteps} />
        </div>
      </section>

      <CTASection
        heading="Planning Complete Home Woodwork?"
        text="Tell us about the rooms and elements you'd like designed, and we'll help you plan a coordinated scheme."
      />
    </div>
  );
}
