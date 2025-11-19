// HeaderSection.tsx
import { ToggleDarkModeProps } from '../interfaces';
import { useState } from 'react';

const scrollToSection = (id: string) => {
  const element = document.getElementById(id);
  if (element) {
    element.scrollIntoView({ behavior: 'smooth' });
  }
};

const HeaderSection = ({ darkMode, toggleDarkMode }: { darkMode: boolean } & ToggleDarkModeProps) => {
  const [showLoginDropdown, setShowLoginDropdown] = useState(false);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const navItems = [
    { name: 'Fitur', id: 'features' },
    { name: 'Keunggulan', id: 'benefits' },
    { name: 'Cara Kerja', id: 'how-it-works' },
    { name: 'FAQ', id: 'faqs' },
    { name: 'Kontak', id: 'contact' }
  ];

  return (
    <header className={`fixed top-0 left-0 right-0 backdrop-blur-sm z-50 border-b ${darkMode ? 'bg-gray-900/90 border-gray-700' : 'bg-white/90 border-[#e5e7eb]'}`}>
      <div className="max-w-[1200px] mx-auto px-[40px] py-4">
        <div className="flex justify-between items-center h-16">
          <div className="flex items-center">
            <div className={`text-xl font-bold ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>InternBridge</div>
          </div>

          {/* Desktop Navigation */}
          <nav className="hidden md:flex space-x-8">
            {navItems.map((item) => (
              <a
                key={item.name}
                href={`#${item.id}`}
                onClick={(e) => {
                  e.preventDefault();
                  scrollToSection(item.id);
                  setMobileMenuOpen(false); // Close mobile menu if open
                }}
                className={`${darkMode ? 'text-white hover:text-gray-300' : 'text-[#0f0f0f] hover:text-[#737373]'} transition-colors font-medium`}
              >
                {item.name}
              </a>
            ))}
          </nav>

          {/* Desktop Login and Controls */}
          <div className="hidden md:flex items-center space-x-4">
            {/* Login Dropdown */}
            <div className="relative">
              <button
                onClick={() => setShowLoginDropdown(!showLoginDropdown)}
                className="bg-[#f59e0b] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#d97706] transition-colors"
              >
                Masuk
              </button>

              {showLoginDropdown && (
                <div className={`absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 z-50 ${darkMode ? 'bg-gray-800' : 'bg-white'} border ${darkMode ? 'border-gray-700' : 'border-gray-200'}`}>
                  <a
                    href="#"
                    className={`block px-4 py-2 text-sm ${darkMode ? 'text-white hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100'}`}
                    onClick={(e) => {
                      e.preventDefault();
                      // Handle student login
                      setShowLoginDropdown(false);
                    }}
                  >
                    Mahasiswa
                  </a>
                  <a
                    href="#"
                    className={`block px-4 py-2 text-sm ${darkMode ? 'text-white hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100'}`}
                    onClick={(e) => {
                      e.preventDefault();
                      // Handle company login
                      setShowLoginDropdown(false);
                    }}
                  >
                    Perusahaan
                  </a>
                </div>
              )}
            </div>

          </div>

          {/* Mobile Menu Button */}
          <div className="flex items-center space-x-4">
            <button
              className={`md:hidden ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            >
              {mobileMenuOpen ? (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                </svg>
              ) : (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              )}
            </button>
          </div>
        </div>

        {/* Mobile Menu */}
        {mobileMenuOpen && (
          <div className={`md:hidden mt-4 py-4 border-t ${darkMode ? 'border-gray-700' : 'border-gray-200'}`}>
            <div className="flex flex-col space-y-4">
              {navItems.map((item) => (
                <a
                  key={item.name}
                  href={`#${item.id}`}
                  onClick={(e) => {
                    e.preventDefault();
                    scrollToSection(item.id);
                    setMobileMenuOpen(false);
                  }}
                  className={`${darkMode ? 'text-white hover:text-gray-300' : 'text-[#0f0f0f] hover:text-[#737373]'} transition-colors font-medium`}
                >
                  {item.name}
                </a>
              ))}
              <button
                onClick={() => setShowLoginDropdown(!showLoginDropdown)}
                className="bg-[#f59e0b] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#d97706] transition-colors"
              >
                Masuk
              </button>
              {showLoginDropdown && (
                <div className={`mt-2 rounded-md shadow-lg py-1 z-50 ${darkMode ? 'bg-gray-800' : 'bg-white'} border ${darkMode ? 'border-gray-700' : 'border-gray-200'}`}>
                  <a
                    href="#"
                    className={`block px-4 py-2 text-sm ${darkMode ? 'text-white hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100'}`}
                    onClick={(e) => {
                      e.preventDefault();
                      // Handle student login
                      setShowLoginDropdown(false);
                    }}
                  >
                    Mahasiswa
                  </a>
                  <a
                    href="#"
                    className={`block px-4 py-2 text-sm ${darkMode ? 'text-white hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100'}`}
                    onClick={(e) => {
                      e.preventDefault();
                      // Handle company login
                      setShowLoginDropdown(false);
                    }}
                  >
                    Perusahaan
                  </a>
                </div>
              )}
            </div>
          </div>
        )}
      </div>
    </header>
  );
};

export default HeaderSection;