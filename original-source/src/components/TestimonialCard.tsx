type Testimonial = {
  name: string;
  location: string;
  quote: string;
};

export default function TestimonialCard({ testimonial }: { testimonial: Testimonial }) {
  return (
    <div className="flex flex-col gap-5 rounded-2xl border border-stone-dark/50 bg-white p-8">
      <svg viewBox="0 0 32 24" className="h-7 w-7 text-bronze-light" fill="currentColor" aria-hidden="true">
        <path d="M0 24V14.4C0 6.4 4.8 1.2 13.2 0l1.2 3.6C9.6 4.8 7.2 7.8 7.2 12h6v12H0Zm18 0V14.4c0-8 4.8-13.2 13.2-14.4l1.2 3.6c-4.8 1.2-7.2 4.2-7.2 8.4h6v12H18Z" />
      </svg>
      <p className="text-[0.95rem] leading-relaxed text-espresso-light italic">{testimonial.quote}</p>
      <div className="pt-2 border-t border-stone-dark/40">
        <p className="text-sm font-medium text-espresso">{testimonial.name}</p>
        <p className="text-xs uppercase tracking-wide text-bronze mt-0.5">{testimonial.location}</p>
      </div>
    </div>
  );
}
