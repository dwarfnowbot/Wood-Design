import { useState } from "react";
import PageHero from "../components/PageHero";
import SectionHeading from "../components/SectionHeading";
import ProcessTimeline from "../components/ProcessTimeline";
import CTASection from "../components/CTASection";
import Button from "../components/Button";
import { images } from "../data/media";
import { processSteps, kitchenStyles, kitchenFeatures, kitchenFaqs } from "../data/content";

export default function Kitchens() {
  const [openFaq, setOpenFaq] = useState<number | null>(0);

  return (
    <div>
      <PageHero
        eyebrow="Custom Kitchens"
        title="Custom Kitchens Designed for the Way You Live"
        description="From layout to finish, every kitchen is planned around your space, storage needs, and cooking habits."
        image={images.heroKitchen}
      />

      {/* Introduction */}
      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-20 sm:py-28 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        <div>
          <SectionHeading
            eyebrow="Introduction"
            heading="A Kitchen Shaped by How You Cook, Gather, and Live"
            description="Your kitchen is often the busiest room in the home. We design each layout around practical workflow — storage, preparation space, and appliance placement — before layering in the material palette and finishing details."
          />
          <div className="mt-8">
            <Button to="/get-a-quote" variant="primary">
              Discuss Your Kitchen Project
            </Button>
          </div>
        </div>
        <div className="aspect-[4/5] overflow-hidden rounded-2xl">
          <img src={images.kitchen[3]} alt="Custom kitchen island with pendant lighting" className="h-full w-full object-cover" />
        </div>
      </section>

      {/* Styles */}
      <section className="bg-stone py-20 sm:py-28">
        <div className="mx-auto max-w-7xl px-5 sm:px-8">
          <SectionHeading eyebrow="Design Direction" heading="Kitchen Styles We Design" />
          <div className="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            {kitchenStyles.map((style, i) => (
              <div key={style.title} className="group overflow-hidden rounded-2xl border border-stone-dark/50 bg-white">
                <div className="h-56 overflow-hidden">
                  <img
                    src={images.kitchen[i % images.kitchen.length]}
                    alt={style.title}
                    loading="lazy"
                    className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                  />
                </div>
                <div className="p-6">
                  <h3 className="font-serif-display text-xl text-espresso mb-2">{style.title}</h3>
                  <p className="text-sm leading-relaxed text-espresso-light">{style.description}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Features */}
      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-20 sm:py-28 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        <div className="aspect-[4/5] overflow-hidden rounded-2xl order-2 lg:order-1">
          <img src={images.kitchen[5]} alt="Kitchen with integrated storage and tall units" className="h-full w-full object-cover" />
        </div>
        <div className="order-1 lg:order-2">
          <SectionHeading eyebrow="Functionality" heading="Kitchen Features We Plan For" />
          <ul className="mt-8 grid grid-cols-2 gap-x-6 gap-y-4">
            {kitchenFeatures.map((f) => (
              <li key={f} className="flex items-center gap-3 text-sm text-espresso">
                <span className="h-1.5 w-1.5 rounded-full bg-bronze shrink-0" />
                {f}
              </li>
            ))}
          </ul>
        </div>
      </section>

      {/* Gallery */}
      <section className="bg-stone py-20 sm:py-28">
        <div className="mx-auto max-w-7xl px-5 sm:px-8">
          <SectionHeading eyebrow="Gallery" heading="Kitchen Project Gallery" />
          <div className="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {images.kitchen.map((img, i) => (
              <div key={img} className={`overflow-hidden rounded-2xl ${i === 0 ? "sm:col-span-2 sm:row-span-2 aspect-square sm:aspect-auto" : "aspect-square"}`}>
                <img src={img} alt={`Custom kitchen project ${i + 1}`} loading="lazy" className="h-full w-full object-cover hover:scale-105 transition-transform duration-700" />
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

      {/* FAQ */}
      <section className="bg-stone py-20 sm:py-28">
        <div className="mx-auto max-w-4xl px-5 sm:px-8">
          <SectionHeading eyebrow="Questions" heading="Kitchen FAQs" align="left" />
          <div className="mt-10 flex flex-col gap-4">
            {kitchenFaqs.map((faq, i) => (
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
        heading="Discuss Your Kitchen Project"
        text="Share your space and requirements, and we'll help you plan the design and next steps."
        primaryLabel="Discuss Your Kitchen Project"
      />
    </div>
  );
}
