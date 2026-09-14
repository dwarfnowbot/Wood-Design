import { useState } from "react";
import PageHero from "../components/PageHero";
import SectionHeading from "../components/SectionHeading";
import ProcessTimeline from "../components/ProcessTimeline";
import CTASection from "../components/CTASection";
import Button from "../components/Button";
import { images } from "../data/media";
import { processSteps, wardrobeTypes, wardrobeFeatures, wardrobeFaqs, whyChooseUs } from "../data/content";

export default function Wardrobes() {
  const [openFaq, setOpenFaq] = useState<number | null>(0);

  return (
    <div>
      <PageHero
        eyebrow="Custom Wardrobes"
        title="Custom Wardrobes. Beautifully Organized."
        description="Wardrobes designed around your wardrobe, your room, and the way you get ready each day."
        image={images.wardrobe[0]}
      />

      {/* Introduction */}
      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-20 sm:py-28 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        <div>
          <SectionHeading
            eyebrow="Introduction"
            heading="Storage That Is Planned, Not Just Installed"
            description="A well-designed wardrobe starts with understanding what needs to be stored — clothing, shoes, accessories — and how much space is available. We plan the internal layout first, then design the exterior finish to suit your room."
          />
          <div className="mt-8">
            <Button to="/get-a-quote" variant="primary">
              Discuss Your Wardrobe Project
            </Button>
          </div>
        </div>
        <div className="aspect-[4/5] overflow-hidden rounded-2xl">
          <img src={images.wardrobe[2]} alt="Custom wardrobe with organised internal shelving" className="h-full w-full object-cover" />
        </div>
      </section>

      {/* Types */}
      <section className="bg-stone py-20 sm:py-28">
        <div className="mx-auto max-w-7xl px-5 sm:px-8">
          <SectionHeading eyebrow="Wardrobe Types" heading="Wardrobe Formats We Design" />
          <div className="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            {wardrobeTypes.map((type, i) => (
              <div key={type.title} className="group overflow-hidden rounded-2xl border border-stone-dark/50 bg-white">
                <div className="h-56 overflow-hidden">
                  <img
                    src={images.wardrobe[i % images.wardrobe.length]}
                    alt={type.title}
                    loading="lazy"
                    className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                  />
                </div>
                <div className="p-6">
                  <h3 className="font-serif-display text-xl text-espresso mb-2">{type.title}</h3>
                  <p className="text-sm leading-relaxed text-espresso-light">{type.description}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Internal storage planning */}
      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-20 sm:py-28 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        <div className="aspect-[4/5] overflow-hidden rounded-2xl order-2 lg:order-1">
          <img src={images.wardrobe[5]} alt="Wardrobe drawers and internal storage detail" className="h-full w-full object-cover" />
        </div>
        <div className="order-1 lg:order-2">
          <SectionHeading eyebrow="Internal Planning" heading="Drawers, Shelves & Accessories" description="Every wardrobe interior is planned to suit what you'll actually store." />
          <ul className="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
            {wardrobeFeatures.map((f) => (
              <li key={f} className="flex items-center gap-3 text-sm text-espresso">
                <span className="h-1.5 w-1.5 rounded-full bg-bronze shrink-0" />
                {f}
              </li>
            ))}
          </ul>
        </div>
      </section>

      {/* Benefits */}
      <section className="bg-espresso py-20 sm:py-28">
        <div className="mx-auto max-w-7xl px-5 sm:px-8">
          <SectionHeading eyebrow="Benefits" heading="Why Clients Choose Our Wardrobes" light />
          <div className="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-10 gap-y-12">
            {whyChooseUs.slice(0, 3).map((item) => (
              <div key={item.title} className="flex flex-col gap-3 border-t border-ivory/15 pt-6">
                <h3 className="font-serif-display text-xl text-champagne">{item.title}</h3>
                <p className="text-sm leading-relaxed text-ivory/70">{item.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Gallery */}
      <section className="py-20 sm:py-28">
        <div className="mx-auto max-w-7xl px-5 sm:px-8">
          <SectionHeading eyebrow="Gallery" heading="Wardrobe Project Gallery" />
          <div className="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {images.wardrobe.map((img, i) => (
              <div key={img} className={`overflow-hidden rounded-2xl ${i === 0 ? "sm:col-span-2 sm:row-span-2 aspect-square sm:aspect-auto" : "aspect-square"}`}>
                <img src={img} alt={`Custom wardrobe project ${i + 1}`} loading="lazy" className="h-full w-full object-cover hover:scale-105 transition-transform duration-700" />
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Process */}
      <section className="bg-stone py-20 sm:py-28">
        <div className="mx-auto max-w-7xl px-5 sm:px-8">
          <SectionHeading eyebrow="How We Work" heading="Our Process" />
          <div className="mt-14">
            <ProcessTimeline steps={processSteps} />
          </div>
        </div>
      </section>

      {/* FAQ */}
      <section className="py-20 sm:py-28">
        <div className="mx-auto max-w-4xl px-5 sm:px-8">
          <SectionHeading eyebrow="Questions" heading="Wardrobe FAQs" align="left" />
          <div className="mt-10 flex flex-col gap-4">
            {wardrobeFaqs.map((faq, i) => (
              <div key={faq.q} className="rounded-2xl border border-stone-dark/50 bg-white overflow-hidden">
                <button
                  className="w-full flex items-center justify-between gap-4 px-6 py-5 text-left"
                  onClick={() => setOpenFaq(openFaq === i ? null : i)}
                >
                  <span className="font-medium text-espresso">{faq.q}</span>
                  <span className="text-xl text-bronze shrink-0">{openFaq === i ? "−" : "+"}</span>
                </button>
                {openFaq === i && (
                  <div className="px-6 pb-5 text-sm leading-relaxed text-espresso-light">{faq.a}</div>
                )}
              </div>
            ))}
          </div>
        </div>
      </section>

      <CTASection
        heading="Plan Your Custom Wardrobe"
        text="Tell us about your room and storage needs, and we'll help you plan the layout and finishes."
      />
    </div>
  );
}
