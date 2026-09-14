/**
 * page-copy.mjs
 * ---------------------------------------------------------------------------
 * Page-level copy taken verbatim from the original project's page components
 * (original-source/src/pages/*.tsx). This is merged into
 * inc/content/site-content.json by build-content.mjs so that the WordPress
 * implementation, the Elementor page blueprints and the theme's fallback
 * templates all render the exact same words as the original website.
 *
 * Nothing here is invented: every string exists in the original source.
 */

export const pageCopy = {
  /* ------------------------------------------------------------------ Home */
  home: {
    title: 'Home',
    slug: 'home',
    hero: {
      eyebrow: 'Lahore · Custom Kitchens & Interior Woodwork',
      heading: 'Custom Kitchens & Complete Home Woodwork',
      text: 'Beautifully designed, precisely crafted, and built around the way you live.',
      image: 'heroKitchen',
      buttons: [
        { label: 'Book a Consultation', path: '/get-a-quote', variant: 'ghost' },
        { label: 'View Our Projects', path: '/projects', variant: 'secondary' },
      ],
      whatsapp: true,
    },
    intro: {
      eyebrow: 'Our Studio',
      heading: 'Crafted Around Your Space. Designed Around Your Life.',
      description:
        'We create custom kitchens, wardrobes, and complete woodwork solutions that combine thoughtful design, practical functionality, and refined craftsmanship. Every project is tailored to the client\u2019s space, lifestyle, and aesthetic.',
      button: { label: 'Learn About Our Studio', path: '/about', variant: 'outline' },
      image: 'introKitchen',
      insetImage: 'wardrobe.3',
      insetAlt: 'Custom wardrobe interior detail',
    },
    services: { eyebrow: 'What We Do', heading: 'Our Expertise' },
    projects: {
      eyebrow: 'Portfolio',
      heading: 'Selected Projects',
      button: { label: 'View All Projects', path: '/projects', variant: 'outline' },
      count: 6,
    },
    why: { eyebrow: 'Our Difference', heading: 'Why Clients Choose Us' },
    process: {
      eyebrow: 'How We Work',
      heading: 'Our Process',
      button: { label: 'See Full Process', path: '/process', variant: 'outline' },
    },
    materials: {
      eyebrow: 'Craftsmanship',
      heading: 'Materials. Finishes. Details.',
      link: { label: 'Explore Materials & Finishes', path: '/materials-finishes' },
    },
    testimonials: {
      eyebrow: 'Client Feedback',
      heading: 'What Our Clients Say',
      note: 'Sample placeholder testimonials shown for illustration \u2014 not verified reviews.',
    },
  },

  /* ----------------------------------------------------------------- About */
  about: {
    title: 'About Us',
    slug: 'about',
    hero: {
      eyebrow: 'About Our Studio',
      heading: 'Thoughtful Design. Reliable Craftsmanship.',
      description:
        'A Lahore-based studio dedicated to custom kitchens, wardrobes, and complete home woodwork.',
      image: 'about',
    },
    intro: {
      eyebrow: 'Who We Are',
      heading: 'A Studio Built Around Considered Woodwork',
      description:
        '[Editable placeholder] Our studio designs and builds custom kitchens, wardrobes, and complete home woodwork for homeowners across Lahore. We work closely with each client \u2014 from first conversation to final installation \u2014 to create spaces that are both beautiful and genuinely functional.',
      secondary:
        '[Editable placeholder] Whether you are building a new home, renovating an existing one, or furnishing a single room, our approach stays the same: understand the space, plan around how it will be used, and execute with care.',
      image: 'aboutSecondary',
    },
    approach: {
      eyebrow: 'Our Approach',
      heading: 'How We Think About Every Project',
      points: [
        {
          title: 'Craftsmanship Philosophy',
          description:
            'We believe good woodwork is judged in the details \u2014 clean joinery, consistent finishes, and hardware that performs quietly for years. Every project is approached with the same level of care, regardless of scale.',
        },
        {
          title: 'Design & Functionality',
          description:
            'Beautiful design should also work hard for the way you actually live. We plan layouts around daily routines \u2014 how you cook, store, dress, and relax \u2014 before finalising any aesthetic direction.',
        },
        {
          title: 'Quality & Attention to Detail',
          description:
            'From board selection to the final alignment of a cabinet door, our team reviews work at each stage so that the finished result feels considered and precise.',
        },
        {
          title: 'Customization Process',
          description:
            'Nothing is off-the-shelf. Each kitchen, wardrobe, or woodwork element is designed from your space\u2019s actual measurements, structural constraints, and personal preferences.',
        },
        {
          title: 'Professional Execution',
          description:
            'Our team manages the process from consultation through to installation, keeping communication clear and the site tidy and respected throughout.',
        },
      ],
    },
    area: {
      eyebrow: 'Where We Work',
      heading: 'Serving Lahore & Nearby Areas',
      description:
        'We currently take on projects across {serviceArea}. Whether you are locally based or planning a home from overseas, we coordinate site visits, measurements, and installation to fit your schedule.',
      secondary:
        '[Editable placeholder] For overseas clients building or renovating a home in Lahore, we can coordinate consultations remotely and schedule site visits and installation around your availability.',
      image: 'kitchen.6',
      alt: 'Custom kitchen designed for a Lahore residence',
    },
    cta: {
      heading: 'Have a Project in Mind?',
      text: 'Tell us about your home and requirements, and we\u2019ll help you plan the next step.',
      primaryLabel: 'Request a Quote',
    },
  },

  /* -------------------------------------------------------------- Kitchens */
  kitchens: {
    title: 'Kitchens',
    slug: 'kitchens',
    hero: {
      eyebrow: 'Custom Kitchens',
      heading: 'Custom Kitchens Designed for the Way You Live',
      description:
        'From layout to finish, every kitchen is planned around your space, storage needs, and cooking habits.',
      image: 'heroKitchen',
    },
    intro: {
      eyebrow: 'Introduction',
      heading: 'A Kitchen Shaped by How You Cook, Gather, and Live',
      description:
        'Your kitchen is often the busiest room in the home. We design each layout around practical workflow \u2014 storage, preparation space, and appliance placement \u2014 before layering in the material palette and finishing details.',
      button: { label: 'Discuss Your Kitchen Project', path: '/get-a-quote', variant: 'primary' },
      image: 'kitchen.3',
      alt: 'Custom kitchen island with pendant lighting',
    },
    styles: { eyebrow: 'Design Direction', heading: 'Kitchen Styles We Design' },
    features: {
      eyebrow: 'Functionality',
      heading: 'Kitchen Features We Plan For',
      image: 'kitchen.5',
      alt: 'Kitchen with integrated storage and tall units',
    },
    gallery: { eyebrow: 'Gallery', heading: 'Kitchen Project Gallery' },
    process: { eyebrow: 'How We Work', heading: 'Our Process' },
    faq: { eyebrow: 'Questions', heading: 'Kitchen FAQs' },
    cta: {
      heading: 'Discuss Your Kitchen Project',
      text: 'Share your space and requirements, and we\u2019ll help you plan the design and next steps.',
      primaryLabel: 'Discuss Your Kitchen Project',
    },
  },

  /* ------------------------------------------------------------ Wardrobes */
  wardrobes: {
    title: 'Wardrobes',
    slug: 'wardrobes',
    hero: {
      eyebrow: 'Custom Wardrobes',
      heading: 'Custom Wardrobes. Beautifully Organized.',
      description:
        'Wardrobes designed around your wardrobe, your room, and the way you get ready each day.',
      image: 'wardrobe.0',
    },
    intro: {
      eyebrow: 'Introduction',
      heading: 'Storage That Is Planned, Not Just Installed',
      description:
        'A well-designed wardrobe starts with understanding what needs to be stored \u2014 clothing, shoes, accessories \u2014 and how much space is available. We plan the internal layout first, then design the exterior finish to suit your room.',
      button: { label: 'Discuss Your Wardrobe Project', path: '/get-a-quote', variant: 'primary' },
      image: 'wardrobe.2',
      alt: 'Custom wardrobe with organised internal shelving',
    },
    types: { eyebrow: 'Wardrobe Types', heading: 'Wardrobe Formats We Design' },
    internal: {
      eyebrow: 'Internal Planning',
      heading: 'Drawers, Shelves & Accessories',
      description: 'Every wardrobe interior is planned to suit what you\u2019ll actually store.',
      image: 'wardrobe.5',
      alt: 'Wardrobe drawers and internal storage detail',
    },
    benefits: { eyebrow: 'Benefits', heading: 'Why Clients Choose Our Wardrobes' },
    gallery: { eyebrow: 'Gallery', heading: 'Wardrobe Project Gallery' },
    process: { eyebrow: 'How We Work', heading: 'Our Process' },
    faq: { eyebrow: 'Questions', heading: 'Wardrobe FAQs' },
    cta: {
      heading: 'Plan Your Custom Wardrobe',
      text: 'Tell us about your room and storage needs, and we\u2019ll help you plan the layout and finishes.',
      primaryLabel: 'Request a Quote',
    },
  },

  /* --------------------------------------------------- Interior Woodwork */
  'interior-woodwork': {
    title: 'Interior Woodwork',
    slug: 'interior-woodwork',
    hero: {
      eyebrow: 'Interior Woodwork',
      heading: 'Complete Woodwork for Considered Interiors',
      description:
        'From TV units to wall panels, vanities, and storage \u2014 coordinated joinery designed as part of one cohesive interior.',
      image: 'living.6',
    },
    intro: {
      eyebrow: 'Introduction',
      heading: 'One Studio for Every Woodwork Element in Your Home',
      description:
        'Beyond kitchens and wardrobes, we design and build the smaller woodwork elements that tie an interior together \u2014 media walls, wall panelling, vanities, and custom storage \u2014 all designed to feel like part of one considered scheme.',
      image: 'living.2',
      alt: 'Living room with coordinated wood joinery',
    },
    categories: { eyebrow: 'Our Services', heading: 'Woodwork Categories' },
    process: { eyebrow: 'How We Work', heading: 'Our Process' },
    cta: {
      heading: 'Planning Complete Home Woodwork?',
      text: 'Tell us about the rooms and elements you\u2019d like designed, and we\u2019ll help you plan a coordinated scheme.',
      primaryLabel: 'Request a Quote',
    },
  },

  /* ------------------------------------------------------------- Projects */
  projects: {
    title: 'Projects',
    slug: 'projects',
    hero: {
      eyebrow: 'Portfolio',
      heading: 'Selected Projects',
      description:
        'A look at the kinds of kitchens, wardrobes, and woodwork projects we design \u2014 presented as sample project concepts.',
      image: 'living.3',
    },
    categories: {
      eyebrow: 'Browse by Category',
      heading: 'Our Project Categories',
      description:
        'Sample project concepts shown for presentation purposes. Replace with real completed project photography as it becomes available.',
    },
    cta: {
      heading: 'Like What You See?',
      text: 'Share your space and inspiration with us, and we\u2019ll help you plan a project of your own.',
      primaryLabel: 'Request a Quote',
    },
  },

  /* --------------------------------------------------- Materials & Finishes */
  'materials-finishes': {
    title: 'Materials & Finishes',
    slug: 'materials-finishes',
    hero: {
      eyebrow: 'Materials & Finishes',
      heading: 'Materials. Finishes. Details.',
      description:
        'A general overview of the boards, finishes, and hardware options we work with across projects.',
      image: 'kitchen.1',
      height: 'short',
    },
    overview: {
      eyebrow: 'Overview',
      heading: 'A Considered Palette of Materials',
      description:
        'We select boards, finishes, and hardware based on durability, everyday practicality, and the aesthetic direction of each project.',
    },
    countertops: {
      eyebrow: 'Countertops',
      heading: 'Stone & Countertop Combinations',
      description:
        'Countertop material and cabinetry tone are selected together so the finished kitchen or vanity feels cohesive. Options are discussed during the design and material selection stage of your project.',
      image: 'kitchen.4',
      alt: 'Kitchen showing stone countertop and wood cabinetry combination',
    },
    availability: { heading: 'A Note on Availability' },
    cta: {
      heading: 'Not Sure Which Finish Is Right for You?',
      text: 'Share your style preferences and space with us, and we\u2019ll help you choose a suitable palette.',
      primaryLabel: 'Request a Quote',
    },
  },

  /* -------------------------------------------------------------- Process */
  process: {
    title: 'Process',
    slug: 'process',
    hero: {
      eyebrow: 'Our Process',
      heading: 'A Clear, Considered Process From Start to Finish',
      description:
        'From the first consultation to final handover, every step is planned so you know what to expect.',
      image: 'living.4',
      height: 'short',
    },
    steps: { eyebrow: 'Step by Step', heading: 'How a Project Comes Together' },
    cta: {
      heading: 'Ready to Start the Process?',
      text: 'Book a consultation and we\u2019ll walk you through each step in more detail.',
      primaryLabel: 'Request a Quote',
    },
  },

  /* ---------------------------------------------------------- Get a Quote */
  'get-a-quote': {
    title: 'Get a Quote',
    slug: 'get-a-quote',
    hero: {
      eyebrow: 'Get a Quote',
      heading: 'Tell Us About Your Project',
      description:
        'Share a few details about your space and requirements, and we\u2019ll be in touch to discuss the next steps.',
      image: 'kitchen.2',
      height: 'short',
    },
    form: {
      heading: 'Project Inquiry Form',
      submit: 'Submit Inquiry',
      note: 'We\u2019ll review your requirements and contact you to discuss the next steps.',
      success: {
        heading: 'Thank You',
        text: 'We\u2019ve received your inquiry. Our team will review your requirements and contact you shortly to discuss the next steps.',
        whatsappLabel: 'Chat on WhatsApp',
        whatsappMessage:
          'Hi, I just submitted a quote request and wanted to follow up.',
      },
    },
    aside: {
      heading: 'Prefer to Talk Directly?',
      whatsappMessage: 'Hi, I\u2019d like to request a quote for my project.',
      hoursHeading: 'Business Hours',
    },
  },

  /* -------------------------------------------------------------- Contact */
  contact: {
    title: 'Contact',
    slug: 'contact',
    hero: {
      eyebrow: 'Contact Us',
      heading: 'Let\u2019s Talk About Your Space',
      description: 'Reach out to book a consultation, ask a question, or say hello.',
      image: 'living.5',
      height: 'short',
    },
    form: {
      eyebrow: 'Get in Touch',
      heading: 'Send Us a Message',
      submit: 'Send Message',
      success: {
        heading: 'Message Sent',
        text: 'Thank you for reaching out. We\u2019ll get back to you as soon as possible.',
      },
    },
    details: { heading: 'Contact Details' },
    hours: { heading: 'Business Hours' },
    social: { heading: 'Follow Us' },
    map: { heading: 'Studio location map' },
  },

  /* ----------------------------------------------------------- Not found */
  notfound: {
    eyebrow: '404',
    heading: 'Page Not Found',
    text: 'The page you\u2019re looking for doesn\u2019t exist or may have been moved.',
    button: { label: 'Back to Home', path: '/' },
  },
};

