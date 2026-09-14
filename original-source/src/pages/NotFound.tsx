import Button from "../components/Button";

export default function NotFound() {
  return (
    <div className="flex min-h-[60vh] flex-col items-center justify-center gap-6 px-5 text-center py-24">
      <span className="text-xs uppercase tracking-[0.3em] text-bronze">404</span>
      <h1 className="font-serif-display text-4xl sm:text-5xl text-espresso">Page Not Found</h1>
      <p className="max-w-md text-sm text-espresso-light leading-relaxed">
        The page you're looking for doesn't exist or may have been moved.
      </p>
      <Button to="/" variant="primary">
        Back to Home
      </Button>
    </div>
  );
}
