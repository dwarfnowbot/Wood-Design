type Step = {
  number: string;
  title: string;
  description: string;
};

export default function ProcessTimeline({ steps }: { steps: Step[] }) {
  return (
    <div className="relative">
      <div className="hidden lg:block absolute top-8 left-0 right-0 h-px bg-stone-dark/60" />
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-6">
        {steps.map((step) => (
          <div key={step.number} className="relative flex flex-col gap-4">
            <div className="relative z-10 flex h-16 w-16 items-center justify-center rounded-full bg-espresso text-ivory font-serif-display text-xl">
              {step.number}
            </div>
            <h3 className="font-serif-display text-xl text-espresso">{step.title}</h3>
            <p className="text-sm leading-relaxed text-espresso-light">{step.description}</p>
          </div>
        ))}
      </div>
    </div>
  );
}
