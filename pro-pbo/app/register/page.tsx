// app/register/page.tsx

'use client'; // Karena menggunakan komponen RegisterForm yang berisi hooks

import RegisterForm from '../components/auth/RegisterForm';

const RegisterPage = () => {
  return (
    <div className="min-h-[calc(100vh-120px)] flex items-center justify-center p-4"> {/* Gunakan flex dan min-h untuk centering vertikal & horizontal */}
      <div className="w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 space-y-6 border border-gray-200 dark:border-gray-700"> {/* Gunakan styling card yang sama */}
        <RegisterForm />
      </div>
    </div>
  );
};

export default RegisterPage;