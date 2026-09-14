import { whatsappLink } from "../data/siteConfig";

type Props = {
  message?: string;
  floating?: boolean;
};

function WhatsAppIcon({ className }: { className?: string }) {
  return (
    <svg viewBox="0 0 32 32" className={className} fill="currentColor" aria-hidden="true">
      <path d="M16.02 3C9.4 3 4 8.38 4 15c0 2.36.64 4.57 1.76 6.47L4 29l7.73-1.7A11.9 11.9 0 0 0 16.02 27C22.65 27 28 21.62 28 15S22.65 3 16.02 3Zm0 21.68c-1.99 0-3.85-.56-5.44-1.53l-.39-.23-4.58 1.01 1-4.48-.25-.4A9.63 9.63 0 0 1 6.34 15c0-5.34 4.35-9.68 9.68-9.68S25.7 9.66 25.7 15s-4.35 9.68-9.68 9.68Zm5.36-7.26c-.29-.15-1.72-.85-1.99-.95-.27-.1-.46-.15-.66.15-.2.29-.76.95-.93 1.15-.17.2-.34.22-.63.07-.29-.15-1.23-.45-2.34-1.44-.87-.77-1.45-1.72-1.62-2.01-.17-.29-.02-.45.13-.6.13-.13.29-.34.44-.51.15-.17.2-.29.29-.49.1-.2.05-.37-.02-.51-.07-.15-.66-1.59-.9-2.17-.24-.57-.48-.5-.66-.51h-.56c-.2 0-.51.07-.78.37-.27.29-1.02 1-1.02 2.44s1.05 2.83 1.19 3.03c.15.2 2.07 3.16 5.02 4.43.7.3 1.25.48 1.68.61.7.22 1.34.19 1.85.11.56-.08 1.72-.7 1.96-1.38.24-.68.24-1.26.17-1.38-.07-.12-.26-.2-.55-.34Z" />
    </svg>
  );
}

export default function WhatsAppButton({ message = "Hi, I'd like to know more about your custom woodwork services.", floating = false }: Props) {
  if (floating) {
    return (
      <a
        href={whatsappLink(message)}
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Chat with us on WhatsApp"
        className="fixed bottom-6 right-6 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-xl shadow-black/20 transition-transform duration-300 hover:scale-105"
      >
        <WhatsAppIcon className="h-7 w-7" />
      </a>
    );
  }

  return (
    <a
      href={whatsappLink(message)}
      target="_blank"
      rel="noopener noreferrer"
      className="inline-flex items-center justify-center gap-2 rounded-full bg-[#25D366] px-7 py-3 text-sm uppercase tracking-wide font-medium text-white transition-all duration-300 hover:brightness-95"
    >
      <WhatsAppIcon className="h-4 w-4" />
      Chat on WhatsApp
    </a>
  );
}
