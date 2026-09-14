import type { Project } from "../data/projects";

export default function ProjectCard({ project, onView }: { project: Project; onView?: (project: Project) => void }) {
  return (
    <div className="group flex flex-col overflow-hidden rounded-2xl border border-stone-dark/50 bg-white transition-shadow duration-500 hover:shadow-xl hover:shadow-espresso/10">
      <div className="relative h-72 overflow-hidden">
        <img
          src={project.image}
          alt={project.title}
          loading="lazy"
          className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
        />
        <span className="absolute top-4 left-4 rounded-full bg-ivory/90 px-3 py-1 text-[11px] uppercase tracking-wide text-walnut-dark font-medium">
          {project.category}
        </span>
      </div>
      <div className="flex flex-1 flex-col gap-2 p-6">
        <h3 className="font-serif-display text-xl text-espresso">{project.title}</h3>
        <p className="text-xs uppercase tracking-wide text-bronze">{project.location}</p>
        <p className="text-sm leading-relaxed text-espresso-light">{project.description}</p>
        <button
          onClick={() => onView?.(project)}
          className="mt-2 inline-flex items-center gap-2 text-sm uppercase tracking-wide text-walnut-dark font-medium self-start group-hover:gap-3 transition-all duration-300"
        >
          View Project
          <span aria-hidden="true">&rarr;</span>
        </button>
      </div>
    </div>
  );
}
