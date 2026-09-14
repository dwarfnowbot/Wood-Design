import { Link } from "react-router-dom";
import Button from "../components/Button";
import WhatsAppButton from "../components/WhatsAppButton";
import SectionHeading from "../components/SectionHeading";
import TrustStrip from "../components/TrustStrip";
import ServiceCard from "../components/ServiceCard";
import ProjectCard from "../components/ProjectCard";
import TestimonialCard from "../components/TestimonialCard";
import ProcessTimeline from "../components/ProcessTimeline";
import CTASection from "../components/CTASection";
import { images } from "../data/media";
import { services } from "../data/services";
import { projects } from "../data/projects";
import { processSteps, whyChooseUs, testimonials, materialCategories, materialsDisclaimer } from "../data/content";

const featuredProjects = projects.slice(0, 6);

export default function Home() {
  return (
    <div>
      {/* HERO */}
      <section className="relative h-screen min-h-[640px] w-full overflow-hidden">
        <img
          src={images.heroKitchen}
          alt="Custom luxury kitchen with warm wood cabinetry and stone countertops"
          className="absolute inset-0 h-full w-full object-cover"
        />
        <div className="absolute inset-0 bg-gradient-to-b from-espresso/70 via-espresso/45 to-espresso/80" />

        <div className="relative z-10 flex h-full flex-col items-start justify-end px-5 sm:px-10 pb-24 sm:pb-28 mx-auto max-w-7xl">
          <span className="text-xs sm:text-sm uppercase tracking-[0.35em] text-champagne mb-5 animate-fade-up">
            Lahore &middot; Custom Kitchens &amp; Interior Woodwork
          </span>
          <h1 className="font-serif-display text-4xl sm:text-6xl md:text-7xl text-ivory max-w-3xl leading-[1.08] animate-fade-up-delay-1">
            Custom Kitchens &amp; Complete Home Woodwork
          </h1>
          <p className="mt-6 max-w-xl text-base sm:text-lg text-ivory/85 leading-relaxed animate-fade-up-delay-2">
            Beautifully designed, precisely crafted, and built around the way you live.
          </p>
          <div className="mt-9 flex flex-col sm:flex-row items-start sm:items-center gap-4 animate-fade-up-delay-3">
            <Button to="/get-a-quote" variant="ghost">
              Book a Consultation
            </Button>
            <Button to="/projects" variant="secondary">
              View Our Projects
            </Button>
            <WhatsAppButton />
          </div>
        </div>
      </section>

      <TrustStrip />

      {/* INTRO */}
      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-20 sm:py-28 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        <div className="order-2 lg:order-1">
          <SectionHeading
            eyebrow="Our Studio"
            heading="Crafted Around Your Space. Designed Around Your Life."
            description="We create custom kitchens, wardrobes, and complete woodwork solutions that combine thoughtful design, practical functionality, and refined craftsmanship. Every project is tailored to the client's space, lifestyle, and aesthetic."
          />
          <div className="mt-8">
            <Button to="/about" variant="outline">
              Learn About Our Studio
            </Button>
          </div>
        </div>
        <div className="order-1 lg:order-2 relative">
          <div className="aspect-[4/5] overflow-hidden rounded-2xl">
            <img
              src={images.introKitchen}
              alt="Bright custom kitchen interior with wooden cabinetry and natural light"
              className="h-full w-full object-cover"
            />
          </div>
          <div className="hidden sm:block absolute -bottom-8 -left-8 w-40 h-40 rounded-2xl overflow-hidden border-8 border-ivory shadow-xl">
            <img src={images.wardrobe[3]} alt="Custom wardrobe interior detail" className="h-full w-full object-cover" />
          </div>
        </div>
      </section>

      {/* SERVICES */}
      <section className="bg-stone py-20 sm:py-28">
        <div className="mx-auto max-w-7xl px-5 sm:px-8">
          <SectionHeading eyebrow="What We Do" heading="Our Expertise" align="left" />
          <div className="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            {services.map((service) => (
              <ServiceCard key={service.slug} service={service} />
            ))}
          </div>
        </div>
      </section>

      {/* FEATURED PROJECTS */}
      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-20 sm:py-28">
        <div className="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
          <SectionHeading eyebrow="Portfolio" heading="Selected Projects" />
          <Button to="/projects" variant="outline">
            View All Projects
          </Button>
        </div>
        <div className="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          {featuredProjects.map((project) => (
            <ProjectCard key={project.id} project={project} />
          ))}
        </div>
      </section>

      {/* WHY CHOOSE US */}
      <section className="bg-espresso py-20 sm:py-28">
        <div className="mx-auto max-w-7xl px-5 sm:px-8">
          <SectionHeading eyebrow="Our Difference" heading="Why Clients Choose Us" light />
          <div className="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-10 gap-y-12">
            {whyChooseUs.map((item) => (
              <div key={item.title} className="flex flex-col gap-3 border-t border-ivory/15 pt-6">
                <h3 className="font-serif-display text-xl text-champagne">{item.title}</h3>
                <p className="text-sm leading-relaxed text-ivory/70">{item.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* PROCESS */}
      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-20 sm:py-28">
        <SectionHeading eyebrow="How We Work" heading="Our Process" align="left" />
        <div className="mt-14">
          <ProcessTimeline steps={processSteps} />
        </div>
        <div className="mt-12">
          <Button to="/process" variant="outline">
            See Full Process
          </Button>
        </div>
      </section>

      {/* MATERIALS */}
      <section className="bg-stone py-20 sm:py-28">
        <div className="mx-auto max-w-7xl px-5 sm:px-8">
          <SectionHeading eyebrow="Craftsmanship" heading="Materials. Finishes. Details." />
          <div className="mt-12 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            {materialCategories.map((mat) => (
              <div key={mat.title} className="flex flex-col gap-3 rounded-2xl bg-white border border-stone-dark/50 p-5">
                <div className={`h-20 w-full rounded-xl bg-gradient-to-br ${mat.swatchClass}`} />
                <h3 className="text-sm font-medium text-espresso">{mat.title}</h3>
              </div>
            ))}
          </div>
          <p className="mt-8 text-xs text-espresso-light/80 max-w-2xl">{materialsDisclaimer}</p>
          <div className="mt-6">
            <Link to="/materials-finishes" className="text-sm uppercase tracking-wide text-walnut-dark font-medium">
              Explore Materials &amp; Finishes &rarr;
            </Link>
          </div>
        </div>
      </section>

      {/* TESTIMONIALS */}
      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-20 sm:py-28">
        <SectionHeading eyebrow="Client Feedback" heading="What Our Clients Say" align="left" />
        <p className="mt-3 text-xs uppercase tracking-wide text-bronze">
          Sample placeholder testimonials shown for illustration — not verified reviews.
        </p>
        <div className="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          {testimonials.map((t) => (
            <TestimonialCard key={t.name} testimonial={t} />
          ))}
        </div>
      </section>

      <CTASection />
    </div>
  );
}
