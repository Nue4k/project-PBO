'use client';

import { useState } from 'react';
import { useTheme } from './lib/ThemeContext';
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
  const { darkMode, toggleDarkMode } = useTheme();
  const [openFaqIndex, setOpenFaqIndex] = useState<number | null>(null);

  const toggleFaq = (index: number) => {
    setOpenFaqIndex(openFaqIndex === index ? null : index);
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