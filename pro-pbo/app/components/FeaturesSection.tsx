// FeaturesSection.tsx
import { SectionProps } from '../interfaces';
import { DataService } from '../dataService';

const FeaturesSection = ({ darkMode }: SectionProps) => {
  const features = DataService.getFeatures();

  return (
    <section id="features" className={`py-20 px-5 ${darkMode ? 'bg-gray-800' : 'bg-[#f5f5f5]'}`}>
      <div className="max-w-[1200px] mx-auto px-[40px]">
        <div className="text-center mb-16">
          <h2 className={`text-[3rem] font-bold mb-4 ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>Fitur Unggulan</h2>
          <p className={`text-xl max-w-3xl mx-auto ${darkMode ? 'text-gray-300' : 'text-[#737373]'}`}>
            Semua yang Anda butuhkan untuk mengelola siklus rekrutmen magang secara lengkap.
          </p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[30px]">
          {features.map((feature, index) => (
            <div key={index} className={`p-8 rounded-lg border shadow-sm ${darkMode ? 'bg-gray-700 border-gray-600' : 'bg-white border-[#e5e7eb]'}`}>
              <div className={`w-12 h-12 rounded-full flex items-center justify-center mb-6 ${darkMode ? 'bg-gray-600' : 'bg-[#f5f5f5]'}`}>
                <div className={`w-6 h-6 rounded-full ${darkMode ? 'bg-yellow-400' : 'bg-[#f59e0b]'}`}></div>
              </div>
              <h3 className={`text-xl font-bold mb-4 ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>{feature.title}</h3>
              <p className={`mb-6 ${darkMode ? 'text-gray-300' : 'text-[#737373]'}`}>{feature.description}</p>
              <ul className="space-y-3">
                {feature.features.map((feat, featIndex) => (
                  <li key={featIndex} className={`flex items-center ${darkMode ? 'text-gray-300' : 'text-[#737373]'}`}>
                    <div className={`w-5 h-5 rounded-full flex items-center justify-center mr-3 ${darkMode ? 'bg-gray-600' : 'bg-[#f5f5f5]'}`}>
                      <div className={`w-2 h-2 rounded-full ${darkMode ? 'bg-yellow-400' : 'bg-[#f59e0b]'}`}></div>
                    </div>
                    {feat}
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default FeaturesSection;