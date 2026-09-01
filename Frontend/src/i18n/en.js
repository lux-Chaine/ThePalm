export default {
  app: {
    pageTitle: 'The Palm Hotel | Luxury in Kafr El Sheikh',
  },

  header: {
    brand: 'THE PALM',
    nav: {
      about: 'About',
      rooms: 'Rooms & Suites',
      dining: 'Dining',
      meetings: 'Meetings',
      offers: 'Offers',
      location: 'Location',
      reserve: 'Reserve',
    },
    menuAriaLabel: 'Toggle menu',
  },

  hero: {
    eyebrow: '4-Star Luxury · Kafr El Sheikh',
    title: 'Defining luxury hospitality in the heart of Kafr El Sheikh.',
    titleEm: 'luxury',
    subtitle:
      "Meticulously designed rooms and suites where modern comfort meets traditional Egyptian hospitality — steps from the university, the museum, and the city's landmarks.",
    cta: {
      primary: 'Reserve Now',
      ghost: 'View location',
    },
    stats: {
      hotelClass: { value: '4★', label: 'Hotel Class' },
      rooms: { value: '48', label: 'Rooms & Suites' },
      banquet: { value: '250', label: 'Banquet Capacity' },
      rating: { value: '9.1', label: 'Guest Rating' },
      frontDesk: { value: '24/7', label: 'Front Desk' },
    },
  },

  overview: {
    eyebrow: 'The Palm Hotel · Kafr El Sheikh',
    lead: 'A refined four-star address in the heart of the Nile Delta, The Palm pairs contemporary comfort with the warmth of Egyptian hospitality. Forty-eight rooms and suites, three dining destinations, and 250 m² of event space sit minutes from the University, the Museum, and the city\'s commercial district — making The Palm the address of choice for business travel, family visits, and celebrations alike.',
    awards: {
      guestChoice: { value: '2026', label: 'Guest Choice Award' },
      score: { value: '9.1 / 10', label: 'Average Guest Score' },
      ranked: { value: '#1', label: 'Rated in Kafr El Sheikh' },
      established: { value: '2018', label: 'Established' },
    },
  },

  reception: {
    eyebrow: 'Reception & Services',
    title: 'A warm welcome, any time you arrive.',
    info: {
      frontDesk: { label: 'Front Desk', value: 'Available 24/7' },
      languages: { label: 'Languages', value: 'Arabic / English' },
      checkIn: { label: 'Check-in', value: 'From 14:00' },
      checkOut: { label: 'Check-out', value: 'Until 12:00' },
      payment: { label: 'Payment', value: 'Visa / Mastercard' },
      address: { label: 'Address', value: '2, Kafr Abu Tabl, Kafr El Sheikh' },
    },
  },

  location: {
    eyebrow: 'Perfectly Situated',
    title: 'Everything within easy reach.',
    nearby: {
      university: { name: 'Kafr El Sheikh University', distance: '0.8 km from hotel' },
      church: { name: 'St. Demiana Church', distance: '1.2 km from hotel' },
      museum: { name: 'Kafr El Sheikh Museum', distance: '2.1 km from hotel' },
      cityCenter: { name: 'City Centre & Souq', distance: '1.5 km from hotel' },
    },
    distances: {
      head: { place: 'Getting Around', distance: 'Distance', time: 'Approx. Travel Time' },
      rows: [
        { place: 'Kafr El Sheikh Train Station', distance: '1.4 km', time: '5 min by car' },
        { place: 'Tanta City', distance: '45 km', time: '40 min by car' },
        { place: 'Alexandria', distance: '95 km', time: '1 h 15 min by car' },
        { place: 'Cairo International Airport (CAI)', distance: '145 km', time: '2 h by car' },
        { place: 'Borg El Arab Airport (HBE)', distance: '110 km', time: '1 h 30 min by car' },
      ],
    },
    facilities: ['Non-smoking', 'Restaurant', 'Room Service', 'Free Parking', 'Free WiFi', 'Tea / Coffee'],
  },

  rooms: {
    eyebrow: 'Exquisite Accommodation',
    title: 'Rooms & suites, designed for comfort.',
    booking: 'Book This Room',
    meta: {
      occupancy: 'Occupancy',
      view: 'View',
      floor: 'Floor',
    },
    list: [
      {
        size: '25 m²',
        name: 'Standard Room',
        config: 'King Bed (4 Rooms) · Twin Bed (28 Rooms)',
        description: 'Sized for corporate travelers and short city breaks alike. Every Standard Room features premium Egyptian cotton linens and a dedicated workspace.',
        occupancy: '2 Adults',
        view: 'City / Courtyard',
        floor: '1st – 4th',
        amenities: ['A/C', 'Shower', 'Safe Box', 'WiFi', 'Mini Bar'],
      },
      {
        size: '32 m²',
        name: 'Superior Room',
        config: 'King or Twin Bed (10 Rooms)',
        description: 'A step up in space and outlook, with a seating nook by the window and upgraded bath amenities — well suited to longer stays.',
        occupancy: '2 Adults, 1 Child',
        view: 'Garden / Pool',
        floor: '2nd – 5th',
        amenities: ['A/C', 'Bathtub', 'Safe Box', 'WiFi', 'Mini Bar', 'Seating Area'],
      },
      {
        size: '45 m²',
        name: 'Junior Suite',
        config: 'Premium Suite Collection (4 Suites)',
        description: 'An expansive living area alongside a spacious master bedroom — built for guests who want extra room to settle in and unwind.',
        occupancy: '3 Adults',
        view: 'Garden',
        floor: '3rd – 5th',
        amenities: ['A/C', 'Shower', 'Safe Box', 'WiFi', 'Mini Bar', 'Living Area'],
      },
      {
        size: '60 m²',
        name: 'Executive Suite',
        config: 'Top-Floor Collection (2 Suites)',
        description: "The Palm's most spacious address — a separate lounge, dining nook, and master bedroom, finished with upgraded furnishings for guests staying longer or hosting privately.",
        occupancy: '4 Adults',
        view: 'City Panorama',
        floor: 'Top Floor',
        amenities: ['A/C', 'Bathtub', 'Safe Box', 'WiFi', 'Mini Bar', 'Dining Nook', 'Lounge'],
      },
    ],
    policyNote:
      'Property policy: for the comfort of all guests, pets are not allowed within the hotel premises. Extra bed available on request, subject to room capacity and surcharge.',
  },

  dining: {
    eyebrow: 'Foods & Beverages',
    title: 'Dining, from breakfast to sunset.',
    metaLabels: {
      cuisine: 'Cuisine',
      hours: 'Hours',
      breakfast: 'Breakfast',
      seating: 'Seating',
      location: 'Location',
    },
    outlets: [
      {
        name: 'Main Restaurant',
        description: 'International buffet breakfast alongside a-la-carte dining, pairing local flavors with world classics, right off the lobby.',
        cuisine: 'International & Egyptian',
        hours: '07:00 – 23:00',
        breakfast: '07:00 – 11:00',
        location: 'Ground Floor',
      },
      {
        name: 'White Garden',
        description: 'An open-air courtyard café for shaded lunches, afternoon coffee, and shisha evenings under string lights.',
        cuisine: 'Café & Light Bites',
        hours: '12:00 – 00:00',
        seating: 'Outdoor Courtyard',
        location: 'Ground Floor Garden',
      },
      {
        name: 'Blue Sky Roof',
        description: 'Rooftop dining and shisha lounge with panoramic views over Kafr El Sheikh, best enjoyed at sunset.',
        cuisine: 'Grill & Shisha Lounge',
        hours: '16:00 – 01:00',
        seating: 'Open-Air Rooftop',
        location: 'Top Floor',
      },
    ],
  },

  events: {
    eyebrow: 'Events & Conferences',
    title: 'An elegant banquet room for every occasion.',
    description:
      'From weddings to grand galas and corporate conferences, our banquet and meeting spaces flex to fit — with attentive service throughout.',
    capacity: [
      { value: '120', label: 'Cocktail' },
      { value: '100', label: 'Auditorium' },
      { value: '80', label: 'Classroom' },
      { value: '60', label: 'U-Shape' },
      { value: '110', label: 'Theatre' },
    ],
    maxCap: {
      capacity: { label: 'Max Capacity', value: '250 Guests' },
      availability: { label: 'Availability', value: '24/7 Booking' },
    },
    venueTable: {
      head: { venue: 'Venue', area: 'Area', capacity: 'Max Capacity' },
      rows: [
        { name: 'Grand Ballroom', area: '180 m²', capacity: '250 guests' },
        { name: 'Palm Meeting Room', area: '60 m²', capacity: '60 guests' },
        { name: 'Boardroom', area: '35 m²', capacity: '20 guests' },
      ],
    },
  },

  kids: {
    eyebrow: 'Family & Leisure',
    title: 'The Palm Kids Zone',
    description:
      'A safe, creative space for our younger guests, with professional supervision on hand so the whole family can relax.',
    note: 'Service chargeable',
  },

  amenities: {
    eyebrow: 'Facilities & Services',
    title: 'Everything a stay calls for.',
    list: [
      { title: 'Front Desk', description: 'Staffed around the clock in Arabic and English, with luggage storage and wake-up calls on request.' },
      { title: 'Free WiFi', description: 'High-speed wireless internet throughout all rooms, public areas, and event spaces.' },
      { title: 'Free Parking', description: 'Private on-site parking for guests and event attendees, available at no extra charge.' },
      { title: 'Airport Transfer', description: 'Shuttle service to Cairo and Borg El Arab airports arranged through the front desk, surcharge applies.' },
      { title: 'Laundry & Dry Cleaning', description: 'Same-day laundry and pressing service available Sunday through Thursday.' },
      { title: 'Currency Exchange', description: 'On-site currency exchange and ATM access in the main lobby.' },
      { title: 'Room Service', description: 'In-room dining available during Main Restaurant hours, 07:00 to 23:00.' },
      { title: 'Business Centre', description: 'Printing, photocopying, and fax services, plus meeting-ready workstations near the lobby.' },
    ],
  },

  offers: {
    eyebrow: 'Offers & Packages',
    title: 'Tailored ways to stay with us.',
    cta: 'Enquire →',
    list: [
      { tag: 'Popular', title: 'Bed & Breakfast', description: 'Room rate inclusive of our international buffet breakfast at the Main Restaurant, served daily from 07:00.' },
      { tag: 'Save More', title: 'Stay Longer', description: 'Book three consecutive nights or more and receive a complimentary late check-out until 16:00, subject to availability.' },
      { tag: 'For Business', title: 'Corporate Rate', description: 'Preferential rates for corporate accounts and long-stay business travelers, with flexible billing on request.' },
      { tag: 'Celebrations', title: 'Events Package', description: 'Bundle the Grand Ballroom with catering from our dining team for weddings, conferences, and family celebrations.' },
    ],
  },

  guests: {
    eyebrow: 'Guest Experiences',
    title: "Stories from those who've stayed.",
    ratings: [
      { value: '9.1', label: 'Overall' },
      { value: '9.3', label: 'Staff' },
      { value: '8.9', label: 'Cleanliness' },
      { value: '9.0', label: 'Location' },
      { value: '8.8', label: 'Value' },
    ],
    people: [
      { name: 'Mohamed Almogtaba', role: 'Guest', image: 'https://the-palm.vercel.app/images/palm14.jpg' },
      { name: 'Abdullah', role: 'Guest', image: 'https://the-palm.vercel.app/images/palm17.jpg' },
      { name: 'Ahmed', role: 'Guest', image: 'https://the-palm.vercel.app/images/palm15.jpg' },
      { name: 'Hussain', role: 'Guest', image: 'https://the-palm.vercel.app/images/palm16.jpg' },
    ],
  },

  policies: {
    eyebrow: 'Good to Know',
    title: 'Policies at a glance.',
    list: [
      { title: 'Check-in / Check-out', description: 'Check-in from 14:00, check-out until 12:00. Early check-in and late check-out are offered subject to availability.' },
      { title: 'Cancellation', description: 'Free cancellation up to 48 hours before arrival for standard-rate bookings; non-refundable rates are noted at booking.' },
      { title: 'Children & Extra Beds', description: 'Children of all ages are welcome. Extra beds and cots are available on request, subject to room capacity and a surcharge.' },
      { title: 'Payment & ID', description: 'Visa and Mastercard accepted alongside cash. A valid national ID or passport is required for check-in.' },
      { title: 'Pets', description: 'For the comfort of all guests, pets are not permitted within the hotel premises.' },
      { title: 'Smoking', description: 'The Palm is a non-smoking property; designated outdoor smoking areas are available.' },
    ],
  },

  cta: {
    eyebrow: 'Make Your Reservation',
    title: "We're happy if you contact us.",
    contact: {
      email: 'reservation@chain-luxe.com',
      phone: '+20 110 314 5834',
      whatsapp: 'WhatsApp',
    },
    button: 'Reserve Now',
  },

  bookingModal: {
    eyebrow: 'Reservation Request',
    title: 'Book your stay with us',
    description: 'Fill in your details and our reservation team will contact you shortly.',
    fields: {
      name: 'Full name',
      namePlaceholder: 'Enter your full name',
      phone: 'Phone number',
      phonePlaceholder: 'e.g. 1xx xxxx xxxx',
      email: 'Email address',
      emailPlaceholder: 'you@example.com',
      guests: 'Number of guests',
      checkIn: 'Check-in date',
      checkOut: 'Check-out date',
      roomType: 'Room type',
      notes: 'Special requests',
      notesPlaceholder: 'Any additional details or requests...',
    },
    roomOptions: {
      standard: 'Standard Room',
      superior: 'Superior Room',
      junior: 'Junior Suite',
      executive: 'Executive Suite',
    },
    submit: 'Confirm Booking',
    submitting: 'Sending...',
    cancel: 'Cancel',
    close: 'Close',
    required: 'Please fill in your name, phone and dates.',
    error: 'Failed to send booking request. Please try again.',
    networkError: 'Network error. Please check your connection and try again.',
    successTitle: 'Booking request sent!',
    successMessage: 'Your reservation request has been received. Our reservation team will contact you soon to confirm the booking.',
  },

  footer: {
    brand: 'THE PALM',
    tagline: 'A 4-star address in Kafr El Sheikh with 48 rooms and suites, three dining venues, and 250 m² of event space. Established 2018.',
    explore: {
      heading: 'Explore',
      links: [
        { label: 'About Property', href: '#about' },
        { label: 'Rooms & Suites', href: '#rooms' },
        { label: 'Foods & Beverages', href: '#dining' },
        { label: 'Meetings & Events', href: '#meetings' },
        { label: 'Offers & Packages', href: '#offers' },
      ],
    },
    reservations: {
      heading: 'Reservations',
      email: 'reservation@chain-luxe.com',
      phone: '+20 110 314 5834',
      address: 'Kafr El Sheikh, Egypt',
    },
    follow: {
      heading: 'Follow',
      facebook: 'Facebook',
      instagram: 'Instagram',
    },
    bottom: {
      copyright: '© 2026 The Palm Hotel',
      location: 'Kafr El Sheikh, Egypt',
    },
  },
};
