import { images } from "./media";

export type Project = {
  id: string;
  title: string;
  category: "Kitchens" | "Wardrobes" | "Living Rooms" | "Bedrooms" | "TV Units" | "Complete Interiors";
  location: string;
  description: string;
  materials?: string;
  image: string;
};

// Sample project concepts for presentation purposes only.
// Replace with real, completed project photography and details once available.
export const projects: Project[] = [
  {
    id: "p1",
    title: "Walnut Handleless Kitchen",
    category: "Kitchens",
    location: "Sample Project Concept — DHA, Lahore",
    description:
      "A handleless galley kitchen in warm walnut tones with an integrated stone island and hidden storage.",
    materials: "Walnut veneer, engineered stone counter, soft-close hardware",
    image: images.kitchen[1],
  },
  {
    id: "p2",
    title: "Contemporary Island Kitchen",
    category: "Kitchens",
    location: "Sample Project Concept — Bahria Town, Lahore",
    description:
      "An open-plan kitchen built around a central island, designed for family living and entertaining.",
    materials: "Laminate finish, quartz countertop, tall pantry units",
    image: images.kitchen[4],
  },
  {
    id: "p3",
    title: "Walk-In Wardrobe Suite",
    category: "Wardrobes",
    location: "Sample Project Concept — Gulberg, Lahore",
    description:
      "A full walk-in dressing room with layered lighting, open shelving, and dedicated accessory drawers.",
    materials: "Matte laminate, soft-close drawers, mirrored panels",
    image: images.wardrobe[3],
  },
  {
    id: "p4",
    title: "Sliding Door Bedroom Wardrobe",
    category: "Wardrobes",
    location: "Sample Project Concept — Johar Town, Lahore",
    description:
      "A floor-to-ceiling sliding wardrobe designed to maximise storage in a compact bedroom footprint.",
    materials: "Veneer finish, aluminium sliding track, internal organisers",
    image: images.wardrobe[6],
  },
  {
    id: "p5",
    title: "Warm Wood Media Wall",
    category: "TV Units",
    location: "Sample Project Concept — DHA Phase 6, Lahore",
    description:
      "A floating media wall combining open display shelving with concealed storage either side.",
    materials: "Wood-textured laminate, brass inlay detailing",
    image: images.living[1],
  },
  {
    id: "p6",
    title: "Panelled Living Room Feature Wall",
    category: "Living Rooms",
    location: "Sample Project Concept — Model Town, Lahore",
    description:
      "A textured wood panel wall that anchors the seating area and adds warmth to a neutral interior.",
    materials: "Fluted wood panels, painted finish trims",
    image: images.living[6],
  },
  {
    id: "p7",
    title: "Complete Residence Woodwork",
    category: "Complete Interiors",
    location: "Sample Project Concept — Cavalry Ground, Lahore",
    description:
      "Coordinated kitchen, wardrobes, TV unit and vanities delivered as one cohesive design language.",
    materials: "Mixed veneer and laminate palette, stone counters",
    image: images.living[3],
  },
  {
    id: "p8",
    title: "Minimal Bedroom Woodwork",
    category: "Bedrooms",
    location: "Sample Project Concept — Askari, Lahore",
    description:
      "A calm, minimal bedroom with a built-in headboard, bedside units, and matching wardrobe run.",
    materials: "Painted matte finish, warm oak accents",
    image: images.wardrobe[5],
  },
  {
    id: "p9",
    title: "Marble & Wood Bathroom Vanity",
    category: "Complete Interiors",
    location: "Sample Project Concept — DHA Phase 5, Lahore",
    description:
      "A dual-basin vanity unit pairing warm wood cabinetry with a honed stone countertop.",
    materials: "Water-resistant board, stone top, brass fittings",
    image: images.vanity[0],
  },
  {
    id: "p10",
    title: "Family Kitchen with Breakfast Island",
    category: "Kitchens",
    location: "Sample Project Concept — Valencia, Lahore",
    description:
      "A light-toned family kitchen with an island breakfast counter and full-height storage wall.",
    materials: "Matte laminate, quartz top, integrated appliances",
    image: images.kitchen[5],
  },
];

export const projectCategories = [
  "All",
  "Kitchens",
  "Wardrobes",
  "Living Rooms",
  "Bedrooms",
  "TV Units",
  "Complete Interiors",
] as const;
