import PageHero from "../components/PageHero";
import SectionHeading from "../components/SectionHeading";
import CTASection from "../components/CTASection";
import { images } from "../data/media";
import { siteConfig } from "../data/siteConfig";

const philosophyPoints = [
  {
    title: "Craftsmanship Philosophy",
    description:
      "We believe good woodwork is judged in the details — clean joinery, consistent finishes, and hardware that performs quietly for years. Every project is approached with the same level of care, regardless of scale.",
  },
  {
    title: "Design & Functionality",
    description:
      "Beautiful design should also work hard for the way you actually live. We plan layouts around daily routines — how you cook, store, dress, and relax — before finalising any aesthetic direction.",
  },
  {
    title: "Quality & Attention to Detail",
    description:
      "From board selection to the final alignment of a cabinet door, our team reviews work at each stage so that the finished result feels considered and precise.",
  },
  {
    title: "Customization Process",
    description:
      "Nothing is off-the-shelf. Each kitchen, wardrobe, or woodwork element is designed from your space's actual measurements, structural constraints, and personal preferences.",
  },
  {
    title: "Professional Execution",
    description:
      "Our team manages the process from consultation through to installation, keeping communication clear and the site tidy and respected throughout.",
  },
];

export default function About() {
  return (
    <div>
      <PageHero
        eyebrow="About Our Studio"
        title="Thoughtful Design. Reliable Craftsmanship."
        description="A Lahore-based studio dedicated to custom kitchens, wardrobes, and complete home woodwork."
        image={images.about}
      />

      {/* Introduction */}
      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-20 sm:py-28 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        <div>
          <SectionHeading
            eyebrow="Who We Are"
            heading="A Studio Built Around Considered Woodwork"
            description="[Editable placeholder] Our studio designs and builds custom kitchens, wardrobes, and complete home woodwork for homeowners across Lahore. We work closely with each client — from first conversation to final installation — to create spaces that are both beautiful and genuinely functional."
          />
          <p className="mt-6 text-sm leading-relaxed text-espresso-light max-w-xl">
            [Editable placeholder] Whether you are building a new home, renovating an existing one, or
            furnishing a single room, our approach stays the same: understand the space, plan around how
            it will be used, and execute with care.
          </p>
        </div>
        <div className="aspect-[4/5] overflow-hidden rounded-2xl">
          <img
            src={images.aboutSecondary}
            alt="Custom kitchen cabinetry detail with warm wood tones"
            className="h-full w-full object-cover"
          />
        </div>
      </section>

      {/* Philosophy */}
      <section className="bg-stone py-20 sm:py-28">
        <div className="mx-auto max-w-7xl px-5 sm:px-8">
          <SectionHeading eyebrow="Our Approach" heading="How We Think About Every Project" />
          <div className="mt-14 grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-12">
            {philosophyPoints.map((point, i) => (
              <div key={point.title} className="flex gap-5">
                <span className="font-serif-display text-3xl text-bronze shrink-0">{String(i + 1).padStart(2, "0")}</span>
                <div>
                  <h3 className="font-serif-display text-xl text-espresso mb-2">{point.title}</h3>
                  <p className="text-sm leading-relaxed text-espresso-light">{point.description}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Service area */}
      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-20 sm:py-28 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div className="aspect-video lg:aspect-[4/3] overflow-hidden rounded-2xl order-2 lg:order-1">
          <img
            src={images.kitchen[6]}
            alt="Custom kitchen designed for a Lahore residence"
            className="h-full w-full object-cover"
          />
        </div>
        <div className="order-1 lg:order-2">
          <SectionHeading
            eyebrow="Where We Work"
            heading="Serving Lahore & Nearby Areas"
            description={`We currently take on projects across ${siteConfig.serviceArea}. Whether you are locally based or planning a home from overseas, we coordinate site visits, measurements, and installation to fit your schedule.`}
          />
          <p className="mt-6 text-sm leading-relaxed text-espresso-light max-w-xl">
            [Editable placeholder] For overseas clients building or renovating a home in Lahore, we can
            coordinate consultations remotely and schedule site visits and installation around your
            availability.
          </p>
        </div>
      </section>

      <CTASection
        heading="Have a Project in Mind?"
        text="Tell us about your home and requirements, and we'll help you plan the next step."
      />
    </div>
  );
}
