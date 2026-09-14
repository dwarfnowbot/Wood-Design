type PageHeroProps = {
  eyebrow?: string;
  title: string;
  description?: string;
  image: string;
  height?: string;
};

export default function PageHero({ eyebrow, title, description, image, height = "h-[60vh] min-h-[420px]" }: PageHeroProps) {
  return (
    <section className={`relative ${height} w-full overflow-hidden`}>
      <img src={image} alt={title} className="absolute inset-0 h-full w-full object-cover" />
      <div className="absolute inset-0 bg-gradient-to-b from-espresso/70 via-espresso/50 to-espresso/70" />
      <div className="relative z-10 flex h-full max-w-4xl flex-col items-start justify-end gap-4 px-5 sm:px-8 pb-14 sm:pb-16 mx-auto lg:max-w-7xl">
        {eyebrow && (
          <span className="text-xs uppercase tracking-[0.3em] text-champagne animate-fade-up">{eyebrow}</span>
        )}
        <h1 className="font-serif-display text-4xl sm:text-5xl md:text-6xl text-ivory max-w-3xl leading-tight animate-fade-up-delay-1">
          {title}
        </h1>
        {description && (
          <p className="text-ivory/85 max-w-2xl text-base sm:text-lg leading-relaxed animate-fade-up-delay-2">
            {description}
          </p>
        )}
      </div>
    </section>
  );
}
