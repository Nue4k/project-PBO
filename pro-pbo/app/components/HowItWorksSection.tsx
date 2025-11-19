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
          <h2 className={`text-[3rem] font-bold mb-4 ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>Cara Kerja</h2>
          <p className={`text-xl max-w-3xl mx-auto ${darkMode ? 'text-gray-300' : 'text-[#737373]'}`}>
            Langkah-langkah sederhana untuk menghubungkan mahasiswa dan perusahaan untuk magang yang bermakna.
          </p>
        </div>
        
        <div className="mb-20">
          <h3 className={`text-[2.25rem] font-bold mb-10 text-center ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>Untuk Mahasiswa</h3>
          <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
            {studentSteps.map((item, index) => (
              <div key={index} className="text-center">
                <div className={`w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 ${darkMode ? 'bg-gray-700' : 'bg-[#f5f5f5]'}`}>
                  <span className={`text-xl font-bold ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>{item.step}</span>
                </div>
                <h4 className={`text-xl font-bold mb-3 ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>{item.title}</h4>
                <p className={`${darkMode ? 'text-gray-300' : 'text-[#737373]'}`}>{item.desc}</p>
              </div>
            ))}
          </div>
        </div>

        <div>
          <h3 className={`text-[2.25rem] font-bold mb-10 text-center ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>Untuk Perusahaan</h3>
          <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
            {companySteps.map((item, index) => (
              <div key={index} className="text-center">
                <div className={`w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 ${darkMode ? 'bg-gray-700' : 'bg-[#f5f5f5]'}`}>
                  <span className={`text-xl font-bold ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>{item.step}</span>
                </div>
                <h4 className={`text-xl font-bold mb-3 ${darkMode ? 'text-white' : 'text-[#0f0f0f]'}`}>{item.title}</h4>
                <p className={`${darkMode ? 'text-gray-300' : 'text-[#737373]'}`}>{item.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
};

export default HowItWorksSection;