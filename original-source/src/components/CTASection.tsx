import Button from "./Button";
import WhatsAppButton from "./WhatsAppButton";
import { images } from "../data/media";

type CTASectionProps = {
  heading?: string;
  text?: string;
  primaryLabel?: string;
  primaryTo?: string;
};

export default function CTASection({
  heading = "Let's Create a Space That Feels Like Yours.",
  text = "Planning a new kitchen, wardrobe, or complete home woodwork project? Tell us about your space and requirements.",
  primaryLabel = "Request a Quote",
  primaryTo = "/get-a-quote",
}: CTASectionProps) {
  return (
    <section className="relative overflow-hidden bg-espresso">
      <img
        src={images.living[3]}
        alt="Elegant custom woodwork interior"
        className="absolute inset-0 h-full w-full object-cover opacity-25"
      />
      <div className="absolute inset-0 bg-gradient-to-r from-espresso via-espresso/95 to-espresso/80" />
      <div className="relative z-10 mx-auto max-w-5xl px-5 sm:px-8 py-20 sm:py-24 flex flex-col items-center text-center gap-6">
        <h2 className="font-serif-display text-3xl sm:text-4xl md:text-5xl text-ivory max-w-2xl leading-tight">
          {heading}
        </h2>
        <p className="text-ivory/75 max-w-xl leading-relaxed">{text}</p>
        <div className="flex flex-col sm:flex-row items-center gap-4 pt-2">
          <Button to={primaryTo} variant="ghost">
            {primaryLabel}
          </Button>
          <WhatsAppButton />
        </div>
      </div>
    </section>
  );
}
