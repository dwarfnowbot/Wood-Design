import { useState, type FormEvent } from "react";
import PageHero from "../components/PageHero";
import SectionHeading from "../components/SectionHeading";
import WhatsAppButton from "../components/WhatsAppButton";
import { images } from "../data/media";
import { siteConfig } from "../data/siteConfig";

const inputClass =
  "w-full rounded-xl border border-stone-dark/60 bg-white px-4 py-3 text-sm text-espresso placeholder:text-espresso-light/50 focus:outline-none focus:ring-2 focus:ring-bronze/40 focus:border-bronze transition-colors";
const labelClass = "text-xs uppercase tracking-wide text-espresso-light font-medium mb-2 block";

export default function Contact() {
  const [submitted, setSubmitted] = useState(false);

  const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    // NOTE: Connect this form to your backend or a form service to receive submissions.
    setSubmitted(true);
  };

  return (
    <div>
      <PageHero
        eyebrow="Contact Us"
        title="Let's Talk About Your Space"
        description="Reach out to book a consultation, ask a question, or say hello."
        image={images.living[5]}
        height="h-[46vh] min-h-[340px]"
      />

      <section className="mx-auto max-w-7xl px-5 sm:px-8 py-16 sm:py-20 grid grid-cols-1 lg:grid-cols-2 gap-14">
        <div>
          <SectionHeading eyebrow="Get in Touch" heading="Send Us a Message" align="left" />

          {submitted ? (
            <div className="mt-8 rounded-2xl border border-stone-dark/60 bg-white p-8">
              <h3 className="font-serif-display text-2xl text-espresso mb-2">Message Sent</h3>
              <p className="text-sm leading-relaxed text-espresso-light">
                Thank you for reaching out. We'll get back to you as soon as possible.
              </p>
            </div>
          ) : (
            <form onSubmit={handleSubmit} className="mt-8 flex flex-col gap-5">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                  <label className={labelClass} htmlFor="c-name">Full Name</label>
                  <input id="c-name" required className={inputClass} placeholder="Your full name" />
                </div>
                <div>
                  <label className={labelClass} htmlFor="c-phone">Phone Number</label>
                  <input id="c-phone" type="tel" required className={inputClass} placeholder="03xx xxxxxxx" />
                </div>
              </div>
              <div>
                <label className={labelClass} htmlFor="c-email">Email Address</label>
                <input id="c-email" type="email" className={inputClass} placeholder="you@example.com" />
              </div>
              <div>
                <label className={labelClass} htmlFor="c-message">Message</label>
                <textarea id="c-message" rows={5} required className={inputClass} placeholder="How can we help?" />
              </div>
              <button
                type="submit"
                className="inline-flex items-center justify-center rounded-full bg-espresso px-8 py-3.5 text-sm uppercase tracking-wide font-medium text-ivory hover:bg-walnut-dark transition-colors duration-300 self-start"
              >
                Send Message
              </button>
            </form>
          )}
        </div>

        <div className="flex flex-col gap-6">
          <div className="rounded-2xl border border-stone-dark/60 bg-stone p-8 flex flex-col gap-4">
            <h3 className="font-serif-display text-xl text-espresso">Contact Details</h3>
            <ul className="flex flex-col gap-3 text-sm text-espresso-light">
              <li>📍 {siteConfig.address}</li>
              <li>🛠️ Serving {siteConfig.serviceArea}</li>
              <li>
                <a href={`tel:${siteConfig.phone.replace(/\s/g, "")}`} className="hover:text-espresso transition-colors">
                  📞 {siteConfig.phone}
                </a>
              </li>
              <li>
                <a href={`mailto:${siteConfig.email}`} className="hover:text-espresso transition-colors">
                  ✉️ {siteConfig.email}
                </a>
              </li>
            </ul>
            <div className="pt-2">
              <WhatsAppButton />
            </div>
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

          <div className="rounded-2xl border border-stone-dark/60 bg-white p-8 flex flex-col gap-4">
            <h3 className="font-serif-display text-xl text-espresso">Follow Us</h3>
            <div className="flex gap-5 text-sm text-espresso-light">
              {Object.entries(siteConfig.social).map(([key, url]) => (
                <a key={key} href={url} target="_blank" rel="noopener noreferrer" className="capitalize hover:text-espresso transition-colors">
                  {key}
                </a>
              ))}
            </div>
          </div>

          <div className="overflow-hidden rounded-2xl border border-stone-dark/60 h-64">
            <iframe
              title="Studio location map"
              src={siteConfig.mapEmbedUrl}
              className="h-full w-full border-0"
              loading="lazy"
              referrerPolicy="no-referrer-when-downgrade"
            />
          </div>
        </div>
      </section>
    </div>
  );
}
