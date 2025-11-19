// HowItWorksSection.tsx
import { SectionProps } from '../interfaces';

const HowItWorksSection = ({ darkMode }: SectionProps) => {
  const studentSteps = [
    { step: "1", title: "Daftar", desc: "Buat profil dan verifikasi status Anda" },
    { step: "2", title: "Unggah", desc: "Tambahkan CV, transkrip, dan portofolio" },
    { step: "3", title: "Cari", desc: "Temukan peluang magang yang sesuai" },
    { step: "4", title: "Lamar", desc: "Kirim lamaran dan lacak statusnya" }
  ];

  const companySteps = [
    { step: "1", title: "Register", desc: "Buat profil perusahaan dan verifikasi" },
    { step: "2", title: "Posting", desc: "Buat deskripsi pekerjaan magang" },
    { step: "3", title: "Review", desc: "Kelola aplikasi dan pelamar" },
    { step: "4", title: "Pilih", desc: "Pilih kandidat dan buat penawaran" }
  ];

  return (
    <section id="how-it-works" className={`py-20 px-5 ${darkMode ? 'bg-gray-800' : ''}`}>
      <div className="max-w-[1200px] mx-auto px-[40px]">
        <div className="text-center mb-16">
          <h2 className={`text-[2rem] sm:text-[2.5rem] md:text-[3rem] font-bold mb-4 ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>Cara Kerja</h2>
          <p className={`text-base sm:text-lg md:text-xl max-w-3xl mx-auto ${darkMode ? 'text-gray-300' : 'text-[#737373]'}`}>
            Langkah-langkah sederhana untuk menghubungkan mahasiswa dan perusahaan untuk magang yang bermakna.
          </p>
        </div>

        <div className="mb-20">
          <h3 className={`text-[1.75rem] sm:text-[2rem] md:text-[2.25rem] font-bold mb-8 sm:mb-10 text-center ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>Untuk Mahasiswa</h3>
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            {studentSteps.map((item, index) => (
              <div key={index} className="text-center">
                <div className={`w-14 h-14 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 ${darkMode ? 'bg-gray-700' : 'bg-[#f5f5f5]'}`}>
                  <span className={`text-lg sm:text-xl font-bold ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>{item.step}</span>
                </div>
                <h4 className={`text-base sm:text-lg md:text-xl font-bold mb-2 sm:mb-3 ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>{item.title}</h4>
                <p className={`text-xs sm:text-sm ${darkMode ? 'text-gray-300' : 'text-[#737373]'}`}>{item.desc}</p>
              </div>
            ))}
          </div>
        </div>

        <div>
          <h3 className={`text-[1.75rem] sm:text-[2rem] md:text-[2.25rem] font-bold mb-8 sm:mb-10 text-center ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>Untuk Perusahaan</h3>
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            {companySteps.map((item, index) => (
              <div key={index} className="text-center">
                <div className={`w-14 h-14 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 ${darkMode ? 'bg-gray-700' : 'bg-[#f5f5f5]'}`}>
                  <span className={`text-lg sm:text-xl font-bold ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>{item.step}</span>
                </div>
                <h4 className={`text-base sm:text-lg md:text-xl font-bold mb-2 sm:mb-3 ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>{item.title}</h4>
                <p className={`text-xs sm:text-sm ${darkMode ? 'text-gray-300' : 'text-[#737373]'}`}>{item.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
};

export default HowItWorksSection;