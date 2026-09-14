import PageHero from "../components/PageHero";
import SectionHeading from "../components/SectionHeading";
import CTASection from "../components/CTASection";
import { images } from "../data/media";
import { detailedProcessSteps } from "../data/content";

export default function Process() {
  return (
    <div>
      <PageHero
        eyebrow="Our Process"
        title="A Clear, Considered Process From Start to Finish"
        description="From the first consultation to final handover, every step is planned so you know what to expect."
        image={images.living[4]}
        height="h-[50vh] min-h-[380px]"
      />

      <section className="mx-auto max-w-5xl px-5 sm:px-8 py-20 sm:py-28">
        <SectionHeading eyebrow="Step by Step" heading="How a Project Comes Together" align="left" />

        <div className="mt-16 relative">
          <div className="hidden sm:block absolute left-8 top-2 bottom-2 w-px bg-stone-dark/60" />
          <div className="flex flex-col gap-12">
            {detailedProcessSteps.map((step) => (
              <div key={step.number} className="relative flex gap-6 sm:gap-8">
                <div className="relative z-10 flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-espresso text-ivory font-serif-display text-xl">
                  {step.number}
                </div>
                <div className="pt-3">
                  <h3 className="font-serif-display text-2xl text-espresso mb-2">{step.title}</h3>
                  <p className="text-sm leading-relaxed text-espresso-light max-w-xl">{step.description}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      <CTASection
        heading="Ready to Start the Process?"
        text="Book a consultation and we'll walk you through each step in more detail."
      />
    </div>
  );
}
