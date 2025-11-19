import { useState } from 'react';

type MenuItem = {
  id: string;
  label: string;
  icon: string;
};

const Sidebar = ({ darkMode }: { darkMode: boolean }) => {
  const [activeItem, setActiveItem] = useState('dashboard');
  
  const menuItems: MenuItem[] = [
    { id: 'dashboard', label: 'Dashboard', icon: '📊' },
    { id: 'internships', label: 'Magang', icon: '💼' },
    { id: 'students', label: 'Mahasiswa', icon: '👥' },
    { id: 'applications', label: 'Lamaran', icon: '📋' },
    { id: 'company', label: 'Perusahaan', icon: '🏢' },
    { id: 'settings', label: 'Pengaturan', icon: '⚙️' },
  ];

  return (
    <aside className={`fixed top-16 left-0 h-[calc(100vh-4rem)] w-64 ${darkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'} border-r z-40`}>
      <div className="p-4 pt-12">
        <div className={`text-xl font-bold mb-8 ${darkMode ? 'text-white' : 'text-gray-900'}`}>InternBridge</div>

        <nav>
          <ul className="space-y-2">
            {menuItems.map((item) => (
              <li key={item.id}>
                <button
                  onClick={() => setActiveItem(item.id)}
                  className={`w-full text-left px-4 py-3 rounded-lg flex items-center space-x-3 transition-colors ${
                    activeItem === item.id
                      ? darkMode 
                        ? 'bg-[#f59e0b] text-white' 
                        : 'bg-[#f59e0b] text-white'
                      : darkMode
                        ? 'text-gray-300 hover:bg-gray-700'
                        : 'text-gray-700 hover:bg-gray-100'
                  }`}
                >
                  <span className="text-lg">{item.icon}</span>
                  <span>{item.label}</span>
                </button>
              </li>
            ))}
          </ul>
        </nav>
      </div>
    </aside>
  );
};

export default Sidebar;