/** Form definitions — field names, labels and option lists from the original forms. */
export const formCopy = {
  quote: {
    fields: {
      fullName: { label: 'Full Name', placeholder: 'Your full name', type: 'text', required: true },
      phone: { label: 'Phone Number', placeholder: '03xx xxxxxxx', type: 'tel', required: true },
      whatsapp: { label: 'WhatsApp Number', placeholder: '03xx xxxxxxx', type: 'tel' },
      email: { label: 'Email Address', placeholder: 'you@example.com', type: 'email' },
      projectType: {
        label: 'Project Type',
        placeholder: 'Select project type',
        type: 'select',
        required: true,
        options: ['Kitchen', 'Wardrobe', 'Complete Home Woodwork', 'TV Unit', 'Vanity', 'Wall Panels', 'Other'],
      },
      location: {
        label: 'Preferred Location / Area in Lahore',
        placeholder: 'e.g. DHA, Gulberg, Bahria Town',
        type: 'text',
      },
      budget: {
        label: 'Estimated Budget',
        placeholder: 'Select a budget range',
        type: 'select',
        options: [
          'Under PKR 5 Lac',
          'PKR 5 \u2013 10 Lac',
          'PKR 10 \u2013 20 Lac',
          'PKR 20 \u2013 40 Lac',
          'PKR 40 Lac+',
          'Not sure yet',
        ],
      },
      stage: {
        label: 'Project Stage',
        placeholder: 'Select project stage',
        type: 'select',
        options: ['Planning', 'Under Construction', 'Renovation', 'Ready for Installation'],
      },
      message: {
        label: 'Message / Requirements',
        placeholder: 'Tell us about your space, style preferences, and timeline',
        type: 'textarea',
      },
      reference: {
        label: 'Upload Reference Images or Plans',
        type: 'file',
        multiple: true,
        accept: 'image/*,.pdf',
      },
      contactMethod: {
        label: 'Preferred Contact Method',
        type: 'radio',
        options: ['Phone Call', 'WhatsApp', 'Email'],
      },
    },
  },
  contact: {
    fields: {
      fullName: { label: 'Full Name', placeholder: 'Your full name', type: 'text', required: true },
      phone: { label: 'Phone Number', placeholder: '03xx xxxxxxx', type: 'tel', required: true },
      email: { label: 'Email Address', placeholder: 'you@example.com', type: 'email' },
      message: { label: 'Message', placeholder: 'How can we help?', type: 'textarea', required: true },
    },
  },
};
