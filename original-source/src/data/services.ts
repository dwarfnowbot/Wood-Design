import { images } from "./media";

export type Service = {
  slug: string;
  title: string;
  shortDescription: string;
  image: string;
  to: string;
};

export const services: Service[] = [
  {
    slug: "kitchens",
    title: "Custom Kitchens",
    shortDescription:
      "Functional, beautiful kitchens designed to match your lifestyle, space, and personal taste.",
    image: images.kitchen[0],
    to: "/kitchens",
  },
  {
    slug: "wardrobes",
    title: "Custom Wardrobes",
    shortDescription:
      "Smartly planned wardrobes with refined finishes, practical storage, and a seamless fit.",
    image: images.wardrobe[0],
    to: "/wardrobes",
  },
  {
    slug: "home-woodwork",
    title: "Complete Home Woodwork",
    shortDescription:
      "Coordinated woodwork solutions for bedrooms, living rooms, kitchens, and other spaces.",
    image: images.living[4],
    to: "/interior-woodwork",
  },
  {
    slug: "tv-units",
    title: "TV Units & Media Walls",
    shortDescription:
      "Modern TV units and media walls designed to enhance your living space.",
    image: images.living[1],
    to: "/interior-woodwork",
  },
  {
    slug: "vanities-storage",
    title: "Vanities & Storage",
    shortDescription:
      "Custom bathroom vanities, shoe cabinets, and storage solutions made for your needs.",
    image: images.vanity[0],
    to: "/interior-woodwork",
  },
  {
    slug: "wall-panels",
    title: "Wall Panels & Interior Details",
    shortDescription:
      "Elegant wall panels and custom wood features that add warmth and character to interiors.",
    image: images.living[7],
    to: "/interior-woodwork",
  },
];
