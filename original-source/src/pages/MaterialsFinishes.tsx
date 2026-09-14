import PageHero from "../components/PageHero";
import SectionHeading from "../components/SectionHeading";
import CTASection from "../components/CTASection";
import { images } from "../data/media";
import { materialCategories, materialsDisclaimer } from "../data/content";

export default function MaterialsFinishes() {
  return (
    <div>
      <PageHero
        eyebrow="Materials & Finishes"
        title="Materials. Finishes. Details."
        description="A general overview of the boards, finishes, and hardware options we work with across projects."
        image={images.kitchen[1]}
        height="h-[50vh] min-h-[380px]"
      />

      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-20 sm:py-24">
        <SectionHeading
          eyebrow="Overview"
          heading="A Considered Palette of Materials"
          description="We select boards, finishes, and hardware based on durability, everyday practicality, and the aesthetic direction of each project."
        />

        <div className="mt-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
          {materialCategories.map((mat) => (
            <div key={mat.title} className="flex flex-col gap-4 rounded-2xl bg-white border border-stone-dark/50 p-6">
              <div className={`h-28 w-full rounded-xl bg-gradient-to-br ${mat.swatchClass}`} />
              <h3 className="font-serif-display text-xl text-espresso">{mat.title}</h3>
              <p className="text-sm leading-relaxed text-espresso-light">{mat.description}</p>
            </div>
          ))}
        </div>
      </section>

      <section className="bg-stone py-20 sm:py-24">
        <div className="mx-auto max-w-7xl px-5 sm:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div className="aspect-[4/3] overflow-hidden rounded-2xl">
            <img src={images.kitchen[4]} alt="Kitchen showing stone countertop and wood cabinetry combination" className="h-full w-full object-cover" />
          </div>
          <div>
            <SectionHeading
              eyebrow="Countertops"
              heading="Stone & Countertop Combinations"
              description="Countertop material and cabinetry tone are selected together so the finished kitchen or vanity feels cohesive. Options are discussed during the design and material selection stage of your project."
            />
          </div>
        </div>
      </section>

      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-20 sm:py-24">
        <div className="rounded-2xl border border-stone-dark/60 bg-white p-8 sm:p-10">
          <h3 className="font-serif-display text-2xl text-espresso mb-3">A Note on Availability</h3>
          <p className="text-sm leading-relaxed text-espresso-light max-w-3xl">{materialsDisclaimer}</p>
        </div>
      </section>

      <CTASection
        heading="Not Sure Which Finish Is Right for You?"
        text="Share your style preferences and space with us, and we'll help you choose a suitable palette."
      />
    </div>
  );
}
