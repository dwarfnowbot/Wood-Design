type SectionHeadingProps = {
  eyebrow?: string;
  heading: string;
  description?: string;
  align?: "left" | "center";
  light?: boolean;
};

export default function SectionHeading({
  eyebrow,
  heading,
  description,
  align = "left",
  light = false,
}: SectionHeadingProps) {
  const alignClass = align === "center" ? "text-center items-center mx-auto" : "text-left items-start";

  return (
    <div className={`flex flex-col gap-4 max-w-2xl ${alignClass}`}>
      {eyebrow && (
        <span
          className={`text-xs uppercase tracking-[0.25em] font-medium ${
            light ? "text-champagne" : "text-bronze"
          }`}
        >
          {eyebrow}
        </span>
      )}
      <h2
        className={`font-serif-display text-3xl sm:text-4xl md:text-[2.75rem] leading-tight ${
          light ? "text-ivory" : "text-espresso"
        }`}
      >
        {heading}
      </h2>
      {description && (
        <p className={`text-base leading-relaxed ${light ? "text-ivory/80" : "text-espresso-light"}`}>
          {description}
        </p>
      )}
    </div>
  );
}
