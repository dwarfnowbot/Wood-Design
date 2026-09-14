// ---------------------------------------------------------------------------
// SITE CONFIGURATION — EDITABLE BUSINESS INFORMATION
// Update the placeholders below with real company details before launch.
// ---------------------------------------------------------------------------

export const siteConfig = {
  brandName: "Maison Woodcraft",
  brandTagline: "Custom Kitchens & Complete Home Woodwork",
  tagline: "Designed for Living. Crafted for You.",
  secondaryTagline: "Custom Kitchens. Exceptional Craftsmanship.",

  // Contact placeholders — replace with real details
  phone: "+92 300 0000000",
  whatsapp: "923000000000", // digits only, with country code, no + or spaces
  email: "hello@maisonwoodcraft.example",
  address: "Studio & Workshop, DHA Phase 6, Lahore, Pakistan",
  serviceArea: "Lahore & surrounding areas",

  businessHours: [
    { day: "Monday – Saturday", hours: "10:00 AM – 7:00 PM" },
    { day: "Sunday", hours: "By appointment only" },
  ],

  social: {
    instagram: "https://instagram.com/",
    facebook: "https://facebook.com/",
    pinterest: "https://pinterest.com/",
    linkedin: "https://linkedin.com/",
  },

  mapEmbedUrl:
    "https://www.google.com/maps?q=DHA+Phase+6+Lahore+Pakistan&output=embed",
};

export const whatsappLink = (message?: string) => {
  const base = `https://wa.me/${siteConfig.whatsapp}`;
  return message ? `${base}?text=${encodeURIComponent(message)}` : base;
};

export const navLinks = [
  { label: "Home", to: "/" },
  { label: "Kitchens", to: "/kitchens" },
  { label: "Wardrobes", to: "/wardrobes" },
  { label: "Interior Woodwork", to: "/interior-woodwork" },
  { label: "Projects", to: "/projects" },
  { label: "About Us", to: "/about" },
  { label: "Contact", to: "/contact" },
];
