'use client';

import { useState, useEffect } from 'react';
import HeaderSection from './components/HeaderSection';
import HeroSection from './components/HeroSection';
import FeaturesSection from './components/FeaturesSection';
import BenefitsSection from './components/BenefitsSection';
import HowItWorksSection from './components/HowItWorksSection';
import UserTypesSection from './components/UserTypesSection';
import FAQSection from './components/FAQSection';
import FooterCtaSection from './components/FooterCtaSection';
import Footer from './components/Footer';
import FloatingLoginButton from './components/FloatingLoginButton';
import FloatingThemeToggle from './components/FloatingThemeToggle';

export default function Home() {
  const [openFaqIndex, setOpenFaqIndex] = useState<number | null>(null);
  const [darkMode, setDarkMode] = useState(false);

  useEffect(() => {
    // Check system preference for dark mode
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      setDarkMode(true);
    }
  }, []);

  useEffect(() => {
    // Update the class on the document element
    if (darkMode) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  }, [darkMode]);

  const toggleFaq = (index: number) => {
    setOpenFaqIndex(openFaqIndex === index ? null : index);
  };

  const toggleDarkMode = () => {
    setDarkMode(!darkMode);
  };

  return (
    <div className={`min-h-screen font-sans ${darkMode ? 'dark bg-gray-900' : 'bg-white'}`} style={{ fontFamily: 'Instrument Sans, system-ui, sans-serif' }}>
      <HeaderSection
        darkMode={darkMode}
        toggleDarkMode={toggleDarkMode}
        showThemeToggle={false}
      />
      <HeroSection darkMode={darkMode} />
      <FeaturesSection darkMode={darkMode} />
      <BenefitsSection darkMode={darkMode} />
      <HowItWorksSection darkMode={darkMode} />
      <UserTypesSection darkMode={darkMode} />
      <FAQSection
        darkMode={darkMode}
        openFaqIndex={openFaqIndex}
        toggleFaq={toggleFaq}
      />
      <FooterCtaSection darkMode={darkMode} />
      <Footer darkMode={darkMode} />
      <FloatingThemeToggle
        darkMode={darkMode}
        toggleDarkMode={toggleDarkMode}
      />
    </div>
  );
}