import { useState, type FormEvent } from "react";
import PageHero from "../components/PageHero";
import SectionHeading from "../components/SectionHeading";
import WhatsAppButton from "../components/WhatsAppButton";
import { images } from "../data/media";
import { siteConfig } from "../data/siteConfig";

const projectTypes = [
  "Kitchen",
  "Wardrobe",
  "Complete Home Woodwork",
  "TV Unit",
  "Vanity",
  "Wall Panels",
  "Other",
];

const budgetRanges = [
  "Under PKR 5 Lac",
  "PKR 5 – 10 Lac",
  "PKR 10 – 20 Lac",
  "PKR 20 – 40 Lac",
  "PKR 40 Lac+",
  "Not sure yet",
];

const projectStages = ["Planning", "Under Construction", "Renovation", "Ready for Installation"];
const contactMethods = ["Phone Call", "WhatsApp", "Email"];

const inputClass =
  "w-full rounded-xl border border-stone-dark/60 bg-white px-4 py-3 text-sm text-espresso placeholder:text-espresso-light/50 focus:outline-none focus:ring-2 focus:ring-bronze/40 focus:border-bronze transition-colors";
const labelClass = "text-xs uppercase tracking-wide text-espresso-light font-medium mb-2 block";

export default function GetQuote() {
  const [submitted, setSubmitted] = useState(false);

  const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    // NOTE: Connect this form to your backend, CRM, or form service (e.g. Formspree, EmailJS)
    // to receive submissions. Currently this is a front-end only placeholder.
    setSubmitted(true);
  };

  return (
    <div>
      <PageHero
        eyebrow="Get a Quote"
        title="Tell Us About Your Project"
        description="Share a few details about your space and requirements, and we'll be in touch to discuss the next steps."
        image={images.kitchen[2]}
        height="h-[46vh] min-h-[340px]"
      />

      <section className="mx-auto max-w-6xl px-5 sm:px-8 py-16 sm:py-20 grid grid-cols-1 lg:grid-cols-[1.4fr_1fr] gap-12">
        <div>
          {submitted ? (
            <div className="rounded-2xl border border-stone-dark/60 bg-white p-10 flex flex-col gap-4">
              <h2 className="font-serif-display text-3xl text-espresso">Thank You</h2>
              <p className="text-sm leading-relaxed text-espresso-light">
                We've received your inquiry. Our team will review your requirements and contact you shortly
                to discuss the next steps.
              </p>
              <div className="pt-2">
                <WhatsAppButton message="Hi, I just submitted a quote request and wanted to follow up." />
              </div>
            </div>
          ) : (
            <form onSubmit={handleSubmit} className="flex flex-col gap-6">
              <SectionHeading heading="Project Inquiry Form" align="left" />

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                  <label className={labelClass} htmlFor="fullName">Full Name</label>
                  <input id="fullName" name="fullName" required className={inputClass} placeholder="Your full name" />
                </div>
                <div>
                  <label className={labelClass} htmlFor="phone">Phone Number</label>
                  <input id="phone" name="phone" type="tel" required className={inputClass} placeholder="03xx xxxxxxx" />
                </div>
                <div>
                  <label className={labelClass} htmlFor="whatsapp">WhatsApp Number</label>
                  <input id="whatsapp" name="whatsapp" type="tel" className={inputClass} placeholder="03xx xxxxxxx" />
                </div>
                <div>
                  <label className={labelClass} htmlFor="email">Email Address</label>
                  <input id="email" name="email" type="email" className={inputClass} placeholder="you@example.com" />
                </div>
                <div>
                  <label className={labelClass} htmlFor="projectType">Project Type</label>
                  <select id="projectType" name="projectType" required className={inputClass} defaultValue="">
                    <option value="" disabled>Select project type</option>
                    {projectTypes.map((t) => (
                      <option key={t} value={t}>{t}</option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className={labelClass} htmlFor="location">Preferred Location / Area in Lahore</label>
                  <input id="location" name="location" className={inputClass} placeholder="e.g. DHA, Gulberg, Bahria Town" />
                </div>
                <div>
                  <label className={labelClass} htmlFor="budget">Estimated Budget</label>
                  <select id="budget" name="budget" className={inputClass} defaultValue="">
                    <option value="" disabled>Select a budget range</option>
                    {budgetRanges.map((b) => (
                      <option key={b} value={b}>{b}</option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className={labelClass} htmlFor="stage">Project Stage</label>
                  <select id="stage" name="stage" className={inputClass} defaultValue="">
                    <option value="" disabled>Select project stage</option>
                    {projectStages.map((s) => (
                      <option key={s} value={s}>{s}</option>
                    ))}
                  </select>
                </div>
              </div>

              <div>
                <label className={labelClass} htmlFor="message">Message / Requirements</label>
                <textarea id="message" name="message" rows={5} className={inputClass} placeholder="Tell us about your space, style preferences, and timeline" />
              </div>

              <div>
                <label className={labelClass} htmlFor="reference">Upload Reference Images or Plans</label>
                <input id="reference" name="reference" type="file" multiple accept="image/*,.pdf" className={`${inputClass} file:mr-4 file:rounded-full file:border-0 file:bg-stone file:px-4 file:py-2 file:text-xs file:uppercase file:tracking-wide file:text-espresso`} />
              </div>

              <div>
                <span className={labelClass}>Preferred Contact Method</span>
                <div className="flex flex-wrap gap-6 pt-1">
                  {contactMethods.map((method) => (
                    <label key={method} className="flex items-center gap-2 text-sm text-espresso">
                      <input type="radio" name="contactMethod" value={method} className="accent-bronze" />
                      {method}
                    </label>
                  ))}
                </div>
              </div>

              <p className="text-xs text-espresso-light/80">
                We'll review your requirements and contact you to discuss the next steps.
              </p>

              <button
                type="submit"
                className="inline-flex items-center justify-center rounded-full bg-espresso px-8 py-3.5 text-sm uppercase tracking-wide font-medium text-ivory hover:bg-walnut-dark transition-colors duration-300 self-start"
              >
                Submit Inquiry
              </button>
            </form>
          )}
        </div>

        <aside className="flex flex-col gap-6">
          <div className="rounded-2xl border border-stone-dark/60 bg-stone p-8 flex flex-col gap-5">
            <h3 className="font-serif-display text-xl text-espresso">Prefer to Talk Directly?</h3>
            <div className="flex flex-col gap-3 text-sm text-espresso-light">
              <a href={`tel:${siteConfig.phone.replace(/\s/g, "")}`} className="hover:text-espresso transition-colors">
                📞 {siteConfig.phone}
              </a>
              <a href={`mailto:${siteConfig.email}`} className="hover:text-espresso transition-colors">
                ✉️ {siteConfig.email}
              </a>
            </div>
            <WhatsAppButton message="Hi, I'd like to request a quote for my project." />
          </div>

          <div className="rounded-2xl border border-stone-dark/60 bg-white p-8 flex flex-col gap-3">
            <h3 className="font-serif-display text-xl text-espresso">Business Hours</h3>
            {siteConfig.businessHours.map((bh) => (
              <div key={bh.day} className="flex justify-between text-sm text-espresso-light">
                <span>{bh.day}</span>
                <span>{bh.hours}</span>
              </div>
            ))}
          </div>
        </aside>
      </section>
    </div>
  );
}
