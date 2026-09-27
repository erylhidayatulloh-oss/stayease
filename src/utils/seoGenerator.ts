import { Property, CityHub } from '../types';

export function generatePropertySchema(property: Property, baseUrl: string = 'https://stayease.id') {
  const propertyUrl = `${baseUrl}/p/${property.slug}`;
  
  return {
    "@context": "https://schema.org",
    "@type": property.seo.schemaType || "RealEstateListing",
    "@id": propertyUrl,
    "name": property.title,
    "description": property.description,
    "url": propertyUrl,
    "image": property.images,
    "datePosted": "2026-01-15T08:00:00+07:00",
    "offers": {
      "@type": "AggregateOffer",
      "priceCurrency": "IDR",
      "lowPrice": property.basePriceMonthly,
      "highPrice": (property.basePriceMonthly * 1.5),
      "priceSpecification": {
        "@type": "UnitPriceSpecification",
        "price": property.basePriceMonthly,
        "priceCurrency": "IDR",
        "unitCode": "MON",
        "unitText": "Bulan"
      },
      "availability": property.isAvailable ? "https://schema.org/InStock" : "https://schema.org/OutOfStock",
      "validFrom": "2026-01-01"
    },
    "address": {
      "@type": "PostalAddress",
      "streetAddress": property.address,
      "addressLocality": property.subDistrict,
      "addressRegion": property.city,
      "addressCountry": "ID"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": property.coords.lat,
      "longitude": property.coords.lng
    },
    "aggregateRating": {
      "@type": "AggregateRating",
      "ratingValue": property.rating,
      "reviewCount": property.reviewCount,
      "bestRating": 5,
      "worstRating": 1
    },
    "amenityFeature": property.facilities.map(facility => ({
      "@type": "LocationFeatureSpecification",
      "name": facility,
      "value": true
    })),
    "provider": {
      "@type": "RealEstateAgent",
      "name": property.landlord.name,
      "telephone": property.landlord.phoneWhatsapp,
      "image": property.landlord.avatar
    }
  };
}

export function generateBreadcrumbSchema(items: { name: string; url: string }[], baseUrl: string = 'https://stayease.id') {
  return {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": items.map((item, index) => ({
      "@type": "ListItem",
      "position": index + 1,
      "name": item.name,
      "item": item.url.startsWith('http') ? item.url : `${baseUrl}${item.url}`
    }))
  };
}

export function generateCityFaqSchema(city: CityHub) {
  return {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": city.faqs.map(faq => ({
      "@type": "Question",
      "name": faq.q,
      "acceptedAnswer": {
        "@type": "Answer",
        "text": faq.a
      }
    }))
  };
}
