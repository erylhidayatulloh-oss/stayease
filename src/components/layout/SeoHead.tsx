import React, { useEffect } from 'react';

interface SeoHeadProps {
  title: string;
  description: string;
  keywords?: string[];
  canonicalUrl?: string;
  ogImage?: string;
  schemaJson?: object | null;
}

export const SeoHead: React.FC<SeoHeadProps> = ({
  title,
  description,
  keywords,
  canonicalUrl = 'https://stayease.id',
  ogImage = 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&h=630&q=80',
  schemaJson
}) => {
  useEffect(() => {
    // Update Title
    document.title = title;

    // Update Meta Description
    let metaDesc = document.querySelector('meta[name="description"]');
    if (!metaDesc) {
      metaDesc = document.createElement('meta');
      metaDesc.setAttribute('name', 'description');
      document.head.appendChild(metaDesc);
    }
    metaDesc.setAttribute('content', description);

    // Update Meta Keywords
    if (keywords && keywords.length > 0) {
      let metaKeywords = document.querySelector('meta[name="keywords"]');
      if (!metaKeywords) {
        metaKeywords = document.createElement('meta');
        metaKeywords.setAttribute('name', 'keywords');
        document.head.appendChild(metaKeywords);
      }
      metaKeywords.setAttribute('content', keywords.join(', '));
    }

    // Update OpenGraph Tags
    const updateOgMeta = (property: string, content: string) => {
      let ogTag = document.querySelector(`meta[property="${property}"]`);
      if (!ogTag) {
        ogTag = document.createElement('meta');
        ogTag.setAttribute('property', property);
        document.head.appendChild(ogTag);
      }
      ogTag.setAttribute('content', content);
    };

    updateOgMeta('og:title', title);
    updateOgMeta('og:description', description);
    updateOgMeta('og:url', canonicalUrl);
    updateOgMeta('og:image', ogImage);

    // Inject Dynamic JSON-LD Schema
    const existingDynamicScript = document.getElementById('dynamic-page-schema');
    if (existingDynamicScript) {
      existingDynamicScript.remove();
    }

    if (schemaJson) {
      const script = document.createElement('script');
      script.id = 'dynamic-page-schema';
      script.type = 'application/ld+json';
      script.text = JSON.stringify(schemaJson, null, 2);
      document.head.appendChild(script);
    }

    return () => {
      const cleanupScript = document.getElementById('dynamic-page-schema');
      if (cleanupScript) {
        cleanupScript.remove();
      }
    };
  }, [title, description, keywords, canonicalUrl, ogImage, schemaJson]);

  return null;
};
